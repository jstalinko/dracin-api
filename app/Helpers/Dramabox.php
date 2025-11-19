<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class Dramabox
{
    public static function replaceEnv(string $search, string $replace): void
    {
        $env_path = base_path('.env');

        if (File::exists($env_path)) {
            $env_content = File::get($env_path);

            if (strpos($env_content, $search) !== false) {
                $env_content = preg_replace("/^{$search}.*$/m", $replace, $env_content);
            } else {
                $env_content .= "\n{$replace}\n";
            }

            File::put($env_path, $env_content);
        } else {
            throw new \Exception('File .env tidak ditemukan.');
        }
    }

    public static function getEnv(string $key): ?string
    {
        $env_path = base_path('.env');

        if (File::exists($env_path)) {
            $env_content = File::get($env_path);

            if (preg_match("/^{$key}=(.*)$/m", $env_content, $matches)) {
                return str_replace("\r", "", $matches[1]);
            }
        }

        return null;
    }

    public static function getProxies()
    {
        $filePath = app_path('Helpers/Proxies');
        if (file_exists($filePath)) {
            try {
                $proxies = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $selectProxy = explode(":", $proxies[array_rand($proxies)]);
                $ip = $selectProxy[0];
                $port = $selectProxy[1];
                $username = $selectProxy[2];
                $password = $selectProxy[3];
                return "http://$username:$password@$ip:$port";
            } catch (\Throwable $th) {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function getTokens()
    {
        $filePath = app_path('Helpers/Tokens');
        if (file_exists($filePath)) {
            $tokens = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            return $tokens[array_rand($tokens)];
        } else {
            return null;
        }
    }
    public static function getRandomDeviceModel(): string
    {
        $models = [
            'Redmi Note 7',
            'Samsung Galaxy S21',
            'Google Pixel 6',
            'OnePlus 9 Pro',
            'Realme GT Neo 2',
            'Xiaomi 12 Pro',
            'Oppo Find X5 Pro',
            'Vivo X80 Pro',
        ];
        return $models[array_rand($models)];
    }
    public static function generateRandomCid()
    {
        return "DRA1000000";
        $random_number = mt_rand(1000000, 9999999);
        $cid = "DRA$random_number";
        return $cid;
    }
    public static function generateRandomAndroidId(): string
    {
        return str_pad(dechex(mt_rand(0, 0xFFFFFFFF)) . dechex(mt_rand(0, 0xFFFFFFFF)) . dechex(mt_rand(0, 0xFFFFFFFF)) . dechex(mt_rand(0, 0xFFFFFFFF)), 16, '0', STR_PAD_LEFT);
    }
    public static function getPrivateKey()
    {
        $fullKeyString = "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC9Q4Y5QX5j08HrnbY3irfKdkEllAU2OORnAjlXDyCzcm2Z6ZRrGvtTZUAMelfU5PWS6XGEm3d4kJEKbXi4Crl8o2E/E3YJPk1lQD1d0JTdrvZleETN1ViHZFSQwS3L94Woh0E3TPebaEYq88eExvKu1tDdjSoFjBbgMezySnas5Nc2xF28XhPuC8m15u+dectsrJl+ALGcTDX3Lv3FURuwV/dN7WMEkgcseIKVMdJxzUB0PeSqCNftfxmdBV/U4yXFRxPhnSFSXCrkj6uJjickiYq1pQ1aZfrQe1eLD3MB2hKq7crhMcA3kpggQlnmy1wRR4BAttmSU4fPb/yF8D3hAgMBAAECggEBAJdru6p5RLZ3h/GLF2rud8bqv4piF51e/RWQyPFnMAGBrkByiYT7bFI3cnvJMhYpLHRigqjWfUofV3thRDDym54lVLtTRZ91khRMxgwVwdRuk8Fw7JNFenOwCJxbgdlq6iuAMuQclwll7qWUrm8DgMvzH93xf8o6X171cp4Sh0og1Ra7E9GZ37dzBlX2aJBK8VFfctZntuDPx52e71nafqfbjXxZuEtpu92oJd6A9mWbd0BZTk72ZHUmDcKcqjfcEH19SWOphMJFYkxU5FRoIEr3/zisyTO4Mt33ZmwELOrY9PdlyAAyed7ZoH+hlTr7c025QROvb2LmqgRiUT56tMECgYEA+jH5m6iMRK6XjiBhSUnlr3DzRybwlQrtIj5sZprWe2my5uYHG3jbViYIO7GtQvMTnDrBCxNhuM6dPrL0cRnbsp/iBMXe3pyjT/aWveBkn4R+UpBsnbtDn28r1MZpCDtr5UNc0TPj4KFJvjnV/e8oGoyYEroECqcw1LqNOGDiLhkCgYEAwaemNePYrXW+MVX/hatfLQ96tpxwf7yuHdENZ2q5AFw73GJWYvC8VY+TcoKPAmeoCUMltI3TrS6K5Q/GoLd5K2BsoJrSxQNQFd3ehWAtdOuPDvQ5rn/2fsvgvc3rOvJh7uNnwEZCI/45WQg+UFWref4PPc+ArNtp9Xj2y7LndwkCgYARojIQeXmhYZjG6JtSugWZLuHGkwUDzChYcIPdW25gdluokG/RzNvQn4+W/XfTryQjr7RpXm1VxCIrCBvYWNU2KrSYV4XUtL+B5ERNj6In6AOrOAifuVITy5cQQQeoD+AT4YKKMBkQfO2gnZzqb8+ox130e+3K/mufoqJPZeyrCQKBgC2fobjwhQvYwYY+DIUharri+rYrBRYTDbJYnh/PNOaw1CmHwXJt5PEDcml3+NlIMn58I1X2U/hpDrAIl3MlxpZBkVYFI8LmlOeR7ereTddN59ZOE4jY/OnCfqA480Jf+FKfoMHby5lPO5OOLaAfjtae1FhrmpUe3EfIx9wVuhKBAoGBAPFzHKQZbGhkqmyPW2ctTEIWLdUHyO37fm8dj1WjN4wjRAI4ohNiKQJRh3QE11E1PzBTl9lZVWT8QtEsSjnrA/tpGr378fcUT7WGBgTmBRaAnv1P1n/Tp0TSvh5XpIhhMuxcitIgrhYMIG3GbP9JNAarxO/qPW6Gi0xWaF7il7Or";
        $privateKeyPEM = "-----BEGIN PRIVATE KEY-----\n" .
            chunk_split($fullKeyString, 64, "\n") .
            "-----END PRIVATE KEY-----";
        return $privateKeyPEM;
    }

    public static function createSignature($dataToSign, $privateKeyPEM)
    {
        $privateKey = openssl_pkey_get_private($privateKeyPEM);

        if ($privateKey === false) {
            error_log("Kesalahan: Gagal memuat kunci privat.");
            return null;
        }

        $success = openssl_sign($dataToSign, $signatureBinary, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        if ($success) {
            return base64_encode($signatureBinary);
        } else {
            error_log("Kesalahan saat membuat tanda tangan.");
            return null;
        }
    }
    public static   function getRandomAndroidVersion(): string
    {
        $versions = ['10', '11', '12', '13', '14'];
        return $versions[array_rand($versions)];
    }
    public static function getManufacturerFromModel(string $model): string
    {
        if (Str::contains($model, ['Redmi', 'Xiaomi'])) {
            return 'XIAOMI';
        }

        if (Str::contains($model, 'Samsung')) {
            return 'SAMSUNG';
        }

        if (Str::contains($model, 'Google Pixel')) {
            return 'GOOGLE';
        }

        if (Str::contains($model, 'OnePlus')) {
            return 'ONEPLUS';
        }

        if (Str::contains($model, 'Realme')) {
            return 'REALME';
        }

        if (Str::contains($model, 'Oppo')) {
            return 'OPPO';
        }

        if (Str::contains($model, 'Vivo')) {
            return 'VIVO';
        }

        return 'UNKNOWN';
    }
    public static function getBrandFromModel(string $model): string
    {
        if (Str::contains($model, 'Redmi')) {
            return 'Redmi';
        }

        if (Str::contains($model, 'Xiaomi')) {
            return 'Xiaomi';
        }

        if (Str::contains($model, 'Samsung')) {
            return 'Samsung';
        }

        if (Str::contains($model, 'Google Pixel')) {
            return 'Google';
        }

        if (Str::contains($model, 'OnePlus')) {
            return 'OnePlus';
        }

        if (Str::contains($model, 'Realme')) {
            return 'Realme';
        }

        if (Str::contains($model, 'Oppo')) {
            return 'Oppo';
        }

        if (Str::contains($model, 'Vivo')) {
            return 'Vivo';
        }

        return 'UNKNOWN';
    }
    public static function generateRandomHeaders(string $lang, $token = '', $deviceId = '', $androidId = '', $jsonBody = ''): array
    {
        $cid = self::generateRandomCid();
        $selectedModel = self::getRandomDeviceModel();
        $token = strlen($token) > 0 ? "Bearer $token" : '';
        $timestamp = now()->timestamp * 1000;
        $deviceId = $deviceId != '' ? $deviceId : (string) Str::uuid();
        $androidId = $androidId != '' ? $androidId : self::generateRandomAndroidId();

        $snData = "timestamp=$timestamp$jsonBody$deviceId$androidId$token";
        $sn = self::createSignature($snData, self::getPrivateKey());

        $headers = [
            'User-Agent' => 'okhttp/4.10.0',
            'Accept-Encoding' => 'gzip',
            'Content-Type' => 'application/json',
            'tn' => $token,
            'pline' => 'ANDROID',
            'version' => '451',
            'vn' => '4.5.1',
            'userid' => '',
            'cid' => 'DRA1000042',
            'package-name' => 'com.storymatrix.drama',
            'apn' => 2,
            'device-id' => $deviceId,
            'android-id' => $androidId,
            'language' => $lang,
            'current-language' => $lang,
            'p' => '43',
            'local-time' => now()->format('Y-m-d H:i:s.v O'),
            'time-zone' => '+0800',
            'md' => $selectedModel,
            'ov' => self::getRandomAndroidVersion(),
            'mf' => self::getManufacturerFromModel($selectedModel),
            'tz' => '-480',
            'brand' => self::getBrandFromModel($selectedModel),
            'srn' => '1080x2340',
            'ins' => '',
            'mbid' => '',
            'mchid' => '',
            'nchid' => $cid,
            'lat' => '0',
            'build' => 'Build/TQ3C.230805.001.B2',
            'locale' => 'en_US',
            'over-flow' => 'new-fly',
            'afid' => '1756119656048-1239764007359349262',
            'instanceid' => '65fe08aec2022244139d6a062313cbc5',
            'country-code' => '',
            'store-source' => 'store_google',
            'is_vpn' => 0,
            'is_root' => 0,
            'is_emulator' => 0,
            'sn' => $sn,
            'active-time' => '3083',
        ];

        return $headers;
    }

    public static function normalizeFromDramabox(array $item): array
{
    $cdn = $item['cdnList'][0]['videoPathList'] ?? [];

    $videos = collect($cdn)->map(function ($v) {
        return [
            'quality'     => $v['quality'] ?? null,
            'url'         => $v['videoPath'] ?? null,
            'is_default'  => $v['isDefault'] ?? 0,
        ];
    })->values();

    // Cari video default
    $default = $videos->firstWhere('is_default', 1) ?? $videos->first();

    return [
        'id'          => $item['chapterId'] ?? null,
        'title'       => $item['chapterName'] ?? $item['bookName'] ?? null,
        'description' => null,
        'image'       => $item['coverWap'] ?? null,
        'video_urls'  => $videos,
        'default_url' => $default['url'] ?? null,
    ];
}
}
