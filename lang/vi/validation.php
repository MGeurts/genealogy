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

    'accepted'        => 'Trường :attribute phải được chấp nhận.',
    'accepted_if'     => 'Trường :attribute phải được chấp nhận khi :other là :value.',
    'active_url'      => 'Trường :attribute phải là một URL hợp lệ.',
    'after'           => 'Trường :attribute phải là một ngày sau :date.',
    'after_or_equal'  => 'Trường :attribute phải là một ngày sau hoặc bằng :date.',
    'alpha'           => 'Trường :attribute chỉ có thể chứa chữ cái.',
    'alpha_dash'      => 'Trường :attribute chỉ có thể chứa chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num'       => 'Trường :attribute chỉ có thể chứa chữ cái và số.',
    'array'           => 'Trường :attribute phải là một mảng.',
    'ascii'           => 'Trường :attribute chỉ có thể chứa ký tự chữ và số đơn byte cùng các ký hiệu.',
    'before'          => 'Trường :attribute phải là một ngày trước :date.',
    'before_or_equal' => 'Trường :attribute phải là một ngày trước hoặc bằng :date.',
    'between'         => [
        'array'   => 'Trường :attribute phải có từ :min đến :max mục.',
        'file'    => 'Trường :attribute phải có kích thước từ :min đến :max kilobyte.',
        'numeric' => 'Trường :attribute phải nằm trong khoảng từ :min đến :max.',
        'string'  => 'Trường :attribute phải có từ :min đến :max ký tự.',
    ],
    'boolean'           => 'Trường :attribute phải là true hoặc false.',
    'can'               => 'Trường :attribute chứa một giá trị không được phép.',
    'confirmed'         => 'Xác nhận trường :attribute không khớp.',
    'current_password'  => 'Mật khẩu không chính xác.',
    'date'              => 'Trường :attribute phải là một ngày hợp lệ.',
    'date_equals'       => 'Trường :attribute phải là một ngày bằng :date.',
    'date_format'       => 'Trường :attribute phải khớp với định dạng :format.',
    'decimal'           => 'Trường :attribute phải có :decimal chữ số thập phân.',
    'declined'          => 'Trường :attribute phải bị từ chối.',
    'declined_if'       => 'Trường :attribute phải bị từ chối khi :other là :value.',
    'different'         => 'Trường :attribute và :other phải khác nhau.',
    'digits'            => 'Trường :attribute phải có :digits chữ số.',
    'digits_between'    => 'Trường :attribute phải có từ :min đến :max chữ số.',
    'dimensions'        => 'Trường :attribute có kích thước hình ảnh không hợp lệ.',
    'distinct'          => 'Trường :attribute có một giá trị trùng lặp.',
    'doesnt_end_with'   => 'Trường :attribute không được kết thúc bằng một trong các giá trị sau: :values.',
    'doesnt_start_with' => 'Trường :attribute không được bắt đầu bằng một trong các giá trị sau: :values.',
    'email'             => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
    'ends_with'         => 'Trường :attribute phải kết thúc bằng một trong các giá trị sau: :values.',
    'enum'              => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'exists'            => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'file'              => 'Trường :attribute phải là một tệp.',
    'filled'            => 'Trường :attribute phải có giá trị.',
    'gt'                => [
        'array'   => 'Trường :attribute phải có hơn :value mục.',
        'file'    => 'Trường :attribute phải lớn hơn :value kilobytes.',
        'numeric' => 'Trường :attribute phải lớn hơn :value.',
        'string'  => 'Trường :attribute phải nhiều hơn :value ký tự.',
    ],
    'gte' => [
        'array'   => 'Trường :attribute phải có :value mục hoặc nhiều hơn.',
        'file'    => 'Trường :attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'numeric' => 'Trường :attribute phải lớn hơn hoặc bằng :value.',
        'string'  => 'Trường :attribute phải lớn hơn hoặc bằng :value ký tự.',
    ],
    'image'     => 'Trường :attribute phải là một hình ảnh.',
    'in'        => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'in_array'  => 'Trường :attribute phải tồn tại trong :other.',
    'integer'   => 'Trường :attribute phải là một số nguyên.',
    'ip'        => 'Trường :attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4'      => 'Trường :attribute phải là một địa chỉ IPv4 hợp lệ.',
    'ipv6'      => 'Trường :attribute phải là một địa chỉ IPv6 hợp lệ.',
    'json'      => 'Trường :attribute phải là một chuỗi JSON hợp lệ.',
    'lowercase' => 'Trường :attribute phải là chữ thường.',
    'lt'        => [
        'array'   => 'Trường :attribute phải có ít hơn :value mục.',
        'file'    => 'Trường :attribute phải nhỏ hơn :value kilobytes.',
        'numeric' => 'Trường :attribute phải nhỏ hơn :value.',
        'string'  => 'Trường :attribute phải ít hơn :value ký tự.',
    ],
    'lte' => [
        'array'   => 'Trường :attribute không được có nhiều hơn :value mục.',
        'file'    => 'Trường :attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'numeric' => 'Trường :attribute phải nhỏ hơn hoặc bằng :value.',
        'string'  => 'Trường :attribute phải nhỏ hơn hoặc bằng :value ký tự.',
    ],
    'mac_address' => 'Trường :attribute phải là một địa chỉ MAC hợp lệ.',
    'max'         => [
        'array'   => 'Trường :attribute không được có nhiều hơn :max mục.',
        'file'    => 'Trường :attribute không được lớn hơn :max kilobytes.',
        'numeric' => 'Trường :attribute không được lớn hơn :max.',
        'string'  => 'Trường :attribute không được lớn hơn :max ký tự.',
    ],
    'max_digits' => 'Trường :attribute không được có nhiều hơn :max chữ số.',
    'mimes'      => 'Trường :attribute phải là một tệp loại: :values.',
    'mimetypes'  => 'Trường :attribute phải là một tệp loại: :values.',
    'min'        => [
        'array'   => 'Trường :attribute phải có ít nhất :min mục.',
        'file'    => 'Trường :attribute phải có ít nhất :min kilobytes.',
        'numeric' => 'Trường :attribute phải có ít nhất :min.',
        'string'  => 'Trường :attribute phải có ít nhất :min ký tự.',
    ],
    'min_digits'       => 'Trường :attribute phải có ít nhất :min chữ số.',
    'missing'          => 'Trường :attribute phải bị thiếu.',
    'missing_if'       => 'Trường :attribute phải bị thiếu khi :other là :value.',
    'missing_unless'   => 'Trường :attribute phải bị thiếu trừ khi :other là :value.',
    'missing_with'     => 'Trường :attribute phải bị thiếu khi :values có mặt.',
    'missing_with_all' => 'Trường :attribute phải bị thiếu khi :values có mặt.',
    'multiple_of'      => 'Trường :attribute phải là bội số của :value.',
    'not_in'           => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'not_regex'        => 'Định dạng trường :attribute không hợp lệ.',
    'numeric'          => 'Trường :attribute phải là một số.',
    'password'         => [
        'letters'       => 'Trường :attribute phải chứa ít nhất một chữ cái.',
        'mixed'         => 'Trường :attribute phải chứa ít nhất một chữ hoa và một chữ thường.',
        'numbers'       => 'Trường :attribute phải chứa ít nhất một số.',
        'symbols'       => 'Trường :attribute phải chứa ít nhất một ký hiệu.',
        'uncompromised' => 'Giá trị đã cho của :attribute đã xuất hiện trong một vụ rò rỉ dữ liệu. Vui lòng chọn một giá trị khác cho :attribute.',
    ],
    'present'              => 'Trường :attribute phải có mặt.',
    'prohibited'           => 'Trường :attribute bị cấm.',
    'prohibited_if'        => 'Trường :attribute bị cấm khi :other là :value.',
    'prohibited_unless'    => 'Trường :attribute bị cấm trừ khi :other là trong :values.',
    'prohibits'            => 'Trường :attribute cấm :other có mặt.',
    'regex'                => 'Định dạng trường :attribute không hợp lệ.',
    'required'             => 'Trường :attribute là bắt buộc.',
    'required_array_keys'  => 'Trường :attribute phải chứa các khóa: :values.',
    'required_if'          => 'Trường :attribute là bắt buộc khi :other là :value.',
    'required_if_accepted' => 'Trường :attribute là bắt buộc khi :other được chấp nhận.',
    'required_unless'      => 'Trường :attribute là bắt buộc trừ khi :other là trong :values.',
    'required_with'        => 'Trường :attribute là bắt buộc khi :values có mặt.',
    'required_with_all'    => 'Trường :attribute là bắt buộc khi :values có mặt.',
    'required_without'     => 'Trường :attribute là bắt buộc khi :values ​​không có.',
    'required_without_all' => 'Trường :attribute là bắt buộc khi không có giá trị :value nào.',
    'same'                 => 'Trường :attribute và :other phải khớp.',
    'size'                 => [
        'array'   => 'Trường :attribute phải chứa :size mục.',
        'file'    => 'Trường :attribute phải có kích thước :size kilobyte.',
        'numeric' => 'Trường :attribute phải bằng :size.',
        'string'  => 'Trường :attribute phải có :size ký tự.',
    ],
    'starts_with' => 'Trường :attribute phải bắt đầu bằng một trong các giá trị sau: :values.',
    'string'      => 'Trường :attribute phải là một chuỗi.',
    'timezone'    => 'Trường :attribute phải là một vùng giờ hợp lệ.',
    'unique'      => 'Giá trị đã có trong trường :attribute.',
    'uploaded'    => 'Trường :attribute không thể tải lên.',
    'uppercase'   => 'Trường :attribute phải viết hoa.',
    'url'         => 'Định dạng trường :attribute không hợp lệ.',
    'ulid'        => 'Trường :attribute phải là ULID hợp lệ.',
    'uuid'        => 'Trường :attribute phải là một UUID hợp lệ.',

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

    'surname.required_without'   => 'Khi thêm một người MỚI, họ là bắt buộc.',
    'person_id.required_without' => 'Khi thêm một người ĐÃ CÓ, hãy chọn một người.',

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
