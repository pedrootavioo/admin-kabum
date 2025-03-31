<?php

namespace Source\Support;

class Helper
{
    public static function dd() : void
    {
        echo "<pre>";
        foreach (func_get_args() as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }
}