<?php

namespace GoGlobal\Enum;

use GoGlobal\EnumAbstract;

class Star extends EnumAbstract
{
    const STAR_1_0        = 1;
    const STAR_1_5        = 2;
    const STAR_2_0        = 3;
    const STAR_2_5        = 4;
    const STAR_3_0        = 5;
    const STAR_3_5        = 6;
    const STAR_4_0        = 7;
    const STAR_4_5        = 8;
    const STAR_5_0        = 9;
    const STAR_5_5        = 10;
    const STAR_6_0        = 11;

    public static function toFloat($val) {
        return ($val+1)/2;
    }

}
