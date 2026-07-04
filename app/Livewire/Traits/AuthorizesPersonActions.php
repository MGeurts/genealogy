<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

/**
 * Trait for enforcing permission checks inside Livewire component action methods.
 *
 * Blade views often hide buttons/menu items based on `auth()->user()->hasPermission(...)`,
 * but Livewire component methods (e.g. `confirm()`, `delete()`, `disconnect()`) remain
 * directly callable regardless of what the UI renders. Any such method that mutates data
 * MUST call `$this->authorizePermission(...)` as its first line so the check is enforced
 * server-side, not just implied by the Blade guard.
 *
 * Named `authorizePermission()` rather than `authorize()` to avoid any confusion with
 * Laravel's policy-based `AuthorizesRequests::authorize()`, which this does not use.
 */
trait AuthorizesPersonActions
{
    /**
     * Abort with a 403 unless the current user has the given permission.
     */
    protected function authorizePermission(string $permission): void
    {
        abort_unless((bool) auth()->user()?->hasPermission($permission), 403, __('app.unauthorized_access'));
    }
}
