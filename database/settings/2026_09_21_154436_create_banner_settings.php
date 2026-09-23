<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $body = <<<'HTML'
<h3 class="nhsuk-notification-banner__heading">
    Organisation change
</h3>
<p>
    The NHS Leadership Academy will soon move to the NHS College of Leadership and Management.
    <br>
    During this transition you can continue to use this service as normal.
</p>
HTML;

        $this->migrator->add(
            'banner.title',
            'Announcement',
        );

        $this->migrator->add(
            'banner.body',
            $body,
        );
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('banner.title');
        $this->migrator->deleteIfExists('banner.body');
    }
};
