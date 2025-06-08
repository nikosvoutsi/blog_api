<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\PasswordReset;
use App\Support\Helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
{
    try {
        $return = [];

        // Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Βρες τον χρήστη με βάση το email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong credentials'
            ], 403);
        }

        // Πληροφορίες χρήστη
        $return['info'] = $user->only(['id', 'name', 'email']);

        // Εξαρτάται αν είναι remember me ή όχι
        $rememberMe = (bool) $request->get('remember_me', false);

        $tokenResult = $user->createToken(
            'ApiToken',
            ['remember'],
            $rememberMe ? now()->addYears(2) : now()->addHours(5)
        );

        $return['token'] = $tokenResult->plainTextToken;
        $return['tokenType'] = 'Bearer';

        return response()->json($return, 200);
    } catch (Throwable $e) {
        return response()->json([
            'message' => 'Login failed',
            'error' => $e->getMessage(),
        ], $e->getCode() > 0 ? $e->getCode() : 500);
    }
}

public function register(Request $request)
{
    try {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Δημιουργία χρήστη
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);

    } catch (ValidationException $e) {
        // Επιστρέφει τα errors validation με status 422
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        // Γενικό σφάλμα
        return response()->json([
            'message' => 'Registration failed',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
