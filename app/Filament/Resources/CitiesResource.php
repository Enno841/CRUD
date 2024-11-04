<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitiesResource\Pages;
use App\Filament\Resources\CitiesResource\RelationManagers;
use App\Models\Cities;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TrashedFilter;

class CitiesResource extends Resource
{
    protected static ?string $model = Cities::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Cities';

    protected static ?string $modelLabel = 'Person City';

    protected static ?string $navigationGroup = 'Location Management';

    protected static ?string $slug = 'persons-cities';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('region_id')
                    ->relationship(name: 'region', titleAttribute: 'name')
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('region_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCities::route('/create'),
            'view' => Pages\ViewCities::route('/{record}'),
            'edit' => Pages\EditCities::route('/{record}/edit'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            TrashedFilter::make(),
        ];
    }

    protected static function getTableQuery(): Builder
    {
        return parent::getTableQuery()->withTrashed();
    }
}
