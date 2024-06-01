<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use ApiResponses;
    /**
     * Login
     *
     * Authentication the user and returns the user's Api token
     *
     * @unauthenticated
     * @group Authentication
     * @response 200
     *  "message": "Authenticated",
     *"status": 200,
     *"data": {
     *    "token": "1|KD1heH2TvQuFDn12DrgnTRhMDB6XXeK3TBEmsc3Yad2580df"
     *}
     */
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }
        $user = User::firstWhere('email', $request->get('email'));
        return $this->ok('Authenticated', [
            'token' => $user->createToken(
                'Api token for ' . $user->email,
                Abilities::getAbilities($user),
                now()->addHour()
            )->plainTextToken
        ]);
    }

    public function register()
    {
        //  return $this->ok("register");
    }

    /**
     * Logout
     *
     * Signs out the user and destroy's the API token
     *
     * @unauthenticated
     * @group Authentication
     * @response 200 {}
     *

     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->ok('');
    }
}
