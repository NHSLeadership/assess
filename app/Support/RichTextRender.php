<?php
namespace App\Support;

class RichTextRender
{
    // Render merge tags into values for a given html, user and framework
    public static function render(?string $html, $user, $framework, array $extra = []): ?string    {

        // return null if content is null or empty
        if (empty($html)) {
            return $html;
        }

        // Define merge tags and their corresponding values
        $mergeTags = [
            'userName'   => $user->name,
            'userID'     => $user->user_id,
            'userEmail'  => $user->email,
            'today'      => now()->translatedFormat('j F Y'),
            'framework'  => $framework->name,
        ];

        // Apply merge tags to the html content
        foreach ($mergeTags as $mergeTag => $value) {
            $html = preg_replace(
                '#<span[^>]+data-type="mergeTag"[^>]+data-id="' . $mergeTag . '"[^>]*></span>#',
                e($value),
                $html
            );
        }

        // Apply custom blocks
        return preg_replace_callback(
            '#<div[^>]+data-type="customBlock"[^>]+data-id="([^"]+)"[^>]*></div>#',
            function ($matches) use ($extra) {
                $blockId = $matches[1];

                // Map block ID to block class
                $blocks = [
                    'bar_chart' => \App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\BarChartBlock::class,
                ];

                if (! isset($blocks[$blockId])) {
                    return $matches[0];
                }

                // Call block renderer
//                return $blocks[$blockId]::toHtml([], []);
                return $blocks[$blockId]::toHtml($config ?? [], $extra);
//                return $blocks[$blockId]::toHtml(
//                    config: [],
//                    data: $extra
//                );
            },
            $html
        );
    }
}
