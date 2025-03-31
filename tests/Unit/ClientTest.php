<?php

namespace Tests\Unit;

use Source\Models\Client;
use Source\Models\Person;
use Source\Services\CpfService;
use Source\Support\Formatter;

beforeEach(function () {

    $this->client = new Client();
    $this->data = [
        'name' => 'João da Silva',
        'email' => rand(1, 99999) . 'joao@email.com',
        'document' => CpfService::generate(),
        'identity' => 'MG123456',
        'birthdate' => '01/11/1990'
    ];
});

it('should create a client linked to a person with valid field formats', function () {

    /** @var Client $clientCreatedWithPerson */
    $clientCreatedWithPerson = $this->client->persist($this->data);

    /** @var Person $person */
    $person = $clientCreatedWithPerson->person();

    expect($clientCreatedWithPerson)
        ->toBeInstanceOf(Client::class)
        ->and($person)->toBeInstanceOf(Person::class)
        ->and($person->name)->toBe(Formatter::uppercase($this->data['name']))
        ->and($person->email)->toBe(Formatter::lowercase($this->data['email']))
        ->and($person->birthdate)->toBe(Formatter::dateFormat($person->birthdate, Formatter::DATE_FORMAT_DB)
        );
});
