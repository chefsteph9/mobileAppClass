<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Belief;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;

class BeliefController extends Controller
{
    use Helpers;

    public function index()
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();
	    return $currentUser
	        ->beliefs()
	        ->orderBy('created_at', 'DESC')
	        ->get()
	        ->toArray();
	}

	public function store(Request $request)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $belief = new Belief;

	    $belief->beliefText = $request->get('beliefText');

	    if($currentUser->beliefs()->save($belief))
	        return $this->response->created();
	    else
	        return $this->response->error('could_not_create_belief', 500);
	}

	public function show($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $belief = $currentUser->beliefs()->find($id);

	    if(!$belief)
	        throw new NotFoundHttpException; 

	    return $belief;
	}

	public function update(Request $request, $id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $belief = $currentUser->beliefs()->find($id);
	    
	    if(!$belief)
	        throw new NotFoundHttpException;

	    $belief->fill($request->all());

	    if($belief->save())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_update_belief', 500);
	}

	public function destroy($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $belief = $currentUser->beliefs()->find($id);

	    if(!$belief)
	        throw new NotFoundHttpException;

	    if($belief->delete())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_delete_belief', 500);
	}

}
