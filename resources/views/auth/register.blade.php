@section('title')
    &vert; {{ __('auth.register') }}
@endsection

<x-app-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @if (session('teamInvitation'))
            <x-slot name="heading">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                    {{ __('auth.register') }} {{ __('auth.or') }} <a class="underline hover:text-primary" href="{{ route('login') }}">{{ __('auth.log_in') }}</a>
                    {{ __('auth.to_join') }} {{ __('auth.team') }} <strong class="text-primary">{{ session('teamInvitation') }}</strong>.
                </h2>
            </x-slot>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="md:flex md:items-center mt-2">
                <div class="md:w-1/3">
                    <x-label for="firstname" value="{{ __('user.firstname') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="firstname" class="block w-full" type="text" name="firstname" :value="old('firstname')" autofocus autocomplete="firstname" />
                </div>
            </div>

            <div class="md:flex md:items-center mt-2">
                <div class="md:w-1/3">
                    <x-label for="surname" value="{{ __('user.surname') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="surname" class="block w-full" type="text" name="surname" :value="old('surname')" required autocomplete="surname" />
                </div>
            </div>

            <div class="md:flex md:items-center mt-2">
                <div class="md:w-1/3">
                    <x-label for="email" value="{{ __('user.email') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>
            </div>

            <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">

            <div class="md:flex md:items-center mt-2">
                <div class="md:w-1/3">
                    <x-label for="password" value="{{ __('user.password') }} :" />
                </div>
                <div class="md:w-2/3">
                    <x-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" />
                </div>
            </div>

            <div class="md:flex md:items-center mt-1">
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
                                        '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                        __('app.terms_of_service') .
                                        '</a>',
                                    'privacy_policy' =>
                                        '<a target="_blank" href="' .
                                        route('policy.show') .
                                        '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                        __('app.privacy_policy') .
                                        '</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('auth.already_registered') }}?
                </a>

                <x-ts-button color="primary" class="ml-4">
                    {{ __('auth.register') }}
                </x-ts-button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
