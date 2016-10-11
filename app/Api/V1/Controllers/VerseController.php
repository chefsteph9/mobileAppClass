<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Verse;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;

class VerseController extends Controller
{
    use Helpers;

    public function index()
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();
	    return $currentUser
	        ->verses()
	        ->orderBy('created_at', 'DESC')
	        ->get()
	        ->toArray();
	}

	public function store(Request $request)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $verse = new Verse;

	    $verse->verse = $request->get('verse');
	    $verse->book = $request->get('book');
	    $verse->chapter = $request->get('chapter');
	    $verse->verseStart = $request->get('verseStart');
	    $verse->verseEnd = $request->get('verseEnd');

	    if($currentUser->verses()->save($verse))
	        return $this->response->created();
	    else
	        return $this->response->error('could_not_create_verse', 500);
	}

	public function show($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $verse = $currentUser->verses()->find($id);

	    if(!$verse)
	        throw new NotFoundHttpException; 

	    return $verse;
	}

	public function update(Request $request, $id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $verse = $currentUser->verses()->find($id);
	    
	    if(!$verse)
	        throw new NotFoundHttpException;

	    $verse->fill($request->all());

	    if($verse->save())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_update_verse', 500);
	}

	public function destroy($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $verse = $currentUser->verses()->find($id);

	    if(!$verse)
	        throw new NotFoundHttpException;

	    if($verse->delete())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_delete_verse', 500);
	}

}
