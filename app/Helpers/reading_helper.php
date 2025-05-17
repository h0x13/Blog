<?php

if (!function_exists('estimate_reading_time')) {
    function estimate_reading_time(string $content, int $wpm = 200): string
    {
        $text = strip_tags($content);
        $word_count = str_word_count($text);
        $minutes = floor($word_count / $wpm);
        $seconds = floor(($word_count % $wpm) / ($wpm / 60));

        if ($minutes < 1) {
            return $seconds . ' secs read';
        }

        return $minutes . ' mins read';
    }
}
