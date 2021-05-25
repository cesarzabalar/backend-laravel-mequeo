<?php

namespace App\Services\Interfaces;

/**
 * Interface LogServiceInterface
 * @package App\Services\Interfaces
 */
interface LogServiceInterface
{
    /**
     * This function return logs of the Cash Register
     *
     * @return array
     */
    public function getLogs():array;

    /**
     * This funtion return Cash Register status by date
     *
     * @param string $date
     * @return array
     */
    public function getStatusByDate(string $date): array;
}
