<?php

namespace App\Repositories\Interfaces;


/**
 * Interface MainCashRegisterRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface MainCashRegisterRepositoryInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function cashRegister(array $data): void;

}
