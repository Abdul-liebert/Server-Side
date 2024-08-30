<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:15',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images');
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'date_of_birth' => $validatedData['date_of_birth'],
            'phone' => $validatedData['phone'],
            'profile_image' => $profileImagePath,
        ]);

        // Generate token
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'dateOfBirth' => $user->date_of_birth,
                'phone' => $user->phone,
                'profileImage' => $user->profile_image,
                'createdAt' => $user->created_at,
                'updatedAt' => $user->updated_at,
            ],
            'accessToken' => $token,
            'tokenType' => 'Bearer',
        ]);
    }

    public function update(Request $request, $id)
    {
        // Mencari pengguna berdasarkan ID
        $user = User::find($id);

        // Memeriksa apakah pengguna ditemukan
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validasi data masukan
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'date_of_birth' => 'sometimes|required|date',
            'phone' => 'sometimes|required|string|max:15',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        // Update data pengguna
        $user->name = $validatedData['name'] ?? $user->name;
        $user->username = $validatedData['username'] ?? $user->username;
        $user->email = $validatedData['email'] ?? $user->email;
        if (isset($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }
        $user->date_of_birth = $validatedData['date_of_birth'] ?? $user->date_of_birth;
        $user->phone = $validatedData['phone'] ?? $user->phone;

        // Proses upload gambar profil jika ada
        if ($request->hasFile('profile_image')) {
            // Hapus gambar profil lama jika ada
            if ($user->profile_image) {
                Storage::delete($user->profile_image);
            }

            // Simpan gambar profil baru
            $user->profile_image = $request->file('profile_image')->store('profile_images');
        }

        // Simpan perubahan
        $user->save();

        // Kembalikan respons JSON yang sesuai
        return response()->json([
            'message' => 'User updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'dateOfBirth' => $user->date_of_birth,
                'phone' => $user->phone,
                'profileImage' => $user->profile_image,
                'createdAt' => $user->created_at,
                'updatedAt' => $user->updated_at,
            ]
        ]);
    }
}