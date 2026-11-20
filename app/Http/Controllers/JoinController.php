<?php

namespace App\Http\Controllers;

use App\Services\Notifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Business sign-up. Same shape as ShuleSoft's join-now: a row in admin.clients
 * (project_id 3 = Safaribook), a one-time code by WhatsApp and email, then the
 * owner continues on https://{username}.safaribook.africa, where the main
 * platform runs the business setup.
 */
class JoinController extends Controller
{
    public const PROJECT_ID = 3;

    public const BUSINESS_TYPES = [
        'retail'         => 'Retail / shop',
        'ecommerce'      => 'Online / e-commerce',
        'restaurant'     => 'Restaurant / food & drink',
        'service'        => 'Services',
        'consulting'     => 'Consulting / professional',
        'healthcare'     => 'Healthcare / pharmacy',
        'manufacturing'  => 'Manufacturing',
        'transportation' => 'Transport / logistics',
        'general'        => 'Other / general',
    ];

    public const CURRENCIES = ['TZS', 'KES', 'UGX', 'USD', 'EUR', 'GBP'];

    public const REVENUE_RANGES = [
        'startup' => 'Just starting',
        'small'   => 'Small (growing)',
        'medium'  => 'Medium (established)',
        'large'   => 'Large',
    ];

    public function __construct(private Notifier $notifier)
    {
    }

    public function form()
    {
        return view('site.join', [
            'countries'     => DB::table('constant.refer_countries')->orderBy('country')->get(['id', 'country']),
            'businessTypes' => self::BUSINESS_TYPES,
            'currencies'    => self::CURRENCIES,
            'revenueRanges' => self::REVENUE_RANGES,
        ]);
    }

    public function otpForm()
    {
        if (!session('otp_client_id')) {
            return redirect('/join');
        }

        return view('site.otp', ['phone' => session('otp_phone'), 'email' => session('otp_email')]);
    }

    public function submit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'business_name'    => 'required|string|min:2|max:150',
                'business_type'    => 'required|in:' . implode(',', array_keys(self::BUSINESS_TYPES)),
                'country'          => 'required|integer',
                'location'         => 'required|string|max:300',
                'contact_name'     => 'required|string|max:150',
                'contact_title'    => 'nullable|string|max:100',
                'phone'            => ['required', 'string', 'max:30', 'regex:/^\+?[0-9]{9,15}$/'],
                'email'            => 'required|email:rfc|max:150',
                'primary_currency' => 'required|in:' . implode(',', self::CURRENCIES),
                'monthly_revenue'  => 'nullable|in:' . implode(',', array_keys(self::REVENUE_RANGES)),
                'has_employees'    => 'nullable|in:0,1',
                'needs_inventory'  => 'nullable|in:0,1',
                'username'         => 'nullable|string|min:3|max:30|regex:/^[a-z0-9]+$/',
                'terms_accepted'   => 'accepted',
            ], [
                'phone.regex'             => 'Please enter a valid phone number with country code.',
                'username.regex'          => 'The web address may only contain lowercase letters and numbers.',
                'username.min'            => 'The web address needs at least 3 characters.',
                'terms_accepted.accepted' => 'Please accept the Terms and Privacy Policy to continue.',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }

            $name         = trim(strip_tags($request->business_name));
            $contactName  = trim(strip_tags($request->contact_name));
            $contactTitle = trim(strip_tags((string) $request->contact_title));
            $location     = trim(strip_tags($request->location));
            $phone        = $request->phone;
            $email        = strtolower(trim($request->email));

            if ($this->looksLikeBot($request, [$name, $contactName, $location, $contactTitle])) {
                Log::info('Safaribook sign-up blocked as bot', ['ip' => $request->ip(), 'email' => $email]);

                return $this->fail('We could not submit your application. Please check your details and try again, or WhatsApp us on +255 689 353 642.');
            }

            // An abandoned try (never verified, code expired) must not block a retry.
            $this->purgeAbandoned($name, $email, $phone, $request->input('username'));

            $mine = fn () => DB::table('admin.clients')->where('project_id', self::PROJECT_ID);

            if ($mine()->whereRaw('lower(name) = ?', [strtolower($name)])->exists()) {
                return $this->fail('A business with this name is already registered on Safaribook. If it is yours, contact support@shulesoft.africa.');
            }
            if ($mine()->whereRaw('lower(email) = ?', [$email])->exists()) {
                return $this->fail('This email address is already registered on Safaribook. Please use another email or contact support.');
            }
            if ($mine()->where('phone', $phone)->exists()) {
                return $this->fail('This phone number is already registered on Safaribook. Please use another number or contact support.');
            }

            $requested = (string) $request->input('username');
            if ($requested !== '' && DB::table('admin.clients')->where('username', $requested)->exists()) {
                return $this->fail('This web address is already taken. Please choose a different one.');
            }
            $username = $this->uniqueUsername($requested !== '' ? $requested : $name);

            $extras = [
                'business_type'    => $request->business_type,
                'primary_currency' => $request->primary_currency,
                'monthly_revenue'  => $request->input('monthly_revenue', 'startup'),
                'has_employees'    => (bool) $request->input('has_employees'),
                'needs_inventory'  => (bool) $request->input('needs_inventory'),
            ];

            $otp = random_int(100000, 999999);

            $clientId = DB::table('admin.clients')->insertGetId([
                'name'           => $name,
                'username'       => $username,
                'phone'          => $phone,
                'email'          => $email,
                'country_id'     => (int) $request->country,
                'address'        => $location,
                'level'          => $request->business_type,
                'ownership'      => 'Private',
                'director_name'  => $contactName,
                'contact_title'  => $contactTitle !== '' ? $contactTitle : null,
                'source'         => 'safaribook.africa',
                'note'           => json_encode($extras),
                'project_id'     => self::PROJECT_ID,
                'status'         => 1,
                'otp'            => Hash::make((string) $otp),
                'otp_expires_at' => now()->addMinutes(15),
                'created_at'     => now(),
                'updated_at'     => now(),
            ], 'id');

            $delivery = $this->sendOtp($contactName, $phone, $email, $otp);

            if (!$delivery['whatsapp'] && !$delivery['email']) {
                // Nobody can ever verify this row; remove it so the owner can simply retry.
                DB::table('admin.clients')->where('id', $clientId)->delete();
                Log::error('Safaribook sign-up rolled back: OTP not delivered', ['client_id' => $clientId, 'username' => $username]);

                return $this->fail('We could not send your verification code by WhatsApp or email. Please check your phone number (with country code) and email and try again, or contact support@shulesoft.africa.', 502);
            }

            $team = config('services.notification_api.team_phone');
            if ($team) {
                $this->notifier->whatsapp(
                    $team,
                    "NEW SAFARIBOOK BUSINESS SIGN-UP\n\n"
                    . "Business: {$name}\n"
                    . 'Web address: ' . $username . '.' . config('app.tenant_domain') . "\n"
                    . 'Type: ' . (self::BUSINESS_TYPES[$request->business_type] ?? $request->business_type) . "\n"
                    . "Contact: {$contactName}" . ($contactTitle !== '' ? " ({$contactTitle})" : '') . "\n"
                    . "Phone: {$phone} | Email: {$email}\n"
                    . "Location: {$location}\n"
                    . 'Currency: ' . $request->primary_currency . ' | Size: ' . (self::REVENUE_RANGES[$extras['monthly_revenue']] ?? '') . "\n"
                    . 'Employees: ' . ($extras['has_employees'] ? 'yes' : 'no') . ' | Inventory: ' . ($extras['needs_inventory'] ? 'yes' : 'no') . "\n"
                    . 'Status: awaiting OTP verification',
                    'New Safaribook sign-up'
                );
            }

            session([
                'otp_client_id' => $clientId,
                'otp_phone'     => $phone,
                'otp_name'      => $contactName,
                'otp_email'     => $email,
            ]);

            return response()->json([
                'status'   => 'success',
                'message'  => 'Application received! ' . $this->describe($delivery, $email) . '.',
                'redirect' => url('/verify'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Safaribook sign-up failed: ' . $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);

            return $this->fail('Something went wrong on our side. Please try again, or contact support@shulesoft.africa if it continues.', 500);
        }
    }

    public function verify(Request $request)
    {
        $clientId = session('otp_client_id');
        if (!$clientId) {
            return $this->fail('Your session expired. Please register again.');
        }
        if (!preg_match('/^\d{6}$/', (string) $request->input('otp'))) {
            return $this->fail('Please enter the 6-digit code.');
        }

        $client = DB::table('admin.clients')->where('id', $clientId)->where('project_id', self::PROJECT_ID)->first();
        if (!$client) {
            return $this->fail('Registration not found. Please start again.');
        }
        if (empty($client->otp) || empty($client->otp_expires_at) || now()->isAfter($client->otp_expires_at)) {
            return $this->fail('Your code has expired. Please request a new one.');
        }
        if (!Hash::check((string) $request->input('otp'), $client->otp)) {
            return $this->fail('That code is not correct. Please try again.');
        }

        DB::table('admin.clients')->where('id', $clientId)->update([
            'otp'            => null,
            'otp_expires_at' => null,
            'verified_at'    => now(),
            'phone_verified' => 1,
            'updated_at'     => now(),
        ]);

        $name  = (string) session('otp_name');
        $phone = (string) session('otp_phone');
        session()->forget(['otp_client_id', 'otp_phone', 'otp_name', 'otp_email']);

        $tenantUrl = 'https://' . $client->username . '.' . config('app.tenant_domain');

        $this->notifier->whatsapp(
            $phone,
            "Congratulations {$name}!\n\n{$client->name} is now registered on Safaribook.\n\n"
            . "Next step: finish your business setup (about 3 minutes) at:\n{$tenantUrl}\n\n"
            . "Need help? Call/WhatsApp +255689353642 or email support@shulesoft.africa.\n\nWelcome to Safaribook!",
            'Welcome to Safaribook'
        );

        return response()->json(['status' => 'success', 'message' => 'Verified! Taking you to your business setup...', 'redirect' => $tenantUrl]);
    }

    public function resend(Request $request)
    {
        $clientId = session('otp_client_id');
        $phone    = (string) session('otp_phone');
        $name     = (string) session('otp_name');
        $email    = (string) session('otp_email', '');

        if (!$clientId || $phone === '') {
            return $this->fail('Your session expired. Please register again.');
        }

        $channel = in_array($request->input('channel'), ['whatsapp', 'email', 'both'], true) ? $request->input('channel') : 'both';
        if ($channel === 'email' && $email === '') {
            return $this->fail('We have no email address on file for this registration.');
        }

        $otp = random_int(100000, 999999);
        DB::table('admin.clients')->where('id', $clientId)->where('project_id', self::PROJECT_ID)->whereNull('verified_at')->update([
            'otp'            => Hash::make((string) $otp),
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        $delivery = $this->sendOtp($name, $phone, $email, $otp, $channel);
        if (!$delivery['whatsapp'] && !$delivery['email']) {
            return $this->fail('We could not send a new code right now. Please try again in a moment.', 502);
        }

        return response()->json(['status' => 'success', 'message' => 'A new code has been sent. ' . $this->describe($delivery, $email) . '.']);
    }

    // helpers

    private function fail(string $message, int $code = 422)
    {
        return response()->json(['status' => 'error', 'message' => $message], $code);
    }

    private function sendOtp(string $name, string $phone, string $email, int $otp, string $channel = 'both'): array
    {
        $sent = ['whatsapp' => false, 'email' => false];

        if (in_array($channel, ['both', 'whatsapp'], true)) {
            $sent['whatsapp'] = $this->notifier->whatsapp(
                $phone,
                "Hello {$name}!\n\nYour Safaribook verification code is:\n\n*{$otp}*\n\nIt expires in 15 minutes. Do not share it with anyone.",
                'Safaribook verification',
                (string) $otp
            );
        }
        if (in_array($channel, ['both', 'email'], true) && $email !== '') {
            $sent['email'] = $this->notifier->email(
                $email,
                $name,
                'Your Safaribook verification code',
                "Hello {$name},\n\nYour Safaribook verification code is: {$otp}\n\nIt expires in 15 minutes. Do not share it with anyone.\n\nIf you did not request this, you can ignore this email.\n\nSafaribook"
            );
        }

        return $sent;
    }

    private function describe(array $delivery, string $email): string
    {
        $parts = [];
        if ($delivery['whatsapp']) {
            $parts[] = 'your WhatsApp';
        }
        if ($delivery['email']) {
            $parts[] = 'your email (' . preg_replace('/(^.).*(@.*$)/', '$1***$2', $email) . ')';
        }

        return 'We sent your code to ' . implode(' and ', $parts);
    }

    /** Hidden honeypot, implausibly fast fills, and links in free-text fields. */
    private function looksLikeBot(Request $request, array $texts): bool
    {
        if ($request->filled('website_hp')) {
            return true;
        }
        $rendered = $request->input('form_rendered_at');
        if (is_numeric($rendered) && time() - (int) $rendered < 4) {
            return true;
        }
        foreach ($texts as $t) {
            if (preg_match('~https?://|www\.~i', (string) $t)) {
                return true;
            }
        }

        return false;
    }

    private function purgeAbandoned(string $name, string $email, string $phone, ?string $username): void
    {
        DB::table('admin.clients')
            ->where('project_id', self::PROJECT_ID)
            ->whereNull('verified_at')
            ->whereNotNull('otp')
            ->where(fn ($q) => $q->whereNull('otp_expires_at')->orWhere('otp_expires_at', '<', now()))
            ->where(function ($q) use ($name, $email, $phone, $username) {
                $q->whereRaw('lower(name) = ?', [strtolower($name)])
                  ->orWhereRaw('lower(email) = ?', [$email])
                  ->orWhere('phone', $phone);
                if (!empty($username)) {
                    $q->orWhere('username', $username);
                }
            })
            ->delete();
    }

    private function uniqueUsername(string $input): string
    {
        $base = substr(strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $input)), 0, 20);
        if (strlen($base) < 3) {
            $base = 'business' . random_int(100, 999);
        }
        $username = $base;
        for ($i = 1; DB::table('admin.clients')->where('username', $username)->exists(); $i++) {
            $username = $base . $i;
        }

        return $username;
    }
}
