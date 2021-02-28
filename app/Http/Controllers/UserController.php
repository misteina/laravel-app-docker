<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct() {
        $this->middleware('auth:web', ['except' => ['store', 'destroy']]);
    }


    // Create User.


    public function store(Request $request)
    {
        $credentials = $request->only('name', 'email', 'password');

        $validator = Validator::make($credentials, [
            'name' => 'required|min:5|max:50|',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|filled|not_regex:/[\s\t\n\v\f\r]/'
        ]);

        if (!$validator->fails()) {
            $credentials['password'] = Hash::make($credentials['password']);

            $user = User::create($credentials);

            if ($user){

                $token = Auth::login($user);

                return response()->json(
                    ['status' => 200, 'type' => 'success', 'message' => 'New user created']
                );
            } else {
                return ['status' => 406, 'type' => 'error', 'message' => 'Request failed'];
            }
        } else {
            return ['status' => 406, 'type' => 'error', 'message' => $validator->errors()];
        }
    }


    // Get user using JWT authentication


    public function show($id)
    {
        $user = Auth::user();

        if ($user->id == $id){
            return [
                'status' => 200,
                'type' => 'success',
                'message' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ]
            ];
        } else {
            return ['status' => 401, 'type' => 'error', 'message' => 'Unauthorized request'];
        }
    }

 
    // Delete user and reset database


    public function destroy($id)
    {
        Auth::logout();

        User::query()->truncate();

        return [
            'status' => 200,
            'type' => 'success',
            'message' => 'done'
        ];
    }
}
