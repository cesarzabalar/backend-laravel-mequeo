<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CashRegisterRepositoryInterface;
use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Repositories\Interfaces\MainPaymentRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class MainPaymentRepository
 * @package App\Repositories
 */
class MainPaymentRepository implements MainPaymentRepositoryInterface
{
    /**
     * @var PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @var LogRepositoryInterface
     */
    protected $logRepository;

    /**
     * @var CashRegisterRepositoryInterface
     */
    protected $cashRegisterRepository;

    /**
     * MainPaymentRepository constructor.
     * @param PaymentRepositoryInterface $paymentRegisterRepository
     * @param LogRepositoryInterface $logRepository
     * @param CashRegisterRepositoryInterface $cashRegisterRepository
     */
    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        LogRepositoryInterface $logRepository,
        CashRegisterRepositoryInterface $cashRegisterRepository
    ){
        $this->paymentRepository = $paymentRepository;
        $this->logRepository = $logRepository;
        $this->cashRegisterRepository = $cashRegisterRepository;
    }

    /**
     * This funtion save a cash register movement and log
     *
     * @param array $data
     * @param array $returnCashList
     * @param int $totalReturnMoney
     */
    public function makePayment(array $data, array $returnCashList, int $totalReturnMoney): void
    {
        try {
            DB::beginTransaction();

            $payment = $this->paymentRepository->createPayment($data);

            $dataLogEntry = ['type' => 'entry', 'total' => $payment->total_cash];
            $logEntry = $this->logRepository->createLog($dataLogEntry);

            $cashRegister = $this->cashRegisterRepository->getCashRegisterByDenomination($data['total_cash']);
            $cashRegister->logs()->attach($logEntry, ['cash_register_total' => 1]);
            $add = $this->cashRegisterRepository->cashRegisterAddQuantity($cashRegister->id, 1);

            if (!$add) {
                DB::rollBack();
                throw new \Exception(__('cash_register.system_error'), 500);
            }

            $dataLogEgress = ['type' => 'egress', 'total' => $totalReturnMoney];
            $logEgress = $this->logRepository->createLog($dataLogEgress);

            foreach ($returnCashList['detail_return'] as $return) {
                $cashRegister = $this->cashRegisterRepository->getCashRegisterByDenomination($return['denomination']);
                $cashRegister->logs()->attach($logEgress, ['cash_register_total' => $return['quantity']]);
                $subtract = $this->cashRegisterRepository->cashRegisterSubtractQuantity($cashRegister->id, $return['quantity']);
                if (!$subtract) {
                    DB::rollBack();
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), 500);
        }
    }
}
