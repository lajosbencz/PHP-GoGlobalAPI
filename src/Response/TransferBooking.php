<?php

namespace GoGlobal\Response;


use GoGlobal\ResponseAbstract;
use GoGlobal\ResponseInterface;
use GoGlobal\Helper;


class TransferBooking extends ResponseAbstract implements ResponseInterface
{
    protected static $MAP = [
        'GoBookingCode' => 'code',
        'GoReference' => 'reference',
        'ClientBookingCode' => 'client_code',
        'BookingStatus' => 'status',
        'TotalPrice' => 'price',
        'Currency' => 'currency',
        'ArrivalDate' => 'date',
        'CancellationDeadline' => 'cancel_deadline',
    ];

    protected function process() {

        $transfer = Helper::map(self::$MAP, $this->_xml); //fenti static alapján átnevezi

        $this->_data = $transfer;

    }
}

