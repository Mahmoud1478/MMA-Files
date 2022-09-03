<?php

namespace MMAE\Files\Enum;

enum PropertyEnum :string implements BaseEnum
{
    case File = 'file';
    case Media = 'media';
    case isImage = 'is_image';
    case Thumb = 'thumb';
    case Resize = 'resize';
    case FileIsImage = 'file.is_image';
    case MediaIsImage = 'media.is_image';
    case MediaActivate = 'media.activate';
    case FileResizeActivate = 'file.resize.activate';
    case FileResizeHeight = 'file.resize.height';
    case FileResizeWidth = 'file.resize.width';
    case MediaResizeActivate = 'media.resize.activate';
    case MediaResizeHeight = 'media.resize.height';
    case MediaResizeWidth = 'media.resize.width';
    case FileThumbActivate = 'file.thumb.activate';
    case FileThumbHeight = 'file.thumb.height';
    case FileThumbWidth = 'file.thumb.with';
    case MediaThumbActivate = 'media.thumb.activate';
    case MediaThumbHeight = 'media.thumb.height';
    case MediaThumbWidth = 'media.thumb.with';
    case Models = 'models';
    case ConfigName = 'files';

}
