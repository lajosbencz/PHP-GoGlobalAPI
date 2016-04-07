<?php

namespace GoGlobal;

use SimpleXMLElement;
use GoGlobal\Enum\Star;

class Response
{
    public static function parseSearchHotels($xml)
    {
        $r = [];
        if((int)$xml->Header->Statistics->ResultsQty>0) {
            foreach($xml->Main->children() as $row) {
                $u = explode('/',$row->HotelSearchCode);
                $cxl = trim((string)$row->CxlDeadline);
                if(!$cxl || $cxl == '0000-00-00') $cxl = null;
                if(preg_match('/([\d]{1,2})\/([a-z\d]+)\/([\d]{4})/i',$cxl,$m)) {
                    switch($m[2]) {
                        case 'Jan': $m[2] = '01'; break;
                        case 'Feb': $m[2] = '02'; break;
                        case 'Mar': $m[2] = '03'; break;
                        case 'Apr': $m[2] = '04'; break;
                        case 'May': $m[2] = '05'; break;
                        case 'Jun': $m[2] = '06'; break;
                        case 'Jul': $m[2] = '07'; break;
                        case 'Aug': $m[2] = '08'; break;
                        case 'Sep': $m[2] = '09'; break;
                        case 'Oct': $m[2] = '10'; break;
                        case 'Nov': $m[2] = '11'; break;
                        case 'Dec': $m[2] = '12'; break;
                    }
                    $cxl = $m[3].'-'.$m[2].'-'.$m[1];
                }
                $cat = (string)$row->Category;
                $i = [
                    'pid' => (string)$row->HotelSearchCode,
                    'hotel_unique' => $u[0],
                    'hotel_search_code' => (string)$row->HotelSearchCode,
                    'hotel_code' => (string)$row->HotelCode,
                    'hotel_name' => (string)$row->HotelName,
                    'provider_country_id' => (string)$row->CountryId,
                    'refund_date' => $cxl,
                    'room_description' => (string)$row->RoomType,
                    'board_id' => (string)$row->RoomBasis,
                    //'hotel_search_code' => (string)$row->Availability,
                    'price' => (string)$row->TotalPrice,
                    'currency_id' => (string)$row->Currency,
                    'category' => Star::toFloat($cat),
                    'location' => (string)$row->Location,
                    //'hotel_search_code' => (string)$row->LocationCode,
                    //'hotel_search_code' => (string)$row->Preferred,
                    'remark' => '<strong>'.(string)$row->SpecialOffer.'</strong><br/>'.(string)$row->Remark,
                    'thumbnail' => (string)$row->Thumbnail,
                ];
                if(count($row->GeoCodes->children()) >= 2) {
                    $i['latitude'] = (string)$row->GeoCodes->Latitude;
                    $i['longitude'] = (string)$row->GeoCodes->Longitude;
                }
                if(count($row->TripAdvisor->children()) >= 4) {
                    $i['ta_rating'] = (string)$row->TripAdvisor->Rating;
                    $i['ta_rating_url'] = (string)$row->TripAdvisor->RatingImage;
                    $i['ta_review_url'] = (string)$row->TripAdvisor->Reviews;
                    $i['ta_review_count'] = (string)$row->TripAdvisor->ReviewCount;
                }
                $r[] = $i;
            }
        }
        return $r;
    }

    public static function parseHotelInfo($xml) {
        $i = $xml->Main;
        $r = [
            'Pictures' => [],
        ];
        foreach(['HotelCode','HotelName','Address','CityCode','Phone','Fax','Category','Description','HotelFacilities','RoomFacilities','RoomCount','Location'] as $p) {
            $r[$p] = (string)$i->$p;
        }
        foreach($i->Pictures->children() as $pic) {
            $r['Pictures'][] = (string)$pic;
        }
        if(count($i->GeoCodes->children()) >= 2) {
            $r['Latitude'] 	= (string) $i->GeoCodes->Latitude;
            $r['Longitude'] 	= (string) $i->GeoCodes->Longitude;
        }
        if(count($i->TripAdvisor->children()) >= 4) {
            $r['Rating'] 	= (string) $i->TripAdvisor->Rating;
            $r['RatingImage'] 	= (string) $i->TripAdvisor->RatingImage;
            $r['Reviews']	= (string) $i->TripAdvisor->Reviews;
            $r['ReviewCount'] 	= (string) $i->TripAdvisor->ReviewCount;
        }
        return $r;
    }

    public static function parseBookingInsert ($xml) {
        $booking = $xml->Main;
        $response = [
            'GoBookingCode' 		=> (string) $booking->GoBookingCode,
            'GoReference' 			=> (string) $booking->GoReference,
            'ClientBookingCode' 		=> (string) $booking->ClientBookingCode,
            'BookingStatus' 		=> (string) $booking->BookingStatus,
            'TotalPrice' 			=> (string) $booking->TotalPrice,
            'Currency' 			=> (string) $booking->Currency,
            'HotelName'			=> (string) $booking->HotelName,
            'HotelSearchCode' 		=> (string) $booking->HotelSearchCode,
            'CityCode' 			=> (string) $booking->CityCode,
			'RoomType' 			=> (string) $booking->RoomType,
			'RoomBasis' 			=> (string) $booking->RoomBasis,
			'ArrivalDate' 			=> (string) $booking->ArrivalDate,
			'CancellationDeadline' 		=> (string) $booking->CancellationDeadline,
			'Nights' 			=> (string) $booking->Nights,
			'NoAlternativeHotel' 		=> (string) $booking->NoAlternativeHotel,
			'LeaderPersonID' 		=> (string) $booking->Leader->attributes()->LeaderPersonID,
			'Remark' 			=> (string) $booking->Remark,
			'Preferences'			=> [],
			'Rooms'				=> []
		];
		foreach($booking->Preferences as $preference) {
            $key = (string) $preference->getName();
			$value = (string) $preference;
			$booking->Preferences[$key] = $value;
		}
		foreach($booking->Rooms as $roomType) {
            $roomTypeData = [
                "Type" 		=> (string) $roomType->attributes()->Type,
                "Adults" 	=> (string) $roomType->attributes()->Adults,
                "Cots" 		=> (string) $roomType->attributes()->Cots,
                "Rooms"		=> []
            ];
            foreach($roomType as $room) {
                $roomData = [
                    'RoomID' 	=> $room->attributes()->RoomID,
                    'Category' 	=> $room->attributes()->Category,
                    'Persons'	=> []
                ];
                foreach($room as $person){
                    if($person->getName() == "PersonName"){
                        $person = [
                            'PersonID' 		=> $person->attributes()->PersonID,
                            'Type' 			=> 'ADT',
                            'PersonName' 		=> (string) $person
                        ];
                    } else {
                        $person = [
                            'PersonID' 		=> $person->attributes()->PersonID,
                            'Type' 			=> 'CHD',
                            'Age'			=> $person->attributes()->ChildAge,
                            'PersonName' 		=> (string) $person
                        ];
                    }
                    $roomData['Persons'][] = $person;
                }
                $roomTypeData['Rooms'][] = $roomData;
            }
            $response['Rooms'][] = $roomTypeData;
        }
    		return $response;
	}

    public static function parseBookingCancel ($xml) {
        $booking = $xml->Main;
        $response = [
            'GoBookingCode' => $booking->GoBookingCode,
            'BookingStatus' => $booking->BookingStatus,
        ];
        return $response;
    }

    public static function parseBookingSearch ($xmlString) {
        $xml = new SimpleXMLElement($xmlString);
        $booking = $xml->Main;
        $response = [
            'GoBookingCode' 		=> (string) $booking->GoBookingCode,
            'GoReference' 			=> (string) $booking->GoReference,
            'ClientBookingCode' 		=> (string) $booking->ClientBookingCode,
            'BookingStatus' 		=> (string) $booking->BookingStatus,
            'TotalPrice' 			=> (string) $booking->TotalPrice,
            'Currency' 			=> (string) $booking->Currency,
            'HotelName'			=> (string) $booking->HotelName,
            'HotelSearchCode' 		=> (string) $booking->HotelSearchCode,
            'CityCode' 			=> (string) $booking->CityCode,
			'RoomType' 			=> (string) $booking->RoomType,
			'RoomBasis' 			=> (string) $booking->RoomBasis,
			'ArrivalDate' 			=> (string) $booking->ArrivalDate,
			'CancellationDeadline' 		=> (string) $booking->CancellationDeadline,
			'Nights' 			=> (string) $booking->Nights,
			'NoAlternativeHotel' 		=> (string) $booking->NoAlternativeHotel,
			'LeaderPersonID' 		=> (string) $booking->Leader->attributes()->LeaderPersonID,
			'Remark' 			=> (string) $booking->Remark,
			'Preferences'			=> [],
			'Rooms'				=> []
		];
		foreach($booking->Preferences as $preference) {
            $key = (string) $preference->getName();
			$value = (string) $preference;
			$booking->Preferences[$key] = $value;
		}
		foreach($booking->Rooms as $roomType) {
            $roomTypeData = [
                "Type" 		=> (string) $roomType->attributes()->Type,
                "Adults" 	=> (string) $roomType->attributes()->Adults,
                "Cots" 		=> (string) $roomType->attributes()->Cots,
                "Rooms"		=> []
            ];
            foreach($roomType as $room) {
                $roomData = [
                    'RoomID' 	=> $room->attributes()->RoomID,
                    'Category' 	=> $room->attributes()->Category,
                    'Persons'	=> []
                ];
                foreach($room as $person){
                    if($person->getName() == "PersonName"){
                        $person = [
                            'PersonID' 		=> $person->attributes()->PersonID,
                            'Type' 			=> 'ADT',
                            'PersonName' 	=> (string) $person
                        ];
                    } else {
                        $person = [
                            'PersonID' 		=> $person->attributes()->PersonID,
                            'Type' 			=> 'CHD',
                            'Age'			=> $person->attributes()->ChildAge,
                            'PersonName' 	=> (string) $person
                        ];
                    }
                    $roomData['Persons'][] = $person;
                }
                $roomTypeData['Rooms'][] = $roomData;
            }
            $response['Rooms'][] = $roomTypeData;
        }
        return $response;
	}

    public static function parseBookingSearchAdv ($xml) {
        $responses = [];
        foreach($xml->Main->Bookings->Booking as $booking){
            $response = [
                'GoBookingCode' 		=> (string) $booking->GoBookingCode,
                'GoReference' 			=> (string) $booking->GoReference,
                'ClientBookingCode' 		=> (string) $booking->ClientBookingCode,
                'BookingStatus' 		=> (string) $booking->BookingStatus,
                'TotalPrice' 			=> (string) $booking->TotalPrice,
                'Currency' 			=> (string) $booking->Currency,
                'HotelName'			=> (string) $booking->HotelName,
                'HotelSearchCode' 		=> (string) $booking->HotelSearchCode,
                'CityCode' 			=> (string) $booking->CityCode,
				'RoomType' 			=> (string) $booking->RoomType,
				'RoomBasis' 			=> (string) $booking->RoomBasis,
				'ArrivalDate' 			=> (string) $booking->ArrivalDate,
				'CancellationDeadline' 		=> (string) $booking->CancellationDeadline,
				'Nights' 			=> (string) $booking->Nights,
				'NoAlternativeHotel' 		=> (string) $booking->NoAlternativeHotel,
				'LeaderPersonID' 		=> (string) $booking->Leader->attributes()->LeaderPersonID,
				'Remark' 			=> (string) $booking->Remark,
				'Preferences'			=> [],
				'Rooms'					=> []
			];
			foreach($booking->Preferences as $preferance) {
                $key = (string) $preferance->getName();
				$value = (string) $preferance;
				$booking->Preferences[$key] = $value;
			}
			foreach($booking->Rooms as $roomType) {
                $roomTypeData = [
                    "Type" 		=> (string) $roomType->attributes()->Type,
                    "Adults" 	=> (string) $roomType->attributes()->Adults,
                    "Cots" 		=> (string) $roomType->attributes()->Cots,
                    "Rooms"		=> []
                ];
                foreach($roomType as $room) {
                    $roomData = [
                        'RoomID' 	=> $room->attributes()->RoomID,
                        'Category' 	=> $room->attributes()->Category,
                        'Persons'	=> []
                    ];
                    foreach($room as $person){
                        if($person->getName() == "PersonName"){
                            $person = [
                                'PersonID' 		=> $person->attributes()->PersonID,
                                'Type' 			=> 'ADT',
                                'PersonName' 		=> (string) $person
                            ];
                        } else {
                            $person = [
                                'PersonID' 		=> $person->attributes()->PersonID,
                                'Type' 			=> 'CHD',
                                'Age'			=> $person->attributes()->ChildAge,
                                'PersonName' 		=> (string) $person
                            ];
                        }
                        $roomData['Persons'][] = $person;
                    }
                    $roomTypeData['Rooms'][] = $roomData;
                }
                $response['Rooms'][] = $roomTypeData;
            }
			$responses[] = $response;
		}
        return $responses;
	}

    public static function parseBookingCheckStatus ($xml) {
        $responses = [];
        foreach($xml->Main->GoBookingCode as $booking){
            $id = (string) $booking;
            $response = [
                'GoBookingCode' => $id,
                'GoReference' 	=> (string) $booking->attributes()->GoReference,
                'Status' 	=> (string) $booking->attributes()->Status,
                'TotalPrice' 	=> (string) $booking->attributes()->TotalPrice,
                'Currency' 	=> (string) $booking->attributes()->TotalPrice,
            ];
            $responses[$id] = $response;
        }
        return $responses;
	}
}