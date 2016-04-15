<?php

namespace App\Http\Controllers;

use Auth;
use App\Album;
use App\Photo;
use App\Http\Requests\AlbumRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Http\Requests;

class AlbumsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth', ['only' => 'create']);
        $this->middleware('auth', ['except' => ['index','show']]);
    }
    
    public function index() {
        $albums = Album::latest()->paginate(Config::get('listing.albums_paginate'));
        
        return view('albums.index',compact('albums'));
    }
    
    public function create() {
        $categories = \App\Category::lists('name', 'id');
        return view('albums.create',compact('categories'));        
    }

    /**
     * Validated with Requests class method
     * 
     * @param CreateArticleRequest $request
     * @return type
     */
    public function store(AlbumRequest $request) {
        $requestArr = $this->cleanRequest($request);

        $album = $this->createAlbum($requestArr);        
        flash()->overlay('Your Album is now created.<br>Starting uploading pics now!','Congratulations!');
        
        if ($request->has('continue') && $request['continue'] == 'true') {
            return redirect(action('AlbumsController@edit', $album->id));
        }
        else {
            return redirect('albums');
        }        
    }
    
    public function edit(Album $album) {
        if (!$this->isOwner($album)) {
            $this->notOwnerResponse();
        }
        
        if (array_key_exists('success', $_GET) && $_GET['success'] == '1') {
            flash()->overlay('All valid photos are uploaded.','Congratulations!');
        }
        
        // Prepare select Album box for mass photo move
        $ownerAlbums = Auth::user()->albums()->where('id', '<>', $album->id)->lists('name', 'id');
        $ownerAlbums->prepend('Choose an Album', 0);
        
        $categories = \App\Category::lists('name', 'id');
        $photos = $album->photos()->ranked()->get();
        $tags = \App\Tag::lists('name', 'id');
        
        return view('albums.edit', compact('album', 'categories','photos','tags', 'ownerAlbums'));
    }

    public function update(Album $album, AlbumRequest $request) {
        $requestArr = $this->cleanRequest($request);

        $album->update($requestArr);

        // Save & Continue Button Pressed
        if ($request->has('continue') && $request['continue'] == 'true') {
            return redirect(action('AlbumsController@edit', $album->id));
        }
        else {
            return redirect('albums');
        }
    }    
    
    public function show(Album $album) {
        // A Possible Back Button destination
        session()->put('url.photoList', request()->path());
        
        $photos = $album->photos()->ranked()->paginate(Config::get('listing.photos_paginate'));
        
        return view('albums.view', compact('album', 'photos'));
    }
    
    public function destroy(Album $album) {
        $album->delete();
        
        return redirect('albums');
    }

    public function upload(Album $album, Request $request) {
        if (Auth::user()->id != $album->user_id) {
            return response()->json('error', 403);
        }
        
        $file = $request->file('file');
        $destPath = Photo::IMAGE_FOLDER;
        $filename = uniqid($album->id . '_', true) . '.' . $file->getClientOriginalExtension();
        
        if(substr($file->getMimeType(), 0, 5) == 'image' && $file->isValid()) {
            $file->move($destPath, $filename);
            
            $photo = new Photo;
            $photo->album_id = $album->id;
            $photo->filename = $filename;
            $photo->save();
            
            $photo->generateThumbnail(true);
        }
        else {
            return response()->json('error', 400);
        }
        
        if (!$request->ajax()) {
            flash()->overlay('All valid photos are uploaded.','Congratulations!');            
            return redirect(action('AlbumsController@edit', $album->id));
        }
    }
    
    public function massMove(Request $request) {
        $ids = $this->validateMassMoveRequest($request);
        if (!$ids) {
            if ($request->ajax()) {
                return response()->json('error', 400);
            }
            else {
                flash()->error('Error: Mass Move Request Invalid');
                return back();
            }
        }
        $album = Album::find($request['album_id']);
        if (!$this->isOwner($album) || is_null($album)) {
            flash()->error('Error: Mass Move Request Invalid');
            return back();
        }
        
        foreach ($ids as $id) {
            $photo = Photo::find($id);
            $photo->album_id = $album->id;
            $photo->save();
        }
        flash()->overlay('All selected photos have been moved.','Success!');
        return back();
    }
    
    /**
     * Save a new Album
     * 
     * @param ArticleRequest $request
     */
    private function createAlbum($requestArr) {
        $album = Auth::user()->albums()->create($requestArr);
        return $album;
    }
    
    /**
     * Return request array with Save & Continue info removed
     * 
     * @param AlbumRequest $request
     * @return array
     */
    private function cleanRequest(AlbumRequest $request) {
        $requestArr = $request->all();
        if ($request->has('continue') && $request['continue'] == 'true') {
            unset($requestArr['continue']);
        }
        return $requestArr;
    }
    
    private function isOwner(Album $album) {
        return Auth::user()->id == $album->user_id;
    }

    private function notOwnerResponse(Request $request = null) {
        if (!isNull($request) && $request->ajax()) {
            return response()->json('Error: You are not the owner of this Photo', 403);
        }            
        flash()->error('Error: You are not the owner of this Album');
        return back();        
    }
    
    
    private function validateMassMoveRequest(Request $request) {
        if ($request->ajax() || !$request->has('ids')) {
            return false;
        }
        $ids = explode(',',$request['ids']);
        foreach ($ids as $id) {
            $photo = Photo::find($id);
            if (is_null($photo)) {
                return false;
            }
            elseif (Auth::user()->id != $photo->album->user_id) {
                return false;
            }
        }
        return $ids;
    }
    
}
