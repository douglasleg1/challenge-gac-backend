<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Método para login de usuários.
     */
    public function login(Request $request)
    {
        try {
            // Validação dos campos
            $validate = Validator::make($request->all(), [
                'login' => ['required', 'string'], // Corrigido
                'password' => ['required', 'string'],
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validate->errors(),
                ], 422);
            }

            // Busca do usuário pelo login
            $user = User::where('login', $request->login)->first();

            // Verificação de credenciais
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Credenciais inválidas',
                ], 401);
            }

            // Geração do token para autenticação
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Usuário autenticado com sucesso',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Erro interno no servidor',
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Método para logout do usuário.
     */
    public function logout(Request $request)
    {
        try {
            // Deletar o token atual do usuário
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout realizado com sucesso',
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao realizar logout',
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
