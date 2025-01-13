<?php

namespace App\Http\Middleware;

use App\Services\Log\LogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    protected $logsService;

    public function __construct(LogService $logsService)
    {
        $this->logsService = $logsService;
    }

    /**
     * Manipula uma requisição e registra os logs.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Processa a requisição
        $response = $next($request);

        // Registra o log da requisição
        $this->logsService->logRequest(
            $request,
            $response->getContent(),
            $response->getStatusCode()
        );

        return $response;
    }
}
