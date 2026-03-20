<?php
namespace App\Support;

class MergeTags
{
    // Render merge tags into values for a given html, user and framework
    public static function apply(?string $html, $user, $framework): ?string    {

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
        return $html;
    }
}
