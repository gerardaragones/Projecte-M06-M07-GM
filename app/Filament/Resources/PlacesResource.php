<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlacesResource\Pages;
use App\Filament\Resources\PlacesResource\RelationManagers;
use App\Models\Places;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlacesResource extends Resource
{
    protected static ?string $model = Places::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('File')
                    ->translateLabel()
                    ->relationship('file')
                    ->saveRelationshipsWhenHidden()
                    ->schema([
                        Forms\Components\FileUpload::make('filepath')
                        ->translateLabel()
                        ->required()
                        ->image()
                        ->maxSize(2048)
                        ->directory('uploads')
                        ->getUploadedFileNameForStorageUsing(function (Livewire\TemporaryUploadedFile $file): string {
                            return time() . '_' . $file->getClientOriginalName();
                        }),
                    ]),
                Forms\Components\Fieldset::make('Place')
                    ->translateLabel()
                    ->schema([
                        Forms\Components\Hidden::make('file_id')
                        ->translateLabel()
                        ->required(),
                        Forms\Components\Select::make('author_id')
                            ->translateLabel()
                            ->label('Author')
                            ->options($authors)
                            ->default(auth()->id())
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_id')->translateLabel(),
                Tables\Columns\TextColumn::make('author_id')->translateLabel(),
                Tables\Columns\TextColumn::make('name')->translateLabel(),
                Tables\Columns\TextColumn::make('description')->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                ->translateLabel()
                ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                ->translateLabel()    
                ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->translateLabel(),
                Tables\Actions\EditAction::make()->translateLabel(),
                Tables\Actions\DeleteAction::make()->translateLabel(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->translateLabel(),
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
            'create' => Pages\CreatePlaces::route('/create'),
            'view' => Pages\ViewPlaces::route('/{record}'),
            'edit' => Pages\EditPlaces::route('/{record}/edit'),
        ];
    }    
}
