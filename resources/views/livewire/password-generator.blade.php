<form wire:submit.prevent="generate">
    @csrf

    <div class="p-2 w-full">
        <div class="md:w-3xl flex flex-col rounded-sm bg-white shadow dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            <div class="flex items-center justify-between p-2 text-lg font-medium border-b-2 border-neutral-100 dark:border-neutral-600">
                <span>{{ __('app.password_generator') }}</span>

                <x-ts-icon icon="tabler.key" class="inline-block size-5" />
            </div>

            <div class="p-4 bg-neutral-200">
                <x-ts-errors class="mb-2" close />

                <div class="grid grid-cols-6 gap-5">
                    <div class="col-span-6">
                        <x-ts-input label="{{ __('app.password_length') }} : *" type="number" wire:model="length" min="6" max="128" />
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
                <div x-data="{ copied: false }" class="relative p-4 bg-neutral-200">
                    <x-ts-alert title="{{ __('auth.password') }}" color="secondary" icon="tabler.key" outline>
                        <div class="flex justify-between items-center">
                            <div class="font-mono break-all text-xl text-black">{{ $generatedPassword }}</div>

                            <x-ts-button color="secondary" @click="navigator.clipboard.writeText('{{ $generatedPassword }}'); copied = true; setTimeout(() => copied = false, 1500);" type="button" class="ml-8" title="{{ __('app.copy_to_clipboard') }}">
                                <x-ts-icon icon="tabler.clipboard-copy" class="inline-block size-5" />
                            </x-ts-button>
                        </div>

                        <x-slot:footer>
                            <x-hr.normal class="my-2" />

                            <div class="flex justify-between">
                                {{-- Entropy badges --}}
                                <div>
                                    <x-ts-badge :text="$estimatedEntropy" md :color="$passwordColor" />
                                    <x-ts-badge :text="$shannonEntropy" md :color="$passwordColor" />
                                </div>

                                {{-- Password Strength badge --}}
                                <x-ts-badge :text="__($passwordStrength)" md :color="$passwordColor"/>
                            </div>
                        </x-slot:footer>
                    </x-ts-alert>

                    <div x-show="copied" x-transition class="absolute top-4 right-4 bg-cyan-600 text-white p-2 rounded">
                        {{ __('app.copied_to_clipboard') }}
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between p-4 rounded-b">
                    <x-ts-link href="https://haveibeenpwned.com/" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        {{ __('app.check_breach') }}
                    </x-ts-link>

                <x-ts-button type="submit">
                    {{ __('app.generate') }}
                </x-ts-button>
            </div>
        </div>
    </div>
</form>
