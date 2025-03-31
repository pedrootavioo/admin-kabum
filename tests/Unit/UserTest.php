<?php

namespace Tests\Unit;

use Source\Core\Db\Connect;
use Source\Models\Person;
use Source\Models\User;
use Source\Services\CpfService;
use Source\Support\Formatter;
use function Test\Helpers\seedAdminUser;
use function Test\Helpers\truncateTables;

beforeEach(function () {

    $pdo = Connect::getConnection();
    truncateTables($pdo, ['users', 'clients', 'persons','addresses']);
    seedAdminUser();

    $this->user = new User();
    $this->data = [
        'name' => 'João da Silva',
        'email' => rand(1, 99999) . 'joao@email.com',
        'document' => CpfService::generate(),
        'identity' => 'MG123456',
        'birthdate' => '01/11/1990',
        'password' => '123456',
        'confirm_password' => '123456'
    ];
});

it('should create a user linked to a person with valid field formats', function () {

    /** @var User $userCreatedWithPerson */
    $userCreatedWithPerson = $this->user->persist($this->data);

    /** @var Person $person */
    $person = $userCreatedWithPerson->person();

    expect($userCreatedWithPerson)
        ->toBeInstanceOf(User::class)
        ->and($userCreatedWithPerson->password)->toBeString()
        ->and($person)->toBeInstanceOf(Person::class)
        ->and($person->name)->toBe(Formatter::uppercase($this->data['name']))
        ->and($person->email)->toBe(Formatter::lowercase($this->data['email']))
        ->and($person->birthdate)->toBe(Formatter::dateFormat($person->birthdate, Formatter::DATE_FORMAT_DB)
        );
});

it('should not save user when password inputs are not equals', function () {

    $this->data['confirm_password'] = '1234567';

    $userCreatedWithPerson = $this->user->persist($this->data);

    expect($userCreatedWithPerson)
        ->toBeFalse()
        ->and($this->user->errors())->toHaveKey('confirm_password');
});
