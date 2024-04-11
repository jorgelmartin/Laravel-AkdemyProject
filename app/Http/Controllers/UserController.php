<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    //UPDATE PROFILE FUNCTION
    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['string', 'max:40', 'regex:/^[a-zA-Z ]+$/'],
                'surname' => ['string', 'max:40', 'regex:/^[a-zA-Z ]+$/'],
                'email' => ['email', 'unique:users,email', 'max:50'],
                'password' => [Password::min(8)->mixedCase()->numbers()],
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $validData = $validator->validated();
            $user = User::find($validData['id']);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ]);
            }
            if (isset($validData['name'])) {
                $user->name = $validData['name'];
            }
            if (isset($validData['surname'])) {
                $user->surname = $validData['surname'];
            }
            if (isset($validData['email'])) {
                $user->email = $validData['email'];
            }
            if (isset($validData['password'])) {
                $user->password = $validData['password'];
            }
            $user->save();
            return response()->json([
                'message' => 'User updated'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error updating user ' . $th->getMessage());
            return response()->json([
                'message' => 'Error updating user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //DELETE MY ACCOUNT FUNCTION
    public function deleteMyAccount()
    {
        try {
            $user = auth()->user();
            $userFound = User::find($user->id);
            $userFound->delete();
            return response()->json([
                'message' => 'User deleted',
                'data' => $user,
            ], Response::HTTP_OK);
        }catch (\Throwable $th) {
            Log::error('Error deleting user ' . $th->getMessage());
            return response()->json([
                'message' => 'Error deleting user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //RESTORE ACCOUNT FUNCITON
    public function restoreAccount($id){
        try {
            User::withTrashed()->where('id',$id)->restore();
            return response()->json([
                'message'=>'User restored'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error restoring user ' . $th->getMessage());
            return response()->json([
                'message' => 'Error restoring user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //GET ALL USERS FUNCTION
    public function getAllUsers()
    {
        try {
            $user = User::get();
            return response()->json([
                'message' => 'Users retrieved',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting users ' .
                $th->getMessage());
            return response()->json([
                'message' => 'Error retrieving users'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
