<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $permissions = [
            'general' => 'Settings Site',
            'logos-favicon' => 'Settings Logo & Favicon',
            'contact' => 'Settings Contact',
            'social-media' => 'Settings Social Media',
            'smtp' => 'Settings Smtp',
        ];
        $section = $this->route('section');

        return is_string($section)
            && isset($permissions[$section])
            && ($this->user()?->can($permissions[$section]) ?? false);
    }

    public function rules(): array
    {
        return match ($this->route('section')) {
            'general' => [
                'site_name' => ['required', 'string', 'max:255'],
                'business_name' => ['required', 'string', 'max:255'],
                'site_url' => ['nullable', 'url', 'max:255'],
                'short_bio' => ['required', 'string', 'max:1000'],
                'meta_description' => ['required', 'string', 'max:1000'],
                'developed_by' => ['nullable', 'string', 'max:255'],
                'developed_by_url' => ['nullable', 'url', 'max:255'],
            ],
            'logos-favicon' => array_fill_keys(
                ['site_logo', 'site_favicon', 'site_footer_logo', 'site_dark_logo'],
                ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ),
            'contact' => [
                'address' => ['required', 'string', 'max:1000'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['required', 'string', 'max:50'],
                'whatsapp' => ['required', 'string', 'max:50'],
                'opening_time' => ['required', 'string', 'max:50'],
                'closing_time' => ['required', 'string', 'max:50'],
                'closed_on' => ['nullable', 'array'],
                'closed_on.*' => ['string', Rule::in(['1', '2', '3', '4', '5', '6', '7'])],
            ],
            'social-media' => array_fill_keys(
                ['facebook_url', 'x_url', 'instagram_url', 'linkedin_url', 'youtube_url'],
                ['nullable', 'url', 'max:255'],
            ),
            'smtp' => [
                'MAIL_MAILER' => ['required', Rule::in(['smtp', 'log', 'array'])],
                'MAIL_SCHEME' => ['nullable', Rule::in(['smtp', 'smtps'])],
                'MAIL_HOST' => ['required_if:MAIL_MAILER,smtp', 'nullable', 'string', 'max:255', 'not_regex:/[\r\n]/'],
                'MAIL_PORT' => ['required_if:MAIL_MAILER,smtp', 'nullable', 'integer', 'between:1,65535'],
                'MAIL_USERNAME' => ['nullable', 'string', 'max:255', 'not_regex:/[\r\n]/'],
                'MAIL_PASSWORD' => ['nullable', 'string', 'max:255', 'not_regex:/[\r\n]/'],
                'MAIL_FROM_ADDRESS' => ['required', 'email', 'max:255'],
                'MAIL_FROM_NAME' => ['required', 'string', 'max:255', 'not_regex:/[\r\n]/'],
            ],
            default => [],
        };
    }
}
