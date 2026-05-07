<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Signature('copy:export
    {--path=copy-for-proofreading.txt : Output file path relative to storage/app/private}
')]
#[Description('Export user-visible copy as plain text for spelling and grammar checks')]
class ExportCopyForProofreading extends Command
{
    public function handle(): int
    {
        $lines = [];

        /*
         |--------------------------------------------------------------------------
         | Frameworks
         |--------------------------------------------------------------------------
         */
        $lines[] = '=== Frameworks ===';
        $lines[] = '';

        $frameworks = DB::table('frameworks')
            ->select([
                'id',
                'name',
                'description',
                'instructions',
                'report_intro',
                'report_ending',
                'report_html',
            ])
            ->orderBy('id')
            ->get();

        foreach ($frameworks as $f) {
            $this->appendText($lines, "frameworks:{$f->id}:name", $f->name);
            $this->appendHtml($lines, "frameworks:{$f->id}:description", $f->description);
            $this->appendHtml($lines, "frameworks:{$f->id}:instructions", $f->instructions);
            $this->appendHtml($lines, "frameworks:{$f->id}:report_intro", $f->report_intro);
            $this->appendHtml($lines, "frameworks:{$f->id}:report_ending", $f->report_ending);
            $this->appendHtml($lines, "frameworks:{$f->id}:report_html", $f->report_html);
        }

        /*
         |--------------------------------------------------------------------------
         | Nodes
         |--------------------------------------------------------------------------
         */
        $lines[] = '';
        $lines[] = '=== Nodes ===';
        $lines[] = '';

        $nodes = DB::table('nodes')
            ->select(['id', 'framework_id', 'name', 'description'])
            ->orderBy('id')
            ->get();

        foreach ($nodes as $n) {
            $this->appendText($lines, "nodes:{$n->id}:name", $n->name);
            $this->appendHtml($lines, "nodes:{$n->id}:description", $n->description);
        }

        /*
         |--------------------------------------------------------------------------
         | Questions
         |--------------------------------------------------------------------------
         */
        $lines[] = '';
        $lines[] = '=== Questions ===';
        $lines[] = '';

        $questions = DB::table('questions')
            ->select(['id', 'node_id', 'title', 'text', 'hint', 'placeholder'])
            ->orderBy('id')
            ->get();

        foreach ($questions as $q) {
            $this->appendText($lines, "questions:{$q->id}:title", $q->title);
            $this->appendHtml($lines, "questions:{$q->id}:text", $q->text);
            $this->appendText($lines, "questions:{$q->id}:hint", $q->hint);
            $this->appendText($lines, "questions:{$q->id}:placeholder", $q->placeholder);
        }

        /*
         |--------------------------------------------------------------------------
         | Question variants
         |--------------------------------------------------------------------------
         */
        $lines[] = '';
        $lines[] = '=== Question variants ===';
        $lines[] = '';

        $variants = DB::table('question_variants')
            ->select(['id', 'question_id', 'text'])
            ->orderBy('id')
            ->get();

        foreach ($variants as $v) {
            $this->appendText($lines, "question_variants:{$v->id}", $v->text);
        }

        /*
         |--------------------------------------------------------------------------
         | Write file
         |--------------------------------------------------------------------------
         */
        $path = $this->option('path');

        Storage::disk('local')->put(
            $path,
            implode("\n", $lines)
        );

        $this->info("✅ Copy exported to storage/app/{$path}");

        return self::SUCCESS;
    }

    /*
     |--------------------------------------------------------------------------
     | Helpers
     |--------------------------------------------------------------------------
     */

    private function appendText(array &$lines, string $label, ?string $text): void
    {
        $text = trim((string) $text);

        if ($text === '') {
            return;
        }

        $lines[] = "[{$label}]";
        $lines[] = $text;
        $lines[] = '';
    }

    private function appendHtml(array &$lines, string $label, ?string $html): void
    {
        $text = $this->htmlToProofreadableText($html);

        if ($text === null || $text === '') {
            return;
        }

        $lines[] = "[{$label}]";
        $lines[] = $text;
        $lines[] = '';
    }

    /**
     * Convert stored HTML-ish content into grammar-checker-friendly prose.
     */
    private function htmlToProofreadableText(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        // Decode entity-escaped HTML
        $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove non-content blocks
        $decoded = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $decoded);
        $decoded = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $decoded);

        // Structural separator (must not contain < or >)
        $sep = "__UNIT__";

        // Treat block boundaries as sentence units
        $decoded = preg_replace('/<\/(p|div|h[1-6])\s*>/i', $sep, $decoded);
        $decoded = preg_replace('/<\/li\s*>/i', $sep, $decoded);
        $decoded = preg_replace('/<\/?(ul|ol|hr)\b[^>]*>/i', $sep, $decoded);

        // Inline <br> is just a space (you cleaned the worst cases at source)
        $decoded = preg_replace('/<br\s*\/?>/i', ' ', $decoded);

        // Strip remaining tags
        $plain = strip_tags($decoded);

        // Normalise punctuation
        $plain = str_replace(
            ['‑', '–', '“', '”', '’'],
            ['-', '-', '"', '"', '\''],
            $plain
        );

        $units = array_filter(
            array_map('trim', explode($sep, $plain)),
            fn ($u) => $u !== ''
        );

        if (empty($units)) {
            return null;
        }

        $sentences = array_map(
            fn ($u) => $this->normaliseUnitToSentence($u),
            $units
        );

        $out = implode(' ', array_filter($sentences));

        $out = preg_replace('/([.?!:])(?=\p{L})/u', '$1 ', $out);
        $out = preg_replace('/\s+/u', ' ', $out);

        return trim($out);
    }

    private function normaliseUnitToSentence(string $text): string
    {
        $text = preg_replace('/\s+/u', ' ', trim($text));

        if ($text === '') {
            return '';
        }

        $text = preg_replace_callback(
            '/^([^\p{L}]*)(\p{L})/u',
            fn ($m) => $m[1] . mb_strtoupper($m[2], 'UTF-8'),
            $text,
            1
        );

        if (!$this->endsWithTerminalPunctuation($text)) {
            $text .= '.';
        }

        return trim($text);
    }

    private function endsWithTerminalPunctuation(string $text): bool
    {
        return (bool) preg_match('/[.?!:](?:[\]\)\}"\'”’]+)?$/u', $text);
    }
}