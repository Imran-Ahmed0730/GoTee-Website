<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Services\Admin\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SettingController extends Controller implements HasMiddleware
{
    public function __construct(private SettingService $settingService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Settings View', only: ['index']),
            new Middleware('permission:Settings Update', only: ['update']),
        ];
    }

    public function index(): View
    {
        $data['items'] = $this->settingService->getAllSettings();

        return view('admin.setting.index', $data);
    }

    public function edit(string $section): View
    {
        $sectionDetails = $this->sectionDetails($section);
        abort_unless(auth()->user()->can($sectionDetails['permission']), 403);

        $settings = [];
        foreach ($sectionDetails['fields'] as $field) {
            $settings[$field] = getSetting($field);
        }

        if ($section === 'contact') {
            $settings['closed_on'] = json_decode($settings['closed_on'] ?? '[]', true) ?: [];
        }

        if ($section === 'smtp') {
            $settings = [
                'MAIL_MAILER' => config('mail.default'),
                'MAIL_HOST' => config('mail.mailers.smtp.host'),
                'MAIL_PORT' => config('mail.mailers.smtp.port'),
                'MAIL_USERNAME' => config('mail.mailers.smtp.username'),
                'MAIL_FROM_ADDRESS' => config('mail.from.address'),
                'MAIL_FROM_NAME' => config('mail.from.name'),
                'MAIL_SCHEME' => config('mail.mailers.smtp.scheme'),
            ];
        }

        return view($sectionDetails['view'], [
            'section' => $section,
            'settings' => $settings,
        ]);
    }

    public function update(SettingRequest $request, string $section): RedirectResponse
    {
        $sectionDetails = $this->sectionDetails($section);
        $validated = $request->validated();

        if ($section === 'general') {
            $this->settingService->updateSettings($validated);
        } elseif ($section === 'logos-favicon') {
            $fields = $sectionDetails['fields'];

            foreach ($fields as $field) {
                if ($request->hasFile($field)) {
                    $oldImage = getSetting($field);
                    $imagePath = updateImagePath($request->file($field), $oldImage, 'website-image');
                    $this->settingService->updateSettings([$field => $imagePath]);
                }
            }
        } elseif ($section === 'contact') {
            $validated['closed_on'] = json_encode($validated['closed_on'] ?? [], JSON_THROW_ON_ERROR);

            $this->settingService->updateSettings($validated);
        } elseif ($section === 'social-media') {
            $this->settingService->updateSettings($validated);
        } else {
            foreach ($validated as $key => $value) {
                if ($key !== 'MAIL_PASSWORD' || filled($value)) {
                    if (! overWriteEnvFile($key, $value)) {
                        return back()->withInput()->with('error', 'SMTP settings cannot be changed in demo mode.');
                    }
                }
            }

            config([
                'mail.default' => $validated['MAIL_MAILER'],
                'mail.mailers.smtp.scheme' => $validated['MAIL_SCHEME'] ?? null,
                'mail.mailers.smtp.host' => $validated['MAIL_HOST'] ?? null,
                'mail.mailers.smtp.port' => $validated['MAIL_PORT'] ?? null,
                'mail.mailers.smtp.username' => $validated['MAIL_USERNAME'] ?? null,
                'mail.mailers.smtp.password' => $validated['MAIL_PASSWORD'] ?? config('mail.mailers.smtp.password'),
                'mail.from.address' => $validated['MAIL_FROM_ADDRESS'],
                'mail.from.name' => $validated['MAIL_FROM_NAME'],
            ]);
            Mail::purge();
        }

        return redirect()
            ->route('admin.setting.edit', $section)
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * @return array{view: string, permission: string, fields: array<int, string>}
     */
    private function sectionDetails(string $section): array
    {
        $sections = [
            'general' => [
                'view' => 'admin.setting.general',
                'permission' => 'Settings Site',
                'fields' => ['site_name', 'business_name', 'site_url', 'short_bio', 'meta_description', 'developed_by', 'developed_by_url'],
            ],
            'logos-favicon' => [
                'view' => 'admin.setting.logos-favicon',
                'permission' => 'Settings Logo & Favicon',
                'fields' => ['site_logo', 'site_favicon', 'site_footer_logo', 'site_dark_logo'],
            ],
            'contact' => [
                'view' => 'admin.setting.contact',
                'permission' => 'Settings Contact',
                'fields' => ['address', 'email', 'phone', 'whatsapp', 'opening_time', 'closing_time', 'closed_on'],
            ],
            'social-media' => [
                'view' => 'admin.setting.social-media',
                'permission' => 'Settings Social Media',
                'fields' => ['facebook_url', 'x_url', 'instagram_url', 'linkedin_url', 'youtube_url'],
            ],
            'smtp' => [
                'view' => 'admin.setting.smtp',
                'permission' => 'Settings Smtp',
                'fields' => [],
            ],
        ];

        abort_unless(array_key_exists($section, $sections), 404);

        return $sections[$section];
    }
}
