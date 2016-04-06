<?php

namespace Travelhood\Library\Provider\GoGlobal\Response;


use Travelhood\Library\Provider\GoGlobal\ResponseAbstract;
use Travelhood\Library\Provider\GoGlobal\ResponseInterface;
use Travelhood\Library\Provider\GoGlobal\Helper;


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

