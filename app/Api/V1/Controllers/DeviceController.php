<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Device;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;

class DeviceController extends Controller
{
    use Helpers;

    public function index()
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();
	    return $currentUser
	        ->devices()
	        ->orderBy('created_at', 'DESC')
	        ->get()
	        ->toArray();
	}

	public function store(Request $request)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $device = new Device;

	    $device->name = $request->get('name');
	    $device->lastSync = $request->get('lastSync');

	    if($currentUser->devices()->save($device))
	        return $this->response->created();
	    else
	        return $this->response->error('could_not_create_device', 500);
	}

	public function show($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $device = $currentUser->devices()->find($id);

	    if(!$device)
	        throw new NotFoundHttpException; 

	    return $device;
	}

	public function update(Request $request, $id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $device = $currentUser->devices()->find($id);
	    
	    if(!$device)
	        throw new NotFoundHttpException;

	    $device->fill($request->all());

	    if($device->save())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_update_device', 500);
	}

	public function destroy($id)
	{
	    $currentUser = JWTAuth::parseToken()->authenticate();

	    $device = $currentUser->devices()->find($id);

	    if(!$device)
	        throw new NotFoundHttpException;

	    if($device->delete())
	        return $this->response->noContent();
	    else
	        return $this->response->error('could_not_delete_device', 500);
	}

}
