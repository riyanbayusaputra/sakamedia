<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages;
use App\Models\Article;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Berita & Artikel';

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $pluralModelLabel = 'Berita & Artikel';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Artikel')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Artikel')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            )
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label('Slug (identifier unik)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan Singkat')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('content')
                            ->label('Isi Lengkap (opsional)')
                            ->rows(8)
                            ->columnSpanFull(),
                    ]),

                Section::make('Info & Kategori')
                    ->schema([
                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'Teknologi' => 'Teknologi',
                                'Biografi' => 'Biografi',
                                'Promo' => 'Promo',
                                'Event' => 'Event',
                            ])
                            ->required(),
                        TextInput::make('author')
                            ->label('Nama Penulis')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Terbit')
                            ->default(now()),
                        Toggle::make('is_published')
                            ->label('Publikasikan (tampilkan di website)')
                            ->default(true),
                    ])->columns(2),

                Section::make('Gambar Sampul')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Upload Gambar Sampul')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('articles')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Sampul')
                    ->disk('public')
                    ->visibility('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('author')
                    ->label('Penulis'),
                TextColumn::make('published_at')
                    ->label('Tanggal Terbit')
                    ->date('d M Y')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'Teknologi' => 'Teknologi',
                        'Biografi' => 'Biografi',
                        'Promo' => 'Promo',
                        'Event' => 'Event',
                    ]),
            ])
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}