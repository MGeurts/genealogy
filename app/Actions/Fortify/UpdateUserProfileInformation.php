<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

final class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'firstname' => ['nullable', 'string', 'max:255'],
            'surname'   => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo'     => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'language'  => ['required', Rule::in(config('app.available_locales'))],
            'timezone'  => ['required', Rule::in(timezone_identifiers_list())],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        /** @phpstan-ignore if.alwaysFalse, instanceof.alwaysFalse, logicalAnd.alwaysFalse */
        if ($input['email'] !== $user->email and $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'firstname' => $input['firstname'] ?? null,
                'surname'   => $input['surname'],
                'email'     => $input['email'],
                'language'  => $input['language'],
                'timezone'  => $input['timezone'],
            ])->save();
        }

        // -----------------------------------------------------------------------------------
        // store timezone and language in session
        // actual language switching wil be handled by App\Http\Middleware\Localization::class
        // -----------------------------------------------------------------------------------
        if ($input['timezone'] !== session()->get('timezone')) {
            session()->put('timezone', $input['timezone']);
        }

        if ($input['language'] !== session()->get('locale')) {
            session()->put('locale', $input['language']);

            redirect('/user/profile');
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    private function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'firstname'         => $input['firstname'] ?? null,
            'surname'           => $input['surname'],
            'email'             => $input['email'],
            'email_verified_at' => null,
            'language'          => $input['language'],
            'timezone'          => $input['timezone'],
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
