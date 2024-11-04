<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonsResource\Pages;
use App\Filament\Resources\PersonsResource\RelationManagers;
use App\Models\Persons;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TrashedFilter;

class PersonsResource extends Resource
{
    protected static ?string $model = Persons::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Person Management';

    protected static ?string $modelLabel = 'Person';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Relationships')
                    ->schema([
                        Forms\Components\Select::make('region_id')
                            ->relationship(name: 'region', titleAttribute: 'name')
                            ->searchable()
                            ->native(false)
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->relationship(name: 'city', titleAttribute: 'name')
                            ->searchable()
                            ->native(false)
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Person Name')
                    ->description('Put your name here.')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('middle_name')
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Person Address')
                    ->description('Put your address here.')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zip_code')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Person Age')
                    ->description('Put your age and birthday here.')
                    ->schema([
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('age')
                            ->required()
                            ->numeric(),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('softDelete')
                    ->label('Delete')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->action(fn ($record) => $record->delete())
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->deleted_at === null), // Show only if not soft deleted
                Action::make('restore')
                    ->label('Restore')
                    ->color('success')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn ($record) => $record->restore())
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->trashed()), // Show only if soft deleted
                Action::make('forceDelete')
                    ->label('Permanently Delete')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->action(fn ($record) => $record->forceDelete())
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->trashed()), // Show only if soft deleted
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPersons::route('/'),
            'create' => Pages\CreatePersons::route('/create'),
            'view' => Pages\ViewPersons::route('/{record}'),
            'edit' => Pages\EditPersons::route('/{record}/edit'),
        ];
    }
}
