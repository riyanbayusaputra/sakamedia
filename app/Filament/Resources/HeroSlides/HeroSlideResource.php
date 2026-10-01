<?php

namespace App\Filament\Resources\HeroSlides;

use App\Filament\Resources\HeroSlides\Pages;
use App\Models\HeroSlide;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Banner Hero';

    protected static ?string $modelLabel = 'Banner';

    protected static ?string $pluralModelLabel = 'Banner Hero';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Banner')
                    ->schema([
                        TextInput::make('badge')
                            ->label('Label Badge')
                            ->required()
                            ->helperText('Contoh: OFFICIAL CAMPAIGN, PROMO GAMER'),
                        TextInput::make('title')
                            ->label('Judul Banner')
                            ->required(),
                        TextInput::make('link_url')
                            ->label('Tujuan Link (opsional)')
                            ->helperText('Contoh: #produk-pilihan atau #hubungi-kami'),
                    ]),

                Section::make('Gambar Banner')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Upload Gambar Banner')
                            ->image()
                            ->disk('public')
        
                            ->visibility('public')
                            ->directory('hero-slides')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:10'])
                            ->helperText('Rekomendasi rasio 16:10, ukuran minimal 800x500px')
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan Tampil')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif (tampilkan di banner)')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')   
                    ->disk('public')
                    ->visibility('public')
                    ->square()
                    ->height(60),
                TextColumn::make('badge')
                    ->label('Badge')
                    ->badge(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->limit(40),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit' => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}