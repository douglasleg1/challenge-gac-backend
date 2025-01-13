<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(
        private UserService $userService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->userService->index();
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->userService->store($request);

        if ($data['status']) {
            return $this->response(
                status: $data['status'],
                data: $data['data'],
                message: 'Usuário criado com sucesso',
                httpStatusCode: 200
            );
        }

        return $this->response(
            status: $data['status'],
            message: $data['error'],
            httpStatusCode: 400
        );
    }
    
    public function me() {
        $data = $this->userService->me();
        return $this->response(
            status: $data['status'],
            data: $data['data'],
            message: 'Usuário localizado com sucesso',
            httpStatusCode: 200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->userService->show($id);

        if ($data['status']) {
            return $this->response(
                status: $data['status'],
                data: $data['data'],
                message: 'Usuário localizado com sucesso',
                httpStatusCode: 200
            );
        }

        return $this->response(
            status: $data['status'],
            message: $data['error'],
            httpStatusCode: 400
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $this->userService->update($request, $id);

        if ($data['status']) {
            return $this->response(
                status: $data['status'],
                data: $data['data'],
                message: 'Usuário atualizado com sucesso',
                httpStatusCode: 200
            );
        }

        return $this->response(
            status: $data['status'],
            message: $data['error'],
            httpStatusCode: 400
        );    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->userService->destroy($id);

        if ($data['status']) {
            return $this->response(
                status: $data['status'],
                message: 'Usuário deletado com sucesso',
                httpStatusCode: 200
            );
        }

        return $this->response(
            status: $data['status'],
            message: $data['error'],
            httpStatusCode: 400
        );    
    }

}