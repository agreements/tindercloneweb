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

    'accepted'   => 'В :attribute должен быть принят.',
    'active_url' => 'В :attribute не является допустимым URL-адресом.',
    'after'      => 'В :attribute должен быть Дата после :date.',
    'alpha'      => 'В :attribute может содержать только буквы.',
    'alpha_dash' => 'В :attribute может содержать только буквы, цифры и дефисы.',
    'alpha_num'  => 'В :attribute может содержать только буквы и цифры.',
    'array'      => 'В :attribute должен быть массивом.',
    'before'     => 'В :attribute должен быть Дата до :date.',
    'between'              => [
        'numeric' => 'В :attribute должен быть между :min и :max.',
        'file'    => 'В :attribute должен быть между :min и :max килобайт.',
        'string'  => 'В :attribute должен быть между :min и :max символов.',
        'array'   => 'В :attribute должен быть между :min и :max элементов.',
    ],
    'boolean'        => 'В поле :attribute должно быть значение True или false.',
    'confirmed'      => 'В подтверждение :attribute не совпадает.',
    'date'           => 'В :attribute не является допустимой датой.',
    'date_format'    => 'В :attribute не соответствует :format.',
    'different'      => 'В :attribute и другие должны :other разными.',
    'digits'         => 'В :attribute должен быть :digits цифры.',
    'digits_between' => 'В :attribute должен быть между :min и :max цифры.',
    'email'          => 'В :attribute должен быть действительный адрес электронной почты.',
    'exists'         => 'Выбранного :attribute является недействительным.',
    'filled'         => 'Требуется поле :attribute.',
    'image'          => 'В :attribute должен быть образ.',
    'in'             => 'Выбранного :attribute является недействительным.',
    'integer'        => 'В :attribute должен быть целым числом.',
    'ip'             => 'В :attribute должен быть действительный IP-адрес.',
    'json'           => 'В :attribute должен быть допустимым json-строку.',
    'max'                  => [
       'numeric' => 'В :attribute не может быть больше :max.',
       'file'    => 'В :attribute не может быть больше :max килобайт.',
       'string'  => 'В :attribute не может быть больше :max персонажей.',
       'array'   => 'В :attribute не может иметь более чем :max деталей.',
    ],
    'mimes' => 'В :attribute должен быть файл типа: :values.',
    'min'                  => [
       'numeric3' => 'В :attribute должен быть не менее :min.',
       'file'     => 'В :attribute должен быть не менее :min килобайт.',
       'string'   => 'В :attribute должен быть как минимум :min характеры.',
       'array'    => 'В :attribute должен иметь как минимум :min элементы.',
    ],
    'not_in'               => 'Выбранного :attribute является недействительным.',
    'numeric'              => 'В :attribute должен быть числом.',
    'regex'                => 'В формат :attribute является недействительным.',
    'required'             => 'Требуется поле :attribute.',
    'required_if'          => 'В поле :attribute необходим при :other :value.',
    'required_with'        => 'В поле :attribute является обязательным, если :values присутствует.',
    'required_with_all'    => 'В поле :attribute является обязательным, если :values присутствует.',
    'required_without'     => 'В поле :attribute является обязательным, если :values нет.',
    'required_without_all' => 'В поле :attribute является обязательным, если нет присутствуют :values.',
    'same'                 => 'В :attribute и :other должны совпадать.',
    'size'                 => [
        'numeric' => 'В :attribute должен :size.',
        'file'    => 'В :attribute должен :size килобайт.',
        'string'  => 'В :attribute должен быть :size символов.',
        'array'   => 'В :attribute должен содержать элементы :size.',
    ],
    'string'   => 'В :attribute должен быть строкой.',
    'timezone' => 'В :attribute должен быть действительным зоны.',
    'unique'   => 'В :attribute уже принято.',
    'url'      => 'В формат :attribute является недействительным.',

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

        'name'             => 'Имя',
        'password '        => 'пароль',
        'confirm_password' => 'Подтверждение Пароля',
        'username'         => 'Адрес Электронной Почты',
        'defaultCredits'   => 'Кредиты По Умолчанию',
        'credit'           => 'Кредиты',
        'credits'          => 'Кредиты',
        'packageName'      => 'Имя Пакета',
        'amount'           => 'Сумма',
        'package_name'     => 'Имя Пакета',
        'duration'         => 'Продолжительность',
        'title'            => 'Название Сайта',
        'port'             => 'Порт',
        'firstname'        => 'Имя',
        'lastname'         => 'Фамилия',
        'gender'           => 'Пол',
        'dob'              => 'Дата рождения',
        'lat'              => 'Широта',
        'lng'              => 'Долготы',
        'city'             => 'Город',
        'country'          => 'Страна',
        'hereto'           => 'Здесь',
        'password'         => 'Пароль',
        'password_confirm' => 'Подтверждение Пароля',
    ],

];
