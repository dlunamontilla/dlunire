<?php

if (!function_exists('get_format_currency_en')) {

    /**
     * Devuelve un número en formato de moneda en inglés. En posteriores actualizaciones se agregarán más prefijos de monedas.
     * 
     * @param float $number Número a ser convertido a formato de moneda
     * @param string $currency_code Código o prefijo de la moneda
     * @return string
     */
    function get_format_currency_en(float $number, string $currency_code = 'USD'): string {
        /**
         * Utiliza la función `number_format` para formatear el número como moneda.
         * 
         * @var string $formatted_currency
         */
        $formatted_currency = number_format($number, 2, '.', ',');

        /**
         * Agrega el símbolo de la moneda.
         * 
         * @var string $formatted_currency
         */
        $formatted_currency = sprintf('%s %s', get_currency_symbol($currency_code), $formatted_currency);

        return $formatted_currency;
    }
}

if (!function_exists('get_format_currency_es')) {

    /**
     * Devuelve un número como formato de moneda en español 
     *
     * @param float $number Número a ser procesado y convertido a formato de moneda
     * @param string $currency_code Prefijo de la moneda a utilizarse
     * @return string
     */
    function get_format_currency_es(float $number, string $currency_code = 'VEF'): string {
        /**
         * Utiliza la función `number_format` para formatear el número como moneda.
         * 
         * @var string $formatted_currency
         */
        $formatted_currency = number_format($number, 2, ',', '.');

        /**
         * Agrega el símbolo de la moneda.
         * 
         * @var string $formatted_currency
         */
        $formatted_currency = sprintf('%s %s', get_currency_symbol($currency_code), $formatted_currency);

        return $formatted_currency;
    }
}

if (!function_exists('get_currency_symbol')) {

    /**
     * Devuelve el símbolo de la moneda en función del código o prefijo.
     *
     * @param string $currency_code
     * @return string
     */
    function get_currency_symbol(string $currency_code): string {
        $currency_symbols = [
            'ARS' => '$',
            'BRL' => 'R$',
            'CLP' => '$',
            'COP' => '$',
            'MXN' => '$',
            'PEN' => 'S/',
            'UYU' => '$',
            'VEF' => 'Bs.',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'AUD' => 'A$',
            'CAD' => 'CA$',
        ];

        return isset($currency_symbols[$currency_code]) ? $currency_symbols[$currency_code] : $currency_code;
    }
}
