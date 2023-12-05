<?php

namespace App\Filament\Resources\PlacesResource\Pages;

use App\Filament\Resources\PlacesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlaces extends ListRecords
{
    protected static string $resource = PlacesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
