<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->merge(['idPay' => $request->input('idPay', ' ')]); // Valeur par défaut ""


        $filled = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'role' => 'string',
            'idPay' => 'nullable|string',
            'phone' => 'string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'lastname' => $filled['lastname'],
            'firstname' => $filled['firstname'],
            'email' => $filled['email'],
            'password' => bcrypt($filled['password']),
            'phone' => $filled['phone'],
            'idPay' => $filled['idPay'] ?? null,
            'role' => $filled['role'] ?? 'client',
        ]);

        $token = $user->createToken('myToken')->plainTextToken;

        $response = [
            'user' => $user,
            'myToken' => $token,
            'statut' => 'created'
        ];
        return response($response, 201);
    }

    public function login(Request $request)
    {

        $filled = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $filled['email'])->first();


        if (!$user || !Hash::check($filled['password'], $user->password)) {
            return response([
                'message' => 'Bad credential'
            ], 401);
        } else {

            $token = $user->createToken('myToken')->plainTextToken;

            $response = [
                'user' => $user,
                'myToken' => $token,
                'statut' => "OK"
            ];
            return response($response, 200);
        }
    }

    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Logged out'], 200);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function getUser(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}
