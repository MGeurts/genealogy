<form wire:submit="savePhoto">
    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.add_photo') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <div class="grid grid-cols-6 gap-5">
                {{-- image --}}
                <div class="col-span-6">
                    <x-label for="image" value="{{ __('person.upload_photo') }}" />
                    <x-input type="file" id="image{{ $photoForm->iteration }}" accept="image/webp, image/png, image/jpeg" wire:model="photoForm.image"
                        class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal leading-[1.60] text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-800 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary" />

                    <span class="text-xs dark:text-neutral-800">Format: <b>jpeg/jpg</b>, <b>png</b> ,<b>svg</b> or <b>webp</b>, Max: <b>1024 Kb</b>. </span>

                    <x-input-error for="photoForm.image" />

                    <div class="col-span-6">
                        <div wire:loading wire:target="image" role="status"
                            class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]">
                            <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>

                        @if ($photoForm->image)
                            <img class="block mt-2 rounded w-36" src="{{ $photoForm->image->temporaryUrl() }}" alt="image preview" />
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6 rounded-b">
            <div class="flex-grow max-w-full flex-1 text-left">
                <x-action-message class="p-2.5 rounded bg-warning-200 text-warning-700" role="alert" on="" wire:dirty>
                    {{ __('app.unsaved_changes') }} ...
                </x-action-message>

                <x-action-message class="p-2.5 rounded bg-success-200 text-success-700" role="alert" on="saved">
                    {{ __('app.saved') }}
                </x-action-message>
            </div>

            <div class="flex-grow max-w-full flex-1 text-end">
                <x-button.secondary class="mr-2" wire:click="resetPerson()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-button.secondary>

                <x-button.primary>
                    {{ __('app.save') }}
                </x-button.primary>
            </div>
        </div>
    </div>
</form>
