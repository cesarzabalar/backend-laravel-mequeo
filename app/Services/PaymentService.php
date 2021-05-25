<?php

namespace App\Services;

use App\Repositories\Interfaces\CashRegisterRepositoryInterface;
use App\Repositories\Interfaces\MainPaymentRepositoryInterface;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Services\Interfaces\HandlerResponseServiceInterface;

/**
 * Class PaymentService
 * @package App\Services
 */
class PaymentService implements PaymentServiceInterface
{
    /**
     * @var MainPaymentRepositoryInterface
     */
    protected $mainPaymentRepository;

    /**
     * @var CashRegisterRepositoryInterface
     */
    protected $cashRegisterRepository;

    /**
     * @var HandlerResponseServiceInterface
     */
    protected $handlerResponseService;

    /**
     * PaymentService constructor.
     * @param MainPaymentRepositoryInterface $mainPaymentRepository
     * @param CashRegisterRepositoryInterface $cashRegisterRepository
     * @param HandlerResponseServiceInterface $handlerResponseService
     */
    public function __construct(
        MainPaymentRepositoryInterface $mainPaymentRepository,
        CashRegisterRepositoryInterface $cashRegisterRepository,
        HandlerResponseServiceInterface $handlerResponseService
    ){
        $this->mainPaymentRepository = $mainPaymentRepository;
        $this->cashRegisterRepository = $cashRegisterRepository;
        $this->handlerResponseService = $handlerResponseService;
    }

    /**
     * Add payment, validate back money
     *
     * @param array $data
     * @return array
     */
    public function createPayment(array $data): array
    {
        try {
            $totalReturnMoney = $data['total_cash'] - $data['total_product'];
            $returnCashList = $this->getReturnMoney($totalReturnMoney);

            if (empty($returnCashList)) {
                return $this->handlerResponseService->response(__('cash_register.no_return_money'), false);
            }

            $this->mainPaymentRepository->makePayment($data, $returnCashList, $totalReturnMoney);
            return $this->handlerResponseService->response(__('cash_register.payment_success'), true, $returnCashList);
        } catch (\Exception $e) {
            return $this->handlerResponseService->response($e->getMessage(), false);
        }
    }

    /**
     * Get back money paid
     *
     * @param int $totalReturnMoney
     * @return array
     */
    private function getReturnMoney(int $totalReturnMoney): array
    {
        $cashRegisterList = $this->cashRegisterRepository->listCashRegisters();
        $returnCashTmp = $totalReturnMoney;
        $response = [];
        $limit = 200;
        $totalReturn = 0;

        while ($returnCashTmp > 0 && $limit > 0) {
            $cont = 0;
            $limit--;
            foreach ($cashRegisterList as $key => $cashRegister) {
                if ($cashRegister['quantity'] > 0 && $returnCashTmp >= $cashRegister['denomination']) {
                    $cont++;
                    $returnCashTmp -= $cashRegister['denomination'];

                    if (isset($response[$cashRegister['denomination']]) && $response[$cashRegister['denomination']]['denomination'] == $cashRegister['denomination']) {
                        $cont = $response[$cashRegister['denomination']]['quantity'] + 1;
                    }

                    $response[$cashRegister['denomination']] = ['denomination' => $cashRegister['denomination'], 'quantity' => $cont];
                    $totalReturn += $cashRegister['denomination'] * $cont;
                    $cashRegisterList[$key]['quantity'] -= 1;
                    break;
                }
            }
        }

        if ($returnCashTmp > 0) {
            return [];
        }

        $response = $this->prepareDetailsReturn($totalReturn, $response);
        return $response;
    }

    /**
     * @param int $totalReturn
     * @param array $detailsReturn
     */
    private function prepareDetailsReturn(int $totalReturn, array $detailsReturn): array
    {
        $details['total_return'] = $totalReturn;
        $details['detail_return'] = [];
        array_walk($detailsReturn, function($detail) use (&$details){
            $details['detail_return'][] = $detail;
        });

        return $details;
    }
}
