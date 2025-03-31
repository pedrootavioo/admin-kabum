<?php

namespace Source\Support;

use DateTime;
use Exception;

class DateHelper
{
    /**
     * Verifica se uma data é válida com base em um formato específico.
     *
     * @param string $date Data a ser verificada.
     * @param string $format Formato esperado (padrão: 'Y-m-d').
     * @return bool Retorna true se a data for válida, false caso contrário.
     */
    public static function isValid(?string $date, string $format = 'Y-m-d'): bool
    {
        if (empty($date)) return false; // Data vazia não é válida

        $dt = DateTime::createFromFormat($format, $date);
        return $dt && $dt->format($format) === $date;
    }

    /**
     * Converte uma data de um formato para outro.
     *
     * @param string $date Data a ser convertida.
     * @param string $fromFormat Formato de entrada (padrão: 'd/m/Y').
     * @param string $toFormat Formato de saída (padrão: 'Y-m-d').
     * @return string|null Data convertida ou null se a conversão falhar.
     */
    public static function convert(string $date, string $fromFormat = 'd/m/Y', string $toFormat = 'Y-m-d'): ?string
    {
        $dt = DateTime::createFromFormat($fromFormat, $date);
        if (!$dt) {
            return null;
        }
        return $dt->format($toFormat);
    }

    /**
     * Normaliza uma data, tentando criar um objeto DateTime a partir da string e
     * retornando-a formatada no padrão desejado.
     *
     * @param string $date Data a ser normalizada.
     * @param string $outputFormat Formato de saída (padrão: 'Y-m-d').
     * @return string|null Data formatada ou null se inválida.
     */
    public static function normalize(string $date, string $outputFormat = 'Y-m-d'): ?string
    {
        try {
            $dt = new DateTime($date);
            return $dt->format($outputFormat);
        } catch (Exception $e) {
            return null;
        }
    }
}
