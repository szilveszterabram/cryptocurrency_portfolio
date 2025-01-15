<?php

namespace App\Enums;

enum ImpersonationPermissionEnum: string
{
    case ImpersonateUser = 'impersonate user at impersonate.start';
    case StopImpersonation = 'stop impersonation at impersonate.stop';
}
