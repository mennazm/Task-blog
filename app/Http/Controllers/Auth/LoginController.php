<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
   public function login(Request $request){

    $request->validate([
        'phone_number' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('phone_number', $request->phone_number)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
   }
   if (!$user->is_verified) {
    return response()->json(['message' => 'your Account not verified'], 403);
    }
    $token = $user->createToken('auth_token')->plainTextToken;
    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}
}

