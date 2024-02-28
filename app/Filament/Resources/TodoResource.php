<?php

namespace App\Filament\Resources;

use App\Enums\TodoPriorityEnum;
use App\Filament\Resources\TodoResource\Pages;
use App\Filament\Resources\TodoResource\RelationManagers;
use App\Models\Todo;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TodoResource extends Resource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('title')
                        ->maxLength(150)
                        ->required(),
                    Forms\Components\DateTimePicker::make('due_date')
                        ->required()
                        ->minDate(now())
                        ->timezone('Asia/Qatar'),
                ])->columns(2),

                Section::make()->schema([
                    Forms\Components\RichEditor::make('note')
                        ->maxLength(500)
                        ->disableToolbarButtons([
                            'blockquote',
                            'attachFiles'
                        ]),
                    Forms\Components\Select::make('priority')
                        ->options(TodoPriorityEnum::class)->default(TodoPriorityEnum::NORMAL)->required()

                ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->sortable()
                    ->color(fn ($state) => $state < now()  ? 'gray' : 'white'),
                Tables\Columns\TextColumn::make('priority')
                    ->color(fn ($state) => match (strtolower($state)) {
                        'normal' => 'warning',
                        'low' => 'gray',
                        'high' => 'danger',
                        // 'rejected' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListTodos::route('/'),
            'create' => Pages\CreateTodo::route('/create'),
            'edit' => Pages\EditTodo::route('/{record}/edit'),
        ];
    }
}
