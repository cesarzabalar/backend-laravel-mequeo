<?php

namespace App\Services;

use App\Services\Interfaces\HandlerResponseServiceInterface;

/**
 * Class HandlerResponseService
 * @package App\Services
 */
class HandlerResponseService implements HandlerResponseServiceInterface
{
    /**
     * This function return standar response structure
     *
     * @param string $message
     * @param bool $success
     * @param array $data
     * @return array
     */
    public function response(string $message, bool $success, array $data = []):array
    {
        return ['message' => $message, 'success' => $success, 'data' => $data];
    }
}
