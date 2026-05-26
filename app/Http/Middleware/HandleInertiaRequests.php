<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'appName' => config('app.name'),
            'footer' => fn () => [
                'contacts' => Setting::getValue('footer_contacts', [
                    'phone' => '+7 (999) 123-45-67',
                    'email' => 'info@vedma.ru',
                    'address' => 'г. Москва, ул. Цветочная, 13',
                ]),
                'socials' => Setting::getValue('footer_socials', [
                    'telegram_enabled' => true,
                    'telegram_url' => '',
                    'vk_enabled' => true,
                    'vk_url' => '',
                    'instagram_enabled' => true,
                    'instagram_url' => '',
                    'whatsapp_enabled' => false,
                    'whatsapp_url' => '',
                ]),
            ],
        ]);
    }
}
