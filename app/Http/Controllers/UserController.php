<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function registerUser(){
        $payLoad = json_decode(request()->getContent(), true);
        
        $newUser = User::create([
            'name'              => $payLoad['name'],
            'email'             => $payLoad['email'],
            'password'          => Hash::make($payLoad['password']),
        ]);

        return response()->json([
            'message' => 'New user added', 
            'user' => $newUser
        ], 201);
    }
}