<?php

namespace App\Enums;

enum ProfilePermissionEnum: string
{
    case NavigateToProfile = 'navigate to profile';
    case AddToBalance = 'add to balance at profile.update-balance';
}
