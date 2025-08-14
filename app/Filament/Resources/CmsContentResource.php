<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CmsContentResource\Pages;
use App\Filament\Resources\CmsContentResource\RelationManagers;
use App\Models\CmsContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Resources\CmsContentResource\Form\HeaderJumbotronForm;
use App\Filament\Resources\CmsContentResource\Form\AboutUsForm;
use App\Filament\Resources\CmsContentResource\Form\DiscountsForm;
use App\Filament\Resources\CmsContentResource\Form\ServicesForm;
use App\Filament\Resources\CmsContentResource\Form\PriceCatalogForm;

class CmsContentResource extends Resource
{
    protected static ?string $model = CmsContent::class;


    // protected static ?string $navigationIcon = 'heroicon-o-collection'; // icono para el menú
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';


    protected static ?string $navigationLabel = 'Gestión CMS';

    // protected static ?int $navigationSort = 90;
    public static function form(Form $form): Form
    {
        // Obtener el slug del registro si estamos editando
        $record = $form->getRecord(); // obtiene el registro actual (null si es create)

        $slug = $record?->slug;

        // Seleccionar el esquema según el slug
        $schema = match ($slug) {
            'header-jumbotron' => HeaderJumbotronForm::schema(),
            'about-us' => AboutUsForm::schema(),
            'discounts' => DiscountsForm::schema(),
            'services' => ServicesForm::schema(),
            'price-catalog' => PriceCatalogForm::schema(),
        };

        return $form->schema(array_merge(
            [
                // Forms\Components\TextInput::make('slug')
                //     ->label('Slug')
                //     ->required()
                //     ->disabled(), // que no se pueda cambiar
                Forms\Components\Toggle::make('active')
                    ->label('Activo')
                    ->inline(false)
                    ->default(true),
            ],
            $schema
        ));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('component_description')->label("Descripción componente")->sortable()->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label('activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('active')
                    ->label("¿Activo?")
                    ->options([
                        '1' => 'Activo',
                        '0' => 'No activo',
                    ])->placeholder('Todos')
                    ->indicateUsing(function (array $data): array {
                        if (! isset($data['value'])) {
                            return [];
                        }

                        return match ($data['value']) {
                            '1' => ['Activo'],
                            '0' => ['No activo'],
                            default => [],
                        };
                    })
                    ->query(function ($query, array $data) {
                        if (! isset($data['value'])) {
                            return $query;
                        }

                        return $query->where('active', $data['value']);
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Editar'),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //       Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCmsContents::route('/'),
            'create' => Pages\CreateCmsContent::route('/create'),
            'edit' => Pages\EditCmsContent::route('/{record}/edit'),
        ];
    }
}
