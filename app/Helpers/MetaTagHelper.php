<?php

namespace App\Helpers;

use App\Models\Page;
use Illuminate\Support\Facades\Route;

class MetaTagHelper
{
    public static function getMetaTags()
    {
        $routeName = Route::currentRouteName();
        $page = Page::with('metatag')->where('slug', $routeName)->first();

        if ($page && $page->metatag) {
            return $page->metatag;
        }

        return null;
    }
} 