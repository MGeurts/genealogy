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
 * @property int $person1_id
 * @property int $person2_id
 * @property \Carbon\CarbonImmutable|null $date_start
 * @property \Carbon\CarbonImmutable|null $date_end
 * @property bool $is_married
 * @property bool $has_ended
 * @property int|null $team_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $children
 * @property-read int|null $children_count
 * @property-read string|null $date_start_formatted
 * @property-read string|null $name
 * @property-read \App\Models\Person|null $person_1
 * @property-read \App\Models\Person|null $person_2
 * @property-read \App\Models\Team|null $team
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple olderThan(?string $birth_year = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple whereHasEnded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple whereIsMarried($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple wherePerson1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple wherePerson2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Couple youngerThan(?string $birth_year = null)
 */
	class Couple extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender whereUpdatedAt($value)
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUserId($value)
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
 * @property \Carbon\CarbonImmutable|null $dob
 * @property int|null $yob
 * @property string|null $pob
 * @property \Carbon\CarbonImmutable|null $dod
 * @property int|null $yod
 * @property string|null $pod
 * @property string|null $summary
 * @property string|null $street
 * @property string|null $number
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $province
 * @property string|null $state
 * @property string|null $country
 * @property string|null $phone
 * @property string|null $photo
 * @property int|null $team_id
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person> $children_with_children
 * @property-read int|null $children_with_children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Couple> $couples
 * @property-read int|null $couples_count
 * @property-read Person|null $father
 * @property-read \App\Models\Gender|null $gender
 * @property-read string|null $address
 * @property-read string|null $address_google
 * @property-read int|null $age
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person olderThan(?string $birth_year)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person partnerOffset(?string $birth_year, int $offset = 40)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person search(string $searchString)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereBirthname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereDod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereFatherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereMotherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereParentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person wherePob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person wherePod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereYob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereYod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person youngerThan(?string $birth_year)
 */
	class Person extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $person_id
 * @property string $key
 * @property string|null $value
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMetadata whereValue($value)
 */
	class PersonMetadata extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Couple> $couples
 * @property-read int|null $couples_count
 * @property-read \App\Models\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $persons
 * @property-read int|null $persons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUserId($value)
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereUpdatedAt($value)
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
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property string $language
 * @property string $timezone
 * @property bool $is_developer
 * @property \Carbon\CarbonImmutable|null $seen_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read string|null $name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read string $profile_photo_url
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Userlog> $userlogs
 * @property-read int|null $userlogs_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsDeveloper($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $date
 * @property-read mixed $time
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserlogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog whereCountryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Userlog whereUserId($value)
 */
	class Userlog extends \Eloquent {}
}

