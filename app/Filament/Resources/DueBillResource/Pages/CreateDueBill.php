<?php

namespace App\Filament\Resources\DueBillResource\Pages;

use App\Filament\Resources\DueBillResource;
use App\Models\DueBill;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDueBill extends CreateRecord
{
    protected static string $resource = DueBillResource::class;

}
