<?php

declare(strict_types=1);

namespace App\Livewire\Developer;

use App\Livewire\Forms\Developer\SettingsForm;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Settings extends Component
{
    use Interactions;

    public Collection $settings;

    public SettingsForm $settingsForm;

    /**
     * Mount the component and load initial data.
     */
    public function mount(): void
    {
        // Fetch settings and map them to the properties
        $this->settings = Setting::pluck('value', 'key');

        $this->settingsForm->logAllQueries              = (bool) $this->settings->get('log_all_queries');
        $this->settingsForm->logAllQueriesSlowThreshold = $this->settings->get('log_all_queries_slow_threshold');
        $this->settingsForm->logAllQueriesSlow          = (bool) $this->settings->get('log_all_queries_slow');
        $this->settingsForm->logAllQueriesNPlusOne      = (bool) $this->settings->get('log_all_queries_nplusone');
    }

    /**
     * Save the changes
     */
    public function saveSettings(): void
    {
        $settings = [
            'log_all_queries'                => $this->settingsForm->logAllQueries,
            'log_all_queries_slow'           => $this->settingsForm->logAllQueriesSlow,
            'log_all_queries_slow_threshold' => $this->settingsForm->logAllQueriesSlowThreshold,
            'log_all_queries_nplusone'       => $this->settingsForm->logAllQueriesNPlusOne,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Clear the cache
        Cache::forget('settings');

        $this->toast()->success(__('app.save'), __('app.saved'))->send();
    }

    /**
     * Render the Livewire component view.
     */
    public function render(): View
    {
        return view('livewire.developer.settings');
    }
}
