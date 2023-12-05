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
                    ->relationship('file')
                    ->saveRelationshipsWhenHidden()
                    ->schema([
                        Forms\Components\FileUpload::make('filepath')
                        ->required()
                        ->image()
                        ->maxSize(2048)
                        ->directory('uploads')
                        ->getUploadedFileNameForStorageUsing(function (Livewire\TemporaryUploadedFile $file): string {
                            return time() . '_' . $file->getClientOriginalName();
                        }),
                    ]),
                Forms\Components\Fieldset::make('Place')
                    ->schema([
                        Forms\Components\Hidden::make('file_id')
                        ->required(),
                        Forms\Components\Select::make('author_id')
                            ->label('Author')
                            ->options($authors)
                            ->default(auth()->id())
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_id'),
                Tables\Columns\TextColumn::make('author_id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
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
            'create' => Pages\CreatePlaces::route('/create'),
            'view' => Pages\ViewPlaces::route('/{record}'),
            'edit' => Pages\EditPlaces::route('/{record}/edit'),
        ];
    }    
}
