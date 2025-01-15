<?php

namespace App\Enums;

enum EntryPermissionEnum: string
{
    case NavigateToCreate = 'navigate to entry.create';
    case CreateEntry = 'buy assets at entry.store';
    case DeleteEntry = 'delete assets at entry.destroy';
}
