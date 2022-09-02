<?php

namespace Mahmoud147\Files\Enum;

enum AttributeEnum :string implements BaseEnum
{
    case File = 'getFileAttr';
    case RequestField = 'getMediaRequestField';
    case CustomMediaColumns = 'registerMediaColumns';

}
