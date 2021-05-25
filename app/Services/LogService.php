<?php

namespace App\Services;

use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Services\Interfaces\LogServiceInterface;
use App\Services\Interfaces\HandlerResponseServiceInterface;

/**
 * Class LogService
 * @package App\Services
 */
class LogService implements LogServiceInterface
{
    /**
     * @const type movement egress
     */
    private const EGRESS = 'egress';

    /**
     * @var LogRepositoryInterface
     */
    protected $logRepository;

    /**
     * @var HandlerResponseServiceInterface
     */
    protected $handlerResponseService;

    /**
     * LogService constructor.
     * @param LogRepositoryInterface $logRepository
     * @param HandlerResponseServiceInterface $handlerResponseService
     */
    public function __construct(
        LogRepositoryInterface $logRepository,
        HandlerResponseServiceInterface $handlerResponseService
    ){
        $this->logRepository = $logRepository;
        $this->handlerResponseService = $handlerResponseService;
    }

    /**
     * This function get a list of logs
     * @return array
     */
    public function getLogs(): array
    {
        $data = [];

        $listLogs = $this->logRepository->listLogs();

        if (empty($listLogs)) {
            return $this->handlerResponseService->response(__('cash_register.no_logs'), false);
        }

        $data = $this->getMovementLogs($listLogs);

        return $this->handlerResponseService->response('', true, $data);
    }

    /**
     * This function get the movements of the cash register
     *
     * @param array $movements
     * @return array
     */
    private function getMovementLogs(array $movements): array
    {
        $data = [];
        array_walk($movements, function($logMovement) use (&$data){
            $data[] = [
                'type' => $logMovement['type'],
                'id' =>$logMovement['id'],
                'total' => $logMovement['total'],
                'movements' => $this->getMovemenstLogs($logMovement)
            ];
        });

        return $data;
    }

    /**
     * This function get the movements of a log
     *
     * @param array $movement
     * @return array
     */
    private function getMovemenstLogs(array $movement): array
    {
        $data = [];
        array_walk($movement['cash_register'], function($log) use (&$data){
            $data[] = [
                'type' => $log['type'],
                'denomination' => $log['denomination'],
                'quantity' => $log['pivot']['cash_register_total']
            ];
        });

        return $data;
    }

    /**
     * This function get logs by date
     *
     * @param string $date
     * @return array
     */
    public function getStatusByDate(string $date): array
    {
        $totalCashRegister = 0;

        $logsFound = $this->logRepository->getStatusByDate($date);

        if (empty($logsFound)) {
            return $this->handlerResponseService->response( __('cash_register.no_logs'), false);
        }

        $totalCashRegister = $this->calculateTotalCash($logsFound);
        return $this->handlerResponseService->response('', true, ['total_cash_register' => $totalCashRegister]);
    }

    /**
     * This function calculate the global total cash register
     *
     * @param array $logs
     * @return int
     */
    private function calculateTotalCash(array $logs): int
    {
        $totalCashRegister = 0;

        array_walk($logs, function($log) use (&$totalCashRegister){
            if($log['type'] == self::EGRESS){
                $totalCashRegister -= $log['total'];
            } else {
                $totalCashRegister += $log['total'];
            }
        });

        return $totalCashRegister;
    }
}
