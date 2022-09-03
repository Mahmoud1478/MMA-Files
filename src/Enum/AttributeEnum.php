<?php

namespace MMA\Files\Enum;

enum AttributeEnum :string implements BaseEnum
{
    case File = 'getFileAttr';
    case RequestField = 'getMediaRequestField';
    case CustomMediaColumns = 'registerMediaColumns';

}
