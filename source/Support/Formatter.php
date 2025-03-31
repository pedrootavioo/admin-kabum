<?php

namespace Source\Support;

use DateTime;

class Formatter
{
    public const DATE_FORMAT_DB = 'Y-m-d';
    public const DATE_FORMAT_DISPLAY = 'd/m/Y';

    /**
     * Aplica as regras definidas ao valor informado.
     *
     * As regras esperadas são, por exemplo:
     * - "string": aplica trim.
     * - "uppercase": converte para maiúsculas.
     * - "date" ou "date:FORMATO": converte para o formato especificado (padrão 'Y-m-d').
     * - "encrypted": aplica password_hash().
     *
     * @param mixed $value Valor a ser formatado.
     * @param array $rules Array de regras (ex: ['string', 'uppercase', 'date:Y-m-d', 'encrypted']).
     * @return mixed Valor formatado.
     */
    public static function applyRules(mixed $value, array $rules): mixed
    {
        foreach ($rules as $rule) {

            if (str_starts_with($rule, 'date')) {
                $value = self::dateFormat($value, $rule);
            } else {
                $value = match ($rule) {
                    'string' => self::string($value),
                    'uppercase' => self::uppercase($value),
                    'lowercase' => self::lowercase($value),
                    'encrypted' => self::encrypted($value),
                    'numeric' => self::numeric($value),
                    default => $value,
                };
            }
        }
        return $value;
    }

    public static function string(mixed $value): string
    {
        if (empty($value)) return '';
        return trim($value);
    }

    public static function uppercase(mixed $value): string
    {
        if (empty($value)) return '';
        return mb_strtoupper($value);
    }

    public static function lowercase(mixed $value): string
    {
        if (empty($value)) return '';
        return mb_strtolower($value);
    }

    public static function encrypted(mixed $value): string
    {
        if (empty($value)) return '';
        return password_hash($value, PASSWORD_DEFAULT);
    }

    public static function numeric(mixed $value): string
    {
        if (empty($value)) return '';
        return preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Formata a data de acordo com a regra definida.
     *
     * A regra de data deve estar no formato "date:saveFormat,displayFormat"
     * Exemplo: "date:Y-m-d,d/m/Y" - valor salvo como "Y-m-d" e exibido como "d/m/Y".
     *
     * @param mixed $value Valor da data (no formato de salvamento, ex.: "2025-03-28").
     * @param string $rule Regra definida para o campo (ex.: "required|date:Y-m-d,d/m/Y").
     * @return string|null Data formatada ou null se inválida.
     */
    public static function dateFormat(mixed $value, string $rule): ?string
    {
        if (!DateHelper::isValid($value, self::DATE_FORMAT_DISPLAY)) {
            return $value; // Data inválida, retorna o valor como está para que o validator lide com isso
        }

        $parts = explode(':', $rule);
        if (count($parts) < 2) {
            return $value; // Sem formato definido, retorna o valor como está
        }

        $formats = explode(',', $parts[1]);
        $saveFormat = trim($formats[0] ?? self::DATE_FORMAT_DB); // Formato para salvar (padrão 'Y-m-d')

        if (empty($value)) {
            return null;
        }

        // Se o valor contém '/', provavelmente está no formato "d/m/Y"
        if (str_contains($value, '/')) {
            return DateHelper::convert($value, self::DATE_FORMAT_DISPLAY, $saveFormat);
        }

        // Caso contrário, tentamos normalizar a data a partir do formato padrão
        return DateHelper::normalize($value, $saveFormat);
    }

    /**
     * Aplica o formato de exibição para datas.
     *
     * A regra de data deve estar no formato "date:saveFormat,displayFormat"
     * Exemplo: "date:Y-m-d,d/m/Y" - valor salvo como "Y-m-d" e exibido como "d/m/Y".
     *
     * @param mixed $value Valor da data (no formato de salvamento, ex.: "2025-03-28").
     * @param string $rulesString A string de regras definida para o campo (ex.: "required|date:Y-m-d,d/m/Y").
     * @return string|null Data formatada para exibição ou null se inválida.
     */
    public static function displayDate(mixed $value, string $rulesString): ?string
    {
        // Se não houver valor, retorna null
        if (empty($value)) {
            return null;
        }

        // Separa as regras
        $rules = explode('|', $rulesString);
        foreach ($rules as $rule) {
            if (str_starts_with($rule, 'date')) {
                $parts = explode(':', $rule);
                if (isset($parts[1])) {
                    $formats = explode(',', $parts[1]);
                    // O primeiro formato é o de salvamento, o segundo (se existir) é o de exibição.
                    $displayFormat = isset($formats[1]) ? trim($formats[1]) : self::DATE_FORMAT_DISPLAY;
                    try {
                        $dt = new DateTime($value);
                        return $dt->format($displayFormat);
                    } catch (\Exception $e) {
                        return $value;
                    }
                }
            }
        }
        return $value;
    }
}