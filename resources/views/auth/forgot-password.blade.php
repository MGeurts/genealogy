@section('title')
    &vert; {{ __('auth.forgot_password') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('auth.forgot_password') }}
        </h2>
    </x-slot>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('auth.forgot_password_message') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-emerald-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('auth.email') }} :" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-ts-button type="submit" color="primary">
                    {{ __('auth.email_password_reset_link') }}
                </x-ts-button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
