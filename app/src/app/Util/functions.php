<?php

namespace App\Util;

use Exception;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Get a unique string (based on cryptographically secure pseudo-random function).
 *
 * @param int $length
 * @param string $alphabet
 * @return string
 * @throws InvalidArgumentException
 * @throws Exception
 */
function str_unique(int $length = 40, string $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'): string
{
    if ($length < 1) {
        throw new InvalidArgumentException('Length parameter value should be greater than or equals to 1.');
    }

    $str = '';

    $alphabetLength = Str::length($alphabet);

    for ($i = 0; $i < $length; $i++) {
        $str .= $alphabet[random_int(0, $alphabetLength - 1)];
    }

    return $str;
}

/**
 * Remove all malicious code from HTML string.
 *
 * @param mixed $html
 * @return string
 */
function html_purify($html)
{
    $value = value($html);

    if (is_null($value)) {
        return null;
    }

    return app()->make('HTMLPurifier')->purify($value);
}

/**
 * Remove keys from an array that are not exists in a structure array.
 *
 * @example array_filter_structure(['key_0' => 'value', 'key_1' => 'value'], ['key_0']); // ['key_0' => 'value']
 *
 * @param array $array
 * @param array $structure
 * @return array
 */
function array_filter_structure(array $array, array $structure): array
{
    $result = [];

    foreach ($array as $key => $value) {
        if (array_key_exists($key, $structure)) {
            $result[$key] = is_array($value) && is_array($structure[$key]) ? array_filter_structure($value, $structure[$key]) : $value;
        } elseif (is_int($key) && array_key_exists('*', $structure)) {
            $result[$key] = is_array($value) && is_array($structure['*']) ? array_filter_structure($value, $structure['*']) : $value;
        } elseif (in_array($key, $structure, true)) {
            $result[$key] = $value;
        }
    }

    return $result;
}

/**
 * Convert a single-dimensional array with keys in "dot" notation into a multi-dimensional array.
 *
 * @example array_remove_dot_notation(['key_0.key_1' => 'value']); // ['key_0' => ['key_1' => 'value']]
 *
 * @param array $array
 * @return array
 */
function array_remove_dot_notation(array $array): array
{
    $result = [];

    foreach ($array as $key => $value) {
        array_set($result, $key, $value);
    }

    return $result;
}

/**
 * Remove attributes that have no validation rules.
 *
 * @param array $attributes
 * @param array $rules
 * @return array
 */
function validator_filter_attributes(array $attributes, array $rules): array
{
    return array_filter_structure($attributes, array_remove_dot_notation(array_flip(array_keys($rules))));
}

/**
 * Get file storage url.
 *
 * @param string $path
 * @return string
 */
function url_storage(string $path): string
{
    return config('main.storage.url') . $path;
}

/**
 * Get main frontend page url.
 *
 * @param string $path
 * @return string
 */
function url_frontend(string $path = ''): string
{
    return config('main.frontend.url') . $path;
}

/**
 * Get sign in frontend page url.
 *
 * @return string
 */
function url_frontend_sign_in(): string
{
    return url_frontend('/sign-in');
}

/**
 * Get photo frontend page url.
 *
 * @param int $id
 * @return string
 */
function url_frontend_photo(int $id): string
{
    return url_frontend(sprintf('/photo/%s', urlencode($id)));
}

/**
 * Get search by tag frontend page url.
 *
 * @param string $tag
 * @return string
 */
function url_frontend_tag(string $tag): string
{
    return url_frontend(sprintf('/photos/tag/%s', urlencode($tag)));
}

/**
 * Get unsubscription frontend page url.
 *
 * @param string $token
 * @return string
 */
function url_frontend_unsubscription(string $token): string
{
    return url_frontend(sprintf('/unsubscription/%s', urlencode($token)));
}
