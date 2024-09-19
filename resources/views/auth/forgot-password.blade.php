@section('title')
    &vert; {{ __('auth.forgot_password') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('auth.forgot_password') }}
    </x-slot>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('auth.forgot_password_message') }}
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-emerald-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('auth.email') }} :" />
                <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-ts-button type="submit" color="primary">
                    {{ __('auth.email_password_reset_link') }}
                </x-ts-button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
