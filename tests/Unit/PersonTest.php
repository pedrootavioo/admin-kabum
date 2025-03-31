<?php

namespace Tests\Unit;

use Source\Core\Db\Model;
use Source\Models\Person;
use Source\Services\CpfService;
use Source\Support\Formatter;


beforeEach(function () {
    $this->person = new Person();
    $this->person->name = 'João da Silva';
    $this->person->email = rand(1, 99999) . 'joao@email.com';
    $this->person->document = CpfService::generate();
    $this->person->identity = 'MG123456';
    $this->person->birthdate = '01/11/1990';
});

it('can create a person', function () {

    $personSaved = $this->person->save();

    expect($personSaved)->toBeInstanceOf(Model::class)
        ->and($personSaved->name)->toBe(Formatter::uppercase($this->person->name))
        ->and($personSaved->email)->toBe(Formatter::lowercase($this->person->email))
        ->and($personSaved->birthdate)->toBe(Formatter::dateFormat($this->person->birthdate, Formatter::DATE_FORMAT_DB));

});

test('should person with empty name should not be saved', function () {

    $this->person->name = '';
    $result = $this->person->save();

    expect($result)->toBeFalse()
        ->and($this->person->errors())->toHaveKey('name');
});

test('should person with invalid cpf should not be saved', function () {

    $this->person->document = '00000000000'; // CPF inválido
    $result = $this->person->save();

    expect($result)->toBeFalse()
        ->and($this->person->errors())->toHaveKey('document');
});

test('should person with invalid birthdate should not be saved', function () {

    $this->person->birthdate = '01/13/1999'; // Data inválida
    $result = $this->person->save();

    expect($result)->toBeFalse()
        ->and($this->person->errors())->toHaveKey('birthdate');
});
