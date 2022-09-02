<?php

use Mahmoud147\Files\Enum\BaseEnum;

if (!function_exists('propValue')){

    function prop(BaseEnum $prop)
    {
        return $prop->value;
    }
}
