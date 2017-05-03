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

    'accepted'             => 'L\' :attribute deve essere accettato.',
    'active_url'           => 'L\' :attribute non è un URL valido.',
    'after'                => 'L\' :attribute deve essere una data dopo data.',
    'alpha'                => 'L\' :attribute può contenere solo lettere.',
    'alpha_dash'           => 'L\' :attribute può contenere solo lettere, numeri e trattini.',
    'alpha_num'            => 'L\' :attribute può contenere solo lettere e numeri.',
    'array'                => 'L\' :attribute deve contenere :elementi di formato.',
    'before'               => 'L\' :attribute deve essere una data precedente :data.',
    'between'              => [
        'numeric' => 'L\' :attribute :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'L\' campo :attribute deve essere true o false.',
    'confirmed'            => 'L\' :attribute di conferma non corrisponde.',
    'date'                 => 'L\' :attribute non è una data valida.',
    'date_format'          => 'L\' :attribute non corrisponde al formato :format.',
    'different'            => 'L\' :attribute e :other devono essere diversi.',
    'digits'               => 'L\' :attribute deve essere :digits cifre.',
    'digits_between'       => 'L\' :attribute deve essere compresa tra :min e :max cifre.',
    'email'                => 'L\' :attributo deve essere un indirizzo email valido.',
    'exists'               => 'Selezionato :attributo non è valido.',
    'filled'               => 'L\' :attributo di campo è obbligatorio.',
    'image'                => 'L\' :attributo deve essere un\'immagine.',
    'in'                   => 'Selezionato :attributo non è valido.',
    'integer'              => 'L\' :attributo deve essere un numero intero.',
    'ip'                   => 'L\' :attributo deve essere un indirizzo IP valido.',
    'json'                 => 'L\' :attributo deve essere un valido stringa JSON.',
    'max'                  => [
        'numeric' => 'L\' :attributo deve essere un file di tipo :max valori.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'Selezionato :attributo non è valido.',
    'numeric'              => 'The :attribute must be a number.',
    'regex'                => 'L\' :attributo formato non è valido.',
    'required'             => 'L\' :attributo di campo è obbligatorio.',
    'required_if'          => 'L\' :attributo di campo è obbligatorio quando :other valore di.',
    'required_with'        => 'L\' :attributo di campo è obbligatorio quando :values è presente.',
    'required_with_all'    => 'L\' :attributo di campo è obbligatorio quando :values è presente.',
    'required_without'     => 'L\' :attributo di campo è obbligatorio quando i :values non è presente.',
    'required_without_all' => 'L\' :attributo di campo è obbligatorio se nessuno dei :values presenti.',
    'same'                 => 'L\' :attributo e :other devono corrispondere.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'L\' :attributo deve essere una zona valida.',
    'unique'               => 'L\' :attributo è già stata presa.',
    'url'                  => 'L\' :attributo formato non è valido.',

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

        // admin
        "name"             => "Nome",
        "password "        => "password",
        "confirm_password" => "Conferma Password",
        "username"         => "Indirizzo E-Mail",
        "defaultCredits"   => "Predefinito Di Crediti Di",
        "credit"           => "Crediti",
        "credits"          => "Crediti",
        "packageName"      => "Il Nome Del Pacchetto",
        "amount"           => "Quantità",
        "package_name"     => "Il Nome Del Pacchetto",
        "duration"         => "Durata",
        "title"            => "Titolo Del Sito Web",
        "port"             => "Porta",
        

        //user
        "firstname" => "Nome",
        "lastname" => "Cognome",
        "gender" => "Genere",
        "dob" => "Data di Nascita",
        "username" => "Email Address",
        "lat" => "Latitudine",
        "lng" => "Longitudine",
        "city" => "Città",
        "country" => "Paese",
        "hereto" => "Qui",
        "password" => "Password",
        "password_confirm" => "Conferma Password",


    ],

];
