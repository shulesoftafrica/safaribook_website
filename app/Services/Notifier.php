<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Outbound messages for sign-up: WhatsApp (Meta Cloud API first, the shared
 * notification service as fallback) and plain email.
 */
class Notifier
{
    /**
     * @param string|null $otp when set, Meta's approved OTP template is used
     *                         (free text is not allowed outside the 24h window)
     */
    public function whatsapp(string $to, string $message, string $subject = '', ?string $otp = null): bool
    {
        if ($to === '') {
            return false;
        }

        if (config('services.meta_whatsapp.enabled')) {
            try {
                $meta   = app(MetaWhatsAppService::class);
                $result = $otp !== null && $otp !== ''
                    ? $meta->sendOtpTemplate($to, $otp)
                    : $meta->sendTextMessage($to, $message);
                if (!empty($result['success'])) {
                    return true;
                }
                Log::warning('Meta WhatsApp failed, trying notification API', ['error' => $result['error'] ?? 'unknown']);
            } catch (\Throwable $e) {
                Log::error('Meta WhatsApp error: ' . $e->getMessage());
            }
        }

        $url = rtrim((string) config('services.notification_api.url'), '/');
        $key = config('services.notification_api.key');
        if ($url === '' || empty($key)) {
            Log::warning('Notification API not configured; WhatsApp message not sent', ['to' => $to]);

            return false;
        }

        try {
            $response = Http::withToken($key)->timeout(20)->post($url . '/notifications/send', [
                'schema_name' => config('services.notification_api.schema_name'),
                'channel'     => 'whatsapp',
                'to'          => $to,
                'subject'     => $subject,
                'message'     => $message,
                'provider'    => 'whatsapp',
                'priority'    => 'high',
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Notification API error: ' . $e->getMessage());

            return false;
        }
    }

    public function email(string $to, string $name, string $subject, string $body): bool
    {
        if ($to === '' || empty(config('mail.mailers.smtp.host'))) {
            return false;
        }

        try {
            Mail::raw($body, function ($m) use ($to, $name, $subject) {
                $m->to($to, $name)->subject($subject);
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('Email send failed: ' . $e->getMessage());

            return false;
        }
    }
}
