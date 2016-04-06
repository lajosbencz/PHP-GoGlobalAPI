<?php

namespace Travelhood\Library\Provider\GoGlobal\Response;

use Travelhood\Library\Provider\GoGlobal\ResponseInterface;

class HotelSearch extends HotelSearchGeo implements ResponseInterface
{

	protected static $MAP_HOTEL = [
		'HotelCode' => 'hotel_code',
		'HotelName' => 'hotel_name',
		'CountryId' => 'country_id',
		'Category' => 'category',
		'Location' => 'location',
		//'LocationCode' => 'location_code',
		'Thumbnail' => 'thumbnail',
		'TripAdvisor' => [
			'Rating' => 'ta_rating',
			'RatingImage' => 'ta_rating_url',
			'ReviewCount' => 'ta_review_count',
			'Reviews' => 'ta_review_url',
		],
	];

	protected static $MAP_ROOM = [
		'HotelSearchCode' => 'hotel_search_code',
		'CxlDeadline' => 'date_cancel',
		'RoomType' => 'room_description',
		'RoomBasis' => 'room_basis',
		//'Availability' => 'availability',
		'TotalPrice' => 'price',
		'Currency' => 'currency_id',
		'Preferred' => 'preferred',
		'Remark' => 'comment',
		'SpecialOffer' => 'special_offer',
	];

}