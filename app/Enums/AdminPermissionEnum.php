<?php

namespace App\Enums;

enum AdminPermissionEnum: string
{
    case Navigate = 'navigate to admin';
    case ViewUser = 'view a user at admin.show';
    case MakeUserAdmin = 'make a user admin at admin.make-admin';
    case UpdateUser = 'update user info at admin.update';
    case DeleteUser = 'delete a user at admin.delete';
}
