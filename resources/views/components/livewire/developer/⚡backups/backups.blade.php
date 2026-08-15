<div class="w-full">
    @section('title')
        &vert; {{ __('backup.backups') }}
    @endsection

    <div class="max-w-5xl grow p-2 dark:text-neutral-200">
        <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="max-w-full min-w-max flex-1 grow">
                        {{ __('backup.backups') }}
                        @if (count($backups) > 0)
                            <x-ts-badge color="emerald" sm text="{{ count($backups) }}" />
                        @endif
                    </div>

                    <div class="max-w-full min-w-max flex-1 grow text-center">
                        <x-ts-button color="emerald" wire:click="create()" class="text-sm text-white">
                            <x-ts-icon icon="tabler.circle-plus" class="inline-block size-5" />
                            {{ __('backup.create') }}
                        </x-ts-button>
                    </div>

                    <div class="max-w-full min-w-max flex-1 grow text-end">
                        <x-ts-icon icon="tabler.archive" class="inline-block size-5" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="grid grid-cols-1 gap-5 p-5">
                @forelse ($backups as $backup)
                    <div class="block rounded-sm bg-neutral-200 p-3 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-600">
                        <h5 class="mb-2 text-xl leading-tight font-medium text-neutral-800 dark:text-neutral-50">
                            {{ $backup['file_name'] }}
                        </h5>

                        <p class="mb-2 text-base text-neutral-600 dark:text-neutral-200">
                            {{ $backup['file_size'] }}<br />
                            {{ $backup['date_created'] }}<br />
                            {{ $backup['date_ago'] }}
                        </p>

                        <x-ts-button
                            color="primary"
                            class="mr-2 text-sm text-white"
                            wire:click="download('{{ $backup['file_name'] }}')"
                        >
                            <x-ts-icon icon="tabler.download" class="inline-block size-5" />
                            {{ __('backup.download') }}
                        </x-ts-button>

                        <x-ts-button
                            color="red"
                            class="text-sm text-white"
                            wire:click="confirm('{{ $backup['file_name'] }}')"
                        >
                            <x-ts-icon icon="tabler.trash" class="inline-block size-5" />
                            {{ __('backup.delete') }}
                        </x-ts-button>
                    </div>
                @empty
                    {{ __('backup.no_data') }}
                @endforelse
            </div>

            {{-- card footer --}}
            <div class="rounded-b border-t-2 border-neutral-100 p-2 text-sm dark:border-neutral-600">
                <p>{{ __('backup.backup_daily') }}</p>
                <p>{{ __('backup.backup_email') }}</p>

                <hr class="my-1 h-0.5 border-t-0 bg-neutral-600 opacity-100 dark:bg-neutral-400 dark:opacity-75" />

                <p>{{ __('backup.backup_cron_1') }}</p>
                <p class="text-red-600 dark:text-red-400">
                    <code>{{ __('backup.backup_cron_2') }}</code>
                </p>
            </div>
        </div>
    </div>
</div>
