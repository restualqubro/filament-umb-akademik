<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $brand_name;
    public ?string $brand_logo;
    public string $brand_logoHeight;
    public bool $site_active;
    public ?string $site_favicon;
    public array $site_theme;
    // public ?string $akademik_active;
    // public ?string $akademik_id;    

    public static function group(): string
    {
        return 'general';
    }
}
