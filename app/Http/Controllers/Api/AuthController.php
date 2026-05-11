<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
            'utype' => 'USR', // Default to regular user
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'name' => $user->first_name . ' ' . $user->last_name,
                'utype' => $user->utype,
                'is_admin' => $user->utype === 'ADM',
                'sitio' => $user->sitio,
                'barangay' => $user->barangay,
                'city' => $user->city,
                'landmark' => $user->landmark,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'name' => $user->first_name . ' ' . $user->last_name,
                'utype' => $user->utype,
                'is_admin' => $user->utype === 'ADM',
                'sitio' => $user->sitio,
                'barangay' => $user->barangay,
                'city' => $user->city,
                'landmark' => $user->landmark,
            ]
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'name' => $user->first_name . ' ' . $user->last_name,
            'utype' => $user->utype,
            'is_admin' => $user->utype === 'ADM',
            'sitio' => $user->sitio,
            'barangay' => $user->barangay,
            'city' => $user->city,
            'landmark' => $user->landmark,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'mobile' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'sitio' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
        ]);

        // Handle name split into first_name / last_name
        if (isset($validated['name'])) {
            $parts = explode(' ', $validated['name'], 2);
            $user->first_name = $parts[0];
            $user->last_name = $parts[1] ?? '';
        }

        if (isset($validated['mobile'])) $user->mobile = $validated['mobile'];
        if (isset($validated['email'])) $user->email = $validated['email'];
        if (array_key_exists('sitio', $validated)) $user->sitio = $validated['sitio'];
        if (array_key_exists('barangay', $validated)) $user->barangay = $validated['barangay'];
        if (array_key_exists('city', $validated)) $user->city = $validated['city'];
        if (array_key_exists('landmark', $validated)) $user->landmark = $validated['landmark'];

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Account details updated successfully',
            'user' => [
                'id' => $user->id,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'name' => $user->first_name . ' ' . $user->last_name,
                'utype' => $user->utype,
                'is_admin' => $user->utype === 'ADM',
                'sitio' => $user->sitio,
                'barangay' => $user->barangay,
                'city' => $user->city,
                'landmark' => $user->landmark,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($validated['old_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect'
            ], 422);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}