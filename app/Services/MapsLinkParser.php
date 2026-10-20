<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MapsLinkParser
{
    /**
     * Ambil [latitude, longitude] dari link Google Maps (panjang/pendek)
     * atau teks koordinat polos seperti "-7.08361, 109.31207".
     */
    public static function parse(string $input): ?array
    {
        $input = trim($input);

        if ($input === '') {
            return null;
        }

        if (preg_match('~^https?://~i', $input) && self::isShortLink($input)) {
            $input = self::expand($input) ?? $input;
        }

        $patterns = [
            // Titik pin sebenarnya (paling akurat): ...!3d-7.08361!4d109.31207
            '~!3d(-?\d{1,2}(?:\.\d+)?)!4d(-?\d{1,3}(?:\.\d+)?)~',
            // Pusat tampilan peta: /@-7.08361,109.31207,17z
            '~@(-?\d{1,2}(?:\.\d+)?),(-?\d{1,3}(?:\.\d+)?)~',
            // Parameter URL: ?q=-7.08,109.31 | ll= | query= | destination=
            '~[?&](?:q|query|ll|center|destination)=(-?\d{1,2}(?:\.\d+)?)(?:,|%2C)\s*(-?\d{1,3}(?:\.\d+)?)~i',
            // Koordinat polos: "-7.08361, 109.31207"
            '~^(-?\d{1,2}(?:\.\d+)?)(?:\s*[,;]\s*|\s+)(-?\d{1,3}(?:\.\d+)?)$~',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $m)) {
                $lat = (float) $m[1];
                $lng = (float) $m[2];

                if (abs($lat) <= 90 && abs($lng) <= 180) {
                    return [$lat, $lng];
                }
            }
        }

        return null;
    }

    private static function isShortLink(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return in_array($host, ['maps.app.goo.gl', 'goo.gl', 'g.co'], true);
    }

    private static function isGoogleHost(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        foreach (['google.com', 'google.co.id', 'goo.gl', 'g.co'] as $allowed) {
            if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
                return true;
            }
        }

        return false;
    }

    // Ikuti redirect link pendek sampai ketemu link panjang (hanya domain Google)
    private static function expand(string $url): ?string
    {
        try {
            for ($i = 0; $i < 5; $i++) {
                $response = Http::timeout(8)
                    ->withoutRedirecting()
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; SakaMediaBot/1.0)'])
                    ->get($url);

                $location = $response->header('Location');

                if (! $location) {
                    return $url;
                }

                if (! preg_match('~^https?://~i', $location)) {
                    $parts = parse_url($url);
                    $location = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '') . $location;
                }

                if (! self::isGoogleHost($location)) {
                    return null;
                }

                $url = $location;
            }

            return $url;
        } catch (\Throwable $e) {
            return null;
        }
    }
}