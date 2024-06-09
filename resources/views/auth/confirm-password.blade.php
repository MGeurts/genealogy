@section('title')
    &vert; {{ __('auth.confirm_password') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('auth.confirm_password') }}
    </x-slot>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('auth.secure_area') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="{{ __('auth.password') }} :" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-ts-button type="submit" color="primary" class="ms-4">
                    {{ __('auth.confirm') }}
                </x-ts-button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
