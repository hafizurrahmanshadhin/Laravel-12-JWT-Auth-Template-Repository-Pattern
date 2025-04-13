<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterAgentRequest;
use App\Http\Resources\Api\V1\Auth\RegisterAgentResource;
use App\Services\Api\V1\Auth\AgentService;
use App\Traits\V1\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    private AgentService $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }


    /**
     * Registering an agent
     *
     * @param \App\Http\Requests\Api\V1\Auth\RegisterAgentRequest $registerAgentRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterAgentRequest $registerAgentRequest)
    {
        try {
            $validatedData = $registerAgentRequest->validated();

            $response = $this->agentService->register($validatedData);

            return $this->success(201, 'Registration Successful', new RegisterAgentResource($response));
        } catch (Exception $e) {
            Log::error('AgentController::register', ['error' => $e->getMessage()]);
            return $this->error(500, 'Server Error', $e->getMessage());
        }
    }
}
