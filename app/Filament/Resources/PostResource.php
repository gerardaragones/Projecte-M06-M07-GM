<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\Pages\Log;
use Filament\Forms\Components\RichEditor;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    public static function form(Form $form): Form
    {
        $authors = \App\Models\User::pluck('name', 'id')->toArray()->translateLabel();

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
                Forms\Components\Fieldset::make('Post')
                    ->translateLabel()
                    ->schema([
                        Forms\Components\TextInput::make('file_id')
                        ->translateLabel()
                        ->required(),
                        Forms\Components\Select::make('author_id')
                            ->translateLabel()
                            ->label('Author')
                            ->options($authors)
                            ->default(auth()->id())
                            ->required(),
                        Forms\Components\RichEditor::make('body')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255)
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',            'index' => Pages\ManagePosts::route('/'),

                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'underline',
                                'undo',
                            ]),
                        Forms\Components\TextInput::make('latitude')
                            ->translateLabel()
                            ->required(),
                        Forms\Components\TextInput::make('longitude')
                            ->translateLabel()
                            ->required(),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_id')->translateLabel(),
                Tables\Columns\TextColumn::make('author_id')->translateLabel(),
                Tables\Columns\TextColumn::make('body')->translateLabel(),
                Tables\Columns\TextColumn::make('latitude')->translateLabel(),
                Tables\Columns\TextColumn::make('longitude')->translateLabel(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),        
        ];
    }    
}
