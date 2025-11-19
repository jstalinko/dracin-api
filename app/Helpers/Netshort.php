<?php
namespace App\Helpers;

Class Netshort{

    public  static $langArray = [
        'in' => 'id_ID',
        'en' => 'en_US',
        'ja' => 'ja_JP',
        'ko' => 'ko_KR',
        'es' => 'es_ES',
        'fr' => 'fr_FR',
        'pt' => 'pt_PT',
        'th' => 'th_TH',
        'ar' => 'ar_SA',
        'de' => 'de_DE',
        'zh' => 'zh_CN',
        'zhHans' => 'zh_TW',
    ];

    public static $publicKey= 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2poXMstZ8NCWE7915MXzDWC5/t+oB2waGfskPqSZwLqxd4ZBR0H1cb1tAZRZcV7P+LmOd6SYNxhnELaWuKTD+D3xkz8Tt1L5j/ynGqVt1MDbiQIEzXQKUkNDSH6T0A+Xzo/67/8QOQXlVJfW06resbaeNvibfx6Qc78j96bCIPlxPrtieilVTBHUFOXjirxK/ki/mO8P2smRbpt73fsQWdGmTGMfYGvfPApGyxbxLkL/qrBjU25XpM8a0MBqzFWUAchHmqSBJ6Mbfam1SSgf3b2U28s67nOW+JiOrhd6iVLcsLFxXA54HX+Zbej3AbOB6jKaEmp/bz1amneE1NYXwwIDAQAB';
    public static $privateKey = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCK0Tl1pd7bjTRU93bWoHW1hLCDj2+9bg1MgY8j5C7xXaw6bJfToXhWbH1fXNbnFFVqxyYNErcuOUwJZxyDgcxUXM4yWnRseb2GF97GOicAQ2keDzVYmwky4lrSRwvcXutJRLPUCRQNfc6upfk2G5TKh6/CcP4TV1eXTF7+vdEw2SHxAOITKbSfcaZXr/hVs6a1aRHsBF+7RG99ebwZIP6/AgIyqX9RbDVN6ixi1v2G3/bwAULHLSqGdSaqij/ca17fbFGITaeCeEaZ6d/P4ZuOK+PEPdbPQt6SbY4lZaYwRvdrpH73kigPITgDzIDONFybJ1m7wRKlq1wxWHwbimptAgMBAAECggEAPz3cYJXFtt5YphDrahJGLgEabYVOUc2ub1li/eX54OpdCWzpqneYnD7myyg/m5zu4SuDUVdibsOZuXrpSZw7m3+ATP5apgS8bDe5vTNHC16qqBAjrI9NHIp09/F4HNh9dq6/Am10XkUfgP+KTrU4DyDL2NijV+pltD8N1B5kDE1igokVcsavhnu2INoMRXYE78Wq6urNECuFWw9hldv81M9m2w56t1CQOUukpo4mfmLjZRe2s+kwtcBVefGHP8Cj0OeH2dGltjl2YSQMRBFUCVoixYpOrcjIHoqzWri8IfUZ2tW+nUvHl5IZ9RVxefnFaLGnxiXd2sk6Sn4aD/l9YQKBgQDVv3HaOZxHRqlNSPrNGqplGhE066HnDsq6MlPukiovxE43CRBmpTnk9zDCqrDh9t2HbJuao7nSq5WlBERWgwqXU/qDpH43W7Y/lJfHkDv6A2m0viJa0a9x8+CJpNnCDu1ATo4/IQKwoXYice6JKnUyXgkGKn+HipiN6tO0EtWHlQKBgQCmQfklKFtXtm/FZ6NIMs+d+EyvaE5xNLKGYQxmiCR10WGYd8ZV+K0Q6qXHS+a32TirWB9F3TqPOklTytMrfPZB3BCXj4weEldb8W716G8FYf7LLhaT+MdpF7KDcruObwoQAvKV3N4eX6tUEMmdrx9hpCmmIU5EeXUkhGdmwk7BeQKBgAIXMkThJV8pGMTRvuo8pYgBnkN3PoklAuSZU2rU8Sawc9dj9k4atZtAs7BjvQEoyffmHwt/KHUgCoGnrgdulq7uOlgJRtbBxeGPUYC5L2z9lY4YAfwDawThTsPp4dtdDAMCAbAqYX1axu4FUUD0MltAwjPWPJMVzvIsZs+vE3mVAoGAJPja3OaCmZjadj2709xoyypic0dw2j/ry3JdfZec9A5h87P/CTNJ2U81GoLIhe3qakAohDLUSPGfSOD74NnjMXYswmeLs0xE3Q9tq4XK2pmWPby8DJ/wSHCapByplN0gkbr2E1mQk5SW1xT8oPJGukH1eRpC+3s/D6XaEMH5HZECgYEAigoX5l39LDsCgeaUcI4S9grkaas/WsKv37eqo3oD9Qk6VFiMM5L5Zig6aXJxuAPLVjb38caJRPmPmOXLT2kEP1E1h6OJOhEhETwVIUtcBzsK25ju9LqL89bC+W0uS7BPvk6Tcws/tXHCkQCTgb9jVXceZ2ox+6axvlW/5WgHt5Q=';

    public static $token;
     public static function generateDeviceId()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public static function generateRandomModel(): string
    {
        $models = [
            'Samsung Galaxy S24',
            'Google Pixel 8 Pro',
            'OnePlus 12',
            'Xiaomi 14 Ultra',
        ];
        return $models[array_rand($models)];
    }

    public static function generateRandomHeaders(string $lang): array
    {
        return [
            'User-Agent' => 'okhttp/4.10.0',
            'Accept-Encoding' => 'gzip',
            //'Content-Type'     => 'application/json',
            'Authorization' => "Bearer ".self::$token,
            'canary' => 'v1',
            'version' => '1.6.8',
            'Device-Code' => bin2hex(random_bytes(8)),
            'timestamp' => now()->timestamp * 1000,
            'content-language' => self::$langArray[$lang],
            'os' => '1',
        ];
    }
      public static function generatePayload($data)
    {
        $jsonBody = json_encode($data);
        $aesKey = self::generateRandomString(16);
        $encryptedData = self::AESEncrypt($jsonBody, $aesKey);
        $encryptedKey = self::RSAEncrypt(base64_encode($aesKey));

        return [
            'data' => $encryptedData,
            'key' => $encryptedKey,
        ];
    }

    public static function decryptBodyResponse($response)
    {
        $headers = $response->headers();
        $body = $response->body();
        $decryptedBase64AesKey = self::RSADecrypt($headers['encrypt-key'][0]);
        $decryptedAesKey = base64_decode($decryptedBase64AesKey);
        $decryptedBody = self::AESDecrypt($body, $decryptedAesKey);
        $decryptedBody = json_decode($decryptedBody, true);

        return $decryptedBody;
    }
      public static function generateRandomString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $result;
    }

    public static function AESEncrypt($data, $key)
    {

        $key128 = substr($key, 0, 16);
        $encrypted = openssl_encrypt(
            $data,
            'AES-128-ECB',
            $key128,
            OPENSSL_RAW_DATA
        );

        if ($encrypted === false) {
            return '';
        }

        return base64_encode($encrypted);
    }

    public static function RSAEncrypt($dataToEncrypt)
    {
        $formattedPublicKey = "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split(self::$publicKey, 64, "\n")
            . "-----END PUBLIC KEY-----";

        $publicKeyResource = openssl_pkey_get_public($formattedPublicKey);

        if ($publicKeyResource === false) {
            return 'jembot';
        }

        $encrypted = '';
        $success = openssl_public_encrypt($dataToEncrypt, $encrypted, $publicKeyResource, OPENSSL_PKCS1_PADDING);

        if (!$success) {
            return 'gagal mbot';
        }

        return base64_encode($encrypted);
    }

    public static function AESDecrypt($encryptedBase64Data, $key)
    {
        $keyLength = strlen($key);
        $cipher = match ($keyLength) {
            16 => 'AES-128-ECB',
            24 => 'AES-192-ECB',
            32 => 'AES-256-ECB',
            default => null,
        };

        if ($cipher === null) {
            return null;
        }

        $encryptedData = base64_decode($encryptedBase64Data);
        if ($encryptedData === false) {
            return null;
        }

        $decrypted = openssl_decrypt($encryptedData, $cipher, $key, OPENSSL_RAW_DATA);
        return $decrypted !== false ? $decrypted : null;
    }

    public static function RSADecrypt($encryptedBase64Data)
    {
        $formattedPrivateKey = "-----BEGIN PRIVATE KEY-----\n" . chunk_split(self::$privateKey, 64, "\n") . "-----END PRIVATE KEY-----";

        // Mengosongkan antrian error OpenSSL sebelum mencoba
        while (openssl_error_string() !== false) {
        }

        $privateKeyResource = openssl_pkey_get_private($formattedPrivateKey);
        if ($privateKeyResource === false) {
            $error = "ERROR: Gagal memuat Private Key. OpenSSL: " . openssl_error_string();
            return $error;
        }

        $encryptedData = base64_decode($encryptedBase64Data);
        if ($encryptedData === false) {
            return "ERROR: Gagal melakukan Base64 decode pada header encrypt-key.";
        }

        $decrypted = '';
        $success = openssl_private_decrypt($encryptedData, $decrypted, $privateKeyResource, OPENSSL_PKCS1_PADDING);

        if (!$success) {
            $error = "ERROR: Gagal melakukan dekripsi RSA. OpenSSL: " . openssl_error_string();
            return $error;
        }

        return $decrypted;
    }
}

?>