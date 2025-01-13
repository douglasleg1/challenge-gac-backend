<?php 

namespace App\Services\User;

use App\Helpers\ValidateErrors;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserService {

    public function index()
    {
        try {
            $users = User::orderBy('created_at', 'DESC')
                ->get();

            return [
                'status' => true,
                'data' => $users,
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => $error->getCode(),
            ];
        }
    }

    
    public function store(Request $request) {
        try {
            // Validar os dados recebidos
            $validate = $this->validators((array)$request->all());
            
            if ($validate->fails()) {
                $errors = ValidateErrors::toStr($validate->errors());
                throw new Exception($errors, 400);
            }
    
            // Criar o usuário sem o campo "role"
            $userData = $request->except('role');
            $user = User::create($userData);
            
            
            // Atribuir role se informada
            if ($request->has('role')) {
                $role = $request->input('role');
                if (!\Spatie\Permission\Models\Role::where('name', $role)->exists()) {
                    throw new Exception("A role '{$role}' não existe.", 400);
                }
                $user->assignRole($role);
            }
            
            return [
                'status' => true,
                'message' => 'Usuário criado com sucesso.',
                'data' => $user
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => $error->getCode()
            ];
        }
    }
    
    
    public function me()
    {
        try {
            $user = User::with('roles.permissions')->findOrFail(auth()->user()->id);
    
            return [
                'status' => true,
                'data' => $user,
                'message' => 'Usuário localizado com sucesso',
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => $error->getCode(),
            ];
        }
    }
    

    public function show(string $id) {
        try {
            $users = User::where('id', $id)
                ->firstOrFail();

            return [
                'status' => true,
                'data' => $users
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => $error->getCode()
            ];
        }
    }

    public function update(Request $request, string $id) {
        try {
            $validate = $this->validators((array)$request->all());
            
            if ($validate->fails()) {
                $errors = ValidateErrors::toStr($validate->errors());
                throw new Exception($errors, 400);
            }
    
            $user = User::findOrFail($id);
            $userData = $request->except('role');
            $user->update($userData);
    
            // Atualizar role se informada
            if ($request->has('role')) {
                $role = $request->input('role');
                if (!\Spatie\Permission\Models\Role::where('name', $role)->exists()) {
                    throw new Exception("A role '{$role}' não existe.", 400);
                }
                $user->syncRoles([$role]); 
            }
    
            return [
                'status' => true,
                'message' => 'Usuário atualizado com sucesso.',
                'data' => $user
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => $error->getCode()
            ];
        }
    }
    

    public function destroy(string $id) {
        try {
            User::findOrFail($id)->delete();

            return [
                'status' => true
            ];

        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => $error->getCode()
            ];
        }
    }

    private function validators(array $data){
        return Validator::make($data, [
            'name'              => ['required', 'string', 'max:255'],
            'document'          => ['required', 'string', 'max:255', 'unique:users'],
            'phone'             => ['required', 'string', 'max:255'],
            'billing_address'   => ['required', 'string', 'max:255'],
            'login'             => ['required', 'string', 'max:255'],
            'password'          => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);
    }
}