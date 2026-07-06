<?php
/**
 * Simple SMTP password encryption helpers.
 *
 * Behavior:
 * - Primary key source: environment variable CITECRM_SMTP_KEY
 * - Fallback: file at FILE_ROOT . 'conf_smtp.key' or ./conf_smtp.key
 * - Encrypted value stored as: ENC:<base64(iv + ciphertext)>
 */

function citecrm_get_smtp_key()
{
    $key = getenv('CITECRM_SMTP_KEY');
    if ($key !== false && $key !== '') return $key;

    // try file locations
    if (defined('FILE_ROOT')) {
        $p = rtrim(FILE_ROOT, "\/\\") . DIRECTORY_SEPARATOR . 'conf_smtp.key';
        if (is_file($p)) return trim(file_get_contents($p));
    }
    $p2 = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'conf_smtp.key';
    if (is_file($p2)) return trim(file_get_contents($p2));

    // no key available
    return null;
}

function citecrm_encrypt_smtp_pass($plaintext)
{
    if (!function_exists('openssl_random_pseudo_bytes') || !function_exists('openssl_encrypt')) return null;
    $key = citecrm_get_smtp_key();
    if (empty($key)) return null;

    $cipher = 'aes-256-cbc';
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    if ($ciphertext === false) return null;
    return 'ENC:' . base64_encode($iv . $ciphertext);
}

function citecrm_decrypt_smtp_pass($stored)
{
    if (!is_string($stored) || substr($stored, 0, 4) !== 'ENC:') return $stored;
    $b = base64_decode(substr($stored, 4));
    if ($b === false) return null;
    if (!function_exists('openssl_decrypt')) return null;
    $key = citecrm_get_smtp_key();
    if (empty($key)) return null;
    $cipher = 'aes-256-cbc';
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($b, 0, $ivlen);
    $ciphertext = substr($b, $ivlen);
    $plain = openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return $plain === false ? null : $plain;
}

?>
