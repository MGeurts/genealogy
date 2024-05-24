<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('user.profile_information') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('user.profile_information_update') }}
        </div>
    </x-slot>

    <x-slot name="form">
        {{-- profile photo --}}
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                {{-- profile photo file input --}}
                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo"
                    x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('user.photo') }}" />

                {{-- current profile photo --}}
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                {{-- new profile photo preview --}}
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center" x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-ts-button color="secondary" class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('user.select_photo') }}
                </x-ts-button>

                @if ($this->user->profile_photo_path)
                    <x-ts-button color="secondary" type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('user.remove_photo') }}
                    </x-ts-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        {{-- firstname --}}
        <div class="col-span-6 md:col-span-4">
            <x-label for="firstname" value="{{ __('user.firstname') }}" />
            <x-input id="firstname" name="firstname" type="text" class="mt-1 block w-full" wire:model.defer="state.firstname" autocomplete="firstname" />
            <x-input-error for="firstname" class="mt-1" />
        </div>

        {{-- surname --}}
        <div class="col-span-6 md:col-span-4">
            <x-label for="surname" value="{{ __('user.surname') }}" />
            <x-input id="surname" name="surname" type="text" class="mt-1 block w-full" wire:model.defer="state.surname" required autocomplete="surname" />
            <x-input-error for="surname" class="mt-1" />
        </div>

        {{-- email --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && !$this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 dark:text-white">
                    {{ __('Your email address is unverified.') }}

                    <button type="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-emerald-600 dark:text-emerald-400">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        {{-- language --}}
        <div class="col-span-6 md:col-span-4">
            <x-label for="language" value="{{ __('user.language') }}" />
            <select id="language" class="block mt-1 w-full rounded" name="language" wire:model="state.language" required>
                @foreach (config('app.available_locales') as $locale_name => $available_locale)
                    <option value="{{ $available_locale }}" @if (old('language') == '{{ $available_locale }}') selected @endif>{{ $locale_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- timezone --}}
        <div class="col-span-6 md:col-span-4">
            <x-label for="timezone" value="{{ __('user.timezone') }}" />
            <select id="timezone" class="block mt-1 w-full rounded" name="timezone" wire:model="state.timezone" required>
                @foreach (timezone_identifiers_list() as $timezone)
                    <option value="{{ $timezone }}" @if (old('timezone') == '{{ $timezone }}') selected @endif>{{ $timezone }}</option>
                @endforeach
            </select>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="px-4 py-2 mr-3 rounded bg-success-200 text-emerald-600" role="alert" on="saved">
            {{ __('app.saved') }}
        </x-action-message>

        <x-ts-button color="primary" wire:loading.attr="disabled" wire:target="photo">
            {{ __('app.save') }}
        </x-ts-button>
    </x-slot>
</x-form-section>
