@section('title')
    &vert; {{ __('auth.login') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('auth.login') }}
        </h2>
    </x-slot>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @if (session('teamInvitation'))
            <x-slot name="heading">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                    {{ __('auth.log_in') }} {{ __('auth.or') }} <a class="underline hover:text-primary" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                    {{ __('auth.to_join') }} {{ __('auth.team') }} <strong class="text-primary">{{ session('teamInvitation') }}</strong>.
                </h2>
            </x-slot>
        @endif

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-emerald-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('auth.email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('auth.password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('auth.remember_me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        href="{{ route('password.request') }}">
                        {{ __('auth.forgot_password') }}
                    </a>
                @endif

                <x-ts-button color="primary" class="ms-4">
                    {{ __('auth.login') }}
                </x-ts-button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
