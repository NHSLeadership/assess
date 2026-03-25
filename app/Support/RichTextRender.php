<?php

namespace App\Support;

class RichTextRender
{
    public static function render(?string $html, $user, $framework, array $extra = []): ?string
    {
        if (empty($html)) {
            return $html;
        }

        // Merge tags
        $mergeTags = [
            'userName'  => $user->name,
            'userID'    => $user->user_id,
            'userEmail' => $user->email,
            'today'     => now()->translatedFormat('j F Y'),
            'framework' => $framework->name,
        ];

        foreach ($mergeTags as $mergeTag => $value) {
            $html = preg_replace(
                '#<span[^>]+data-type="mergeTag"[^>]+data-id="' . preg_quote($mergeTag, '#') . '"[^>]*></span>#',
                e($value),
                $html
            );
        }

        // Custom blocks
        return preg_replace_callback(
            '#<div\b[^>]*data-type="customBlock"[^>]*></div>#',
            function ($matches) use ($extra) {
                $blockHtml = $matches[0];

                // Extract data-id
                if (! preg_match('#data-id="([^"]+)"#', $blockHtml, $mId)) {
                    return $blockHtml;
                }
                $blockId = $mId[1];

                // Extract data-config
                $config = [];
                if (preg_match('#data-config="([^"]*)"#', $blockHtml, $mCfg)) {
                    $raw = $mCfg[1];

                    // decode HTML entities (&quot; etc)
                    $raw = html_entity_decode($raw, ENT_QUOTES);

                    if ($raw !== '' && $raw !== 'null') {
                        $decoded = json_decode($raw, true);
                        if (is_array($decoded)) {
                            $config = $decoded;
                        }
                    }
                }

                // Map block ID to block class
                $blocks = [
                    'bar_chart' => \App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\BarChartBlock::class,
                ];

                if (! isset($blocks[$blockId])) {
                    return $blockHtml;
                }

                // Call block renderer with config + extra data
                return $blocks[$blockId]::toHtml($config, $extra);
            },
            $html
        );
    }
}