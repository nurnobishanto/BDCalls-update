<?php

namespace App\Filament\Resources\MinuteBundleResource\Pages;

use App\Filament\Resources\MinuteBundleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMinuteBundles extends ListRecords
{
    protected static string $resource = MinuteBundleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
