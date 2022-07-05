<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Vanguard\Events\Settings\Updated as SettingsUpdated;
use Illuminate\Http\Request;
use Setting;
use Vanguard\Http\Controllers\Controller;


class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function general()
    {
        return view('settings.general');
    }

    public function auth()
    {
        return view('settings.auth');
    }

    public function update(Request $request)
    {
        $this->updatesetting($request->except("_token"));

        return back()->withSuccess(__('Settings updated successfully.'));
    }

    private function updatesetting($input)
    {
        foreach ($input as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        event(new SettingsUpdated);
    }

    public function enableTwoFactor()
    {
        $this->updatesetting(['2fa.enabled' => true]);

        return back()->withSuccess(__('Two-Factor Authentication enabled successfully.'));
    }

    public function disableTwoFactor()
    {
        $this->updatesetting(['2fa.enabled' => false]);

        return back()->withSuccess(__('Two-Factor Authentication disabled successfully.'));
    }

    public function enableCaptcha()
    {
        $this->updatesetting(['registration.captcha.enabled' => true]);

        return back()->withSuccess(__('reCAPTCHA enabled successfully.'));
    }

    public function disableCaptcha()
    {
        $this->updatesetting(['registration.captcha.enabled' => false]);

        return back()->withSuccess(__('reCAPTCHA disabled successfully.'));
    }

    public function notifications()
    {
        return view('settings.notifications');
    }
}
