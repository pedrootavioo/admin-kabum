<?php

namespace Source\Support;

use Source\Core\Db\Model;
use Source\Services\CpfService;

class Validator
{
    private array $errors = [];

    public function validate(Model $model, array $data, array $rules): self
    {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            // Se o valor for vazio e não tiver "required", ignora as outras validações
            if (empty($value) && !in_array('required', $rulesArray)) {
                continue;
            }

            foreach ($rulesArray as $rule) {
                [$ruleName, $parameter] = array_pad(explode(':', $rule), 2, null);

                match ($ruleName) {
                    'required' => $this->required($field, $value),
                    'min' => $this->minLength($field, $value, (int)$parameter),
                    'max' => $this->maxLength($field, $value, (int)$parameter),
                    'email' => $this->email($field, $value),
                    'cpf' => $this->cpf($field, $value),
                    'date' => $this->date($field, $value),
                    'unique' => $this->unique($model, $field, $value),
                    'boolean' => $this->boolean($field, $value),
                    default => null,
                };
            }
        }

        return $this;
    }

    private function required(string $field, mixed $value, string $message = 'Campo obrigatório'): void
    {
        if (empty($value) || empty(trim($value))) {
            $this->errors[$field] = $message;
        }
    }

    private function minLength(string $field, ?string $value, int $min, ?string $message = null): void
    {
        if (empty($value) || strlen(trim($value)) < $min) {
            $this->errors[$field] = $message ?? "Mínimo de {$min} caracteres";
        }
    }

    private function maxLength(string $field, ?string $value, int $max, ?string $message = null): void
    {
        if (empty($value)) return;

        if (strlen(trim($value)) > $max) {
            $this->errors[$field] = $message ?? "Máximo de {$max} caracteres";
        }
    }

    private function email(string $field, ?string $value, string $message = 'E-mail inválido'): void
    {
        if (empty($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message;
        }
    }

    private function cpf(string $field, ?string $value, string $message = 'CPF inválido'): void
    {
        if (!CpfService::validate($value)) {
            $this->errors[$field] = $message;
        }
    }

    public function date(string $field, ?string $value, string $message = 'Data inválida'): void
    {
        if (empty($value)) return; // Se estiver vazio, não precisa checar a data

        if (!DateHelper::isValid($value, Formatter::DATE_FORMAT_DB)) {
            $this->errors[$field] = $message;
        }
    }

    private function unique(Model $model, string $field, mixed $value, string $message = 'Já existe um registro com esse valor'): void
    {
        if (empty($value)) {
            return;
        }

        $existingRecord = $model::findByCondition("{$field} = :value", ['value' => $value], false);

        // Não existe nenhum registro com esse valor? Tudo certo.
        if (empty($existingRecord)) return;

        $currentId = $model->{$model->primaryKey} ?? null;
        $existingId = $existingRecord->{$model->primaryKey};

        if ($currentId === $existingId) {
            return; // O registro atual é o mesmo que o encontrado, sem conflito
        }

        $this->errors[$field] = $message;
    }


    private function boolean(string $field, ?string $value): void
    {
        if (!in_array($value, ['0', '1', 'true', 'false'], true)) {
            $this->errors[$field] = 'Valor inválido';
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function error(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }
}