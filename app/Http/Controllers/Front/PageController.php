<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function about(Request $request)
    {
        $aboutFile = Jetstream::localizedMarkdownPath('about.md');

        return view('about', [
            'about' => Str::markdown(file_get_contents($aboutFile)),
        ]);
    }

    public function help(Request $request)
    {
        $helpFile = Jetstream::localizedMarkdownPath('help.md');

        return view('help', [
            'help' => Str::markdown(file_get_contents($helpFile)),
        ]);
    }

    public function test(Request $request)
    {
        return view('front.test', []);
    }
}
