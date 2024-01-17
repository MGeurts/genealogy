<div class="w-full">
    @section('title')
        &vert; {{ __('backup.backups') }}
    @endsection

    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('backup.backups') }}
        </h2>
    </x-slot>

    <div class="grow max-w-5xl py-5 dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
            <!-- card header -->
            <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
                <div class="flex flex-wrap gap-2 justify-center items-start">
                    <div class="flex-grow min-w-max max-w-full flex-1">
                        {{ __('backup.backups') }} ({{ count($backups) }})
                    </div>

                    <div class="min-w-max flex-grow max-w-full flex-1 text-center">
                        <x-button.success class="ml-4" wire:click="create()">
                            <x-icon.tabler icon="circle-plus" class="mr-2" />
                            {{ __('backup.create') }}
                        </x-button.success>
                    </div>

                    <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                        <x-icon.tabler icon="archive-filled" />
                    </div>
                </div>
            </div>

            <!-- card body -->
            <div class="p-5 grid grid-cols-1 gap-5">
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

                        <x-button.primary class="mr-2" wire:click="download('{{ $backup['file_name'] }}')">
                            <x-icon.tabler icon="download" class="mr-2" />
                            {{ __('backup.download') }}
                        </x-button.primary>

                        <x-button.danger wire:click="confirmDeletion('{{ $backup['file_name'] }}')">
                            <x-icon.tabler icon="trash-filled" class="mr-2" />
                            {{ __('backup.delete') }}
                        </x-button.danger>
                    </div>
                @empty
                    {{ __('backup.no_data') }}
                @endforelse
            </div>

            <!-- card footer -->
            <div class="p-2 text-sm border-t-2 border-neutral-100 dark:border-neutral-600 rounded-b">
                <p class="py-0">{{ __('backup.backup_daily') }}</p>
                <p class="py-0">{{ __('backup.backup_email') }}</p>

                <hr class="my-1 h-0.5 border-t-0 bg-neutral-600 dark:bg-neutral-400 opacity-100 dark:opacity-75" />

                <p class="py-0">{{ __('backup.backup_cron_1') }}</p>
                <p class="py-0 text-danger">
                    <code>{{ __('backup.backup_cron_2') }}</code>
                </p>
            </div>
        </div>
    </div>

    @if ($backups)
        <!-- Delete modal -->
        <x-confirmation-modal wire:model.live="deleteConfirmed">
            <x-slot name="title">
                {{ __('app.delete') }}
            </x-slot>

            <x-slot name="content">
                <h1>{{ __('app.delete_question', ['model' => __('backup.delete_backup')]) }}</h1>
                <br />
                <h3 class="text-lg font-medium text-gray-900">{{ $backup_to_delete }}</h3>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$toggle('deleteConfirmed')" wire:loading.attr="disabled">
                    {{ __('app.abort_no') }}
                </x-button.secondary>

                <x-button.danger class="ml-3" wire:click="deleteBackup()" wire:loading.attr="disabled">
                    {{ __('app.delete_yes') }}
                </x-button.danger>
            </x-slot>
        </x-confirmation-modal>
    @endif
</div>
