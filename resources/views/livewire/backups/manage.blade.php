<div class="w-full">
    @section('title')
        &vert; {{ __('backup.backups') }}
    @endsection

    <x-slot name="heading">
        {{ __('backup.backups') }}
    </x-slot>

    <div class="p-2 max-w-5xl grow dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('backup.backups') }}
                        @if (count($backups) > 0)
                            <x-ts-badge color="emerald" text="{{ count($backups) }}" />
                        @endif
                    </div>

                    <div class="flex-1 flex-grow max-w-full text-center min-w-max">
                        <x-ts-button color="emerald" wire:click="create()" class="text-sm text-white">
                            <x-ts-icon icon="tabler.circle-plus" class="size-5" />
                            {{ __('backup.create') }}
                        </x-ts-button>
                    </div>

                    <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                        <x-ts-icon icon="tabler.archive" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="grid grid-cols-1 gap-5 p-5">
                @forelse ($backups as $backup)
                    <div class="block rounded bg-neutral-200 dark:bg-neutral-600 p-3 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
                        <h5 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                            {{ $backup['file_name'] }}
                        </h5>

                        <p class="mb-2 text-base text-neutral-600 dark:text-neutral-200">
                            {{ $backup['file_size'] }}<br />
                            {{ $backup['date_created'] }}<br />
                            {{ $backup['date_ago'] }}
                        </p>

                        <x-ts-button color="primary" class="mr-2 text-sm text-white" wire:click="download('{{ $backup['file_name'] }}')">
                            <x-ts-icon icon="tabler.download" class="size-5" />
                            {{ __('backup.download') }}
                        </x-ts-button>

                        <x-ts-button color="danger" class="text-sm text-white" wire:click="confirmDeletion('{{ $backup['file_name'] }}')">
                            <x-ts-icon icon="tabler.trash" class="size-5" />
                            {{ __('backup.delete') }}
                        </x-ts-button>
                    </div>
                @empty
                    {{ __('backup.no_data') }}
                @endforelse
            </div>

            {{-- card footer --}}
            <div class="p-2 text-sm border-t-2 rounded-b border-neutral-100 dark:border-neutral-600">
                <p>{{ __('backup.backup_daily') }}</p>
                <p>{{ __('backup.backup_email') }}</p>

                <hr class="my-1 h-0.5 border-t-0 bg-neutral-600 dark:bg-neutral-400 opacity-100 dark:opacity-75" />

                <p>{{ __('backup.backup_cron_1') }}</p>
                <p class="text-danger-600 dark:text-danger-400">
                    <code>{{ __('backup.backup_cron_2') }}</code>
                </p>
            </div>
        </div>
    </div>

    @if ($backups)
        {{-- delete modal --}}
        <x-confirmation-modal wire:model.live="deleteConfirmed">
            <x-slot name="title">
                {{ __('app.delete') }}
            </x-slot>

            <x-slot name="content">
                <p>{{ __('app.delete_question', ['model' => __('backup.delete_backup')]) }}</p>
                <p class="text-lg font-medium text-gray-900">{{ $backup_to_delete }}</p>
            </x-slot>

            <x-slot name="footer">
                <x-ts-button color="secondary" wire:click="$toggle('deleteConfirmed')" wire:loading.attr="disabled">
                    {{ __('app.abort_no') }}
                </x-ts-button>

                <x-ts-button color="danger" class="ml-3" wire:click="deleteBackup()" wire:loading.attr="disabled">
                    {{ __('app.delete_yes') }}
                </x-ts-button>
            </x-slot>
        </x-confirmation-modal>
    @endif
</div>
