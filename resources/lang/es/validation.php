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

    'accepted'   => 'El :attribute debe ser aceptada.',
    'active_url' => 'El :attribute no es una URL válida.',
    'after'      => 'El :attribute debe ser una fecha a partir de :date.',
    'alpha'      => 'El :attribute sólo puede contener letras.',
    'alpha_dash' => 'El :attribute sólo puede contener letras, números y guiones.',
    'alpha_num'  => 'El :attribute sólo puede contener letras y números.',
    'array'      => 'El :attribute debe ser una matriz.',
    'before'     => 'El :attribute debe ser una fecha antes de :date.',
    'between'              => [
        'numeric' => 'El :attribute debe ser entre :min y :max.',
        'file'    => 'El :attribute debe ser entre :min y :max kilobytes.',
        'string'  => 'El :attribute debe estar entre :min y :max de caracteres.',
        'array'   => 'El :attribute debe tener entre :min y :max elementos.',
    ],
    'boolean' => 'El :attribute de atributo debe ser verdadera o falsa.',
    'confirmed' => 'El :attribute de confirmación no coinciden.',
    'date' => 'El :attribute no es una fecha válida.',
    'date_format' => 'El :attribute no coincide con el formato :format.',
    'different' => 'El :attribute y :other demás deben ser diferentes.',
    'digits' => 'El :attribute debe ser :digits dígitos.',
    'digits_between' => 'El :attribute debe ser entre :min y :max dígitos.',
    'email' => 'El :attribute debe ser una dirección válida de correo electrónico.',
    'exists' => 'El seleccionado :attribute no es válido.',
    'filled' => 'El :attribute de campo es obligatorio.',
    'image' => 'El :attribute debe ser una imagen.',
    'in' => 'El seleccionado :attribute no es válido.',
    'integer' => 'El :attribute debe ser un entero.',
    'ip' => 'El :attribute debe ser una dirección IP válida.',
    'json' => 'El :attribute debe ser válido cadena JSON.',
    'max'                  => [
       'numeric' => 'El :attribute no puede ser mayor que :max.',
    'file' => 'El :attribute no puede ser mayor que :max kilobytes.',
    'string' => 'El :attribute no puede ser mayor que :max de caracteres.',
    'array' => 'El :attribute no puede tener más de :max elementos.',
    ],
    'mimes' => 'El :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => 'El :attribute debe ser al menos de :min.',
        'file'    => 'El :attribute debe ser al menos de :min kilobytes.',
        'string'  => 'El :attribute debe ser al menos de :min caracteres.',
        'array'   => 'El :attribute debe tener al menos :min elementos.',
    ],
    'not_in'               => 'El seleccionado :attribute no es válido.',
    'numeric'              => 'El :attribute debe ser un número.',
    'regex'                => 'El formato de :attribute no es válido.',
    'required'             => 'El :attribute de campo es obligatorio.',
    'required_if'          => 'El :attribute de campo es obligatorio cuando :other :value.',
    'required_with'        => 'El :attribute de campo es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El :attribute de campo es obligatorio cuando :values está presente.',
    'required_without'     => 'El :attribute de campo es obligatorio cuando los :values no está presente.',
    'required_without_all' => 'El :attribute de campo es obligatorio cuando ninguno de los :values están presentes.',
    'same'                 => 'El :attribute y :other demás deben coincidir.',
    'size'                 => [
        'numeric' => 'El :attribute debe ser el :size.',
        'file'    => 'El :attribute debe ser el :size en kilobytes.',
        'string'  => 'El :attribute debe ser el :size de los caracteres.',
        'array'   => 'El :attribute debe contener :size de los artículos.',
    ],
    'string'   => 'El :attribute debe ser una cadena.',
    'timezone' => 'El :attribute debe ser una zona válida.',
    'unique'   => 'El :attribute ya ha sido tomada.',
    'url'      => 'El :formato de atributo no es válido.',

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

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
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

    'attributes' => [
        'name'             => 'Nombre',
        'password '        => 'contraseña',
        'confirm_password' => 'Confirmar Contraseña',
        'username'         => 'Dirección De Correo Electrónico',
        'defaultCredits'   => 'Por Defecto Créditos',
        'credit'           => 'Créditos',
        'credits'          => 'Créditos',
        'packageName'      => 'Nombre Del Paquete',
        'amount'           => 'Cantidad',
        'package_name'     => 'Nombre Del Paquete',
        'duration'         => 'Duración',
        'title'            => 'Título De Página Web',
        'port'             => 'Puerto',
        'firstname'        => 'Primer Nombre',
        'lastname'         => 'Apellido',
        'gender'           => 'Género',
        'dob'              => 'Fecha de Nacimiento',
        'lat'              => 'La latitud',
        'lng'              => 'Longitud',
        'city'             => 'Ciudad',
        'country'          => 'País',
        'hereto'           => 'Aquí',
        'password'         => 'Contraseña',
        'password_confirm' => 'Confirmar Contraseña',
    ],

];
