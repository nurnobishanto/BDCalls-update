<?php

namespace App\Filament\Resources\IpNumberResource\Pages;

use App\Filament\Resources\IpNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIpNumber extends EditRecord
{
    protected static string $resource = IpNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
