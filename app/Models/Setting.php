<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A tiny key-value store for business rules the university might want to
 * change without a redeploy — right now, just the disposal time window
 * (90 days by default). The .env-driven defaults in config/lostandfound.php
 * still work as the fallback when no override has been saved here, so
 * nothing breaks for anyone who hasn't touched the Settings page.
 *
 * Deliberately uncached: settings correctness — the admin's save taking
 * effect immediately, with no stale cached value lingering — matters more
 * here than shaving a couple of tiny queries.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $value = static::query()->where('key', $key)->value('value');

        return $value !== null && $value !== '' ? $value : $default;
    }

    public static function getInt(string $key, int $default): int
    {
        return (int) static::get($key, $default);
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }
}