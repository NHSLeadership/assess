<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Settings\Retention;

class RetentionSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static string $settings = Retention::class;
    protected static string|null|\UnitEnum $navigationGroup = 'Settings';

    protected array $auditOldValues = [];


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('retention_years')
                    ->helperText('Years data is retained after last update')
                    ->hint('Between 1 and 10')
                    ->numeric()
                    ->integer()
                    ->default(3)
                    ->minValue(1)
                    ->maxValue(10)
                    ->required(),
                TextInput::make('expiry_warning_days')
                    ->helperText('Days before data expiry that warning is sent to owner')
                    ->hint('Between 1 and 100')
                    ->numeric()
                    ->integer()
                    ->default(30)
                    ->minValue(1)
                    ->maxValue(100)
                    ->required(),
                TextInput::make('min_days_after_warning')
                    ->helperText('Minimum days after warning data can be deleted')
                    ->hint('Between 1 and 10')
                    ->numeric()
                    ->integer()
                    ->default(7)
                    ->minValue(1)
                    ->maxValue(10)
                    ->required(),
            ]);
    }

    protected function beforeSave(): void
    {
        /** @var \App\Settings\Retention $settings */
        $settings = app(\App\Settings\Retention::class);

        $this->auditOldValues = [
            'retention_years'        => $settings->retention_years,
            'expiry_warning_days'    => $settings->expiry_warning_days,
            'min_days_after_warning' => $settings->min_days_after_warning,
        ];
    }

    protected function afterSave(): void
    {
        /** @var \App\Settings\Retention $settings */
        $settings = app(\App\Settings\Retention::class);

        $newValues = [
            'retention_years'        => $settings->retention_years,
            'expiry_warning_days'    => $settings->expiry_warning_days,
            'min_days_after_warning' => $settings->min_days_after_warning,
        ];

        \OwenIt\Auditing\Models\Audit::create([
            'auditable_type' => \App\Settings\Retention::class,
            'auditable_id'   => 0,
            'event'          => 'updated',

            'old_values' => json_encode($this->auditOldValues, JSON_THROW_ON_ERROR),
            'new_values' => json_encode($newValues, JSON_THROW_ON_ERROR),

            'tags'        => 'settings,retention',
            'user_id'     => auth()->id(),
            'url'         => request()->fullUrl(),
            'ip_address'  => request()->ip(),
        ]);
    }
}
