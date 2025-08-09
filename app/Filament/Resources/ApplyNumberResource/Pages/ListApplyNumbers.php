<?php

namespace App\Filament\Resources\ApplyNumberResource\Pages;

use App\Filament\Resources\ApplyNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplyNumbers extends ListRecords
{
    protected static string $resource = ApplyNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
