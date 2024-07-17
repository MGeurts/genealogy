<?php

return [
    // Labels
    'biological' => 'biologisch',
    'person'     => 'Persoon',
    'people'     => 'Personen',
    'people_log' => 'Personen logboek',

    'family'  => 'Familie',
    'profile' => 'Profiel',

    'partner'  => 'Partner',
    'partners' => 'Partners',

    'children'      => 'Kinderen',
    'parents'       => 'Ouders',
    'grandchildren' => 'Kleinkinderen',
    'siblings'      => 'Broers of zussen',
    'ancestors'     => 'Voorouders',
    'descendants'   => 'Nakomelingen',
    'dead'          => 'Overleden',
    'death'         => 'Overlijden',
    'deceased'      => 'Overleden',

    'grandmother'   => 'Grootmoeder',
    'grandfather'   => 'Grootvader',
    'nieces'        => 'Nichten',
    'nephews'       => 'Neven',
    'cousins'       => 'Kozijnen',
    'uncles'        => 'Ooms',
    'aunts'         => 'Tantes',
    'relationships' => 'Relaties',
    'age'           => 'Leeftijd',
    'years'         => '[0,1] Jaar|[2,*] Jaar',

    'source'      => 'Bron',
    'source_hint' => 'Geef de bron op van de bestanden die u wilt uploaden',

    // Actions
    'add_father'                     => 'Vader toevoegen',
    'add_new_person_as_father'       => 'NIEUWE persoon als vader toevoegen',
    'add_existing_person_as_father'  => 'BESTAANDE persoon als vader toevoegen',
    'add_mother'                     => 'Moeder toevoegen',
    'add_new_person_as_mother'       => 'NIEUWE persoon als moeder toevoegen',
    'add_existing_person_as_mother'  => 'BESTAANDE persoon als moeder toevoegen',
    'add_child'                      => 'Kind toevoegen',
    'add_new_person_as_child'        => 'NIEUWE persoon als kind toevoegen',
    'add_existing_person_as_child'   => 'BESTAANDE persoon als kind toevoegen',
    'add_person'                     => 'Persoon toevoegen',
    'add_new_person_as_partner'      => 'NIEUWE persoon als partner toevoegen',
    'add_existing_person_as_partner' => 'BESTAANDE persoon als partner toevoegen',
    'add_person_in_team'             => 'Persoon toevoegen aan team : :team',
    'add_photo'                      => 'Foto toevoegen',
    'add_relationship'               => 'Relatie toevoegen',

    'edit'              => 'Editeer',
    'edit_children'     => 'Editeer kinderen',
    'edit_contact'      => 'Editeer contactgegevens',
    'edit_death'        => 'Editeer overlijden',
    'edit_family'       => 'Editeer familie',
    'edit_person'       => 'Editeer persoon',
    'edit_profile'      => 'Editeer profiel',
    'edit_relationship' => 'Editeer relatie',

    'delete_child'        => 'Kind ontkoppelen',
    'delete_person'       => 'Verwijder persoon',
    'delete_relationship' => 'Verwijder relatie',

    // Attributes
    'id'          => 'ID',
    'name'        => 'Naam',
    'firstname'   => 'Voornaam',
    'surname'     => 'Achternaam',
    'birthname'   => 'Geboortenaam',
    'nickname'    => 'Bijnaam',
    'sex'         => 'Geslacht',
    'gender'      => 'Gender identiteit',
    'father'      => 'Vader',
    'mother'      => 'Moeder',
    'parent'      => 'Ouder',
    'dob'         => 'Datum geboorte',
    'yob'         => 'Jaar geboorte',
    'pob'         => 'Plaats geboorte',
    'dod'         => 'Datum overlijden',
    'yod'         => 'Jaar overlijden',
    'pod'         => 'Plaats overlijden',
    'email'       => 'E-mail',
    'password'    => 'Password',
    'address'     => 'Adres',
    'street'      => 'Straat',
    'number'      => 'Nummer',
    'postal_code' => 'Postcode',
    'city'        => 'Plaats',
    'province'    => 'Provincie',
    'state'       => 'Staat',
    'country'     => 'Land',
    'phone'       => 'Telefoon',

    'cemetery'          => 'Begraafplaats',
    'cemetery_location' => 'Locatie begraafplaats',

    // Files
    'upload_files'     => 'Bestanden uploaden',
    'files'            => 'Bestanden',
    'files_saved'      => '[0] Geen bestand bewaard|[1] Bestand bewaard|[2,*] Bestanden bewaard',
    'file'             => 'Bestand',
    'file_deleted'     => 'Bestand verwijderd',
    'update_files_tip' => 'Sleep uw nieuwe bestanden hierheen',

    // Photo
    'avatar'            => 'Avatar',
    'edit_photos'       => 'Editeer afbeeldingen',
    'photo_deleted'     => 'Afbeelding verwijderd',
    'photo'             => 'Afbeelding',
    'photos'            => 'Afbeeldingen',
    'photos_saved'      => '[0] Geen afbeelding bewaard|[1] Afbeelding bewaard|[2,*] Afbeeldingen bewaard',
    'photos_existing'   => 'Bestaande afbeeldingen',
    'set_primary'       => 'Als primaire afbeelding instellen',
    'upload_photos'     => 'Afbeeldingen uploaden',
    'update_photos_tip' => 'Sleep uw nieuwe afbeeldingen hierheen',

    // Messages
    'yod_not_matching_dod' => 'Het Jaar overlijden moet overeenkomen met de Datum overlijden (:value).',
    'yod_before_dob'       => 'Het Jaar overlijden mag niet voor de Datum geboorte (:value) zijn.',
    'yod_before_yob'       => 'Het Jaar overlijden mag niet voor het Jaar geboorte (:value) zijn.',

    'dod_not_matching_yod' => 'De datum overlijden moet overeenkomen met het Jaar overlijden (:value).',
    'dod_before_dob'       => 'De Datum overlijden mag niet voor de Datum geboorte (:value) zijn.',
    'dod_before_yob'       => 'De Datum overlijden mag niet voor de Jaar geboorte (:value) zijn.',

    'yob_not_matching_dob' => 'Het Jaar geboorte moet overeenkomen met de Datum geboorte (:value).',
    'yob_after_dod'        => 'Het Jaar geboorte mag niet na de Datum overlijden (:value) zijn.',
    'yob_after_yod'        => 'Het Jaar geboorte mag niet na het Jaar overlijden (:value) zijn.',

    'dob_not_matching_yob' => 'De Datum geboorte moet overeenkomen met het Jaar geboorte (:value).',
    'dob_after_dod'        => 'De Datum geboorte mag niet na de Datum overlijden (:value) zijn.',
    'dob_after_yod'        => 'De Datum geboorte mag niet na het jaar overlijden (:value) zijn.',

    'not_found' => 'Persoon niet gevonden',
    'use_tab'   => 'Gebruik tab',

    'insert_tip_1' => 'Voer een achternaam, een voornaam, een geboortenaam of een bijnaam in.',
    'insert_tip_2' => 'Niet combineren!',

];
