<?php

namespace App\Services;

use App\Repositories\Interfaces\CashRegisterRepositoryInterface;
use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Repositories\Interfaces\MainCashRegisterRepositoryInterface;
use App\Services\Interfaces\CashRegisterServiceInterface;
use App\Services\Interfaces\HandlerResponseServiceInterface;

/**
 * Class CashRegisterService
 * @package App\Services
 */
class CashRegisterService implements CashRegisterServiceInterface
{
    /**
     * @var MainCashRegisterRepositoryInterface
     */
    protected $mainCashRegisterRepository;

    /**
     * @var CashRegisterRepositoryInterface
     */
    protected $cashRegisterRepository;

    /**
     * @var LogRepositoryInterface
     */
    protected $logRepository;

    /**
     * @var HandlerResponseServiceInterface
     */
    protected $handlerResponseService;

    /**
     * CashRegisterService constructor.
     * @param MainCashRegisterRepositoryInterface $mainCashRegisterRepository
     * @param CashRegisterRepositoryInterface $cashRegisterRepository
     * @param LogRepositoryInterface $logRepository
     * @param HandlerResponseServiceInterface $handlerResponseService
     */
    public function __construct(
        MainCashRegisterRepositoryInterface $mainCashRegisterRepository,
        CashRegisterRepositoryInterface $cashRegisterRepository,
        LogRepositoryInterface $logRepository,
        HandlerResponseServiceInterface $handlerResponseService
    ){
        $this->mainCashRegisterRepository = $mainCashRegisterRepository;
        $this->cashRegisterRepository = $cashRegisterRepository;
        $this->logRepository = $logRepository;
        $this->handlerResponseService = $handlerResponseService;
    }

    /**
     * This function load base for cash register
     *
     * @param array $data
     * @return array
     */
    public function createBaseCashRegister(array $data): array
    {
        try {
            $this->mainCashRegisterRepository->cashRegister($data);
            return $this->handlerResponseService->response( __('cash_register.create_success'), true);
        }catch (\Exception $e){
            return $this->handlerResponseService->response($e->getMessage(), false);
        }
    }

    /**
     * @return array
     */
    public function getStatusCashRegister(): array
    {
        try {
            $listCashRegister = $this->cashRegisterRepository->listCashRegisters();
            $cashStatus = $this->calculateCashStatus($listCashRegister);

            return $this->handlerResponseService->response(__('cash_register.status_register'), true, $cashStatus);
        }catch (\Exception $e){
            return $this->handlerResponseService->response($e->getMessage(), false);
        }
    }

    /**
     * @param array $listCashRegister
     * @return array
     */
    private function calculateCashStatus(array $listCashRegister): array
    {
        $data['total_cash_register'] = $this->calculateTotalCash($listCashRegister);
        $data['inventory'] = $this->showCashInventory($listCashRegister);

        return $data;
    }

    /**
     * This function calculate the cash register total
     *
     * @param array $movements
     * @return int
     */
    private function calculateTotalCash(array $movements): int
    {
        $totalCash = 0;

        array_walk($movements, function($movement) use (&$totalCash){
            $totalCash += $movement['denomination'] * $movement['quantity'];
        });

        return $totalCash;
    }

    /**
     * This function list the cash register inventory
     *
     * @param array $movements
     * @return array
     */
    private function showCashInventory(array $movements): array
    {
        $inventory = [];

        array_walk($movements, function($movement) use (&$inventory){
            $inventory[$movement['type']][] = [
                'denomination' => $movement['denomination'],
                'quantity' => $movement['quantity']
            ];
        });

        return $inventory;
    }

    /**
     * @return array
     */
    public function setEmptyCashRegister(): array
    {
        $response = $this->cashRegisterRepository->setEmptyCashRegister();
        if(!$response){
            return $this->handlerResponseService->response(__('cash_register.system_error'), false);
        }

        return $this->handlerResponseService->response(__('cash_register.empty_cash_register_success'), true);
    }
}
