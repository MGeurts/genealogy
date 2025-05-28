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

    'accepted'        => ':attribute harus diterima.',
    'accepted_if'     => ':attribute harus diterima ketika :other adalah :value.',
    'active_url'      => ':attribute harus berupa URL yang valid.',
    'after'           => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal'  => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'           => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'      => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num'       => ':attribute hanya boleh berisi huruf dan angka.',
    'array'           => ':attribute harus berupa sebuah array.',
    'ascii'           => ':attribute hanya boleh berisi karakter dan simbol alfanumerik byte tunggal.',
    'before'          => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'         => [
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
        'file'    => ':attribute harus berukuran antara :min dan :max kilobita.',
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'string'  => ':attribute harus memiliki panjang antara :min dan :max karakter.',
    ],
    'boolean'           => ':attribute harus bernilai true atau false.',
    'can'               => ':attribute berisi nilai yang tidak sah.',
    'confirmed'         => 'Konfirmasi :attribute tidak cocok.',
    'current_password'  => 'Kata sandi salah.',
    'date'              => ':attribute harus berupa tanggal yang valid.',
    'date_equals'       => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format'       => ':attribute harus sesuai dengan format :format.',
    'decimal'           => ':attribute harus memiliki :decimal tempat desimal.',
    'declined'          => ':attribute harus ditolak.',
    'declined_if'       => ':attribute harus ditolak ketika :other adalah :value.',
    'different'         => ':attribute dan :other harus berbeda.',
    'digits'            => ':attribute harus terdiri dari :digits digit.',
    'digits_between'    => ':attribute harus terdiri dari antara :min dan :max digit.',
    'dimensions'        => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'          => ':attribute memiliki nilai duplikat.',
    'doesnt_end_with'   => ':attribute tidak boleh diakhiri dengan salah satu dari berikut: :values.',
    'doesnt_start_with' => ':attribute tidak boleh diawali dengan salah satu dari berikut: :values.',
    'email'             => ':attribute harus berupa alamat email yang valid.',
    'ends_with'         => ':attribute harus diakhiri dengan salah satu dari berikut: :values.',
    'enum'              => ':attribute yang dipilih tidak valid.',
    'exists'            => ':attribute yang dipilih tidak valid.',
    'file'              => ':attribute harus berupa sebuah file.',
    'filled'            => ':attribute harus memiliki nilai.',
    'gt'                => [
        'array'   => ':attribute harus memiliki lebih dari :value item.',
        'file'    => ':attribute harus berukuran lebih besar dari :value kilobita.',
        'numeric' => ':attribute harus bernilai lebih besar dari :value.',
        'string'  => ':attribute harus memiliki panjang lebih besar dari :value karakter.',
    ],
    'gte' => [
        'array'   => ':attribute harus memiliki :value item atau lebih.',
        'file'    => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
        'numeric' => ':attribute harus bernilai lebih besar dari atau sama dengan :value.',
        'string'  => ':attribute harus memiliki panjang lebih besar dari atau sama dengan :value karakter.',
    ],
    'image'     => ':attribute harus berupa gambar.',
    'in'        => ':attribute yang dipilih tidak valid.',
    'in_array'  => ':attribute harus ada di dalam :other.',
    'integer'   => ':attribute harus berupa bilangan bulat.',
    'ip'        => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'      => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'      => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'      => ':attribute harus berupa string JSON yang valid.',
    'lowercase' => ':attribute harus berupa huruf kecil.',
    'lt'        => [
        'array'   => ':attribute harus memiliki kurang dari :value item.',
        'file'    => ':attribute harus berukuran kurang dari :value kilobita.',
        'numeric' => ':attribute harus bernilai kurang dari :value.',
        'string'  => ':attribute harus memiliki panjang kurang dari :value karakter.',
    ],
    'lte' => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file'    => ':attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
        'numeric' => ':attribute harus bernilai kurang dari atau sama dengan :value.',
        'string'  => ':attribute harus memiliki panjang kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => ':attribute harus berupa alamat MAC yang valid.',
    'max'         => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file'    => ':attribute tidak boleh berukuran lebih besar dari :max kilobita.',
        'numeric' => ':attribute tidak boleh bernilai lebih besar dari :max.',
        'string'  => ':attribute tidak boleh memiliki panjang lebih besar dari :max karakter.',
    ],
    'max_digits' => ':attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes'      => ':attribute harus berupa file dengan tipe: :values.',
    'mimetypes'  => ':attribute harus berupa file dengan tipe: :values.',
    'min'        => [
        'array'   => ':attribute harus memiliki minimal :min item.',
        'file'    => ':attribute harus berukuran minimal :min kilobita.',
        'numeric' => ':attribute harus bernilai minimal :min.',
        'string'  => ':attribute harus memiliki panjang minimal :min karakter.',
    ],
    'min_digits'       => ':attribute harus memiliki minimal :min digit.',
    'missing'          => ':attribute harus hilang.',
    'missing_if'       => ':attribute harus hilang ketika :other adalah :value.',
    'missing_unless'   => ':attribute harus hilang kecuali :other adalah :value.',
    'missing_with'     => ':attribute harus hilang ketika :values ada.',
    'missing_with_all' => ':attribute harus hilang ketika semua :values ada.',
    'multiple_of'      => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'           => ':attribute yang dipilih tidak valid.',
    'not_regex'        => 'Format :attribute tidak valid.',
    'numeric'          => ':attribute harus berupa angka.',
    'password'         => [
        'letters'       => ':attribute harus mengandung setidaknya satu huruf.',
        'mixed'         => ':attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus mengandung setidaknya satu angka.',
        'symbols'       => ':attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present'              => ':attribute harus ada.',
    'prohibited'           => ':attribute dilarang.',
    'prohibited_if'        => ':attribute dilarang ketika :other adalah :value.',
    'prohibited_unless'    => ':attribute dilarang kecuali :other ada di dalam :values.',
    'prohibits'            => ':attribute melarang :other untuk ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_array_keys'  => ':attribute harus berisi entri untuk: :values.',
    'required_if'          => ':attribute wajib diisi ketika :other adalah :value.',
    'required_if_accepted' => ':attribute wajib diisi ketika :other diterima.',
    'required_unless'      => ':attribute wajib diisi kecuali :other ada di dalam :values.',
    'required_with'        => ':attribute wajib diisi ketika :values ada.',
    'required_with_all'    => ':attribute wajib diisi ketika semua :values ada.',
    'required_without'     => ':attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi ketika tidak ada satupun :values yang ada.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'array'   => ':attribute harus mengandung :size item.',
        'file'    => ':attribute harus berukuran :size kilobita.',
        'numeric' => ':attribute harus bernilai :size.',
        'string'  => ':attribute harus memiliki panjang :size karakter.',
    ],
    'starts_with' => ':attribute harus diawali dengan salah satu dari berikut: :values.',
    'string'      => ':attribute harus berupa string.',
    'timezone'    => ':attribute harus berupa zona waktu yang valid.',
    'unique'      => ':attribute sudah ada sebelumnya.',
    'uploaded'    => ':attribute gagal diunggah.',
    'uppercase'   => ':attribute harus berupa huruf besar.',
    'url'         => ':attribute harus berupa URL yang valid.',
    'ulid'        => ':attribute harus berupa ULID yang valid.',
    'uuid'        => ':attribute harus berupa UUID yang valid.',

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

    'surname.required_without'   => 'Saat menambahkan orang BARU, nama keluarga wajib diisi.',
    'sex.required_without'       => 'Saat menambahkan orang BARU, jenis kelamin harus ditentukan.',
    'person_id.required_without' => 'Saat menambahkan orang YANG ADA, seseorang harus dipilih.',

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