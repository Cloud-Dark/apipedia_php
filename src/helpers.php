<?php

if (!function_exists('apipedia')) {
    /**
     * Create a new Apipedia instance.
     *
     * @param string $appkey The application key
     * @param string $authkey The authentication key
     * @return \Apipedia\Apipedia
     */
    function apipedia(string $appkey, string $authkey): \Apipedia\Apipedia
    {
        return new \Apipedia\Apipedia($appkey, $authkey);
    }
}
