<?php

namespace App\Repositories;

use App\Models\CashRegister;
use App\Repositories\Interfaces\CashRegisterRepositoryInterface;

/**
 * Class CashFlowRepository
 * @package App\Repositories
 */
class CashRegisterRepository implements CashRegisterRepositoryInterface
{

    /**
     * @var CashRegister
     */
    protected $cashRegister;

    /**
     * CashFlowRepository constructor.
     * @param CashRegister $cashFlow
     */
    public function __construct(CashRegister $cashRegister)
    {
        $this->cashRegister = $cashRegister;
    }

    /**
     * @param array|string[] $columns
     * @return array
     */
    public function listCashRegisters(array $columns = ['*']): array
    {
        $cashRegisterList = $this->cashRegister->get($columns);

        return (empty($cashRegisterList)) ? [] : $cashRegisterList->toArray();
    }

    /**
     * @param array $data
     * @return CashRegister
     */
    public function createCashRegister(array $data): CashRegister
    {
        $cashRegister = $this->cashRegister->where('denomination', $data['denomination'])->first();

        if($cashRegister){
            $this->cashRegisterAddQuantity($cashRegister->id, $data['quantity']);
            return $cashRegister;
        }

        return $this->cashRegister->create($data);
    }

    /**
     * @param int $denomination
     * @return CashRegister
     */
    public function getCashRegisterByDenomination(int $denomination): CashRegister
    {
        return $this->cashRegister->where('denomination', $denomination)->first();
    }

    /**
     * @param int $id
     * @param $quantity
     * @return bool
     */
    public function cashRegisterAddQuantity(int $id, $quantity): bool
    {
        $cashRegister = $this->cashRegister->where('id', $id)->first();
        $cashRegister->quantity = $cashRegister->quantity + $quantity;

        return $cashRegister->save();
    }

    /**
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function cashRegisterSubtractQuantity(int $id, int $quantity): bool
    {
        $cashRegister = $this->cashRegister->where('id', $id)->first();
        $cashRegister->quantity = $cashRegister->quantity - $quantity;

        return $cashRegister->save();
    }

    /**
     * @return bool
     */
    public function setEmptyCashRegister(): bool
    {
        return $this->cashRegister->where('quantity','>', 0)->update(['quantity' => 0]);
    }
}
