<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Settings Update', only: ['goToSection']),
        ];
    }

    public function update(Request $request)
    {
        $prev_url = url()->previous();
        $languages = json_decode(getSetting('site_language'), true);

        $updateSetting = function ($fields) use ($request) {
            foreach ($fields as $field) {
                Setting::where('key', $field)->update(['value' => $request->$field ?? null]);
            }
        };

        $updateTranslations = function ($fields) use ($request, $languages) {
            foreach ($languages as $lang) {
                foreach ($fields as $field) {
                    $key = $field.'_'.$lang;
                    $value = $request->$key ?? $request->$field ?? null;
                    $setting = Setting::where('key', $key)->first();

                    if ($setting) {
                        $setting->update(['value' => $value]);
                    } elseif (! is_null($value)) {
                        Setting::create(['key' => $key, 'value' => $value]);
                    }
                }
            }
        };

        if (Str::contains($prev_url, '/contact')) {
            $fields = ['email', 'phone', 'whatsapp', 'facebook_messenger_url', 'appointment_number'];
            $request->validate([
                'email' => 'required',
                'phone' => 'required',
                'whatsapp' => 'required',
                'appointment_number' => 'required',
                'facebook_messenger_url' => 'required',
            ]);

            $updateSetting($fields);
            $updateTranslations(['phone', 'whatsapp', 'appointment_number']);
        } elseif (Str::contains($prev_url, '/site')) {
            $request->validate([
                'site_name' => 'required',
                'business_name' => 'required',
                'site_url' => 'required',
                'short_bio' => 'required',
                'meta_description' => 'required',
                'popup' => 'required',
                'popup_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $fields = ['site_name', 'business_name', 'site_url', 'short_bio', 'meta_description', 'toastr_position_for_frontend', 'toastr_position_for_backend', 'popup', 'popup_description', 'popup_title'];
            $updateSetting($fields);

            if ($request->hasFile('popup_image')) {
                $imagePath = updateImagePath($request->file('popup_image'), getSetting('popup_image'), 'website-image');
                Setting::where('key', 'popup_image')->update(['value' => $imagePath]);
            } elseif ($request->popup == 0) {
                $popupImage = Setting::where('key', 'popup_image')->first();
                if ($popupImage && $popupImage->value && file_exists($popupImage->value)) {
                    unlink($popupImage->value);
                    $popupImage->update(['value' => null]);
                }
            }

            $updateTranslations(['popup_title', 'popup_description', 'short_bio']);

            if ($request->developed_by || $request->developed_by_url) {
                Setting::where('key', 'developed_by')->update(['value' => $request->developed_by ?? null]);
                Setting::where('key', 'developed_by_url')->update(['value' => $request->developed_by_url ?? null]);
            }
        } elseif (Str::contains($prev_url, '/logos-favicon')) {
            $fields = ['site_logo', 'site_favicon', 'site_footer_logo', 'site_dark_logo'];
            $request->validate(array_fill_keys($fields, 'nullable|image|mimes:jpeg,png'));

            foreach ($fields as $field) {
                if ($request->hasFile($field)) {
                    $imagePath = updateImagePath($request->file($field), getSetting($field), 'website-image');
                    Setting::where('key', $field)->update(['value' => $imagePath]);
                }
            }
        } elseif (Str::contains($prev_url, '/social-media')) {
            $fields = ['facebook_url', 'x_url', 'instagram_url', 'linkedin_url', 'youtube_url', 'thread_url', 'pinterest_url', 'tiktok_url'];
            $updateSetting($fields);
        } elseif (Str::contains($prev_url, '/hospital')) {
            $request->validate([
                'total_staff' => 'required|numeric',
                'shop_url' => 'nullable|string|url|max:255',
            ]);

            $fields = ['address', 'shop_map_location', 'shop_url', 'total_staff', 'open_hours', 'closed_on'];
            $updateSetting($fields);
            $updateTranslations(['address', 'open_hours', 'closed_on']);
        } elseif (Str::contains($prev_url, '/app')) {
            $fields = ['app_link_of_google_play_store', 'app_link_of_app_store'];
            $updateSetting($fields);
        } elseif (Str::contains($prev_url, '/chief-info')) {
            $request->validate([
                'chief_first_name' => 'required',
                'chief_last_name' => 'nullable',
                'chief_designation' => 'required',
                'chief_image' => 'nullable|image|mimes:jpeg,png',
                'chief_signature' => 'nullable|image|mimes:jpeg,png',
                'chief_message' => 'required',
            ]);

            $fields = ['chief_first_name', 'chief_last_name', 'chief_designation', 'chief_message', 'chief_facebook_url', 'chief_x_url', 'chief_instagram_url', 'chief_linkedin_url', 'chief_youtube_url', 'chief_thread_url', 'chief_pinterest_url', 'chief_tiktok_url'];
            $updateSetting($fields);
            $updateTranslations(['chief_first_name', 'chief_last_name', 'chief_designation', 'chief_message']);

            if ($request->hasFile('chief_image')) {
                $imagePath = updateImagePath($request->file('chief_image'), getSetting('chief_image'), 'website-image');
                Setting::where('key', 'chief_image')->update(['value' => $imagePath]);
            }

            if ($request->hasFile('chief_signature')) {
                $imagePath = updateImagePath($request->file('chief_signature'), getSetting('chief_signature'), 'website-image');
                Setting::where('key', 'chief_signature')->update(['value' => $imagePath]);
            }
        } else {
            abort(404, 'Invalid settings section');
        }

        return back()->with('success', 'Settings updated successfully');
    }

    public function goToSection($slug)
    {
        $data[] = null;
        $view = 'index';
        $permissions = [
            'site' => 'Settings Site',
            'contact' => 'Settings Contact',
            'logos-favicon' => 'Settings Logo & Favicon',
            'social-media' => 'Settings Social Media',
            'store' => 'Settings Store',
            'app' => 'Settings App',
            'chief-info' => 'Settings Chief Information',
        ];

        // Check if the current user has the required permission
        if (array_key_exists($slug, $permissions) && ! auth()->user()->can($permissions[$slug])) {
            abort(403, 'User does not have the right permissions.');
        }

        if ($slug == 'contact') {
            $view = 'contact';
        } elseif ($slug == 'logos-favicon') {
            $view = 'logo_favicon';
        } elseif ($slug == 'social-media') {
            $view = 'social_media';
        } else {
            $view = 'index';
        }

        return view('backend.setting.'.$view, $data);
    }

    public function smtpEdit()
    {
        return view('backend.setting.smtp');
    }

    public function smtpUpdate(Request $request)
    {

        $request->validate([
            'MAIL_HOST' => 'required',
            'MAIL_PORT' => 'required',
            'MAIL_USERNAME' => 'required',
            'MAIL_PASSWORD' => 'required',
            'MAIL_FROM_ADDRESS' => 'required|email',
            'MAIL_FROM_NAME' => 'required|string',
        ]);

        $data = $request->all();

        // Check if any value of the `types` keys is filled
        $hasAtLeastOneValue = collect($request->types)->some(function ($type) use ($data) {
            return ! empty($data[$type]);
        });

        // If at least one value is submitted, validate all keys in `types`
        if ($hasAtLeastOneValue) {
            $rules = [];

            foreach ($request->types as $type) {
                $rules[$type] = 'required'; // Ensure all values are required
            }

            $request->validate($rules); // Perform validation
        }

        foreach ($request->types as $type) {
            overWriteEnvFile($type, $request->$type);
        }

        return back()->with('success', 'SMTP settings updated successfully');
    }
}
