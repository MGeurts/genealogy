<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Countries;
use App\Http\Controllers\Controller;
use App\Models\Userlog;
use Illuminate\View\View;

class DeveloperController extends Controller
{
    public function teams(): View
    {
        return view('back.developer.teams');
    }

    public function people(): View
    {
        return view('back.developer.people');
    }

    public function peoplelog(): View
    {
        return view('back.developer.peoplelog');
    }

    public function users(): View
    {
        return view('back.developer.users');
    }

    // --------------------------------------------------------------------------------
    public function dependencies(): View
    {
        return view('back.developer.dependencies');
    }

    public function session(): View
    {
        return view('back.developer.session');
    }

    public function userlogLog(): View
    {
        $months = 2;

        $userlogs_by_date = Userlog::select('userlogs.country_name', 'userlogs.country_code', 'userlogs.created_at', 'users.surname', 'users.firstname')
            ->leftjoin('users', 'userlogs.user_id', '=', 'users.id')
            ->where('userlogs.created_at', '>=', today()->startOfMonth()->subMonths($months))
            ->orderByDesc('userlogs.created_at')
            ->get()
            ->groupBy('date');

        return view('back.developer.userlog.log', compact('userlogs_by_date', 'months'));
    }

    public function userlogOrigin(): View
    {
        $title = __('userlog.users_by_country');

        $statistics = Userlog::select('country_name')
            ->selectRaw('COUNT(*) AS visitors')
            ->groupBy('country_name')
            ->orderByDesc('visitors')->orderBy('country_name')
            ->get();

        $labels = $statistics->pluck('country_name')->toArray();
        $values = $statistics->pluck('visitors')->toArray();

        return view('back.developer.userlog.origin', compact('title', 'labels', 'values'));
    }

    public function userlogOriginMap(): View
    {
        $title  = __('userlog.visitors');
        $nodata = __('app.nothing_recorded');

        $countries = new Countries(app()->getLocale());
        $countries = $countries->getCountryNamesForSvgMap();

        $data = Userlog::select('country_code')
            ->selectRaw('COUNT(*) AS visitors')
            ->groupBy('country_code')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->country_code => [
                        'visitors' => $item->visitors,
                    ],
                ];
            })->toArray();

        return view('back.developer.userlog.origin-map', compact('title', 'nodata', 'countries', 'data'));
    }

    public function userlogPeriod(): View
    {
        $title = __('userlog.visitors');

        $statistics_year = Userlog::selectRaw('YEAR(created_at) AS period')
            ->selectRaw('COUNT(*) AS visitors')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $statistics_year_labels = $statistics_year->pluck('period')->toArray();
        $statistics_year_values = $statistics_year->pluck('visitors')->toArray();

        $statistics_month = Userlog::selectRaw('LPAD(MONTH(created_at), 2, 0) AS period')
            ->selectRaw('COUNT(*) AS visitors')
            ->whereYear('created_at', date('Y'))
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $statistics_month_labels = $statistics_month->pluck('period')->toArray();
        $statistics_month_values = $statistics_month->pluck('visitors')->toArray();

        return view('back.developer.userlog.period', compact('title', 'statistics_year_labels', 'statistics_year_values', 'statistics_month_labels', 'statistics_month_values'));
    }
}
