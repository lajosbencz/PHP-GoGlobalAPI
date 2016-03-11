<?php

namespace Travelhood\Library\Provider\GoGlobal\Enum;

use Travelhood\Library\Provider\GoGlobal\EnumAbstract;

class Status extends EnumAbstract
{
    const REQUESTED         = 'RQ';
    const CONFIRMED         = 'C';
    const CANCEL_REQUESTED  = 'RX';
    const CANCELLED         = 'X';
    const REJECTED          = 'RJ';
    const VOUCHER_ISSUED    = 'VCH';
    const VOUCHER_REQUESTED = 'VRQ';
}
