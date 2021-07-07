<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'As credenciais fornecidas estão incorretas!'
            ], 400);
        }

        $token = $user->createToken();

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $user = User::getUserByValidToken($request->token)->first();
        if(!$user){
            return response()->json([
                'message' => 'Token inválido!'
            ], 400);
        }

       $user->invalidateToken();

        return response()->json([
            'message' => 'Usuário deslogado com sucesso!'
        ], 200);
    }

    public function checkToken(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $user = User::getUserByValidToken($request->token)->first();
        if(!$user){
            return response()->json([
                'message' => 'Token inválido!',
                'expired' => true
            ], 200);
        }

        return response()->json([
            'message' => 'Token Válido!',
            'user' => $user,
            'expired' => false
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
