@section('title')
    &vert; {{ __('auth.verify_email') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('auth.verify_email') }}
    </x-slot>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('auth.verify_email') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-emerald-600">
                {{ __('auth.link_send') }}
            </div>
        @endif

        <div class="flex items-center justify-between mt-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-ts-button type="submit" color="primary">
                        {{ __('auth.resend') }}
                    </x-ts-button>
                </div>
            </form>

            <div>
                <a href="{{ route('profile.show') }}"
                    class="text-sm text-gray-600 underline rounded hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('auth.edit_profile') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit"
                        class="text-sm text-gray-600 underline rounded hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 ms-2">
                        {{ __('auth.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-app-layout>
