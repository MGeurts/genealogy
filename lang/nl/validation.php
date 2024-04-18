<?php

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

    'accepted'        => ':Attribute dient te worden geaccepteerd.',
    'accepted_if'     => ':Attribute dient te worden geaccepteerd wanneer :other :value is.',
    'active_url'      => ':Attribute is geen geldige URL.',
    'after'           => ':Attribute moet een datum zijn na :date.',
    'after_or_equal'  => ':Attribute moet een datum zijn na of gelijk aan :date.',
    'alpha'           => ':Attribute mag alleen letters bevatten.',
    'alpha_dash'      => ':Attribute mag alleen letters, nummers, en strepen bevatten.',
    'alpha_num'       => ':Attribute mag alleen letters en nummers bevatten.',
    'array'           => ':Attribute moet een array zijn.',
    'ascii'           => ':Attribute mag alleen standaard karakters en cijfers bevatten',
    'before'          => ':Attribute moet een datum zijn eerder dan :date.',
    'before_or_equal' => ':Attribute moet een datum zijn voor of gelijk aan :date.',
    'between'         => [
        'numeric' => ':Attribute moet tussen :min en :max liggen.',
        'file'    => ':Attribute moet tussen :min en :max kilobytes zijn.',
        'string'  => ':Attribute moet tussen :min en :max karakters lang zijn.',
        'array'   => ':Attribute moet tussen :min en :max items bevatten.',
    ],
    'boolean'           => ':Attribute kan enkel waar of niet waar zijn.',
    'can'               => ':attribute bevat een ongeldige waarde.',
    'confirmed'         => ':Attribute bevestiging komt niet overeen.',
    'current_password'  => 'Het wachtwoord is ongeldig.',
    'date'              => ':Attribute is geen geldige datum.',
    'date_equals'       => ':Attribute moet een datum zijn gelijk aan :date.',
    'date_format'       => ':Attribute komt niet overeen met het formaat :format.',
    'decimal'           => ':Attribute moet :decimal decimalen bevatten.',
    'declined'          => ':Attribute dient te worden afgewezen.',
    'declined_if'       => ':Attribute dient te worden afgewezen wanneer :other :value is.',
    'different'         => ':Attribute en :other dienen verschillend te zijn.',
    'digits'            => ':Attribute moet :digits cijfers zijn.',
    'digits_between'    => ':Attribute moet tussen :min en :max cijfers zijn.',
    'dimensions'        => ':Attribute heeft een ongeldige grootte.',
    'distinct'          => ':Attribute heeft een dubbele waarde.',
    'doesnt_end_with'   => ':Attribute kan niet eindigen met de volgende waardes: :values.',
    'doesnt_start_with' => ':Attribute kan niet beginnen met de volgende waardes: :values.',
    'email'             => ':Attribute dient een geldig emailadres te zijn.',
    'ends_with'         => ':Attribute moet eindigen met één van het volgende: :values',
    'enum'              => 'Geselecteerde :attribute is ongeldig.',
    'exists'            => 'Geselecteerde :attribute is ongeldig.',
    'file'              => ':Attribute moet een bestand zijn.',
    'filled'            => ':Attribute veld is verplicht.',
    'gt'                => [
        'array'   => 'Het :attribute veld moet meer dan :value items bevatten.',
        'file'    => 'Het :attribute veld moet groter zijn dan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet groter zijn dan :value.',
        'string'  => 'Het :attribute veld moet groter zijn dan :value tekens.',
    ],
    'gte' => [
        'array'   => 'Het :attribute veld moet :value of meer items bevatten.',
        'file'    => 'Het :attribute veld moet groter of gelijk zijn aan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet groter of gelijk zijn aan :value.',
        'string'  => 'Het :attribute veld moet groter of gelijk zijn aan :value tekens.',
    ],
    'image'     => ':Attribute dient een afbeelding te zijn.',
    'in'        => 'Geselecteerde :attribute is ongeldig.',
    'in_array'  => ':Attribute komt niet voor in :other.',
    'integer'   => ':Attribute dient een geheel getal te zijn.',
    'ip'        => ':Attribute dient een geldig IP adres te zijn.',
    'ipv4'      => ':Attribute dient een geldig IPv4 adres te zijn.',
    'ipv6'      => ':Attribute dient een geldig IPv6 adres te zijn..',
    'json'      => ':Attribute moet een geldige JSON string zijn.',
    'lowercase' => 'De :attribute moet in kleine letters zijn.',
    'lt'        => [
        'array'   => 'Het :attribute veld moet minder dan :value items bevatten.',
        'file'    => 'Het :attribute veld moet kleiner zijn dan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet kleiner zijn dan :value.',
        'string'  => 'Het :attribute veld moet kleiner zijn dan :value tekens.',
    ],
    'lte' => [
        'array'   => 'Het :attribute veld mag maximaal :value items bevatten.',
        'file'    => 'Het :attribute veld moet kleiner of gelijk zijn aan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet kleiner of gelijk zijn aan :value.',
        'string'  => 'Het :attribute veld moet kleiner of gelijk zijn aan :value tekens.',
    ],
    'mac_address' => ':Attribute moet een geldig MAC adres zijn.',
    'max'         => [
        'array'   => ':Attribute mag niet meer dan :max items bevatten.',
        'file'    => ':Attribute mag niet groter zijn dan :max kilobytes.',
        'numeric' => ':Attribute mag niet groter zijn dan :max.',
        'string'  => ':Attribute mag niet groter zijn dan :max karakters.',
    ],
    'max_digits' => 'Het :attribute mag niet meer dan :max cijfers bevatten.',
    'mimes'      => ':Attribute dient een bestand te zijn van het type: :values.',
    'mimetypes'  => ':Attribute dient een bestand te zijn van het type: :values.',
    'min'        => [
        'array'   => ':Attribute dient minimaal :min items te bevatten.',
        'file'    => ':Attribute dient minimaal :min kilobytes te zijn.',
        'numeric' => ':Attribute dient minimaal :min te zijn.',
        'string'  => ':Attribute dient minimaal :min karakters te bevatten.',
    ],
    'min_digits'       => 'Het :attribute moet minstens :min cijfers bevatten.',
    'missing'          => ':Attribute mag niet worden meegestuurd',
    'missing_if'       => ':Attribute mag niet worden meegestuurd wanneer :other :value is.',
    'missing_unless'   => ':Attribute mag niet worden meegestuurd behalve als :other :value is.',
    'missing_with'     => ':Attribute mag niet worden meegestuurd wanneer :values bestaat.',
    'missing_with_all' => ':Attribute mag niet worden meegestuurd wanneer :values meegestuurd wordt.',
    'multiple_of'      => 'Het :attribute moet een veelvoud zijn van :value.',
    'not_in'           => 'Geselecteerde :attribute is ongeldig.',
    'not_regex'        => 'Het :attribute format is ongeldig.',
    'numeric'          => ':Attribute dient een nummer te zijn.',
    'password'         => [
        'letters'       => ':Attribute moet alleen uit letters bestaan.',
        'mixed'         => ':Attribute moet minstens één hoofdletter en één kleine letter bevatten.',
        'numbers'       => ':Attribute moet minstens één nummer bevatten.',
        'symbols'       => ':Attribute moet minstens één symbool bevatten.',
        'uncompromised' => ':Attribute komt voor in een data lek. Kies een ander :attribute.',
    ],
    'present'              => ':Attribute dient aanwezig te zijn.',
    'prohibited'           => 'Het :attribute veld is niet toegestaan.',
    'prohibited_if'        => 'Het :attribute veld is niet toegestaan wanneer :other :value is.',
    'prohibited_unless'    => 'Het :attribute veld is niet toegestaan tenzij :other voorkomt in :values.',
    'prohibits'            => 'Het :attribute veld staat niet toe dat :other aanwezig is.',
    'regex'                => 'Het :attribute formaat is ongeldig.',
    'required'             => 'Het :attribute veld is verplicht.',
    'required_array_keys'  => 'Het veld :attribute moet vermeldingen bevatten voor: :values.',
    'required_if'          => 'Het :attribute veld is verplicht wanneer :other is :value.',
    'required_if_accepted' => 'Het veld :attribute is vereist wanneer :other is geaccepteerd.',
    'required_unless'      => 'Het :attribute veld is verplicht, tenzij :other is in :values.',
    'required_with'        => 'Het :attribute veld is verplicht wanneer :values aanwezig is.',
    'required_with_all'    => 'Het :attribute veld is verplicht wanneer :values aanwezig is.',
    'required_without'     => 'Het :attribute veld is verplicht wanneer :values niet aanwezig is.',
    'required_without_all' => 'Het :attribute veld is verplicht wanneer geen van :values aanwezig is.',
    'same'                 => ':Attribute en :other moeten hetzelfde zijn.',
    'size'                 => [
        'array'   => ':Attribute moet :size items bevatten.',
        'file'    => ':Attribute moet :size kilobytes groot zijn.',
        'numeric' => ':Attribute moet :size zijn.',
        'string'  => ':Attribute moet :size karakters lang zijn.',
    ],
    'starts_with' => ':Attribute moet beginnen met één van het volgende: :values',
    'string'      => ':Attribute moet een string zijn.',
    'timezone'    => ':Attribute moet een geldige tijdszone zijn.',
    'unique'      => ':Attribute is al bezet.',
    'uploaded'    => 'Het uploaden van :attribute is mislukt.',
    'uppercase'   => ':Attribute moet in hoofdletters zijn.',
    'url'         => ':Attribute formaat is ongeldig.',
    'ulid'        => ':Attribute moet een valide ULID zijn.',
    'uuid'        => ':Attribute moet een valide UUID zijn.',

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
