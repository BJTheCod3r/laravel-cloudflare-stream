<?php

namespace Bjthecod3r\CloudflareStream\Params;

/**
 * Class QueryParams
 *
 * @package Bjthecod3r\CloudflareStream\QueryParams
 */
abstract class QueryParams
{
    /**
     * Convert the object properties to an array in snake_case
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = get_object_vars($this);

        // Remove null properties
        $array = array_filter($array, function ($value) {
            return !is_null($value);
        });

        // Convert camelCase to snake_case
        return array_combine(
            array_map(function ($key) {
                return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
            }, array_keys($array)),
            $array
        );
    }
}
