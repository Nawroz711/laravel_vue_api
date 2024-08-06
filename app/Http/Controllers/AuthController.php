<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function signup(AuthRequests $request)
    {
        $data = $request->all();
        $request->validated($data);

        $account_created = User::create($data);

        if ($account_created) {
            if (Auth::attempt($data)) {
                $user = Auth::user();
                $user['token'] =  $user->createToken('auth_token')->plainTextToken;
                return response()->json($user);
            } else {
                return response()->json([
                    'message' => 'Credentials didnt match!'
                ], 404);
            }
        }

        // return response()->json($data);
    }

    public function signin(Request $request)
    {

        $data = $request->all();

        if ($data) {
            if (Auth::attempt($data)) {
                $user = Auth::user();
                $user['token'] =  $user->createToken('auth_token')->plainTextToken;
                return response()->json($user);
            } else {
                return response()->json([
                    'message' => 'Credentials didnt match!'
                ], 404);
            }
        }
    }

    // logout method
    public function logout()
    {
        $user_id = auth()->id();
        $user = User::find($user_id);
        $user->tokens()->delete();


    }
}
