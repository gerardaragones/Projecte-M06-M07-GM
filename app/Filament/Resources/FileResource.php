<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Filament\Resources\FileResource\RelationManagers;
use App\Models\File;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
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
                //Forms\Components\TextInput::make('filesize')
                //    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filepath')
                ->translateLabel(),
                Tables\Columns\TextColumn::make('filesize')
                ->translateLabel(),
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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'view' => Pages\ViewFile::route('/{record}'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }    
}
