<?php

namespace App\Filament\Resources\UserIpNumberResource\Pages;

use App\Filament\Resources\UserIpNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserIpNumber extends EditRecord
{
    protected static string $resource = UserIpNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
