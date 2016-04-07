<?php

namespace GoGlobal\Enum;

use GoGlobal\EnumAbstract;

class Category extends EnumAbstract
{
    const CATEGORY_1_0        = 1;
    const CATEGORY_1_5        = 2;
    const CATEGORY_2_0        = 3;
    const CATEGORY_2_5        = 4;
    const CATEGORY_3_0        = 5;
    const CATEGORY_3_5        = 6;
    const CATEGORY_4_0        = 7;
    const CATEGORY_4_5        = 8;
    const CATEGORY_5_0        = 9;
    const CATEGORY_5_5        = 10;
    const CATEGORY_6_0        = 11;

    public static function toFloat($val) {
        return ($val+1)/2;
    }

    public static function fromFloat($val) {
        return min(11,max(1,floor(($val*2)-1)));
    }

}
