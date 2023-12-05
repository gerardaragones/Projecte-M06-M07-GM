<?php

namespace App\Filament\Resources\PlacesResource\Pages;

use App\Filament\Resources\PlacesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlaces extends EditRecord
{
    protected static string $resource = PlacesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
