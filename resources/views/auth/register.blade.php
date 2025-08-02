@section('title')
    &vert; {{ __('auth.register') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('auth.register') }}
    </x-slot>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-slot name="header">
            {{ __('auth.register') }}
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mt-2 md:flex md:items-center">
                <div class="md:w-1/3">
                    <x-label for="firstname" value="{{ __('user.firstname') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="firstname" class="block w-full" type="text" name="firstname" :value="old('firstname')" autofocus autocomplete="firstname" />
                </div>
            </div>

            <div class="mt-1 md:flex md:items-center">
                <div class="md:w-1/3">
                    <x-label for="surname" value="{{ __('user.surname') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="surname" class="block w-full" type="text" name="surname" :value="old('surname')" required autocomplete="surname" />
                </div>
            </div>

            <div class="mt-1 md:flex md:items-center">
                <div class="md:w-1/3">
                    <x-label for="email" value="{{ __('user.email') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>
            </div>

            <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">

            <div class="mt-2 md:flex md:items-center">
                <div class="md:w-1/3">
                    <x-label for="language" value="{{ __('user.language') }} :" />
                </div>
                <div class="md:w-2/3">
                    <select id="language" class="block w-full rounded-sm" name="language" required>
                        @foreach (config('app.available_locales') as $locale_name => $available_locale)
                            <option value="{{ $available_locale }}" @selected($available_locale === old('language', app()->getLocale()))>{{ $locale_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-1 md:flex md:items-center">
                <div class="md:w-1/3">
                    <x-label for="timezone" value="{{ __('user.timezone') }} :" />
                </div>
                <div class="md:w-2/3">
                    <select id="timezone" class="block w-full rounded-sm" name="timezone" required>
                        @foreach (timezone_identifiers_list() as $timezone)
                            <option value="{{ $timezone }}" @selected(old('timezone', config('app.timezone')) == $timezone)>{{ $timezone }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">

            <div class="mt-2 md:flex md:items-center">
                <div class="md:w-1/3">
                    <x-label for="password" value="{{ __('user.password') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" />
                </div>
            </div>

            <div class="mt-1 md:flex md:items-center">
                <div class="md:w-1/3">
                    <x-label for="password_confirmation" value="{{ __('user.confirm_password') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">

                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ml-2">
                                {!! __('auth.agree', [
                                    'terms_of_service' =>
                                        '<a target="_blank" href="' .
                                        route('terms.show') .
                                        '" class="text-sm text-gray-600 underline rounded-sm hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                        __('app.terms_of_service') .
                                        '</a>',
                                    'privacy_policy' =>
                                        '<a target="_blank" href="' .
                                        route('policy.show') .
                                        '" class="text-sm text-gray-600 underline rounded-sm hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                        __('app.privacy_policy') .
                                        '</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 underline rounded-sm hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('auth.already_registered') }}?
                </a>

                <x-ts-button type="submit" color="primary" class="ml-4">
                    {{ __('auth.register') }}
                </x-ts-button>
            </div>
        </form>
    </x-authentication-card>

    <script>
        setSelectedValue(document.getElementById('timezone'), Intl.DateTimeFormat().resolvedOptions().timeZone);

        function setSelectedValue(selectObj, valueToSet) {
            for (var i = 0; i < selectObj.options.length; i++) {
                if (selectObj.options[i].text === valueToSet) {
                    selectObj.options[i].selected = true;
                    return;
                }
            }
        }
    </script>
</x-app-layout>
