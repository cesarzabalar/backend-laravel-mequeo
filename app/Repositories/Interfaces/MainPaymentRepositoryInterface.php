<?php

namespace App\Repositories\Interfaces;


/**
 * Interface MainPaymentRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface MainPaymentRepositoryInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function makePayment(array $data, array $returnCashList, int $totalReturnMoney): void;

}
