<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

use App\Models\User;

class AuthController extends Controller
{
    public function register (Request $request)
    {
                
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        
        $success = [
            'access_token' => $user->createToken('auth')->plainTextToken,
            'name' => $user->name
        ];

        return $this->sendSucces($success, 'User created successfully.');
    }
}
