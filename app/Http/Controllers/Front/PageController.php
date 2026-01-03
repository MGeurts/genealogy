<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Jetstream\Jetstream;

final class PageController extends Controller
{
    public function home(): View
    {
        $homeFile = Jetstream::localizedMarkdownPath(app()->getLocale() . '/' . 'home.md');
        $content  = file_get_contents($homeFile);

        if ($content === false) {
            abort(404, 'Home page content not found');
        }

        return view('home', [
            'home' => Str::markdown($content),
        ]);
    }

    public function passwordGenerator(): View
    {
        return view('front.password-generator');
    }

    public function about(): View
    {
        $aboutFile = Jetstream::localizedMarkdownPath(app()->getLocale() . '/' . 'about.md');
        $markdown  = file_get_contents($aboutFile);

        if ($markdown === false) {
            abort(404, 'About page content not found');
        }

        // First render as Blade (to process {{ date('Y') }}, etc.)
        $compiledBlade = Blade::render($markdown);

        // Then parse the rendered Blade output as Markdown
        return view('about', [
            'about' => Str::markdown($compiledBlade),
        ]);
    }

    public function help(): View
    {
        $helpFile = Jetstream::localizedMarkdownPath('help.md');
        $content  = file_get_contents($helpFile);

        if ($content === false) {
            abort(404, 'Help page content not found');
        }

        return view('help', [
            'help' => Str::markdown($content),
        ]);
    }
}
