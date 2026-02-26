<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Session Hardening (MUST come BEFORE session_start)
|--------------------------------------------------------------------------
*/

ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

// Set to true only if using HTTPS
define('COOKIE_SECURE', false);

if (COOKIE_SECURE) {
    ini_set('session.cookie_secure', '1');
}

/*
|--------------------------------------------------------------------------
| Start Session (AFTER ini_set)
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'Land Registry DApp');