<?php

if (! function_exists('blog_title_slugify'))
{

    function get_introduction($content)
    {
        libxml_use_internal_errors(true); // Prevent warnings for malformed HTML

        $doc = new DOMDocument();
        // Load HTML properly with UTF-8 handling
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

        $paragraphs = $doc->getElementsByTagName('p'); // This line was missing

        if ($paragraphs->length > 0) {
            $firstParagraph = $paragraphs->item(0);
            $innerHTML = '';
            foreach ($firstParagraph->childNodes as $child) {
                $innerHTML .= $doc->saveHTML($child);
            }
            echo $innerHTML;
        } else {
            echo $content;
        }
    }
}
