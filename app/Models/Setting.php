<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Memo per-request agar allCached() tak menembak cache-store berkali-kali (View composer '*'). */
    protected static ?array $allMemo = null;

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
        Cache::forget('settings.all');
        static::$allMemo = null;
    }

    /**
     * Get all settings as key-value array (cached).
     */
    public static function allCached(): array
    {
        if (static::$allMemo !== null) {
            return static::$allMemo;
        }

        return static::$allMemo = Cache::remember('settings.all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        $settings = static::pluck('key');
        foreach ($settings as $key) {
            Cache::forget("setting.{$key}");
        }
        Cache::forget('settings.all');
        static::$allMemo = null;
    }
}
