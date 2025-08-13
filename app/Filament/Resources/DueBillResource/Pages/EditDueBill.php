<?php

namespace App\Filament\Resources\DueBillResource\Pages;

use App\Filament\Resources\DueBillResource;
use App\Models\DueBill;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDueBill extends EditRecord
{
    protected static string $resource = DueBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
