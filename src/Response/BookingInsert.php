<?php

namespace GoGlobal\Response;

use GoGlobal\Helper;
use GoGlobal\ResponseAbstract;
use GoGlobal\ResponseInterface;

class BookingInsert extends ResponseAbstract implements ResponseInterface
{
    protected static $MAP_INFO = [
        'GoBookingCode' => 'code',
        'GoReference' => 'reference',
        'ClientBookingCode' => 'client_code',
        'BookingStatus' => 'status',
        'TotalPrice' => 'price',
        'Currency' => 'currency',
        'HotelName' => 'hotel_name',
        'HotelSearchCode' => 'hotel_search_code',
        'RoomType' => 'room_type',
        'RoomBasis' => 'room_basis',
        'ArrivalDate' => 'date_from',
        'CancellationDeadline' => 'cancel_deadline',
        'Nights' => 'nights',
        'Remark' => 'remark',
    ];

	protected function process() {
        $this->_data = Helper::map(self::$MAP_INFO, $this->_xml);
        $this->_data['alternative_hotel'] = !is_object($this->_xml->NoAlternativeHotel);
        $this->_data['leader_person'] = max(1,$this->_xml->Leader['LeaderPersonID']);
	}
}
