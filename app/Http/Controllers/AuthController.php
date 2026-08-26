<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SiteSetting;

class AuthController extends Controller
{
    private function generateCaptchaSvg($code)
    {
        $colors = ['#0f172a', '#047857', '#1e40af', '#991b1b', '#065f46', '#3730a3'];
        
        $charSvgs = '';
        $chars = str_split($code);
        $count = max(count($chars), 1);
        $xStep = 160 / $count;

        foreach ($chars as $idx => $char) {
            $x = 18 + ($idx * $xStep);
            $y = 32;
            $color = $colors[$idx % count($colors)];
            $rot = rand(-4, 4);
            $fontSize = 26;
            $charSvgs .= "<text x='{$x}' y='{$y}' fill='{$color}' font-size='{$fontSize}' font-family='Arial, sans-serif' font-weight='900' transform='rotate({$rot}, {$x}, {$y})'>{$char}</text>";
        }

        $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='200' height='46' viewBox='0 0 200 46' class='w-full h-full object-cover rounded-l-lg bg-slate-100'>";
        $svg .= "<rect width='200' height='46' fill='#f1f5f9'/>";
        for ($i = 10; $i < 200; $i += 20) {
            $svg .= "<line x1='{$i}' y1='0' x2='{$i}' y2='46' stroke='#e2e8f0' stroke-width='1'/>";
        }
        for ($j = 10; $j < 46; $j += 12) {
            $svg .= "<line x1='0' y1='{$j}' x2='200' y2='{$j}' stroke='#e2e8f0' stroke-width='1'/>";
        }
        $svg .= $charSvgs;
        $svg .= "</svg>";

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function generateCaptcha()
    {
        // Use unambiguous characters (no 0/O, 1/I/L)
        $pool = '23456789abcdefghjkmnpqrstuvwxyz';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $pool[rand(0, strlen($pool) - 1)];
        }
        session(['captcha_code' => $code]);
        return [
            'code' => $code,
            'svg' => $this->generateCaptchaSvg($code)
        ];
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        $captchaData = $this->generateCaptcha();
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $sliders = \App\Models\HeroSlider::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('auth.login', compact('settings', 'captchaData', 'sliders'));
    }

    public function refreshCaptcha()
    {
        $captchaData = $this->generateCaptcha();
        return response()->json($captchaData);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
            'captcha' => ['required'],
        ]);

        $sessionCaptcha = session('captcha_code');
        if (!$sessionCaptcha || strtolower(trim($request->captcha)) !== strtolower(trim($sessionCaptcha))) {
            $this->generateCaptcha();
            return back()->withErrors([
                'captcha' => 'Kode captcha / keamanan yang Anda masukkan tidak sesuai.',
            ])->withInput();
        }

        $loginInput = $request->email;
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $loginInput,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->forget('captcha_code');
            $request->session()->regenerate();

            \App\Models\ActivityLog::record('LOGIN', 'Autentikasi Sistem', 'User "' . Auth::user()->name . '" berhasil masuk ke Control Panel Admin CMS.');

            return redirect()->intended(route('admin.dashboard'));
        }

        $this->generateCaptcha();

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Models\ActivityLog::record('LOGOUT', 'Autentikasi Sistem', 'User "' . Auth::user()->name . '" keluar dari Control Panel Admin CMS.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
