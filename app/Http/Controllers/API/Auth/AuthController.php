<?php

namespace App\Http\Controllers\API\Auth;

use App\Events\LoginHistory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Passport\Token;

class AuthController extends Controller
{

    //User Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address is already taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
       
        $token = $user->createToken('LaravelAuthApp')->accessToken;
        
 
        return response()->json(['token' => $token], 200);
    }

    //User login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $accessToken = $user->createToken('token')->accessToken;
            $user->update(['token' => $accessToken]);
            $response = [
                'user' => $user->name,
                'token' => $accessToken,
                'email' => $user->email
            ];
            event(new LoginHistory($user));
            return response()->json($response);

        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    //logout
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Bearer token not provided'], 401);
        }

        $user = User::where('token', $token)->first();

        if ($user) {
            $token = Token::where('id', $token)->first();
            if ($token) {
                $token->revoke();
            }
            $user->update(['token' => null]);

            return response()->json(['message' => 'Logged out successfully']);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }
    

}
