<?php

declare(strict_types=1);

return [
    // Labels
    'biological'      => 'biologisch',
    'contact'         => 'Contact',
    'person'          => 'Persoon',
    'person_metadata' => 'Metagegevens van persoon',
    'people'          => 'Personen',

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
    'birth'         => 'Geboorte',
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

    'source'           => 'Bron',
    'source_hint'      => 'Geef de bron op van de bestanden die u wilt uploaden',
    'source_date'      => 'Datum',
    'source_date_hint' => 'Geef de brondatum op van de bestanden die u gaat uploaden',

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
    'edit_files'        => 'Editeer bestanden',
    'edit_person'       => 'Editeer persoon',
    'edit_profile'      => 'Editeer profiel',
    'edit_relationship' => 'Editeer relatie',

    'delete_child'        => 'Kind ontkoppelen',
    'delete_person'       => 'Verwijder persoon',
    'delete_relationship' => 'Verwijder relatie',

    // Attributes
    'id'          => 'ID',
    'name'        => 'Naam',
    'names'       => 'Namen',
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
    'summary'     => 'Samenvatting',
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
    'files'            => 'Bestanden',
    'files_saved'      => '[0] Geen bestand bewaard|[1] Bestand bewaard|[2,*] Bestanden bewaard',
    'file'             => 'Bestand',
    'file_deleted'     => 'Bestand verwijderd',
    'upload_files'     => 'Bestanden uploaden',
    'upload_files_tip' => 'Sleep uw nieuwe bestanden hierheen ...',

    'upload_accept_types' => 'Toegestaan: :types',
    'upload_max_size'     => 'Maximale grootte : :max KB',

    // Photo
    'avatar'                   => 'Avatar',
    'edit_photos'              => 'Foto’s bewerken',
    'photo_delete_failed'      => 'Foto verwijderen mislukt',
    'photo_deleted'            => 'Foto verwijderd',
    'photo'                    => 'Foto',
    'photos'                   => 'Foto’s',
    'photos_saved'             => '[0] Geen foto’s opgeslagen|[1] Foto opgeslagen|[2,*] :count Foto’s opgeslagen',
    'photos_save_failed'       => '(Een aantal) foto’s konden niet worden opgeslagen',
    'photos_existing'          => 'Bestaande foto’s',
    'photo_set_primary'        => 'Instellen als primair',
    'photo_set_primary_failed' => 'Primair instellen mislukt',
    'photo_is_set_primary'     => 'Nieuwe foto is als primair ingesteld',
    'upload_photos'            => 'Foto’s uploaden',
    'upload_photos_tip'        => 'Sleep je nieuwe foto’s hierheen...',

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

    'existing_person_linked_as_father'  => 'Bestaande persoon gekoppeld als vader.',
    'new_person_linked_as_father'       => 'Nieuwe persoon gekoppeld als vader.',
    'existing_person_linked_as_mother'  => 'Bestaande persoon gekoppeld als moeder.',
    'new_person_linked_as_mother'       => 'Nieuwe persoon gekoppeld als moeder.',
    'existing_person_linked_as_child'   => 'Bestaande persoon gekoppeld als kind.',
    'new_person_linked_as_child'        => 'Nieuwe persoon gekoppeld als kind.',
    'existing_person_linked_as_partner' => 'Bestaande persoon gekoppeld als partner.',
    'new_person_linked_as_parther'      => 'Nieuwe persoon gekoppeld als partner.',

    'family_caution_1' => 'Vader en Moeder mogen alleen worden gebruikt voor de biologische ouders en moeten daarom van verschillend geslacht zijn.',
    'family_caution_2' => 'Ouders kunnen de biologische ouders zijn, maar ook niet-biologische (homoseksuele of adoptieve) ouders. Laat in dat geval Vader en Moeder gewoon leeg.',

    'parents_id_exclusive' => 'Ouders is exclusief. Als je Ouders instelt, kun je Vader of Moeder niet instellen.',
];
