<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        //
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'coins' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $password = $request->get('password');
        $attributes = [
            'email' => $request->get('email'),
            'username' => $request->get('username'),
            'password' => app('hash')->make($password),
            'coins'=> $request->get('coins')
        ];
        $user = User::create($attributes);


        $result['data'] = [
            'user' => $user,
        ];

        return $this->response->array($result)->setStatusCode(201);
    }

}
