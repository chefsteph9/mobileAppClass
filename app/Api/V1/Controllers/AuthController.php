<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Validator;
use Config;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\ValidationHttpException;

class AuthController extends Controller
{
    use Helpers;

    public function login(Request $request)
    {
        //If the request does not contain an email field change the required email field to userName
        if( $request->only('email') == array('email' => NULL,) ) {
            $login = 'username';
        }else{
            $login = 'email';
        }

        //Retrieve the required values from the the request for login
        $credentials = $request->only([$login, 'password']);

        //Establish the required rules for validation
        $validator = Validator::make($credentials, [
            $login => 'required',
            'password' => 'required',
        ]);

        //If the validator does not receive the required inputs send errors
        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        //If after attempting to validate with the database and a match can not be made, send Unauthorized error
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }

        //If while trying to validate a server error occurs, send server error
        } catch (JWTException $e) {
            return $this->response->error('could_not_create_token', 500);
        }
        
        //If successful validation, return token
        return response()->json(compact('token'));
    }


    public function signup(Request $request)
    {
        //Retrieves fields we want from config/boilerplate.php
        $signupFields = Config::get('boilerplate.signup_fields');
        $hasToReleaseToken = Config::get('boilerplate.signup_token_release');

        //Assigns the supplied values from $request to the requested fields
        $userData = $request->only($signupFields);

        //Makes sure the supplied values meet our demands
        $validator = Validator::make($userData, Config::get('boilerplate.signup_fields_rules'));

        //If they don't then throw an error
        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        //Unguard allows access to the Users model which is used to create a new user and is then locked back down
        User::unguard();
        $user = User::create($userData);
        User::reguard();

        //If the user couldn't be created then throw an error
        if(!$user->id) {
            return $this->response->error('could_not_create_user', 500);
        }

        //If the user was granted a token, then log them in
        if($hasToReleaseToken) {
            return $this->login($request);
        }
        
        //Return the created instance of the model
        return $this->response->created();
    }


    public function recovery(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => 'required'
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(Config::get('boilerplate.recovery_email_subject'));
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->response->noContent();
            case Password::INVALID_USER:
                return $this->response->errorNotFound();
        }
    }

    public function reset(Request $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $validator = Validator::make($credentials, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        
        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                if(Config::get('boilerplate.reset_token_release')) {
                    return $this->login($request);
                }
                return $this->response->noContent();

            default:
                return $this->response->error('could_not_reset_password', 500);
        }
    }
}