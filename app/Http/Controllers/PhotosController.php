<?php

namespace App\Http\Controllers;

use Auth;
use App\Photo;
use App\Tag;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PhotoRequest;

class PhotosController extends Controller
{
    protected $fillable = [
        'alt_text',
        'caption',
        'sort_order'
    ];

    public function __construct() {
        //$this->middleware('auth', ['only' => 'create']);
        $this->middleware('auth', ['except' => ['tag','createComment','index']]);
    }
    
    public function index() {
        session()->put('url.photoList', request()->path());
        $photos = Photo::orderBy('sort_order','desc')->orderBy('updated_at', 'desc')->paginate(4);
        
        return view('photos.index',compact('photos'));        
    }

    public function createComment(Photo $photo) {
        $this->setLoginReturnPath();
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
        $tags = \App\Tag::lists('name', 'id');
        
        return view('photos.edit', compact('photo','tags'));
    }

    public function update(Photo $photo, PhotoRequest $request) {
        if (!$this->isOwner($photo)) {
            $this->notOwnerResponse($request);
        }
        
        $photo->update($request->all());
        $this->syncTags($photo, $request);
        
        if($request->ajax()){
            return response()->json('success', 200);
        }
        return redirect()->action('AlbumsController@edit', $photo->album->id);
    }
    
    public function tag($tag) {
        session()->put('url.photoList', request()->path());
        $photos = Photo::whereHas('tags', function($q) use ($tag)
        {
            $q->where('name', '=', $tag);

        })->paginate(8);        
        
        return view('photos.index',compact('photos', 'tag'));        
    }
    
    public function destroy(Photo $photo, PhotoRequest $request) {
        if (!$this->isOwner($photo)) {
            $this->notOwnerResponse($request);
        }
        
        $photo_id = $photo->album->id;
        
        // Moved to AppServiceProvider Event
        //unlink(Photo::IMAGE_FOLDER . '/' . $photo->filename);
        //unlink(Photo::THUMBNAIL_FOLDER . '/' . $photo->filename);
        $photo->delete();

        return redirect()->action('AlbumsController@edit', $photo_id);
    }
    
    public function massDestroy(Request $request) {
        $ids = $this->validateMassDeleteRequest($request);
        if (!$ids) {
            if ($request->ajax()) {
                return response()->json('error', 403);
            }
            else {
                flash()->error('Mass Delete Request Invalid');
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

    private function syncTags(Photo $photo, PhotoRequest $request) {
        $tag_list = $this->createNewTags($request);
        $photo->tags()->sync($tag_list);
        
        $this->removeZombieTags();
    }
    
    private function removeZombieTags() {
        $tags = \App\Tag::all();
        foreach ($tags as $tag) {
            if ($tag->photos()->count() < 1) {
                echo $tag->delete();
            }
        }        
    }
    
    private function createNewTags(PhotoRequest $request) {
        $tag_list = $request['tag_list'];
        if (!empty($tag_list)) {
            $tags = \App\Tag::lists('id')->toArray();
            $diff = array_unique(array_diff($tag_list, $tags));
            foreach($diff as $new) {
                $tag = \App\Tag::create(['name' => $new]);
                $index = array_search($new, $tag_list, true);
                $tag_list[$index] = $tag->id;
            }
            return $tag_list;
        }
        return [];
    }
    
    private function setLoginReturnPath() {
        if (Auth::check()) {
            session()->forget('url.intended');
        }
        else {
            session()->put('url.intended', request()->path());
        }
    }
    
    private function getBackButtonUrl(Photo $photo) {
        $backUrl = url()->previous();
        if ($backUrl == action('Auth\AuthController@showLoginForm') || $backUrl == '/' || action('PhotosController@createComment', $photo->id)) {
            $backUrl = session()->has('url.photoList') ? session()->get('url.photoList') : action('PhotosController@index');
        }
        return $backUrl;
    }
}