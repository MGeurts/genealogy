<form wire:submit.prevent="generate">
    @csrf

    <div class="w-full p-2">
        <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow md:w-3xl dark:bg-neutral-700 dark:text-neutral-50">
            <div class="flex items-center justify-between border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600">
                <span>{{ __('app.password_generator') }}</span>

                <x-ts-icon icon="tabler.key" class="inline-block size-5" />
            </div>

            <div class="bg-neutral-200 p-4">
                <x-ts-errors class="mb-2" close />

                <div class="grid grid-cols-6 gap-5">
                    <div class="col-span-6">
                        <x-ts-input
                            label="{{ __('app.password_length') }} : *"
                            type="number"
                            wire:model="length"
                            min="6"
                            max="128"
                        />
                    </div>

                    <div class="col-span-3">
                        <x-ts-toggle wire:model="useNumbers" label="{{ __('app.use_numbers') }}" />
                    </div>

                    <div class="col-span-3">
                        <x-ts-toggle wire:model="useSymbols" label="{{ __('app.use_symbols') }}" />
                    </div>
                </div>
            </div>

            @if ($generatedPassword)
                <div x-data="{ copied: false }" class="relative bg-neutral-200 p-4">
                    <x-ts-alert title="{{ __('auth.password') }}" color="secondary" icon="tabler.key" outline>
                        <div class="flex items-center justify-between">
                            <div class="font-mono text-xl break-all text-black">{{ $generatedPassword }}</div>

                            <x-ts-button
                                color="secondary"
                                @click="navigator.clipboard.writeText('{{ $generatedPassword }}'); copied = true; setTimeout(() => copied = false, 1500);"
                                type="button"
                                class="ml-8"
                                title="{{ __('app.copy_to_clipboard') }}"
                            >
                                <x-ts-icon icon="tabler.clipboard-copy" class="inline-block size-5" />
                            </x-ts-button>
                        </div>

                        <x-slot:footer>
                            <x-hr.normal class="my-2" />

                            <div class="flex justify-between">
                                {{-- Entropy badges --}}
                                <div>
                                    <x-ts-badge :color="$passwordColor" :text="$estimatedEntropy" md />
                                    <x-ts-badge :color="$passwordColor" :text="$shannonEntropy" md />
                                </div>

                                {{-- Password Strength badge --}}
                                <x-ts-badge :text="__($passwordStrength)" md :color="$passwordColor" />
                            </div>
                        </x-slot:footer>
                    </x-ts-alert>

                    <div x-show="copied" x-transition class="absolute top-4 right-4 rounded bg-cyan-600 p-2 text-white">
                        {{ __('app.copied_to_clipboard') }}
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between rounded-b p-4">
                <x-ts-link
                    href="https://haveibeenpwned.com/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-md text-blue-600 hover:underline dark:text-blue-400"
                >
                    <x-ts-icon icon="tabler.key-off" class="me-2 inline-block size-5" />
                    {{ __('app.check_breach') }}
                </x-ts-link>

                <x-ts-button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="generate"
                    class="inline-flex items-center gap-2"
                >
                    <svg wire:loading wire:target="generate" class="size-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="generate"> {{ __('app.generate') }} </span>
                    <span wire:loading wire:target="generate"> {{ __('app.generating') }} </span>
                </x-ts-button>
            </div>
        </div>
    </div>
</form>
