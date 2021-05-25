<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CashRegisterRepositoryInterface;
use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Repositories\Interfaces\MainCashRegisterRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class MainCashRegisterRepository
 * @package App\Repositories
 */
class MainCashRegisterRepository implements MainCashRegisterRepositoryInterface
{
    /**
     * @var CashRegisterRepositoryInterface
     */
    protected $cashRegisterRepository;

    /**
     * @var LogRepositoryInterface
     */
    protected $logRepository;

    /**
     * MainCashRegisterRepository constructor.
     * @param CashRegisterRepositoryInterface $cashRegisterRepository
     * @param LogRepositoryInterface $logRepository
     */
    public function __construct(
        CashRegisterRepositoryInterface $cashRegisterRepository,
        LogRepositoryInterface $logRepository
    ){
        $this->cashRegisterRepository = $cashRegisterRepository;
        $this->logRepository = $logRepository;
    }

    /**
     * This funtion save a cash register movement and log
     *
     * @param array $data
     */
    public function cashRegister(array $data): void
    {
        try {
            DB::beginTransaction();
            $cashRegister = $this->cashRegisterRepository->createCashRegister($data);
            $dataLog = ['type' => 'load', 'total' => $cashRegister->denomination];
            $log = $this->logRepository->createLog($dataLog);
            $cashRegister->logs()->attach($log, ['cash_register_total' => $cashRegister->quantity]);
            DB::commit();
        }catch (\Throwable $e){
            DB::rollBack();
            throw new \Exception($e->getMessage(), 500);
        }
    }
}
