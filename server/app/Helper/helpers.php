<?php

// Written by: Hristo 2024

// proverqva dali daden route e activen

use Illuminate\Support\Facades\Session;
use App\Models\GeneralSetting;
use Illuminate\Support\Str;

function setActive(array $routes) {
    if (is_array($routes)) {
        foreach ($routes as $route) {
         
            if (request()->routeIs($route) || request()->fullUrl() === url($route)) {
                return 'active';
            }
           // Dynamic route matching for "/bcat/{slug}"
           if (strpos($route, '/bcat') === 0 && request()->path() === trim($route, '/')) {
            return 'active';
        }
        }
    }

    return '';
}



/** lemit text */

function limitText($text, $limit = 20)
{
    return Str::limit($text, $limit);
}

// function getCurrencyIcon()
// {
//     $icon = GeneralSetting::first();

//     return $icon->currency_icon;
// }

?>