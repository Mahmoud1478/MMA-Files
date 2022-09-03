<?php

use MMAE\Files\Enum\BaseEnum;

if (!function_exists('propValue')){

    function prop(BaseEnum $prop)
    {
        return $prop->value;
    }
}
