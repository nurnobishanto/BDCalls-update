<?php

namespace App\Filament\Resources\IpNumberResource\Pages;

use App\Filament\Resources\IpNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIpNumbers extends ListRecords
{
    protected static string $resource = IpNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
