<?php

namespace App\Enums;

enum InvitationPermissionEnum: string
{
    case NavigateToInvite = 'navigate to invite';
    case SendInvite = 'send an invite at invite.invite';
}
