<?php

namespace App\Filament\Resources\ModemModels;

use App\Filament\Resources\ModemModels\Pages;
use App\Models\ModemModel;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModemModelResource extends Resource
{
    protected static ?string $model = ModemModel::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'Model Modem';

    protected static ?string $modelLabel = 'Model Modem';

    protected static ?string $pluralModelLabel = 'Model Modem';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Inventaris Modem';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Model Modem')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Modem')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: ZTE F609'),
                        Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'gpon' => 'GPON',
                                'epon' => 'EPON',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Modem')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => strtoupper($state)),
                TextColumn::make('modems_count')
                    ->label('Total Unit')
                    ->counts('modems')
                    ->badge()
                    ->color('info'),
                TextColumn::make('available_count')
                    ->label('Tersedia')
                    ->state(fn (ModemModel $record) => $record->modems()->where('status', 'tersedia')->count())
                    ->badge()
                    ->color('success'),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
                // Model yang masih punya unit tidak bisa dihapus
                DeleteAction::make()
                    ->hidden(fn (ModemModel $record) => $record->modems()->exists()),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModemModels::route('/'),
            'create' => Pages\CreateModemModel::route('/create'),
            'edit' => Pages\EditModemModel::route('/{record}/edit'),
        ];
    }
}