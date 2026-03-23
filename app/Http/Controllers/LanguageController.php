<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function __invoke(Request $request)
    {
        $lang = $request->input('lang');

        if (in_array($lang, ['sr', 'en', 'de'])) {
            Session::put('locale', $lang);
            app()->setLocale($lang);
        }

        return back();
    }
}
