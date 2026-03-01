@section('title')
    &vert; {{ __('auth.login') }}
@endsection

<x-app-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-slot name="header">
            {{ __('auth.login') }}
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <x-ts-alert text="{{ session('status') }}" color="emerald" class="mb-4" />
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('auth.email') }} :" />
                <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('auth.password') }} :" />
                <x-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="text-sm text-gray-600 ms-2">{{ __('auth.remember_me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 underline rounded-sm hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        href="{{ route('password.request') }}">
                        {{ __('auth.forgot_password') }}
                    </a>
                @endif

                <x-ts-button type="submit" color="primary" class="ms-4">
                    {{ __('auth.login') }}
                </x-ts-button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
