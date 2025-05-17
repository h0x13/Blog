<?php

use App\Models\BlogModel;

if (! function_exists('blog_title_slugify'))
{

    function blog_title_slugify(string $title): string
    {
        helper('url');

        $baseSlug = url_title(iconv('UTF-8', 'ASCII//TRANSLIT', $title), '-', true);
        $slug = $baseSlug;

        $blogModel = new \App\Models\BlogModel();

        $existingSlugs = $blogModel->like('slug', $baseSlug)->findColumn('slug');

        if ($existingSlugs && in_array($slug, $existingSlugs)) {
            $i = 1;
            do {
                $slug = $baseSlug . '-' . $i;
                $i++;
            } while (in_array($slug, $existingSlugs));
        }

        return $slug;
    }
}

