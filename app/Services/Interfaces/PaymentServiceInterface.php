<?php

namespace App\Services\Interfaces;

/**
 * Interface PaymentServiceInterface
 * @package App\Services\Interfaces
 */
interface PaymentServiceInterface
{
    /**
     * This function create a payment
     *
     * @param array $data
     * @return array
     */
    public function createPayment(array $data): array;
}
