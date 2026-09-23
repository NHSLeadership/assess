<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Settings\Banner;

class BannerSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSpeakerWave;

    protected static string $settings = Banner::class;
    protected static string|null|\UnitEnum $navigationGroup = 'Settings';

    protected array $auditOldValues = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('settings:update') ?? false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                RichEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    protected function beforeSave(): void
    {
        /** @var \App\Settings\Banner $settings */
        $settings = app(\App\Settings\Banner::class);

        $this->auditOldValues = [
            'title'        => $settings->title,
            'body'    => $settings->body,
        ];
    }

    protected function afterSave(): void
    {
        /** @var \App\Settings\Banner $settings */
        $settings = app(\App\Settings\Banner::class);

        $newValues = [
            'title'        => $settings->title,
            'body'    => $settings->body,
        ];

        \OwenIt\Auditing\Models\Audit::create([
            'auditable_type' => \App\Settings\Banner::class,
            'auditable_id'   => 0,
            'event'          => 'updated',

            'old_values' => $this->auditOldValues,
            'new_values' => $newValues,

            'user_type'   => auth()->user() ? get_class(auth()->user()) : null,
            'user_id'     => auth()->id(),
            'user_agent'  => request()->userAgent(),
            'url'         => request()->fullUrl(),
            'ip_address'  => request()->ip(),

            'tags'        => 'settings,banner',
        ]);
    }
}
