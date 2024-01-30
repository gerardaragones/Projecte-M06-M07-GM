<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaceResource\Pages;
use App\Filament\Resources\PlaceResource\RelationManagers;
use App\Models\Place;
use App\Models\Visibility;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Livewire\TemporaryUploadedFile;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('File')
                    ->relationship('file')
                    ->saveRelationshipsWhenHidden()
                    ->schema([
                        Forms\Components\FileUpload::make('filepath')
                            ->required()
                            ->image()
                            ->maxSize(2048)
                            ->directory('uploads')
                            //->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return time() . '_' . $file->getClientOriginalName();
                            }),
                    ]),
                Forms\Components\Fieldset::make('Place')
                    ->schema([
                        Forms\Components\Hidden::make('file_id'),
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->required(),
                    ]),
                Forms\Components\Fieldset::make('Coordinates')
                    ->schema([                            
                        Forms\Components\TextInput::make('latitude')
                            ->required()
                            ->default("41.2310371"),
                        Forms\Components\TextInput::make('longitude')
                            ->required()
                            ->default("1.7282036"),
                    ]),
                Forms\Components\Fieldset::make('Publish')
                    ->schema([
                        Forms\Components\Select::make('author_id')
                            ->relationship('author', 'name')
                            ->required()
                            ->default(auth()->user()->id),
                        Forms\Components\Select::make('visibility_id')
                            ->relationship('visibility', 'name')
                            ->required()
                            ->default(Visibility::PUBLIC),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file.filepath')
                    ->square()->width(50)->height(50),
                Tables\Columns\TextColumn::make('name')
                    ->limit(30),
                Tables\Columns\TextColumn::make('latitude'),
                Tables\Columns\TextColumn::make('longitude'),
                Tables\Columns\TextColumn::make('author.name'),
                Tables\Columns\TextColumn::make('visibility.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlaces::route('/'),
            'create' => Pages\CreatePlace::route('/create'),
            'view' => Pages\ViewPlace::route('/{record}'),
            'edit' => Pages\EditPlace::route('/{record}/edit'),
        ];
    }    
}
