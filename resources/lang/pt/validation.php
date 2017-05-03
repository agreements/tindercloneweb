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

    'accepted'   => 'A :attribute deve ser aceito.',
    'active_url' => 'O attribute não é uma URL válida.',
    'after'      => 'A :attribute deve ser uma data após a :date.',
    'alpha'      => 'A :attribute pode conter só letras.',
    'alpha_dash' => 'A :attribute só pode conter letras, números e hífens.',
    'alpha_num'  => 'A :attribute pode conter só letras e números.',
    'array'      => 'A :attribute deve ser um array.',
    'before'     => 'A :attribute deve ser uma data antes da :date.',
    'between'              => [
        'numeric' => 'A :attribute deve ser entre :min :max.',
        'file'    => 'A :attribute deve ser entre :min :max kilobytes.',
        'string'  => 'A :attribute deve ser entre :min :max caracteres.',
        'array'   => 'O :attribute deve ter entre :min :max itens.',
    ],
    'boolean'        => 'A campo de attribute deve ser true ou false.',
    'confirmed'      => 'A :attribute de confirmação não correspondem.',
    'date'           => 'O :attribute não é uma data válida.',
    'date_format'    => 'A :attribute não corresponder ao formato :format.',
    'different'      => 'A :attribute e :other devem ser diferentes.',
    'digits'         => 'A :attribute deve ser :digits dígitos.',
    'digits_between' => 'A :attribute deve ser entre :min :max dígitos.',
    'email'          => 'A :attribute deve ser um endereço de email válido.',
    'exists'         => 'O selecionado :attribute é inválido.',
    'filled'         => 'O :attribute do campo é obrigatório.',
    'image'          => 'A :attribute deve ser uma imagem.',
    'in'             => 'O selecionado :attribute é inválido.',
    'integer'        => 'A :attribute deve ser um número inteiro.',
    'ip'             => 'A :attribute deve ser um endereço IP válido.',
    'json'           => 'A :attribute deve ser um válido string JSON.',
    'max'                  => [
        'numeric' => 'A :attribute não pode ser superior a :max.',
        'file'    => 'A :attribute não pode ser maior que :max de kilobytes.',
        'string'  => 'A :attribute não pode ser superior a :max caracteres.',
        'array'   => 'A :attribute não pode ter mais do que :max itens.',
    ],
    'mimes' => 'A :atributo deve ser um arquivo do tipo: :values.',
    'min'                  => [
       'numeric' => 'A :attribute deve ser de pelo menos :min.',
       'file'    => 'A :attribute deve ser de pelo menos :min kilobytes.',
       'string'  => 'A :attribute deve ser de pelo menos :min caracteres.',
       'array'   => 'O :attribute deve ter pelo menos :min itens.',
    ],
    'not_in'               => 'O selecionado :attribute é inválido.',
    'numeric'              => 'A :attribute deve ser um número.',
    'regex'                => 'A :attribute formato é inválido.',
    'required'             => 'O :attribute do campo é obrigatório.',
    'required_if'          => 'A campo de attribute é obrigatório quando :other do :value.',
    'required_with'        => 'A campo de attribute é obrigatório quando :values está presente.',
    'required_with_all'    => 'A campo de attribute é obrigatório quando :values está presente.',
    'required_without'     => 'A campo de attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'A campo de attribute é obrigatório quando nenhum :values estão presentes.',
    'same'                 => 'A :attribute e  :other tem de corresponder.',
    'size'                 => [
       'numeric' => 'A :attribute deve ser :size.',
       'file'    => 'A :attribute deve ser :size kilobytes.',
       'string'  => 'A :attribute deve ser caracteres de :size.',
       'array'   => 'A :attribute deve conter itens de :size.',
    ],
    'string'   => 'A :attribute deve ser uma seqüência de caracteres.',
    'timezone' => 'A :attribute deve ser uma zona válida.',
    'unique'   => 'A :attribute já foi tomada.',
    'url'      => 'A :attribute formato é inválido.',

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
        'name'             => 'Nome',
        'password '        => 'palavra-passe',
        'confirm_password' => 'Confirmar Senha',
        'username'         => 'Endereço De E-Mail',
        'defaultCredits'   => 'Padrão Créditos',
        'credit'           => 'Créditos',
        'credits'          => 'Créditos',
        'packageName'      => 'Nome Do Pacote',
        'amount'           => 'Quantidade',
        'package_name'     => 'Nome Do Pacote',
        'duration'         => 'Duração',
        'title'            => 'Título Do Site',
        'port'             => 'Porta',
        'firstname'        => 'Primeiro Nome',
        'lastname'         => 'Último Nome',
        'gender'           => 'Sexo',
        'dob'              => 'Data de Nascimento',
        'lat'              => 'Latitude',
        'lng'              => 'Longitude',
        'city'             => 'Cidade',
        'country'          => 'País',
        'hereto'           => 'Aqui',
        'password'         => 'Palavra-passe',
        'password_confirm' => 'Confirmar Senha',
    ],

];
