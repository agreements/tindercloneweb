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

	'accepted'   => 'De :attribute moet worden geaccepteerd.',
	'active_url' => 'De :attribute is geen geldige URL.',
	'after'      => 'De :attribute moet een datum na :date.',
	'alpha'      => 'De :attribute mag alleen letters bevatten.',
	'alpha_dash' => 'De :attribute mag alleen bestaan uit letters, cijfers en streepjes.',
	'alpha_num'  => 'De :attribute kan alleen letters en nummers bevatten.',
	'array'      => 'De :attribute moet een array.',
	'before'     => 'De :attribute moet een datum voor :date.',
    'between'              => [
		'numeric' => 'De :attribute moet worden tussen :min :max.',
		'file'    => 'De :attribute moet worden tussen :min :max kilobytes.',
		'string'  => 'De :attribute moet worden tussen :min :max tekens.',
		'array'   => 'De :attribute moet tussen het :min -en :max -items.',
    ],
	'boolean'        => 'De :attribute veld moet true of false zijn.',
	'confirmed'      => 'De :attribute bevestiging behoeft niet overeen.',
	'date'           => 'De :attribute is geen geldige datum.',
	'date_format'    => 'De :attribute komt niet overeen met het formaat :format.',
	'different'      => 'De :attribute en :other moet anders.',
	'digits'         => 'De :attribute moet worden :digits cijfers.',
	'digits_between' => 'De :attribute moet worden tussen :min :max cijfers.',
	'email'          => 'De :attribute moet een geldig e-mail adres.',
	'exists'         => 'De gekozen :attribute is ongeldig.',
	'filled'         => 'De :attribute is een verplicht veld.',
	'image'          => 'De :attribute moet worden van een afbeelding.',
	'in'             => 'De gekozen :attribute is ongeldig.',
	'integer'        => 'De :attributet moet een geheel getal zijn.',
	'ip'             => 'De :attribute moet een geldig IP-adres.',
	'json'           => 'De :attribute moet een geldig JSON-string.',
    'max'                  => [
		'numeric' => 'De :attribute mag niet groter zijn dan :max.',
		'file'    => 'De :attribute mag niet groter zijn dan :max kilobytes.',
		'string'  => 'De :attribute mag niet groter zijn dan :max tekens.',
		'array'   => 'De :attribute mag niet meer dan :max items.',
    ],
    'mimes' => 'De :attribute moet worden een bestand van het type: :values.',
    'min'                  => [
		'numeric' => 'De :attribute moet minimaal zijn :min.',
		'file'    => 'De :attribute moet minimaal zijn :min kilobytes.',
		'string'  => 'De :attribute moet minimaal zijn :min tekens.',
		'array'   => 'De :attribute moet ten minste :min items.',
    ],
	'not_in'               => 'De gekozen :attribute is ongeldig.',
	'numeric'              => 'De :attribute moet een getal zijn.',
	'regex'                => 'De :attribute formaat is ongeldig.',
	'required'             => 'De :attribute is een verplicht veld.',
	'required_if'          => 'De :attribute is een verplicht veld wanneer :other :value.',
	'required_with'        => 'De :attribute is een verplicht veld als :values aanwezig.',
	'required_with_all'    => 'De :attribute is een verplicht veld als :values aanwezig.',
	'required_without'     => 'De :attribute is een verplicht veld als :values niet aanwezig is.',
	'required_without_all' => 'De :attribute is een verplicht veld wanneer er geen values aanwezig zijn.',
	'same'                 => 'De :attribute en :other moeten overeenkomen.',
    'size'                 => [
		'numeric' => 'De :attribute moet worden de :size.',
		'file'    => 'De :attribute moet worden :size in kilobytes.',
		'string'  => 'De :attribute moet worden :size van de tekens.',
		'array'   => 'De :attribute moet bevatten :size items.',
		],
	'string5'  => 'De :attribute moet een tekenreeks zijn.',
	'timezone' => 'De :attribute moet een geldige zone.',
	'unique'   => 'De :attribute is al genomen.',
	'url'      => 'De :attribute formaat is ongeldig.',

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
		'name'             => 'Naam',
		'password '        => 'wachtwoord',
		'confirm_password' => 'Wachtwoord Bevestigen',
		'username'         => 'E-Mail Adres',
		'defaultCredits'   => 'Default Credits',
		'credit'           => 'Credits',
		'credits'          => 'Credits',
		'packageName'      => 'Pakket Naam',
		'amount'           => 'Bedrag',
		'package_name'     => 'Pakket Naam',
		'duration'         => 'Duur',
		'title'            => 'Website Titel',
		'port'             => 'Poort',
		'firstname'        => 'Voornaam',
		'lastname'         => 'Achternaam',
		'gender'           => 'Geslacht',
		'dob'              => 'Geboortedatum',
		'lat'              => 'Breedtegraad',
		'lng'              => 'Breedtegraad',
		'city'             => 'Stad',
		'country'          => 'Land',
		'hereto'           => 'Hier',
		'password'         => 'Wachtwoord',
		'password_confirm' => 'Wachtwoord Bevestigen',
    ],

];
