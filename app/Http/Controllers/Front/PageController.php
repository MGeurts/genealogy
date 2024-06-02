<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;

class PageController extends Controller
{
    public function home()
    {
        $homeFile = Jetstream::localizedMarkdownPath('home.md');

        return view('home', [
            'home' => Str::markdown(file_get_contents($homeFile)),
        ]);
    }

    public function about()
    {
        $aboutFile = Jetstream::localizedMarkdownPath('about.md');

        return view('about', [
            'about' => Str::markdown(file_get_contents($aboutFile)),
        ]);
    }

    public function help()
    {
        $helpFile = Jetstream::localizedMarkdownPath('help.md');

        return view('help', [
            'help' => Str::markdown(file_get_contents($helpFile)),
        ]);
    }
}
