<?php

namespace App\Services;

use Symfony\Component\String\Slugger\AsciiSlugger;

class StringService
{
    public static function getSluggedString(string $text): String
    {
        $slugger = new AsciiSlugger();
        return $slugger->slug($text)->lower();
    }
}
