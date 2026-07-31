@section('title')
    &vert; {{ __('auth.verify_email') }}
@endsection

<x-app-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-slot name="header">{{ __('auth.verify_email') }}</x-slot>

        <div class="mb-4 text-sm text-gray-600">{{ __('auth.verify_email') }}</div>

        @if (session('status') === 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-emerald-600">{{ __('auth.link_send') }}</div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-ts-button type="submit" color="primary"> {{ __('auth.resend') }} </x-ts-button>
                </div>
            </form>

            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="rounded-sm text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-800"
                >
                    {{ __('auth.edit_profile') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button
                        type="submit"
                        class="ms-2 rounded-sm text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-800"
                    >
                        {{ __('auth.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-app-layout>
