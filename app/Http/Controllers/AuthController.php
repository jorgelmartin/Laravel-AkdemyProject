<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    //REGISTER FUNCTION
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:40', 'regex:/^[a-zA-Z ]+$/'],
                'surname' => ['required', 'string', 'max:40', 'regex:/^[a-zA-Z ]+$/'],
                'email' => ['required', 'email', 'unique:users,email', 'max:50'],
                'password' => ['required', Password::min(8)->mixedCase()->numbers()]
            ], [
                'name.required' => 'Name is required.',
                'name.max' => 'Name can have a maximum of 40 characters.',
                'name.regex' => 'Name can only contain letters and spaces.',
                'surname.required' => 'Surname is required.',
                'surname.max' => 'Surname can have a maximum of 40 characters.',
                'surname.regex' => 'Surname can only contain letters and spaces.',
                'email.required' => 'Email is required.',
                'email.email' => 'Email is not valid.',
                'email.unique' => 'Email is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters long.',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $newUser = User::create([
                'name' => $validData['name'],
                'surname' => $validData['surname'],
                'email' => $validData['email'],
                'password' => bcrypt($validData['password']),
                'role_id' => 2
            ]);

            $token = $newUser->createToken('apiToken')->plainTextToken;

            return response()->json([
                'message' => 'User registered',
                'data' => $newUser,
                'token' => $token
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error registering user ' . $th->getMessage());

            return response()->json([
                'message' => 'Error registering user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //LOGIN
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ], [
                'email.required' => 'Email or password are invalid',
                'email.email' => 'Email or password are invalid',
                'password.required' => 'Email or password are invalid'
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
    
            $validData = $validator->validated();
            $user = User::where('email', $validData['email'])->first();
    
            if (!$user || !Hash::check($validData['password'], $user->password)) {
                return response()->json([
                    'message' => 'Email or password are invalid'
                ], Response::HTTP_FORBIDDEN);
            }
            $token = $user->createToken('apiToken')->plainTextToken;
            return response()->json([
                'message' => 'Login success',
                'data' => $user,
                'token' => $token
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error logging user in ' . $th->getMessage());
            return response()->json([
                'message' => 'Error logging user in'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //PROFILE
    public function profile()
    {
        try {
            $user = auth()->user();

            return response()->json([
                'message' => 'User found',
                'data' => $user,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error retrieving user ' .
                $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // LOGOUT
    public function logout(Request $request)
    {
        try {
            $headerToken = $request->bearerToken();
            $token = PersonalAccessToken::findToken($headerToken);
            $token->delete();
            return response()->json([
                'message' => 'User logged out'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error logging user out ' . $th->getMessage());
            return response()->json([
                'message' => 'Error logging user out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
