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

    'accepted'   => 'W :attribute musi być przyjęty.',
    'active_url' => 'W :attribute nie jest prawidłowym adresem URL.',
    'after'      => 'W :attribute musi być Data po :date.',
    'alpha'      => 'W :attribute może zawierać tylko litery.',
    'alpha_dash' => 'W :attribute może zawierać tylko litery, cyfry i myślniki.',
    'alpha_num'  => 'W :attribute może zawierać tylko litery i cyfry.',
    'array'      => 'W :attribute musi być tablicą.',
    'before'     => 'W :attribute musi być Data do :date.',
    'between'              => [
        'numeric' => 'W :attribute musi być pomiędzy :min i :max.',
        'file'    => 'W :attribute musi być pomiędzy :min i :max kilobajtów.',
        'string'  => 'W :attribute musi być pomiędzy :min i :max znaków.',
        'array'   => 'W :attribute musi być pomiędzy :min i :max elementów.',
    ],
    'boolean'        => 'W polu :attribute musi być wartość True lub false.',
    'confirmed'      => 'W potwierdzenie :attribute nie pasuje.',
    'date'           => 'W :attribute nie jest poprawną datą.',
    'date_format'    => 'W :attribute nie odpowiada :format .',
    'different'      => 'W :attribute i :other muszą być różne.',
    'digits'         => 'W ::attribute musi być :digits cyfry.',
    'digits_between' => 'W :attribute musi być pomiędzy :min i :max cyfry.',
    'email'          => 'W :attribute powinien być prawidłowy adres e-mail.',
    'exists'         => 'Wybranego :attribute jest nieważne.',
    'filled'         => 'Wymagane :pole :attribute.',
    'image'          => 'W :attribute powinien być obraz.',
    'in'             => 'Wybranego :attribute jest nieważne.',
    'integer'        => 'W :attribute musi być liczbą całkowitą.',
    'ip'             => 'W :attribute musi być prawidłowy adres IP.',
    'json'           => 'W :attribute powinien być prawidłowy ciąg znaków json.',
    'max'                  => [
        'numeric' => 'W :attribute nie może być więcej :max.',
        'file'    => 'W :attribute nie może być więcej :max kilobajtów.',
        'string'  => 'W :attribute nie może być więcej :max znaków.',
        'array'   => 'W :attribute nie może mieć więcej niż :max szczegółów.',
    ],
    'mimes' => 'W :attribute powinien być plik typu: :values.',
    'min'                  => [
        'numeric' => 'W :attribute musi być nie mniejsza niż :min.',
        'file'    => 'W :attribute musi być nie mniejsza niż :min kilobajtów.',
        'string'  => 'W :attribute musi być co najmniej :min charaktery.',
        'array'   => 'W :attribute musi mieć co najmniej :min elementy.',
    ],
    'not_in'               => 'Wybranego :attribute jest nieważne.',
    'numeric'              => 'W :attribute musi być liczbą.',
    'regex'                => 'W :attribute format jest nieprawidłowy.',
    'required'             => 'Wymagane pole :attribute.',
    'required_if'          => 'W polu :attribute jest wymagany w przypadku :other :value.',
    'required_with'        => 'W polu :attribute jest wymagany, jeżeli :values jest obecny.',
    'required_with_all'    => 'W polu :attribute jest wymagany, jeżeli :values jest obecny.',
    'required_without'     => 'W polu :attribute jest wymagany, jeżeli :values nie ma.',
    'required_without_all' => 'W polu :attribute jest wymagany, jeżeli nie ma są obecne values.',
    'same'                 => 'W :attribute i :other muszą być zgodne.',
    'size'                 => [
        'numeric' => 'W :attribute powinien :size.',
        'file'    => 'W :attribute powinien :size kilobajtów.',
        'string'  => 'W :attribute musi być :size znaków.',
        'array'   => 'W :attribute powinien zawierać :size mapie.',
    ],
    'string'  => 'W :attribute musi być ciągiem znaków.',
    'timezone' => 'W :attribute powinien być ważny strefy.',
    'unique'   => 'W :attribute została już podjęta.',
    'url'      => 'W :attribute format jest nieprawidłowy.',

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
       
       'name'             => 'Nazwa',
       'password '        => 'hasło',
       'confirm_password' => 'Potwierdzenie Hasła',
       'username'         => 'Adres E-Mail',
       'defaultCredits'   => 'Pożyczki Domyślne',
       'credit'           => 'Kredyty',
       'credits'          => 'Kredyty',
       'packageName'      => 'Nazwa Pakietu',
       'amount'           => 'Kwota',
       'package_name'     => 'Nazwa Pakietu',
       'duration'         => 'Długość',
       'title'            => 'Tytuł Strony',
       'port'             => 'Port',
       'firstname'        => 'Nazwa',
       'lastname'         => 'Nazwisko',
       'gender'           => 'Podłoga',
       'dob'              => 'Data urodzenia',
       'lat'              => 'Szerokość',
       'lng'              => 'Długości geograficznej',
       'city'             => 'Miasto',
       'country'          => 'Kraj',
       'hereto'           => 'Tutaj',
       'password'         => 'Hasło',
       'password_confirm' => 'Potwierdzenie Hasła',


    ],

];
