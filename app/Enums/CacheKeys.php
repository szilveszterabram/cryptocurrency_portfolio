<?php

namespace App\Enums;

enum CacheKeys: string
{
    case AssetPage = 'assets:page:';
    case AssetTotalPages = 'assets:total-pages';
    case AssetIcon = 'assets:icon:';
    case AssetTotalIcons = 'assets:icons:count';
}
