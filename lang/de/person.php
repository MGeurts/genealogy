<?php

declare(strict_types=1);

return [
    // Labels
    'biological'      => 'biologisch',
    'contact'         => 'Kontakt',
    'person'          => 'Person',
    'person_metadata' => 'Personenmetadaten',
    'people'          => 'Personen',

    'family'  => 'Familie',
    'profile' => 'Profil',

    'partner'  => 'Partner',
    'partners' => 'Partner',

    'children'      => 'Kinder',
    'parents'       => 'Eltern',
    'grandchildren' => 'Enkelkinder',
    'siblings'      => 'Geschwister',
    'ancestors'     => 'Vorfahren',
    'descendants'   => 'Nachfahren',
    'birth'         => 'Geburt',
    'dead'          => 'Tot',
    'death'         => 'Tod',
    'deceased'      => 'Verstorben',

    'grandmother'   => 'Großmutter',
    'grandfather'   => 'Großvater',
    'nieces'        => 'Nichten',
    'nephews'       => 'Neffen',
    'cousins'       => 'Cousine/Cousin',
    'uncles'        => 'Onkel',
    'aunts'         => 'Tanten',
    'relationships' => 'Beziehungen',
    'age'           => 'Alter',
    'years'         => '[1] Jahr|[0,2,*] Jahre',

    'source'           => 'Quelle',
    'source_hint'      => 'Geben Sie die Quelle der Dateien an, die Sie hochladen möchten',
    'source_date'      => 'Datum',
    'source_date_hint' => 'Geben Sie das Datum der Quelle der Datei(en) an, die Sie hochladen möchten',

    // Actions
    'add_father'                     => 'Vater hinzufügen',
    'add_new_person_as_father'       => 'NEUE Person als Vater hinzufügen',
    'add_existing_person_as_father'  => 'VORHANDENE Person als Vater hinzufügen',
    'add_mother'                     => 'Mutter hinzufügen',
    'add_new_person_as_mother'       => 'NEUE Person als Mutter hinzufügen',
    'add_existing_person_as_mother'  => 'VORHANDENE Person als Mutter hinzufügen',
    'add_child'                      => 'Kind hinzufügen',
    'add_new_person_as_child'        => 'NEUE Person als Kind hinzufügen',
    'add_existing_person_as_child'   => 'VORHANDENE Person als Kind hinzufügen',
    'add_person'                     => 'Person hinzufügen',
    'add_new_person_as_partner'      => 'NEUE Person als Partner hinzufügen',
    'add_existing_person_as_partner' => 'VORHANDENE Person als Partner hinzufügen',
    'add_person_in_team'             => 'Person hinzufügen in Team : :team',
    'add_photo'                      => 'Foto hinzufügen',
    'add_relationship'               => 'Beziehung hinzufügen',

    'edit'              => 'Bearbeiten',
    'edit_children'     => 'Kinder bearbeiten',
    'edit_contact'      => 'Kontakt bearbeiten',
    'edit_death'        => 'Tod bearbeiten',
    'edit_family'       => 'Familie bearbeiten',
    'edit_files'        => 'Dateien bearbeiten',
    'edit_person'       => 'Person bearbeiten',
    'edit_profile'      => 'Profil bearbeiten',
    'edit_relationship' => 'Beziehung bearbeiten',

    'delete_child'        => 'Untergeordnetes Kind trennen',
    'delete_person'       => 'Person löschen',
    'delete_relationship' => 'Beziehung löschen',

    // Attributes
    'id'          => 'ID',
    'name'        => 'Name',
    'names'       => 'Namen',
    'firstname'   => 'Vorname',
    'surname'     => 'Nachname',
    'birthname'   => 'Geburtsname',
    'nickname'    => 'Spitzname',
    'sex'         => 'Geschlecht',
    'gender'      => 'Geschlechtsidentität',
    'father'      => 'Vater',
    'mother'      => 'Mutter',
    'parent'      => 'Elternteil',
    'dob'         => 'Geburtsdatum',
    'yob'         => 'Geburtsjahr',
    'pob'         => 'Geburtsort',
    'dod'         => 'Sterbedatum',
    'yod'         => 'Sterbejahr',
    'pod'         => 'Sterbeort',
    'summary'     => 'Zusammenfassung',
    'email'       => 'E-Mail Adresse',
    'password'    => 'Passwort',
    'address'     => 'Adresse',
    'street'      => 'Straße',
    'number'      => 'Hausnummer',
    'postal_code' => 'Postleitzahl',
    'city'        => 'Stadt',
    'province'    => 'Province',
    'state'       => 'State',
    'country'     => 'Land',
    'phone'       => 'Telefonnummer',

    'cemetery'          => 'Friedhof',
    'cemetery_location' => 'Ort des Friedhofs',

    // Files
    'files'            => 'Dateien',
    'files_saved'      => '[0] Keine Dateien gespeicherd|[1] Datei gespeicherd|[2,*] Dateien gespeicherd',
    'file'             => 'Datei gelöscht',
    'file_deleted'     => 'Datei',
    'upload_files'     => 'Dateien hochladen',
    'upload_files_tip' => 'Ziehen Sie Ihr neue Dateien hierher ...',

    'upload_accept_types' => 'Erlaubt : :types',
    'upload_max_size'     => 'Maximale Größe : :max KB',

    // Photo
    'avatar'            => 'Benutzerbild',
    'edit_photos'       => 'Fotos bearbeiten',
    'photo_deleted'     => 'Foto gelöscht',
    'photo'             => 'Foto',
    'photos'            => 'Fotos',
    'photos_saved'      => '[0] Keine Fotos gespeicherd|[1] Foto gespeicherd|[2,*] Fotos gespeicherd',
    'photos_existing'   => 'Bestehende Fotos',
    'set_primary'       => 'Als Hauptfoto einstellen',
    'upload_photos'     => 'Fotos hochladen',
    'upload_photos_tip' => 'Ziehen Sie Ihr neue Fotos hierher ...',

    // Messages
    'yod_not_matching_dod' => 'Das Sterbejahr muss übereinstimmen mit dem Sterbedatum (:value).',
    'yod_before_dob'       => 'Das Sterbejahr darf nicht vor dem Geburtsdatum (:value) sein.',
    'yod_before_yob'       => 'Das Sterbejahr darf nicht vor dem Geburtsjahr (:value) sein.',

    'dod_not_matching_yod' => 'Das Sterbedatum muss übereinstimmen mit dem Sterbejahr (:value).',
    'dod_before_dob'       => 'Das Sterbedatum darf nicht nach dem Geburtsdatum (:value) sein.',
    'dod_before_yob'       => 'Das Sterbedatum darf nicht nach dem Geburtsjahr (:value) sein.',

    'yob_not_matching_dob' => 'Das Sterbejahr muss übereinstimmen mit dem Sterbedatum (:value).',
    'yob_after_dod'        => 'Das Sterbejahr darf nicht vor dem Geburtsdatum (:value) sein.',
    'yob_after_yod'        => 'Das Sterbejahr darf nicht vor dem Geburtsjahr (:value) sein.',

    'dob_not_matching_yob' => 'Das Geburtsdatum muss übereinstimmen mit dem Geburtsjahr (:value).',
    'dob_after_dod'        => 'Das Geburtsdatum darf nicht nach dem Sterbedatum (:value) sein.',
    'dob_after_yod'        => 'Das Geburtsdatum darf nicht nach dem Sterbejahr (:value) sein.',

    'not_found' => 'Person nicht gefunden',
    'use_tab'   => 'Benutze tab',

    'existing_person_linked_as_father'  => 'Vorhandene Person als Vater verknüpft.',
    'new_person_linked_as_father'       => 'Neue Person als Vater verknüpft.',
    'existing_person_linked_as_mother'  => 'Vorhandene Person als Mutter verknüpft.',
    'new_person_linked_as_mother'       => 'Neue Person als Mutter verknüpft.',
    'existing_person_linked_as_child'   => 'Vorhandene Person als Kind verknüpft.',
    'new_person_linked_as_child'        => 'Neue Person als Kind verknüpft.',
    'existing_person_linked_as_partner' => 'Vorhandene Person als Partner verknüpft.',
    'new_person_linked_as_parther'      => 'Neue Person als Partner verknüpft.',

    'family_caution_1' => 'Vater und Mutter dürfen nur für die biologischen Eltern verwendet werden und müssen daher unterschiedlichen Geschlechts sein.',
    'family_caution_2' => 'Eltern können die biologischen Eltern sein, aber auch nicht-biologische (gleichgeschlechtliche oder Adoptiveltern). In diesem Fall lassen Sie Vater und Mutter einfach leer.',

    'parents_id_exclusive' => 'Eltern sind exklusiv. Wenn Sie Eltern setzen, können Sie Vater oder Mutter nicht setzen.',
];
