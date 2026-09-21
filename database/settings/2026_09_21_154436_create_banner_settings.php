<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $body = <<<'HTML'
<p>
    The NHS Leadership Academy will soon move to the NHS College of Leadership and Management.
    <br>
    During this transition you can continue to use this service as normal.
</p>
HTML;

        $this->migrator->add(
            'banner.title',
            'Organisation change',
        );

        $this->migrator->add(
            'banner.body',
            $body,
        );
    }
};
