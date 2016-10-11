<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\License;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;

class LicenseController extends Controller
{
    use Helpers;

    public function index()
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();
	    return $currentUser
	        ->licenses()
	        ->orderBy('created_at', 'DESC')
	        ->get()
	        ->toArray();
	}

	public function store(Request $request)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $license = new License;

	    $license->licenseType = $request->get('licenseType');
	    $license->startDate = $request->get('startDate');
	    $license->endDate = $request->get('endDate');

	    if($currentUser->licenses()->save($license))
	        return $this->response->created();
	    else
	        return $this->response->error('could_not_create_license', 500);
	}

	public function show($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $license = $currentUser->licenses()->find($id);

	    if(!$license)
	        throw new NotFoundHttpException; 

	    return $license;
	}

	public function update(Request $request, $id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $license = $currentUser->licenses()->find($id);
	    
	    if(!$license)
	        throw new NotFoundHttpException;

	    $license->fill($request->all());

	    if($license->save())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_update_license', 500);
	}

	public function destroy($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $license = $currentUser->licenses()->find($id);

	    if(!$license)
	        throw new NotFoundHttpException;

	    if($license->delete())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_delete_license', 500);
	}

}
