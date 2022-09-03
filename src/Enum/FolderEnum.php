<?php

namespace MMA\Files\Enum;

enum FolderEnum :string implements BaseEnum
{
    case Main = 'main';
    case Model = 'getClassFolder';
    case Thumb = 'thumb';
    case Media = 'media';

}
