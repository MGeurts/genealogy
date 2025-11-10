<?php

declare(strict_types=1);

return [
    // Menus
    'about'            => 'Über',
    'dependencies'     => 'Abhängigkeiten',
    'help'             => 'Hilfe',
    'home'             => 'Start',
    'menu'             => 'Menü',
    'privacy_policy'   => 'Datenschutzrichtlinien',
    'session'          => 'Sitzung',
    'terms_of_service' => 'Nutzungsbedingungen',
    'useful_links'     => 'Nützliche Links',
    'impressum'        => 'Impressum',
    'log_viewer'       => 'Protokollbetrachter',

    // Labels
    'all'               => 'Alle',
    'filter'            => 'Filter',
    'api_tokens'        => 'API-Token',
    'attention'         => 'Achtung',
    'contact'           => 'Kontakt',
    'datasheet'         => 'Datenblatt',
    'death'             => 'Tod',
    'documentation'     => 'Documentation',
    'family_chart'      => 'Familiendiagramm',
    'female'            => 'Weiblich',
    'history'           => 'Geschichte',
    'male'              => 'Männlich',
    'manage_account'    => 'Konto verwalten',
    'my_profile'        => 'Mein Profil',
    'nothing_available' => 'Nichts verfügbar',
    'nothing_found'     => 'Nichts gefunden',
    'nothing_recorded'  => 'Noch nichts erfasst.',
    'search'            => 'Suche',
    'yes'               => 'Ja',
    'no'                => 'Nein',
    'error'             => 'Fehler',

    'created_at' => 'Erstellt am',
    'updated_at' => 'Aktualisiert am',
    'deleted_at' => 'Gelöscht am',

    'language'        => 'Sprache',
    'language_select' => 'Sprache auswählen',
    'language_set'    => 'Sprache gesetzt auf',

    'attribute' => 'Attribuut',
    'old'       => 'Alt',
    'new'       => 'Neu',
    'value'     => 'Wert',

    // Actions
    'add'     => 'Hinzufügen',
    'cancel'  => 'Abbrechen',
    'create'  => 'Erstellen',
    'created' => 'Erstellt',

    'download'    => 'Herunterladen',
    'downloading' => 'Der Download wird gestartet.',

    'move_down' => 'Runter',
    'move_up'   => 'Hoch',

    'show_death'        => 'Tod anzeigen',
    'show_family_chart' => 'Familiendiagramm anzeigen',
    'show_profile'      => 'Profil anzeigen',

    'save'   => 'Speichern',
    'saved'  => 'Gespeichert',
    'select' => 'Auswählen',
    'show'   => 'Zeigen',

    // Deletion confirm attributes
    'abort_no'            => 'Nein, abbrechen',
    'are_you_sure'        => 'Sind sie sicher?',
    'confirm'             => 'Bestätigen',
    'delete'              => 'Löschen',
    'deleted'             => 'wurde gelöscht',
    'delete_yes'          => 'Ja, löschen',
    'delete_question'     => 'Willst du :model wirklich löschen?',
    'delete_person'       => 'diese Person',
    'delete_relationship' => 'diese Beziehung',
    'disconnect'          => 'Trennen',
    'disconnected'        => 'wurde getrennt',
    'disconnect_child'    => 'dieses Kind',
    'disconnect_question' => 'Willst du :model wirklich trennen?',
    'disconnect_yes'      => 'Ja, trennen',

    // Messages
    'image_not_saved' => 'Bild kann nicht gespeichert werden',

    'show_on_google_maps' => 'Auf Google Maps anzeigen',

    'unsaved_changes' => 'Nicht gespeicherte Änderungen',

    'connected_social'   => 'Vernetzt dich mit uns in den sozialen Netzwerken',
    'open_source'        => 'Open-source unter',
    'licence'            => 'MIT-Lizenz',
    'free_use'           => 'Kostenlose Nutzung für nichtkommerzielle Zwecke',
    'design_development' => 'Gestaltet & entwickelt',
    'by'                 => 'von',

    'open_offcanvas' => 'Seitenleiste öffnen',
    'enable_light'   => 'Hellen Stil nutzen',
    'enable_dark'    => 'Dunklen Stil nutzen',

    'no_data'   => 'Keine Daten verfügbar',
    'no_result' => 'Es wurde nichts gefunden, was deinen Kriterien entspricht',

    'people_search'             => 'Suche personen in <span class="text-emerald-600"><strong>:scope</strong></span></span>',
    'people_search_placeholder' => 'Geben Sie einen Namen ein ...',
    'people_search_tip'         => 'Suchen Sie Personen nach Nachname, Vorname, Geburtsname oder Spitzname.',
    'people_found'              => '<span class="text-emerald-600"><strong>:found</strong></span> gefunden mit dem Schlagwort <span class="text-emerald-600"><strong>:keyword</strong></span> in <span class="text-emerald-600"><strong>:total</strong></span> verfügbar in <span class="text-emerald-600"><strong>:scope</strong></span>',
    'people_available'          => '<span class="text-emerald-600"><strong>:total</strong></span> verfügbar in <span class="text-emerald-600"><strong>:scope</strong></span></span>',

    'people_search_help_1' => 'Das System sucht <b class="text-emerald-600">jedes einzelne Wort</b> im Suchfeld in den Attributen <b class="text-emerald-600">Nachname</b>, <b class="text-emerald-600">Vorname</b>, <b class="text-emerald-600">Geburtsname</b> und <b class="text-emerald-600">Spitzname</b>.',
    'people_search_help_2' => 'Beginnen Sie die Suchzeichenfolge mit <b class="text-emerald-600">%</b>, wenn Sie nach Teilen von Namen suchen möchten, zum Beispiel : <b class="text-emerald-600">%Jr</b>.<br/>Beachten Sie, dass diese Art von Suche langsamer ist.',
    'people_search_help_3' => 'Wenn ein Nachname, Vorname, Geburtsname oder Spitzname <b class="text-emerald-600">Leerzeichen</b> enthält, schließen Sie den Namen in doppelte Anführungszeichen ein,<br/>zum Beispiel: <b class="text-emerald-600">"John Fitzgerald Jr." Kennedy</b>.',

    'unauthorized_access' => 'Unautorisierter Zugriff',

    'terminal' => 'Terminal',

    'event_added'       => 'hinzugefügt',
    'event_created'     => 'erstellt',
    'event_updated'     => 'aktualisiert',
    'event_deleted'     => 'gelöscht',
    'event_invited'     => 'eingeladen',
    'event_removed'     => 'entfernt',
    'event_transferred' => 'übertragen',

    'settings' => 'Einstellungen',

    'people_logbook' => 'Personenlogbuch',
    'team_logbook'   => 'Team-Logbuch',

    'under_construction' => 'Im Bau',
    'demonstration'      => 'Demonstration',

    'password_generator'   => 'Passwortgenerator',
    'password_length'      => 'Passwortlänge',
    'use_numbers'          => 'Zahlen verwenden',
    'use_symbols'          => 'Symbole verwenden',
    'generate'             => 'Generieren',
    'copy_to_clipboard'    => 'In Zwischenablage kopieren',
    'copied_to_clipboard'  => 'In Zwischenablage kopiert!',
    'password_very_weak'   => 'Sehr schwach',
    'password_weak'        => 'Schwach',
    'password_moderate'    => 'Mittel',
    'password_strong'      => 'Stark',
    'password_very_strong' => 'Sehr stark',
    'check_breach'         => 'Prüfen Sie, ob Ihre E-Mail-Adresse in einem Datenleck enthalten ist',
];
