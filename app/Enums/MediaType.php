<?php

namespace App\Enums;

enum MediaType: string
{
    case SONG = 'song';
    case SHOW = 'show';
    case PODCAST = 'podcast';
    case JINGLE = 'jingle';
}
