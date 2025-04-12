<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Developer;

use Livewire\Form;

final class SettingsForm extends Form
{
    // -----------------------------------------------------------------------
    public bool $logAllQueries = false;

    public bool $logAllQueriesSlow = false;

    public string $logAllQueriesSlowThreshold = '500';

    public bool $logAllQueriesNPlusOne = false;

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return $rules = [
            'log_all_queries'                => ['boolean'],
            'log_all_queries_slow'           => ['boolean'],
            'log_all_queries_slow_threshold' => ['integer'],
            'log_all_queries_nplusone'       => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [
            'log_all_queries'                => __('settings.log_all_queries'),
            'log_all_queries_slow'           => __('settings.log_all_queries_slow'),
            'log_all_queries_slow_threshold' => __('settings.log_all_queries_slow_threshold'),
            'log_all_queries_nplusone'       => __('settings.log_all_queries_nplusone'),
        ];
    }
}
