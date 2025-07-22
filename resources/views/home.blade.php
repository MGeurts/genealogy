@section('title')
    &vert; {{ __('app.home') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.home') }}
    </x-slot>

    <div class="w-full p-2 space-y-5">
        <div class="pb-10 dark:text-neutral-200">
            <div class="flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <x-authentication-card-logo />
                </div>

                <div class="w-full p-6 mt-6 overflow-hidden prose bg-white rounded-sm shadow-md sm:max-w-5xl">
                    {!! \Illuminate\Support\Str::markdown(<<<'MD'
                    ## 💖 Support This Project

                    Maintaining this project takes time and effort. If you find it useful, consider supporting me:

                    -   [![Star on GitHub](https://img.shields.io/github/stars/MGeurts/genealogy?style=social)](https://github.com/MGeurts/genealogy)
                    -   [![Donate via PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.me/MGeurtsKREAWEB)
                    -   [![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-orange?logo=buy-me-a-coffee)](https://buymeacoffee.com/MGeurts)

                    > Your support helps me improve and maintain [Genealogy](https://github.com/MGeurts/genealogy) and other open-source tools. Every bit is appreciated. Thank you! 🙏
                    MD
                    ) !!}
                </div>

                <div class="w-full p-6 mt-6 overflow-hidden prose bg-white rounded-sm shadow-md sm:max-w-5xl">
                    {!! $home !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
