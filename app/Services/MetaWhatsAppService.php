<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Meta WhatsApp Business API Service
 * 
 * Provides interface for sending messages via Meta's Official WhatsApp Business API.
 * Implements automatic fallback to notification API on failures.
 * 
 * @link https://developers.facebook.com/docs/whatsapp/cloud-api
 */
class MetaWhatsAppService
{
    protected $baseUrl;
    protected $phoneNumberId;
    protected $accessToken;
    protected $apiVersion;
    protected $timeout;
    protected $enableFallback;
    protected $logRequests;

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = config('services.meta_whatsapp');
        
        $this->baseUrl = rtrim($config['base_url'] ?? 'https://graph.facebook.com', '/');
        $this->phoneNumberId = $config['phone_number_id'] ?? '';
        $this->accessToken = $config['access_token'] ?? '';
        $this->apiVersion = $config['api_version'] ?? 'v18.0';
        $this->timeout = $config['timeout'] ?? 30;
        $this->enableFallback = $config['enable_fallback'] ?? true;
        $this->logRequests = $config['log_requests'] ?? true;
        
        if (empty($this->accessToken)) {
            Log::warning('Meta WhatsApp access token not configured');
        }
    }

    /**
     * Send OTP template message
     * 
     * @param string $phoneNumber Phone number in international format
     * @param string $otpCode OTP code (6 digits)
     * @param string|null $otpCode2 Optional second OTP parameter for button
     * @return array Response with success status and data
     */
    public function sendOtpTemplate(string $phoneNumber, string $otpCode, ?string $otpCode2 = null): array
    {
        $cleanPhone = $this->formatPhoneNumber($phoneNumber);
        $otpCode2 = $otpCode2 ?? $otpCode;
        
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $cleanPhone,
            'type' => 'template',
            'template' => [
                'name' => 'otp',
                'language' => [
                    'code' => 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $otpCode
                            ]
                        ]
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'url',
                        'index' => '0',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $otpCode2
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->sendApiRequest($payload, $cleanPhone, 'otp_template');
    }

    /**
     * Send text message
     * 
     * @param string $phoneNumber Phone number
     * @param string $message Message content
     * @param bool $previewUrl Enable URL preview
     * @return array Response
     */
    public function sendTextMessage(string $phoneNumber, string $message, bool $previewUrl = false): array
    {
        $cleanPhone = $this->formatPhoneNumber($phoneNumber);
        
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanPhone,
            'type' => 'text',
            'text' => [
                'preview_url' => $previewUrl,
                'body' => $message
            ]
        ];

        return $this->sendApiRequest($payload, $cleanPhone, 'text');
    }

    /**
     * Send API request to Meta
     * 
     * @param array $payload Request payload
     * @param string $phoneNumber Phone number (for logging)
     * @param string $messageType Message type (for logging)
     * @return array Response
     */
    protected function sendApiRequest(array $payload, string $phoneNumber, string $messageType): array
    {
        if (empty($this->accessToken) || empty($this->phoneNumberId)) {
            return [
                'success' => false,
                'error' => 'Meta WhatsApp not configured'
            ];
        }

        $url = "{$this->baseUrl}/{$this->apiVersion}/{$this->phoneNumberId}/messages";
        
        try {
            if ($this->logRequests) {
                Log::info('Meta WhatsApp → sending', [
                    'phone' => $phoneNumber,
                    'type' => $messageType,
                    'url' => $url,
                    'payload' => $payload
                ]);
            }

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($url, $payload);
            
            $result = $response->json();
            
            if ($response->successful()) {
                if ($this->logRequests) {
                    Log::info('Meta WhatsApp ← success', [
                        'phone' => $phoneNumber,
                        'message_id' => $result['messages'][0]['id'] ?? null,
                        'full_response' => $result
                    ]);
                }
                
                return [
                    'success' => true,
                    'message_id' => $result['messages'][0]['id'] ?? null,
                    'data' => $result
                ];
            }
            
            $errorMessage = $result['error']['message'] ?? 'Unknown error';
            
            Log::warning('Meta WhatsApp ← error', [
                'phone' => $phoneNumber,
                'http_code' => $response->status(),
                'error' => $errorMessage
            ]);
            
            return [
                'success' => false,
                'error' => $errorMessage,
                'http_code' => $response->status()
            ];
            
        } catch (\Exception $e) {
            Log::error('Meta WhatsApp exception: ' . $e->getMessage(), [
                'phone' => $phoneNumber
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to international format
     * 
     * @param string $phoneNumber Phone number
     * @return string Formatted phone number
     */
    public function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters except +
        $clean = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Ensure it starts with +
        if (substr($clean, 0, 1) !== '+') {
            // If starts with 0, remove it
            if (substr($clean, 0, 1) === '0') {
                $clean = substr($clean, 1);
            }
            
            // Default to Kenya if no country code (+254)
            $defaultCountryCode = config('services.meta_whatsapp.default_country_code', '254');
            $clean = '+' . $defaultCountryCode . $clean;
        }
        
        return $clean;
    }

    /**
     * Generate OTP code
     * 
     * @param int $length OTP length
     * @return string OTP code
     */
    public function generateOtp(int $length = 6): string
    {
        return str_pad((string)rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
