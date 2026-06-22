<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class QuizCryptoHelper
{
    /**
     * Encrypt array/object to secure string
     */
    public static function encryptPayload(array $data): array
    {
        $json = json_encode($data);

        // Secret key from env
        $key = base64_decode(env('QUIZ_SECRET_KEY'));

        // Generate random 16-byte IV
        $iv = random_bytes(16);

        // Encrypt using AES-256-CBC
        $encrypted = openssl_encrypt(
            $json,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return [
            'iv'   => base64_encode($iv),
            'data' => base64_encode($encrypted)
        ];
    }

    /**
     * Decrypt encrypted payload (Laravel-side, optional)
     */
    public static function decryptPayload(string $encryptedBase64, string $ivBase64): array
    {
        $key = base64_decode(env('QUIZ_SECRET_KEY'));
        $iv  = base64_decode($ivBase64);

        $decrypted = openssl_decrypt(
            base64_decode($encryptedBase64),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return json_decode($decrypted, true);
    }
}
