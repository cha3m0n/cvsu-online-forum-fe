<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchivesResource\Pages;
use App\Filament\Resources\ArchivesResource\RelationManagers;
use App\Models\Post;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class ArchivesResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Posts Management';

    protected static ?string $navigationLabel = 'Archives';

    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()
            ->schema([
                Forms\Components\Section::make('Details')

                    ->schema([
                        Forms\Components\TextInput::make('title')->required(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('author', 'name')
                            ->label('Author')
                            ->options(function () {
                                return [
                                    auth()->user()->id => auth()->user()->name,
                                ];
                            })
                            // ->options(auth()->user()->name)
                            // ->disabled()
                            ->dehydrated(),
                        Forms\Components\Section::make('Tags(Comma Separated)')->schema([Forms\Components\TextInput::make('tags')->required()]),
                        Forms\Components\MarkdownEditor::make('body')->required(),
                        Forms\Components\TextInput::make('comments_count')
                            ->default(0)
                            ->hidden()
                            ->rules('numeric'),

                        Forms\Components\Section::make('Associations')->schema([
                            Forms\Components\Select::make('categories')
                                ->relationship('categories', 'name')
                                ->multiple()
                                ->required()
                                ->options(function () {
                                    return Category::pluck('name', 'id');
                                }),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->icon('heroicon-s-user')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->words(3)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags')
                    ->words(2)
                    ->separator(',')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('body')
                    ->words(3)
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->searchable()
                    ->dateTime(), // mm/dd/yyyy format
                // ->since(),
            ])->defaultSort('is_archived', '1')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('Bulk Approve Post')
                        ->label('Approve Post/Remove Approval')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function (Post $post) {
                            if ($post->is_approved == false) {
                                $post->is_approved = true;
                                $post->save();
                                Notification::make()
                                    ->title('Approved Successfully!')
                                    ->body('Selected post have been approved.')
                                    ->icon('heroicon-s-check')
                                    ->color('success')
                                    ->send();
                            } else {
                                $post->is_approved = false;
                                $post->save();
                                Notification::make()
                                    ->title('Approval Removed Successfully!')
                                    ->body('Selected post approval has been removed.')
                                    ->icon('heroicon-s-check')
                                    ->color('success')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Bulk Approve Post')
                        ->label('Bulk Approve/Disapprove')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            $records->each(function ($record){
                                $record->is_approved = !$record->is_approved;
                                $record->save();
                                if ($record->is_approved) {
                                    Notification::make()
                                        ->title(__('Approved Successfully!'))
                                        ->body(__('Selected posts have been approved.'))
                                        ->icon('heroicon-s-check')
                                        ->color('success')
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title(__('Approval Removed Successfully!'))
                                        ->body(__('Selected posts approval has been removed.'))
                                        ->icon('heroicon-s-check')
                                        ->color('success')
                                        ->send();
                                }
                            });



                        }),
                ]),
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
            'index' => Pages\ListArchives::route('/'),
            'create' => Pages\CreateArchives::route('/create'),
            'edit' => Pages\EditArchives::route('/{record}/edit'),
        ];
    }
}
