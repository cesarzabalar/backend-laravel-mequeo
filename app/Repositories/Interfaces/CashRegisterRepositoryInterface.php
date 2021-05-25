<?php

namespace App\Repositories\Interfaces;

use App\Models\CashRegister;

/**
 * Interface CashRegisterRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface CashRegisterRepositoryInterface
{
    /**
     * @param array|string[] $columns
     * @return array
     */
    public function listCashRegisters(array $columns = ['*']): array;

    /**
     * @param array $data
     * @return CashRegister
     */
    public function createCashRegister(array $data): CashRegister;

    /**
     * @param int $denomination
     * @return CashRegister
     */
    public function getCashRegisterByDenomination(int $denomination): CashRegister;

    /**
     * @param int $id
     * @param $quantity
     * @return bool
     */
    public function cashRegisterAddQuantity(int $id, $quantity): bool;

    /**
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function cashRegisterSubtractQuantity(int $id, int $quantity): bool;

    /**
     * @return bool
     */
    public function setEmptyCashRegister(): bool;
}
