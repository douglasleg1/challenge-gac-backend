<?php

namespace App\Services\Log;

use App\Models\Log;
use Illuminate\Http\Request;

class LogService
{
    /**
     * Registra o log de uma requisição.
     *
     * @param Request $request
     * @param string $response
     * @param int $status
     * @param string|null $description
     * @return void
     */
    public function logRequest(Request $request, string $response, int $status, ?string $description = null): void
    {

        //if ($request->method() != 'GET'){
            Log::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'method' => $request->method(),
                'endpoint' => $request->path(),
                'headers' => json_encode($request->headers->all()),
                'body' => json_encode($request->all()),
                'response' => $response,
                'status' => $status,
                'description' => $description ?? 'No description provided', // Valor padrão
                'ip' => $request->ip(),
            ]);
        //}

    }
}
