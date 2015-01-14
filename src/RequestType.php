<?php

namespace LajosBencz\GoGlobal;

class RequestType extends Enum {
    const HOTEL_SEARCH          = 1;
    const HOTEL_SEARCH_GEO      = 11;
    const BOOKING_INSERT        = 2;
    const BOOKING_CANCEL        = 3;
    const BOOKING_SEARCH        = 4;
    const ADV_BOOKING_SEARCH    = 10;
    const BOOKING_STATUS        = 5;
    const HOTEL_INFO            = 6;
    const HOTEL_INFO_GEO        = 61;
    const VOUCHER_DETAILS       = 8;
    const PIGGIBANK_STATUS      = 12;
    const BOOKING_PAYMENT       = 13;
}