@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-red-600">{{ __('app.whoops') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
