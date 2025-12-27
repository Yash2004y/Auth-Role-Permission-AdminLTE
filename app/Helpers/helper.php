<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routeNames)
    {
        $currentRoute = Route::currentRouteName();

        if (is_array($routeNames)) {
            return in_array($currentRoute, $routeNames) ? 'active' : '';
        }

        return $currentRoute === $routeNames ? 'active' : '';
    }
}

if (!function_exists('isMenuOpen')) {
    function isMenuOpen($routeNames)
    {
        $currentRoute = Route::currentRouteName();

        if (is_array($routeNames)) {
            return in_array($currentRoute, $routeNames) ? 'menu-open' : '';
        }

        return $currentRoute === $routeNames ? 'menu-open' : '';
    }
}

if (!function_exists('sendResponse')) {
    function sendResponse($message, $data = [], $status = true, $statusCode = 200)
    {
        return [
            "data" => $data,
            "message" => $message,
            "status" => $status,
            "statusCode" => $statusCode,

        ];
    }
}
if (!function_exists('sendAjaxResponse')) {
    function sendAjaxResponse($message, $redirect = '', $data = [], $status = true, $statusCode = 200)
    {
        return [
            "data" => $data,
            "message" => $message,
            "redirect" => $redirect,
            "status" => $status,
            "statusCode" => $statusCode,

        ];
    }
}

if (!function_exists('sendAjaxModalResponse')) {
    function sendAjaxModalResponse($message, $modalHtml, $status = true, $statusCode = 200)
    {
        return [
            "modal" => $modalHtml,
            "message" => $message,
            "status" => $status,
            "statusCode" => $statusCode,
        ];
    }
}

if (!function_exists('sendError')) {
    function sendError($message, $errors = [], $status = false, $statusCode = 400)
    {
        return [
            "errors" => $errors,
            "message" => $message,
            "status" => $status,
            "statusCode" => $statusCode,

        ];
    }
}
