<?php

namespace App\Services\Interfaces;

/**
 * Interface CashRegisterServiceInterface
 * @package App\Services\Interfaces
 */
interface CashRegisterServiceInterface
{
    /**
     * This function load base for cash flow
     *
     * @param array $data
     * @return array
     */
    public function createBaseCashRegister(array $data): array;

    /**
     * This function query the cash register status
     *
     * @return array
     */
    public function getStatusCashRegister(): array;

    /**
     * This function set empty the cash register
     *
     * @return array
     */
    public function setEmptyCashRegister(): array;
}
