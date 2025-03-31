<?php

namespace Source\Services;

class CpfService
{
    public static function generate(bool $withDots = false): string
    {
        // Gera os 9 primeiros dígitos do CPF
        $n = array();
        for ($i = 0; $i < 9; $i++) {
            $n[] = rand(0, 9);
        }

        // Calcula o primeiro dígito verificador
        $d1 = 0;
        for ($i = 0; $i < 9; $i++) {
            $d1 += $n[$i] * (10 - $i);
        }
        $d1 = 11 - ($d1 % 11);
        if ($d1 >= 10) {
            $d1 = 0;
        }

        // Calcula o segundo dígito verificador
        $d2 = 0;
        for ($i = 0; $i < 9; $i++) {
            $d2 += $n[$i] * (11 - $i);
        }
        $d2 += $d1 * 2;
        $d2 = 11 - ($d2 % 11);
        if ($d2 >= 10) {
            $d2 = 0;
        }

        if (!$withDots) {
            return sprintf('%s%s%s%s', implode('', array_slice($n, 0, 3)), implode('', array_slice($n, 3, 3)), implode('', array_slice($n, 6, 3)), $d1 . $d2);
        }

        return sprintf('%s.%s.%s-%s%s', implode('', array_slice($n, 0, 3)), implode('', array_slice($n, 3, 3)), implode('', array_slice($n, 6, 3)), $d1, $d2);
    }

    public static function validate(string $cpf): bool
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) !== 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcula os dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}