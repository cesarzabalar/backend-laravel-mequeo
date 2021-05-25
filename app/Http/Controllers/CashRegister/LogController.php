<?php

namespace App\Http\Controllers\CashRegister;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetLogsByDateRequest;
use App\Services\Interfaces\LogServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class LogController
 * @package App\Http\Controllers
 */
class LogController extends Controller
{
    /**
     * @var LogServiceInterface
     */
    protected $logService;

    /**
     * LogController constructor.
     * @param LogServiceInterface $logService
     */
    public function __construct(
        LogServiceInterface $logService
    )
    {
        $this->logService = $logService;
    }

    /**
     * @return JsonResponse
     */
    public function getLogs(): JsonResponse
    {
        $response = $this->logService->getLogs();

        if (!$response['success']) {
            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    /**
     * @param GetLogsByDateRequest $getLogsByDateRequest
     * @return JsonResponse
     */
    public function getStatusByDate(GetLogsByDateRequest $getLogsByDateRequest): JsonResponse
    {
        $request = $getLogsByDateRequest->validated();
        $response = $this->logService->getStatusByDate($request['date']);

        if (!$response['success']) {
            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
