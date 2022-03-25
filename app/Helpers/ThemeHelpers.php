<?php

define('THEME_PATH', 'themes');
define('SELLING_THEME_PATH', 'themes/_selling');

if (! function_exists('active_theme')) {
    /**
     * Return active theme name
     * @return string
     */
    function active_theme()
    {
        return config('system_settings.active_theme', null) ?: 'default';
    }
}

if (! function_exists('theme_path')) {
    /**
     * Return given/active theme path
     *
     * @param  string $theme name the theme
     * @return string
     */
    function theme_path($theme = null)
    {
        if ($theme == null) {
            $theme = active_theme();
        }

        $path = public_path(THEME_PATH.DIRECTORY_SEPARATOR.strtolower($theme));

        // If the the theme doesn't exist
        if (! file_exists($path) && $theme != '*') {
            return public_path(THEME_PATH.DIRECTORY_SEPARATOR.'default');
        }

        return $path;
    }
}

if (! function_exists('theme_views_path')) {
    /**
     * Return given/active theme views path
     *
     * @param  string $theme name the theme
     * @return string
     */
    function theme_views_path($theme = null)
    {
        return theme_path($theme).'/views';
    }
}

if (! function_exists('theme_asset_url')) {
    /**
     * Return given/active theme assets path
     *
     * @param  string $asset name the theme
     * @param  string $theme name the theme
     * @return string
     */
    function theme_asset_url($asset = null, $theme = null)
    {
        if ($theme == null) {
            $theme = active_theme();
        }

        // If the the theme doesn't exist
        if (! file_exists(public_path(THEME_PATH.DIRECTORY_SEPARATOR.strtolower($theme)))) {
            $theme = 'default';
        }

        $path = asset(THEME_PATH.'/'.$theme.'/assets');

        return  $asset == null ? $path : "{$path}/{$asset}";
    }
}

if (! function_exists('theme_assets_path')) {
    /**
     * Return given/active theme assets path
     *
     * @param  string $theme name the theme
     * @return string
     */
    function theme_assets_path($theme = null)
    {
        return theme_path($theme).'/assets';
    }
}

if (! function_exists('active_selling_theme')) {
    /**
     * Return active selling theme name
     * @return string
     */
    function active_selling_theme()
    {
        return config('system_settings.selling_theme', null) ?: 'default';
    }
}

if (! function_exists('selling_theme_path')) {
    /**
     * Return given/active selling theme views path
     *
     * @param  string $theme name the theme
     * @return string
     */
    function selling_theme_path($theme = null)
    {
        if ($theme == null) {
            $theme = active_selling_theme();
        }

        return public_path(SELLING_THEME_PATH.DIRECTORY_SEPARATOR.strtolower($theme));
    }
}

if (! function_exists('selling_theme_views_path')) {
    /**
     * Return given/active selling theme views path
     *
     * @param  string $theme name the theme
     * @return string
     */
    function selling_theme_views_path($theme = null)
    {
        return selling_theme_path($theme).'/views';
    }
}

if (! function_exists('selling_theme_asset_url')) {
    /**
     * Return given/active selling theme assets url
     *
     * @param  string $asset name the theme
     * @param  string $theme name the theme
     * @return string
     */
    function selling_theme_asset_url($asset = null, $theme = null)
    {
        if ($theme == null) {
            $theme = active_selling_theme();
        }

        $path = asset(SELLING_THEME_PATH.'/'.$theme.'/assets');

        return  $asset == null ? $path : "{$path}/{$asset}";
    }
}

if (! function_exists('selling_theme_assets_path')) {
    /**
     * Return given/active selling theme assets path
     *
     * @param  string $theme name the theme
     * @return string
     */
    function selling_theme_assets_path($theme = null)
    {
        return selling_theme_path($theme).'/assets';
    }
}
