<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $avatarPath = null;
        if($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = Storage::disk('public')->putfile('users', $avatar);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $avatarPath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if($user->status == 'inactive') {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account has been deleted'
            ]);
        }

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        $url = Storage::url($user->avatar);

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token,
            'profile_picture' => $url,
        ]);
    }

    public function update(Request $request){
        try{
            $request->validate([
                'name' => 'nullable|string',
                'password' => 'nullable|min:6',
                'avatar' => 'nullable|image|max:2048',
            ]);

            $user = $request->user();

            if($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $path = Storage::disk('public')->putfile('users', $avatar);
                if($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $path;
            }

            if($request->password){
                $user->password = Hash::make($request->password);
            }

            if($request->name){
                $user->name = $request->name;
            }

            $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => $user,
            'profile_picture' => Storage::url($user->avatar),
        ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully',
            'user' => $request->user(),
        ]);
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully',
        ]);
        }

        return response()->json([
        'status' => 'error',
        'message' => 'No authenticated user',
        ], 401);
    }

    public function deleteAccount(Request $request){
        $user = $request->user();
        $user->status = 'inactive';
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
            'user' => $user,
        ]);
    }
}