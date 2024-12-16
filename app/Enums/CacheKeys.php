<?php

namespace App\Enums;

enum CacheKeys: string
{
    case ASSET_PAGE = 'assets:page:';
    case ASSET_TOTAL_PAGES = 'assets:total-pages';
    case ASSET_ICON = 'assets:icon:';
}
