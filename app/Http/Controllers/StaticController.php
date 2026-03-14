<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    public function __invoke(Request $request)
    {
        $name = $request->route()->getName();
        $component = str($name)->headline()->replace(' ', '');
        return inertia($component);
    }
}
