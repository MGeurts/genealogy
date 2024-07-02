<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property-write string|null $iso2
 * @property-write string|null $iso3
 * @property string $name
 * @property string $name_nl
 * @property string|null $isd
 * @property bool $is_eu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsEu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIso2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNameNl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $person1_id
 * @property int $person2_id
 * @property \Illuminate\Support\Carbon|null $date_start
 * @property \Illuminate\Support\Carbon|null $date_end
 * @property bool $is_married
 * @property bool $has_ended
 * @property int|null $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $children
 * @property-read int|null $children_count
 * @property-read string|null $date_start_formatted
 * @property-read string|null $name
 * @property-read \App\Models\Person|null $person_1
 * @property-read \App\Models\Person|null $person_2
 * @property-read \App\Models\Team|null $team
 * @method static \Illuminate\Database\Eloquent\Builder|Couple newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Couple newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Couple olderThan(?string $date_start)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple query()
 * @method static \Illuminate\Database\Eloquent\Builder|Couple whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple whereHasEnded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple whereIsMarried($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple wherePerson1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple wherePerson2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Couple youngerThan(?string $date_start)
 */
	class Couple extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Gender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereUpdatedAt($value)
 */
	class Gender extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $team_id
 * @property int $user_id
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUserId($value)
 */
	class Membership extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $firstname
 * @property string $surname
 * @property string|null $birthname
 * @property string|null $nickname
 * @property string $sex
 * @property int|null $gender_id
 * @property int|null $father_id
 * @property int|null $mother_id
 * @property int|null $parents_id
 * @property \Illuminate\Support\Carbon|null $dob
 * @property int|null $yob
 * @property string|null $pob
 * @property \Illuminate\Support\Carbon|null $dod
 * @property int|null $yod
 * @property string|null $pod
 * @property string|null $street
 * @property string|null $number
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $province
 * @property string|null $state
 * @property int|null $country_id
 * @property string|null $phone
 * @property string|null $photo
 * @property int|null $team_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Country|null $country
 * @property-read Person|null $father
 * @property-read \App\Models\Gender|null $gender
 * @property-read string|null $address
 * @property-read string|null $address_google
 * @property-read int|null $age
 * @property-read string|null $birth_date
 * @property-read string|null $birth_formatted
 * @property-read string|null $birth_year
 * @property-read string|null $cemetery_google
 * @property-read string|null $death_formatted
 * @property-read string|null $lifetime
 * @property-read string|null $name
 * @property-read int|null $next_birthday_age
 * @property-read \Carbon\Carbon|null $next_birthday
 * @property-read int|null $next_birthday_remaining_days
 * @property-read \Illuminate\Support\Collection $partners
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonMetadata> $metadata
 * @property-read int|null $metadata_count
 * @property-read Person|null $mother
 * @property-read \App\Models\Couple|null $parents
 * @property-read \App\Models\Team|null $team
 * @method static \Illuminate\Database\Eloquent\Builder|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person olderThan(?string $birth_date, ?string $birth_year)
 * @method static \Illuminate\Database\Eloquent\Builder|Person onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Person partnerOffset(?string $birth_date, ?int $birth_year, int $offset = 40)
 * @method static \Illuminate\Database\Eloquent\Builder|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder|Person search(string $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereBirthname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereDod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereFatherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereMotherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereParentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person wherePob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person wherePod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereYob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereYod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Person withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Person youngerThan(?string $birth_date, ?string $birth_year)
 */
	class Person extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $person_id
 * @property-write string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonMetadata whereValue($value)
 */
	class PersonMetadata extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $description
 * @property bool $personal_team
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Couple> $couples
 * @property-read int|null $couples_count
 * @property-read \App\Models\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $persons
 * @property-read int|null $persons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $team_id
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereUpdatedAt($value)
 */
	class TeamInvitation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $firstname
 * @property string $surname
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property string $language
 * @property string $timezone
 * @property bool $is_developer
 * @property \Illuminate\Support\Carbon|null $seen_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read string|null $name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Userlog> $userlogs
 * @property-read int|null $userlogs_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsDeveloper($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $country_name
 * @property string|null $country_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $date
 * @property-read mixed $time
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserlogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog whereCountryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Userlog whereUserId($value)
 */
	class Userlog extends \Eloquent {}
}

