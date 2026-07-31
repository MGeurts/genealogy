@section('title')
    &vert; {{ __('app.session') }}
@endsection

<x-app-layout>
    <div class="max-w-5xl grow overflow-x-auto p-2 dark:text-neutral-200">
        <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="max-w-full min-w-max flex-1 grow">{{ __('app.session') }}</div>

                    <div class="max-w-full min-w-max flex-1 grow text-end">
                        <x-ts-icon icon="tabler.code" class="inline-block size-5" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="overflow-x-auto p-5">
                <pre>
                    @php
                        $safeSession = collect(session()->all())
                            ->reject(
                                fn ($value, $key) => $key === '_token' ||
                                $key === 'password_hash_sanctum' ||
                                str_starts_with($key, 'login_web_')
                            )
                            ->toArray();

                        print_r($safeSession);
                    @endphp
                </pre>
            </div>
        </div>
    </div>
</x-app-layout>
