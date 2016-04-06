<?php

namespace Travelhood\Library\Provider\GoGlobal\Response;


use Travelhood\Library\Provider\GoGlobal\Helper;
use Travelhood\Library\Provider\GoGlobal\ResponseAbstract;
use Travelhood\Library\Provider\GoGlobal\ResponseInterface;

class TransferSearch extends ResponseAbstract implements ResponseInterface
{
    protected static $MAP = [
        'ItemCode' => 'code',
        'Item_Text' => 'description',
        'MaximumPassengers' => 'max_passenger',
        'MaximumLuggage' => 'max_luggage',
        'ConfirmationText' => 'status',
        'VehicleText' => 'vehicle_type',
        'Vehicle_ID' => 'vehicle_id',
        'SellingPrice' => 'price',
        'SellingCurrency' => 'currency',
        'CXLDays' => 'cancel_days',
        'VechicleType' => 'vehicle_type_id',
        //'Time_Text' => 'minute',             Lior 2016.03.17-i levele szerint megszűnnek
        //'PriceLateHour' => 'price_late',
    ];

    protected function process() {
        $transfers = [];
        $search_id=(string)$this->_xml->searchID; //ez a fejben van
        foreach($this->_xml->TransferResult as $node) {

            $transfer = Helper::map(self::$MAP, $node); //fenti static alapján átnevezi

            /*
            //órát át kell váltani percé
            preg_match('/((?<hour>[\d\.\,]+)\shours?)?((?<minute>[\d\.\,]+)\smin(ute)?s?)?/',$transfer['minute'],$matches);
            $hour = floatval(str_replace(',','.',$matches['hour']));
            $minute = floatval(str_replace(',','.',$matches['minute']));
            $transfer['minute'] = $hour * 60 + $minute;
            */

            $transfer['search_id']=$search_id ;
            $transfers[] = $transfer;
        }
        $this->_data = $transfers;
    }
}

