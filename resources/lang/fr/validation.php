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

    'accepted'   => 'A :attribute az el kell fogadni.',
    'active_url' => 'A :attribute az nem érvényes URL-t.',
    'after'      => 'A :attribute azot kell egy dátum után :date.',
    'alpha'      => 'A :attribute az lehet, hogy csak olyan leveleket.',
    'alpha_dash' => 'A :attribute az lehet, hogy csak olyan betűk, számok, odavág.',
    'alpha_num'  => 'A :attribute az kizárólag tartalmaz betűket, számokat.',
    'array'      => 'A :attribute az kell egy tömb.',
    'before'     => 'A :attribute az kell egy randi előtt :date.',
    'between'              => [
        'numeric' => 'A :attribute kell között :min :max.',
        'file'    => 'A :attribute kell között :min :max. kb.',
        'string'  => 'A :attribute kell között :min :max karaktereket.',
        'array'   => 'A :attribute kell között :min :max elemek.',
    ],
    'confirmed'      => 'A az :attributem megerősítést nem egyezik.',
    'date'           => 'A az :attribute nem érvényes dátum.',
    'date_format'    => 'A az :attribute nem egyezik meg a :format formátum.',
    'different'      => 'A az :attribute pedig :other eltérő lehet.',
    'digits'         => 'A az :attribute kell :digits számjegy.',
    'digits_between' => 'A az :attributet kell között :min :max számjegy.',
    'email'          => 'A az :attribute kell egy érvényes e-mail címet.',
    'exists'         => 'A kiválasztott :attribute érvénytelen.',
    'filled'         => 'A :attribute a mező kitöltése kötelező.',
    'image'          => 'A :attributet kell egy képet.',
    'in'             => 'A kiválasztott :attribute érvénytelen.',
    'integer'        => 'A :attribute kell egy egész szám.',
    'ip'             => 'A :attribute kell egy érvényes IP-címet.',
    'json'           => 'A :attribute kell egy érvényes JSON string.',
    'max'                  => [
        'numeric' => 'A :attribute nem lehet nagyobb, mint :max.',
        'file' => 'A :attribute nem lehet nagyobb, mint :max. kb.',
        'string' => 'A :attribute nem lehet nagyobb, mint :max karaktereket.',
        'array' => 'A :attribute nem lehet több, mint :max elemek.',
    ],
    'mimes' => 'A :attribute kell egy fájl típus: :values.',
    'min'                  => [
        'numeric' => 'A :attribute kell legalább :min.',
        'file'    => 'A :attribute kell legalább :min kilobyte.',
        'string'  => 'A :attribute kell legalább :min karaktereket.',
        'array'   => 'A :attribute kell legalább :min elemek.',
    ],
    'not_in'               => 'A kiválasztott :attribute érvénytelen.',
    'numeric'              => 'A :attribute kell egy számot.',
    'regex'                => 'A :attribute formátuma érvénytelen.',
    'required'             => 'A :attributea mező kitöltése kötelező.',
    'required_if'          => 'A :attribute területen van szükség, ha :other :value.',
    'required_with'        => 'A :attributem területen van szükség, ha :values jelen van.',
    'required_with_all'    => 'A :attribute területen van szükség, ha :values jelen van.',
    'required_without'     => 'A :attribute területen van szükség, ha :values nem áll fenn.',
    'required_without_all' => 'A :attribute mező szükséges, ha nem :values vannak jelen.',
    'same'                 => 'A :attribute :other meg kell egyeznie.',
    'size'                 => [
        'numeric' => 'A :attribute kell :size.',
        'file'    => 'A :attribute kell :size kb.',
        'string'  => 'A :attribute kell :size karaktereket.',
        'array'   => 'A :attribute tartalmaznia kell :size elemek.',
    ],
    'string5'  => 'A :attribute kell egy string.',
    'timezone' => 'A :attribute kell egy érvényes zóna.',
    'unique'   => 'A :attribute már foglalt.',
    'url'      => 'A :attribute formátuma érvénytelen.',

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
        'name'             => 'Név',
        'password '        => 'jelszó',
        'confirm_password' => 'Jelszó Megerősítése',
        'username'         => 'E-Mail Cím',
        'defaultCredits'   => 'Alapértelmezett Kredit',
        'credit'           => 'Kredit',
        'credits'          => 'Kredit',
        'packageName'      => 'Csomag Neve',
        'amount'           => 'Összeg',
        'package_name'     => 'Csomag Neve',
        'duration'         => 'Időtartam',
        'title'            => 'Honlap Címe',
        'port'             => 'Port',
        'firstname'        => 'Keresztnév',
        'lastname'         => 'Vezetéknév',
        'gender'           => 'A nemek közötti',
        'dob'              => 'Születési dátuma',
        'lat'              => 'Szélesség',
        'lng'              => 'Hosszúság',
        'city'             => 'Város',
        'country'          => 'Ország',
        'hereto'           => 'Itt',
        'password'         => 'Jelszó',
        'password_confirm' => 'Jelszó Megerősítése',
    ],

];
