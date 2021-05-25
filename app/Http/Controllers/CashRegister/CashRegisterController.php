<?php

namespace App\Http\Controllers\CashRegister;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCashRegisterRequest;
use App\Services\Interfaces\CashRegisterServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
/**
 * Class CashRegisterController
 * @package App\Http\Controllers
 */
class CashRegisterController extends Controller
{
    /**
     * @var CashRegisterServiceInterface
     */
    protected $cashRegisterService;

    /**
     * CashFlowController constructor.
     * @param CashRegisterServiceInterface $cashRegisterService
     */
    public function __construct(CashRegisterServiceInterface $cashRegisterService)
    {
        $this->cashRegisterService = $cashRegisterService;
    }

    /**
     * @param CreateCashRegisterRequest $createCashRegisterRequest
     * @return JsonResponse
     */
    public function createBaseCashRegister(CreateCashRegisterRequest $createCashRegisterRequest): JsonResponse
    {
        $response = $this->cashRegisterService->createBaseCashRegister($createCashRegisterRequest->validated());

        if(!$response['success']){
            return response()->json($response, 500);
        }

        return response()->json($response, 201);
    }

    /**
     * @return JsonResponse
     */
    public function getStatusCashRegister(): JsonResponse
    {
        $response = $this->cashRegisterService->getStatusCashRegister();

        return response()->json($response, 200);
    }

    /**
     * @return JsonResponse
     */
    public function setEmptyCashRegister(): JsonResponse
    {
        $response = $this->cashRegisterService->setEmptyCashRegister();

        if(!$response['success']){
            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
