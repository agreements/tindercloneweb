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

    'accepted'   => 'Die :attribute muss akzeptiert werden.',
    'active_url' => 'Die :attribute ist keine gültige URL.',
    'after'      => 'Der :attribute muss ein Datum nach dem :date sein.',
    'alpha'      => 'Der :attribute darf nur Buchstaben enthalten.',
    'alpha_dash' => 'Der :attribute darf nur aus Buchstaben, zahlen und Bindestriche.',
    'alpha_num'  => 'Der :attribute darf nur Buchstaben und zahlen enthalten.',
    'array'      => 'Die :attribute muss ein array sein.',
    'before'     => 'Der :attribute muss ein Datum vor dem :date sein.',
    'between'              => [
        'numeric' => 'Die :attribute muss zwischen :min und :max.',
        'file'    => 'Die :attribute muss zwischen :min - :max Kilobyte.',
        'string'  => 'Die :attribute muss zwischen :min und :max Zeichen.',
        'array'   => 'Die :attribute muss zwischen :min :max Elemente.',
    ],
    'boolean'        => 'Die :attribute Feld muss true oder false sein.',
    'confirmed'      => 'Die :attribute ist nicht bestaetigt.',
    'date'           => 'Die :attribute ist nicht ein gültiges Datum ist.',
    'date_format'    => 'Die :attribute entspricht nicht dem format :format.',
    'different'      => 'Die :attribute und :der andere muss anders sein.',
    'digits'         => 'Die :attribute muss :digits Ziffern.',
    'digits_between' => 'Die :attribute muss zwischen :min und :max stellen.',
    'email'          => 'Der :attribute muss eine gültige E-Mail-Adresse.',
    'exists'         => 'Die gewählten :attribute ist ungültig.',
    'filled'         => 'Die :attribute Feld ist erforderlich.',
    'image'          => 'Die :attribute muss ein Bild sein.',
    'in'             => 'Die gewählten :attribute ist ungültig.',
    'integer'        => 'Der :attribute muss eine Ganzzahl sein.',
    'ip'             => 'Der :attribute muss eine gültige IP-Adresse.',
    'json'           => 'Der :attribute muss ein Gültiger JSON-string.',
    'max'                  => [
        'numeric' => 'Die :attribute kann nicht größer als :max.',
        'file'    => 'Die :attribute darf nicht größer sein als :max Kilobyte.',
        'string'  => 'Die :attribute darf nicht größer sein als :max Zeichen.',
        'array'   => 'Die :attribute darf nicht mehr als :max Elemente.',
    ],
   'mimes' => 'Der :attribute muss eine Datei vom Typ: :values.',
    'min'                  => [
        'numeric' => 'Die :attribute muss mindestens :min.',
        'file'    => 'Die :attribute muss mindestens :min Kilobyte.',
        'string'  => 'Die :attribute muss mindestens :min Zeichen.',
        'array'   => 'Die :attribute muss mindestens :min-Elemente.',
    ],
    'not_in'               => 'Die gewählten :attribute ist ungültig.',
    'numeric'              => 'Der :attribute muss eine Zahl sein.',
    'regex'                => 'Der :attribute format ist ungültig.',
    'required'             => 'Die :attribute Feld ist erforderlich.',
    'required_if'          => 'Die :attribute Feld ist erforderlich, wenn :other andere ist :value.',
    'required_with'        => 'Die :attribute Feld ist erforderlich, wenn :values vorhanden ist.',
    'required_with_all'    => 'Die :attribute Feld ist erforderlich, wenn :values vorhanden ist.',
    'required_without'     => 'Die :attribute Feld ist erforderlich, wenn :values ist nicht vorhanden.',
    'required_without_all' => 'Die :attribute Feld ist erforderlich, wenn keiner der :values vorhanden sind.',
    'same'                 => 'Der :attribute und :other übereinstimmen muss.',
    'size'                 => [
        'numeric' => 'Der :attribute muss sein :size Größe.',
        'file'    => 'Der :attribute muss sein :size in Kilobyte.',
        'string'  => 'Der :attribute muss sein :size, Zeichen.',
        'array'   => 'Die :attribute muss enthalten :size Größe der Elemente.',
    ],
    'string5'  => 'Die :attribute muss ein string sein.',
    'timezone' => 'Der :attribute muss ein Gültiger zone.',
    'unique'   => 'Die :attribute bereits genommen.',
    'url'      => 'Der :attribute -format ist ungültig.',

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
        'name'             => 'Name',
        'password '        => 'Passwort',
        'confirm_password' => 'Kennwort Bestätigen',
        'username'         => 'E-Mail-Adresse',
        'defaultCredits'   => 'Standard-Credits',
        'credit'           => 'Credits',
        'credits'          => 'Credits',
        'packageName'      => 'Package-Namen',
        'amount'           => 'Höhe',
        'package_name'     => 'Package-Namen',
        'duration'         => 'Dauer',
        'title'            => 'Website-Titel',
        'port'             => 'Port',
        'firstname'        => 'Vorname',
        'lastname'         => 'Nachname',
        'gender'           => 'Geschlecht',
        'dob'              => 'Geburtsdatum',
        'lat'              => 'Latitude',
        'lng'              => 'Länge',
        'city'             => 'Stadt',
        'country'          => 'Land',
        'hereto'           => 'Hier',
        'password'         => 'Passwort',
        'password_confirm' => 'Kennwort Bestätigen',
    ],

];
