<?php
/**
 * This file is part of kasapi-php. 
 * 
 * File: KasApiHelper.php
 *
 * User: dherrman
 * Date: 27.11.2014
 * Time: 15:37
 *
 * Purpose: Please fill...
 */

namespace KasApi;

class KasApiHelper {

    /**
     * Whether a haystack string ends with a needle string
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     * @author Elias Kuiter
     */
    static function ends_with($haystack, $needle) {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

} 