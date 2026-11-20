<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],


    "notification_api" => [
        "url"         => env("NOTIFICATION_API_URL", "https://notifications.shulesoft.africa/api"),
        "key"         => env("NOTIFICATION_API_KEY"),
        "schema_name" => env("NOTIFICATION_API_SCHEMA"),
        "team_phone"  => env("TEAM_WHATSAPP"),
    ],

    "meta_whatsapp" => [
        "enabled"         => env("META_WHATSAPP_ENABLED", false),
        "phone_number_id" => env("META_WHATSAPP_PHONE_NUMBER_ID"),
        "access_token"    => env("META_WHATSAPP_ACCESS_TOKEN"),
        "api_version"     => env("META_WHATSAPP_API_VERSION", "v18.0"),
        "base_url"        => env("META_WHATSAPP_BASE_URL", "https://graph.facebook.com"),
        "timeout"         => env("META_WHATSAPP_TIMEOUT", 30),
        "default_country_code" => env("META_WHATSAPP_DEFAULT_COUNTRY_CODE", "255"),
        "enable_fallback" => env("META_WHATSAPP_ENABLE_FALLBACK", true),
        "log_requests"    => env("META_WHATSAPP_LOG_REQUESTS", true),
        "otp_template"    => [
            "name"     => env("META_WHATSAPP_OTP_TEMPLATE_NAME", "otp_verification"),
            "language" => env("META_WHATSAPP_OTP_TEMPLATE_LANGUAGE", "en"),
        ],
    ],

];
