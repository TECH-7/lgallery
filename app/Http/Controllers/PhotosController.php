<?php

namespace App\Http\Controllers;

use Auth;
use App\Photo;
use App\Tag;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Http\Requests;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PhotoRequest;

class PhotosController extends Controller
{
    public function __construct() {
        //$this->middleware('auth', ['only' => 'create']);
        $this->middleware('auth', ['except' => ['tag','createComment','index']]);
    }
    
    public function index() {
        // Possible Back Button destination
        session()->put('url.photoList', request()->path());
        $photos = Photo::ranked()->orderBy('updated_at', 'desc')->paginate(Config::get('listing.photos_paginate'));
        
        return view('photos.index',compact('photos'));        
    }

    public function createComment(Photo $photo) {
        $this->saveLoginReturnPath();
        $backUrl = $this->getBackButtonUrl($photo);
        
        $comments = $photo->comments()->orderBy('created_at')->get();
        return view('photos.comments',compact('photo', 'comments', 'backUrl'));  
    }

    public function storeComment(Photo $photo, CommentRequest $request) {
        $requestParams = $request->all();
        $requestParams['photo_id'] = $photo->id;
        Auth::user()->comments()->create($requestParams);
        
        return redirect()->action('PhotosController@storeComment', $photo->id);
    }
    
    public function edit(Photo $photo) {
        if (!$this->isOwner($photo)) {
            $this->notOwnerResponse();
        }
        $tags = Tag::lists('name', 'id');
        
        return view('photos.edit', compact('photo','tags'));
    }

    public function update(Photo $photo, PhotoRequest $request) {
        if (!$this->isOwner($photo)) {
            $this->notOwnerResponse($request);
        }
        
        $success = $photo->update($request->all());
        $this->syncTags($photo, $request);
        
        if($request->ajax()){
            if ($success) {
                return response()->json('success', 200);
            }
            else {
                return response()->json('error', 500);
            }
        }
        return redirect()->action('AlbumsController@edit', $photo->album->id);
    }
    
    /**
     * Search photos by Tag page
     * 
     * @param type $tag
     * @return type
     */
    public function tag($tag) {
        session()->put('url.photoList', request()->path());
        $photos = Photo::whereHas('tags', function($q) use ($tag)
        {
            $q->where('name', '=', $tag);

        })->paginate(Config::get('listing.photos_paginate'));        
        
        return view('photos.index',compact('photos', 'tag'));        
    }
    
    public function destroy(Photo $photo, PhotoRequest $request) {
        if (!$this->isOwner($photo)) {
            $this->notOwnerResponse($request);
        }
        
        $album_id = $photo->album->id;
        // Moved to AppServiceProvider Event
        //unlink(Photo::IMAGE_FOLDER . '/' . $photo->filename);
        //unlink(Photo::THUMBNAIL_FOLDER . '/' . $photo->filename);
        $photo->delete();

        return redirect()->action('AlbumsController@edit', $album_id);
    }
    
    public function massDestroy(Request $request) {
        $ids = $this->validateMassDeleteRequest($request);
        if (!$ids) {
            if ($request->ajax()) {
                return response()->json('error', 403);
            }
            else {
                flash()->error('Error: Mass Delete Request Invalid');
                return back();
            }
        }
        foreach ($ids as $id) {
            Photo::find($id)->delete();
        }
        flash()->overlay('All selected photos have been deleted.','Success!');
        return back();
    }

    private function validateMassDeleteRequest(Request $request) {
        if ($request->ajax()) {
            return false;
        }
        if (!$request->has('ids')) {
            return false;
        }
        $ids = explode(',',$request['ids']);
        foreach ($ids as $id) {
            if ((int)$id < 1) {
                return false;
            }
            else {
                $photo = Photo::find($id);
                if (is_null($photo)) {
                    return false;
                }
                elseif (!$this->isOwner($photo)) {
                    return false;
                }
            }
        }
        return $ids;
    }
    
    /**
     * Check if logged-in user is the owner of the photo
     * 
     * @param Photo $photo
     * @return type
     */
    private function isOwner(Photo $photo) {
        return Auth::user()->id == $photo->album->user_id;
    }    
    
    private function notOwnerResponse(Request $request = null) {
        if (!isNull($request) && $request->ajax()) {
            return response()->json('Error: You are not the owner of this Photo', 403);
        }            
        flash()->error('You are not the owner of this Photo');
        return back();        
    }

    /**
     * Sync entries in Photo_Tag anchor table and Create new tag
     * when necessary
     * 
     * @param Photo $photo
     * @param PhotoRequest $request
     */
    private function syncTags(Photo $photo, PhotoRequest $request) {
        $tag_list = $this->createNewTags($request);
        $photo->tags()->sync($tag_list);
        
        $this->removeZombieTags();
    }
    
    /**
     * Remove tags that are no longer used from DB
     */
    private function removeZombieTags() {
        $tags = Tag::all();
        foreach ($tags as $tag) {
            if ($tag->photos()->count() < 1) {
                echo $tag->delete();
            }
        }        
    }
    
    /**
     * Create new tags if added by User.
     * Method will look for new Tag names from the tag_list request field
     * and convert it to the id of the newly created Tag model and sends
     * it back to the original array as a id:name pair
     * 
     * @param PhotoRequest $request
     * @return array
     */
    private function createNewTags(PhotoRequest $request) {
        $tag_list = $request['tag_list'];
        if (!empty($tag_list)) {
            $tags = Tag::lists('id')->toArray();
            $diff = array_unique(array_diff($tag_list, $tags));
            foreach($diff as $new) {
                $tag = Tag::create(['name' => $new]);
                $index = array_search($new, $tag_list, true);
                $tag_list[$index] = $tag->id;
            }
            return $tag_list;
        }
        return [];
    }
    
    /**
     *  For the "Login to add a comment" link.
     *  So that the user is return to the comment page
     *  right after login
     */    
    private function saveLoginReturnPath() {
        if (Auth::check()) {
            session()->forget('url.intended');
        }
        else {
            session()->put('url.intended', request()->path());
        }
    }
    
    /**
     * Prevent undesirable Back Button destination
     * (returning to login page etc..)
     * 
     * @param Photo $photo
     * @return string
     */
    private function getBackButtonUrl(Photo $photo) {
        $backUrl = url()->previous();
        if ($backUrl == action('Auth\AuthController@showLoginForm') || $backUrl == '/' || action('PhotosController@createComment', $photo->id)) {
            $backUrl = session()->has('url.photoList') ? session()->get('url.photoList') : action('PhotosController@index');
        }
        return $backUrl;
    }
}