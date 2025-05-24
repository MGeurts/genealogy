<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => 'Das Feld :attribute muss akzeptiert werden.',
    'accepted_if'     => 'Das Feld :attribute muss akzeptiert werden, wenn :other :value entspricht.',
    'active_url'      => 'Das Feld :attribute muss eine gültige URL sein.',
    'after'           => 'Das Feld :attribute muss ein Datum nach :date sein.',
    'after_or_equal'  => 'Das Feld :attribute muss ein Datum nach oder gleich :date sein.',
    'alpha'           => 'Das Feld :attribute darf nur aus Buchstaben enthalten.',
    'alpha_dash'      => 'Das Feld :attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num'       => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array'           => 'Das Feld :attribute muss ein Array sein.',
    'ascii'           => 'Das Feld :attribute darf nur alphanumerische Einzelbyte-Zeichen und -Symbole enthalten.',
    'before'          => 'Das Feld :attribute muss ein Datum vor :date sein.',
    'before_or_equal' => 'Das Feld :attribute muss ein Datum vor oder gleich :date sein.',
    'between'         => [
        'array'   => 'Das Feld :attribute muss Elemente zwischen :min und :max enthalten.',
        'file'    => 'Das Feld :attribute muss zwischen :min und :max Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss zwischen :min und :max sein.',
        'string'  => 'Das Feld :attribute muss zwischen :min und :max Zeichen enthalten.',
    ],
    'boolean'           => 'Das Feld :attribute muss wahr oder falsch sein.',
    'can'               => 'Das Feld :attribute enthält einen nicht autorisierten Wert.',
    'confirmed'         => 'Die Bestätigung für das Feld :attribute stimmt nicht überein.',
    'current_password'  => 'Das Passwort ist ungültig.',
    'date'              => 'Das Feld :attribute muss ein gültiges Datum sein.',
    'date_equals'       => 'Das Feld :attribute muss ein Datum sein, das :date entspricht.',
    'date_format'       => 'Das Feld :attribute muss dem Format :format entsprechen.',
    'decimal'           => 'Das Feld :attribute muss :decimal Dezimalstellen haben.',
    'declined'          => 'Das Feld :attribute muss abgelehnt werden.',
    'declined_if'       => 'Das Feld :attribute muss abglehnt werden, wenn :other :value entspricht.',
    'different'         => 'Das Feld :attribute und :other müssen sich unterscheiden.',
    'digits'            => 'Das Feld :attribute muss :digits Ziffern haben.',
    'digits_between'    => 'Das Feld :attribute muss zwischen :min und :max Ziffern haben.',
    'dimensions'        => 'Das Feld :attribute hat ungültige Bildabmessungen.',
    'distinct'          => 'Das Feld :attribute hat einen doppelten Wert.',
    'doesnt_end_with'   => 'Das Feld :attribute darf nicht mit einem der folgenden enden: :values.',
    'doesnt_start_with' => 'Das Feld :attribute darf nicht mit einem der folgenden beginnen: :values.',
    'email'             => 'Das Feld :attribute muss eine gültige E-Mail Adresse sein.',
    'ends_with'         => 'Das Feld :attribute muss mit einem der folgenden enden: :values.',
    'enum'              => 'Das ausgewählte :attribute ist ungültig.',
    'exists'            => 'Das ausgewählte :attribute ist ungültig.',
    'file'              => 'Das Feld :attribute muss eine Datei sein.',
    'filled'            => 'Das Feld :attribute muss einen Wert haben.',
    'gt'                => [
        'array'   => 'Das Feld :attribute muss mehr als :value Elemente enthalten.',
        'file'    => 'Das Feld :attribute muss größer als :value Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss größer als :value sein.',
        'string'  => 'Das Feld :attribute muss mehr als :value Zeichen enthalten.',
    ],
    'gte' => [
        'array'   => 'Das Feld :attribute muss :value oder mehr Elemente enthalten.',
        'file'    => 'Das Feld :attribute muss größer oder gleich :value Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss größer oder gleich :value sein.',
        'string'  => 'Das Feld :attribute muss mehr oder gleich :value Zeichen enthalten.',
    ],
    'image'     => 'Das Feld :attribute muss ein Bild sein.',
    'in'        => 'Das ausgewählte :attribute ist ungültig.',
    'in_array'  => 'Das Feld :attribute muss in :other vorhanden sein.',
    'integer'   => 'Das Feld :attribute muss eine Ganzzahl sein.',
    'ip'        => 'Das Feld :attribute muss eine gültige IP-Adresse sein.',
    'ipv4'      => 'Das Feld :attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6'      => 'Das Feld :attribute muss eine gültige IPv6-Adresse sein.',
    'json'      => 'Das Feld :attribute muss eine gültige JSON-Zeichenkette sein.',
    'lowercase' => 'Das Feld :attribute muss in Kleinbuchstaben geschrieben werden.',
    'lt'        => [
        'array'   => 'Das Feld :attribute muss weniger als :value Elemente enthalten.',
        'file'    => 'Das Feld :attribute muss kleiner als :value Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss kleiner als :value sein.',
        'string'  => 'Das Feld :attribute muss weniger als :value Zeichen enthalten.',
    ],
    'lte' => [
        'array'   => 'Das Feld :attribute darf nicht mehr als :value Elemente enthalten.',
        'file'    => 'Das Feld :attribute muss kleiner als oder gleich :value Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss kleiner als oder gleich :value sein.',
        'string'  => 'Das Feld :attribute muss weniger oder gleich :value Zeichen enthalten.',
    ],
    'mac_address' => 'Das Feld :attribute muss eine gültige MAC-Adresse sein.',
    'max'         => [
        'array'   => 'Das Feld :attribute darf nicht mehr als :max Elemente enthalten.',
        'file'    => 'Das Feld :attribute darf nicht größer als :max Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute darf nicht größer als :max sein.',
        'string'  => 'Das Feld :attribute darf nicht mehr als :max Zeichen enthalten.',
    ],
    'max_digits' => 'Das Feld :attribute darf nicht mehr als :max Ziffern enthalten.',
    'mimes'      => 'Das Feld :attribute muss eine Datei vom Typ :values sein.',
    'mimetypes'  => 'Das Feld :attribute muss eine Datei vom Typ :values sein.',
    'min'        => [
        'array'   => 'Das Feld :attribute muss mindestens :min Elemente enthalten.',
        'file'    => 'Das Feld :attribute muss mindestens :min Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss mindestens :min sein.',
        'string'  => 'Das Feld :attribute muss mindestens :min Zeichen enthalten.',
    ],
    'min_digits'       => 'Das Feld :attribute muss mindestens :min Ziffern haben.',
    'missing'          => 'Das Feld :attribute darf nicht vorhanden sein.',
    'missing_if'       => 'Das Feld :attribute darf nicht vorhanden sein, wenn :other :value entspricht.',
    'missing_unless'   => 'Das Feld :attribute darf nicht vorhanden sein, es sei denn :other entspricht :value.',
    'missing_with'     => 'Das Feld :attribute darf nicht vorhanden sein, wenn :values vorhanden ist.',
    'missing_with_all' => 'Das Feld :attribute darf nicht vorhanden sein, wenn :values vorhanden sind.',
    'multiple_of'      => 'Das Feld :attribute muss ein Vielfaches von :value sein.',
    'not_in'           => 'Das ausgewählte :attribute ist ungültig.',
    'not_regex'        => 'Das Feld :attribute hat ein ungültiges Format.',
    'numeric'          => 'Das Feld :attribute muss eine Zahl sein.',
    'password'         => [
        'letters'       => 'Das Feld :attribute muss mindestens einen Buchstaben enthalten.',
        'mixed'         => 'Das Feld :attribute muss mindestens einen Großbuchstaben und einen Kleinbuchstaben enthalten.',
        'numbers'       => 'Das Feld :attribute muss mindestens eine Zahl enthalten.',
        'symbols'       => 'Das Feld :attribute muss mindestens ein Sonderzeichen enthalten.',
        'uncompromised' => 'Das angegebene :attribute ist in einem Datenleck aufgetaucht. Bitte wähle ein anderes :attribute.',
    ],
    'present'              => 'Das Feld :attribute muss vorhanden sein.',
    'prohibited'           => 'Das Feld :attribute ist verboten.',
    'prohibited_if'        => 'Das Feld :attribute ist verboten, wenn :other :value entspricht.',
    'prohibited_unless'    => 'Das Feld :attribute ist verboten, es sei denn :other ist in :values enthalten.',
    'prohibits'            => 'Das Feld :attribute verbietet, dass :other vorhanden ist.',
    'regex'                => 'Das Feld :attribute hat ein ungültiges Format.',
    'required'             => 'Das Feld :attribute ist erforderlich.',
    'required_array_keys'  => 'Das Feld :attribute muss Einträge für folgendes enthalten: :values.',
    'required_if'          => 'Das Feld :attribute ist erforderlich, wenn :other ist :value.',
    'required_if_accepted' => 'Das Feld :attribute ist erforderlich, wenn :other akzeptiert ist.',
    'required_unless'      => 'Das Feld :attribute ist erforderlich, wenn :other ist nicht in :values.',
    'required_with'        => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_with_all'    => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden sind.',
    'required_without'     => 'Das Feld :attribute ist erforderlich, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das Feld :attribute ist erforderlich, wenn keines von :values vorhanden ist.',
    'same'                 => 'Das Feld :attribute muss :other entsprechen.',
    'size'                 => [
        'array'   => 'Das Feld :attribute muss :size Einträge enthalten.',
        'file'    => 'Das Feld :attribute muss :size Kilobytes groß sein.',
        'numeric' => 'Das Feld :attribute muss :size groß sein.',
        'string'  => 'Das Feld :attribute muss :size Zeichen groß sein.',
    ],
    'starts_with' => 'Das Feld :attribute muss mit einem der folgenden Zeichen beginnen: :values.',
    'string'      => 'Das Feld :attribute muss eine Zeichenkette sein.',
    'timezone'    => 'Das Feld :attribute muss eine gültige Zeitzone sein.',
    'unique'      => 'Das Feld :attribute ist bereits vergeben.',
    'uploaded'    => 'Das Feld :attribute konnte nicht hochgeladen werden.',
    'uppercase'   => 'Das Feld :attribute muss in Großbuchstaben geschrieben werden.',
    'url'         => 'Das Feld :attribute muss eine gültige URL sein.',
    'ulid'        => 'Das Feld :attribute muss eine gültige ULID sein.',
    'uuid'        => 'Das Feld :attribute muss eine gültige UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'surname.required_without'   => 'Beim Hinzufügen einer NEUEN Person ist ein Nachname erforderlich.',
    'sex.required_without'       => 'Beim Hinzufügen einer NEUEN Person muss ein Geschlecht angegeben werden.',
    'person_id.required_without' => 'Wenn Sie eine BESTEHENDE Person hinzufügen, wählen Sie eine Person aus.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
];
