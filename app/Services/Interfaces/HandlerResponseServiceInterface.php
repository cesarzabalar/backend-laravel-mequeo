<?php

namespace App\Services\Interfaces;

/**
 * Interface HandlerResponseServiceInterface
 * @package App\Services\Interfaces
 */
interface HandlerResponseServiceInterface
{
    /**
     * This function return standar response structure
     *
     * @param string $message
     * @param bool $success
     * @param array $data
     * @return array
     */
    public function response(string $message, bool $success, array $data = []):array;
}
