<div class="md:grid md:grid-cols-3 md:gap-5">
    <x-section-title>
        <x-slot name="title">
            <div class="dark:text-gray-400">
                {{ __('team.transfer_ownership') }}
            </div>
        </x-slot>

        <x-slot name="description">
            <div class="dark:text-gray-100">
                {{ __('team.transfer_message') }}
            </div>
        </x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        @if ($team->users->count() > 0)
            <form method="POST" action="{{ route('teams.transfer-ownership', $team) }}">
                @csrf
                @method('PUT')

                <div class="px-4 py-5 bg-white sm:p-6 sm:rounded-tl sm:rounded-tr">
                    <div class="grid grid-cols-6 gap-5">
                        <div class="col-span-6">
                            <div class="max-w-xl text-sm text-gray-600">
                                {{ __('team.select_new_owner') }}
                            </div>
                        </div>

                        <div class="col-span-6">
                            <div class="md:w-1/3">
                                <x-label for="new_owner_id" value="{{ __('team.new_owner') }} :" />
                            </div>

                            <div class="md:w-2/3">
                                <select name="new_owner_id" id="new_owner_id" class="block w-full rounded" required>
                                    <option value="">{{ __('app.select') }} ...</option>

                                    @foreach ($team->users as $user)
                                        @if ($user->id !== $team->owner->id)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 text-right bg-gray-200 sm:px-6 sm:rounded-bl sm:rounded-br">
                    <x-ts-button type="submit" color="primary">
                        {{ __('team.transfer') }}
                    </x-ts-button>
                </div>
            </form>
        @else
            <div class="px-4 py-5 bg-white sm:p-6 rounded">
                <x-ts-alert title="{{ __('team.transfer_ownership') }}" text="{{ __('team.can_not_transfer') }}" color="cyan" />
            </div>
        @endif
    </div>
</div>
