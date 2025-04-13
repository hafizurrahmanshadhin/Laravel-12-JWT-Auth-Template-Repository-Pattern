<?php

namespace App\Services\Api\V1\Auth;

use App\Repositories\Api\V1\Auth\OTPRepositoryInterface;
use App\Repositories\Api\V1\Auth\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AgentService
{
    protected UserRepositoryInterface $userRepository;
    protected OTPRepositoryInterface $otpRepository;

    protected $businessId;

    /**
     * Constructor for initializing the class with UserRepository and OTPRepository dependencies.
     *
     * @param UserRepositoryInterface $userRepository The repository used for user-related data operations.
     * @param OTPRepositoryInterface $otpRepository The repository used for OTP-related data operations.
     */
    public function __construct(UserRepositoryInterface $userRepository, OTPRepositoryInterface $otpRepository)
    {
        $this->userRepository = $userRepository;
        $this->otpRepository = $otpRepository;
        $this->businessId = Auth::user()->business()->id;
    }


    /**
     * Registers a new user and generates an authentication token.
     *
     * Creates a user using the provided credentials, sends an OTP to the user's email,
     * and attempts to generate a JWT token. If successful, it returns the token and user details.
     * If an error occurs, it rolls back the transaction and logs the error.
     *
     * @param array $credentials The user's registration details, including email and password.
     *
     * @return array The registration result, including the generated token, user's role, OTP status, and verification status.
     */
    public function register(array $credentials): array
    {
        try {
            DB::beginTransaction();
            // create user with role 3 (Agent)
            $user = $this->userRepository->createUser($credentials, $this->businessId, 3);
            $otp = $this->otpRepository->sendOtp($user, 'email');
            DB::commit();
            $user->load(['profile' => function ($query) {
                $query->select('id', 'user_id', 'phone', 'address', 'date_of_birth', 'bio');
            }, 'role']);
            return ['user' => $user, 'verify' => false];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('AgentService::register', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

}
