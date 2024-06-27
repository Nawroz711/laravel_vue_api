<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(AuthRequests $request)
    {
        $data = $request->all();
       $x = $request->validated($data);

       User::create($data);

        return response()->json($data);
    }

    public function signin(Request $request)
    {

        $data = $request->all();

        if($data)
        {
            if(Auth::attempt($data))
            {
                $user = Auth::user();
                $user['token'] =  $user->createToken('auth_token')->plainTextToken;
            }
        }

        return response()->json($user);
    }
}
