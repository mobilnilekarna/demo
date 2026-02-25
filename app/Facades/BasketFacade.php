<?php

namespace App\Facades;

use App\Models\Basket;
use Illuminate\Support\Facades\Cookie;

class BasketFacade
{
    /**
     * Return basket for current session with items, count and sum.
     *
     * @return array{basket: \App\Models\Basket, items: \Illuminate\Support\Collection, count: int, sum: float}
     */
    public static function getCurrent(): array
    {
        $request = request();
        $ensured = self::ensureCookie($request);
        $key = $ensured['key'];

        $basket = Basket::firstOrCreate(
            ['session_id' => $key],
            ['status' => 'open']
        );

        $items = $basket->items()->get();

        return $items->toArray();
    }

    /**
     * Ensure basket cookie exists or refresh it and return key + cookie.
     *
     * @return array{key: string, cookie: \Symfony\Component\HttpFoundation\Cookie}
     */
    public static function ensureCookie(\Illuminate\Http\Request $request, string $cookieName = 'basket_key'): array
    {
        $key = (string) Cookie::get($cookieName, '');
        if ($key === '') {
            $seed = microtime(true) . random_bytes(16);
            $key = md5($seed);
        }

        $minutes = 30 * 24 * 60;
        $cookie = Cookie::make($cookieName, $key, $minutes, '/', null, app()->environment('production'), true, false, 'lax');

        return ['key' => $key, 'cookie' => $cookie];
    }
}
