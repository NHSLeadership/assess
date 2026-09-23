<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $body = <<<'HTML'
<p>The NHS Leadership Academy is now part of the NHS College of Leadership and Management. <a href="https://www.leadershipacademy.nhs.uk/launch-of-the-nhs-college-of-leadership-and-management/">Find out more about the transition</a></p>
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
