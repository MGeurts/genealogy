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

    'accepted' => 'Das Feld :attribute muss akzeptiert werden.',
    'accepted_if' => 'Das Feld :attribute muss akzeptiert werden, wenn :other ist :value.',
    'active_url' => 'Das Feld :attribute muss eine gültige URL sein.',
    'after' => 'Das Feld :attribute musse ein Datum nach :date sein.',
    'after_or_equal' => 'Das Feld :attribute muss ein Datum nach oder gleich :date sein.',
    'alpha' => 'Das Feld :attribute darf nur aus Buchstaben enthalten.',
    'alpha_dash' => 'Das Feld :attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array' => 'Das Feld :attribute must be an array.',
    'ascii' => 'Das Feld :attribute must only contain single-byte alphanumeric characters and symbols.',
    'before' => 'Das Feld :attribute muss ein Datum vor :date sein.',
    'before_or_equal' => 'Das Feld :attribute must be a date before or equal to :date.',
    'between' => [
        'array' => 'Das Feld :attribute must have between :min and :max items.',
        'file' => 'Das Feld :attribute must be between :min and :max kilobytes.',
        'numeric' => 'Das Feld :attribute must be between :min and :max.',
        'string' => 'Das Feld :attribute must be between :min and :max characters.',
    ],
    'boolean' => 'Das Feld :attribute must be true or false.',
    'can' => 'Das Feld :attribute contains an unauthorized value.',
    'confirmed' => 'Das Feld :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'Das Feld :attribute must be a valid date.',
    'date_equals' => 'Das Feld :attribute must be a date equal to :date.',
    'date_format' => 'Das Feld :attribute must match the format :format.',
    'decimal' => 'Das Feld :attribute must have :decimal decimal places.',
    'declined' => 'Das Feld :attribute must be declined.',
    'declined_if' => 'Das Feld :attribute must be declined when :other is :value.',
    'different' => 'Das Feld :attribute and :other must be different.',
    'digits' => 'Das Feld :attribute must be :digits digits.',
    'digits_between' => 'Das Feld :attribute must be between :min and :max digits.',
    'dimensions' => 'Das Feld :attribute has invalid image dimensions.',
    'distinct' => 'Das Feld :attribute has a duplicate value.',
    'doesnt_end_with' => 'Das Feld :attribute must not end with one of the following: :values.',
    'doesnt_start_with' => 'Das Feld :attribute must not start with one of the following: :values.',
    'email' => 'Das Feld :attribute must be a valid email address.',
    'ends_with' => 'Das Feld :attribute must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'Das Feld :attribute must be a file.',
    'filled' => 'Das Feld :attribute must have a value.',
    'gt' => [
        'array' => 'Das Feld :attribute must have more than :value items.',
        'file' => 'Das Feld :attribute must be greater than :value kilobytes.',
        'numeric' => 'Das Feld :attribute must be greater than :value.',
        'string' => 'Das Feld :attribute must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'Das Feld :attribute must have :value items or more.',
        'file' => 'Das Feld :attribute must be greater than or equal to :value kilobytes.',
        'numeric' => 'Das Feld :attribute must be greater than or equal to :value.',
        'string' => 'Das Feld :attribute must be greater than or equal to :value characters.',
    ],
    'image' => 'Das Feld :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'Das Feld :attribute must exist in :other.',
    'integer' => 'Das Feld :attribute must be an integer.',
    'ip' => 'Das Feld :attribute must be a valid IP address.',
    'ipv4' => 'Das Feld :attribute must be a valid IPv4 address.',
    'ipv6' => 'Das Feld :attribute must be a valid IPv6 address.',
    'json' => 'Das Feld :attribute must be a valid JSON string.',
    'lowercase' => 'Das Feld :attribute must be lowercase.',
    'lt' => [
        'array' => 'Das Feld :attribute must have less than :value items.',
        'file' => 'Das Feld :attribute must be less than :value kilobytes.',
        'numeric' => 'Das Feld :attribute must be less than :value.',
        'string' => 'Das Feld :attribute must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'Das Feld :attribute must not have more than :value items.',
        'file' => 'Das Feld :attribute must be less than or equal to :value kilobytes.',
        'numeric' => 'Das Feld :attribute must be less than or equal to :value.',
        'string' => 'Das Feld :attribute must be less than or equal to :value characters.',
    ],
    'mac_address' => 'Das Feld :attribute must be a valid MAC address.',
    'max' => [
        'array' => 'Das Feld :attribute must not have more than :max items.',
        'file' => 'Das Feld :attribute must not be greater than :max kilobytes.',
        'numeric' => 'Das Feld :attribute must not be greater than :max.',
        'string' => 'Das Feld :attribute must not be greater than :max characters.',
    ],
    'max_digits' => 'Das Feld :attribute must not have more than :max digits.',
    'mimes' => 'Das Feld :attribute must be a file of type: :values.',
    'mimetypes' => 'Das Feld :attribute must be a file of type: :values.',
    'min' => [
        'array' => 'Das Feld :attribute must have at least :min items.',
        'file' => 'Das Feld :attribute must be at least :min kilobytes.',
        'numeric' => 'Das Feld :attribute must be at least :min.',
        'string' => 'Das Feld :attribute must be at least :min characters.',
    ],
    'min_digits' => 'Das Feld :attribute must have at least :min digits.',
    'missing' => 'Das Feld :attribute must be missing.',
    'missing_if' => 'Das Feld :attribute must be missing when :other is :value.',
    'missing_unless' => 'Das Feld :attribute must be missing unless :other is :value.',
    'missing_with' => 'Das Feld :attribute must be missing when :values is present.',
    'missing_with_all' => 'Das Feld :attribute must be missing when :values are present.',
    'multiple_of' => 'Das Feld :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'Das Feld :attribute format is invalid.',
    'numeric' => 'Das Feld :attribute must be a number.',
    'password' => [
        'letters' => 'Das Feld :attribute must contain at least one letter.',
        'mixed' => 'Das Feld :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'Das Feld :attribute must contain at least one number.',
        'symbols' => 'Das Feld :attribute must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'Das Feld :attribute must be present.',
    'prohibited' => 'Das Feld :attribute is prohibited.',
    'prohibited_if' => 'Das Feld :attribute is prohibited when :other is :value.',
    'prohibited_unless' => 'Das Feld :attribute is prohibited unless :other is in :values.',
    'prohibits' => 'Das Feld :attribute prohibits :other from being present.',
    'regex' => 'Das Feld :attribute format is invalid.',
    'required' => 'Das Feld :attribute is required.',
    'required_array_keys' => 'Das Feld :attribute must contain entries for: :values.',
    'required_if' => 'Das Feld :attribute is required when :other is :value.',
    'required_if_accepted' => 'Das Feld :attribute is required when :other is accepted.',
    'required_unless' => 'Das Feld :attribute is required unless :other is in :values.',
    'required_with' => 'Das Feld :attribute is required when :values is present.',
    'required_with_all' => 'Das Feld :attribute is required when :values are present.',
    'required_without' => 'Das Feld :attribute is required when :values is not present.',
    'required_without_all' => 'Das Feld :attribute is required when none of :values are present.',
    'same' => 'Das Feld :attribute must match :other.',
    'size' => [
        'array' => 'Das Feld :attribute must contain :size items.',
        'file' => 'Das Feld :attribute must be :size kilobytes.',
        'numeric' => 'Das Feld :attribute must be :size.',
        'string' => 'Das Feld :attribute must be :size characters.',
    ],
    'starts_with' => 'Das Feld :attribute must start with one of the following: :values.',
    'string' => 'Das Feld :attribute must be a string.',
    'timezone' => 'Das Feld :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'uppercase' => 'Das Feld :attribute must be uppercase.',
    'url' => 'Das Feld :attribute must be a valid URL.',
    'ulid' => 'Das Feld :attribute must be a valid ULID.',
    'uuid' => 'Das Feld :attribute must be a valid UUID.',
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

    'user' => [
        'replacement_user_id' => [
            'required' => 'Please select one.',
        ],
    ],

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
