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
        $colors = ['#8B4513', '#FF4500', '#00008B', '#800080', '#C71585', '#000000', '#B22222', '#2E8B57'];
        $lines = [
            '<line x1="5" y1="35" x2="195" y2="10" stroke="#ec4899" stroke-width="2.5" opacity="0.8"/>',
            '<line x1="10" y1="12" x2="190" y2="38" stroke="#8b5cf6" stroke-width="2" opacity="0.75"/>',
            '<line x1="25" y1="42" x2="175" y2="5" stroke="#3b82f6" stroke-width="1.5" opacity="0.7"/>'
        ];
        
        $stripes = '';
        for ($i = 0; $i < 50; $i += 4) {
            $stripes .= "<line x1='0' y1='{$i}' x2='200' y2='{$i}' stroke='#d1d5db' stroke-width='1.5'/>";
        }

        $charSvgs = '';
        $chars = str_split($code);
        $count = max(count($chars), 1);
        $xStep = 175 / $count;

        foreach ($chars as $idx => $char) {
            $x = 15 + ($idx * $xStep);
            $y = rand(30, 36);
            $color = $colors[$idx % count($colors)];
            $rot = rand(-12, 12);
            $fontSize = rand(26, 30);
            $charSvgs .= "<text x='{$x}' y='{$y}' fill='{$color}' font-size='{$fontSize}' font-family='Georgia, serif, Times New Roman' font-weight='bold' transform='rotate({$rot}, {$x}, {$y})'>{$char}</text>";
        }

        $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='200' height='46' viewBox='0 0 200 46' class='w-full h-full object-cover rounded-l-lg bg-[#e5e7eb]'>";
        $svg .= $stripes;
        $svg .= implode('', $lines);
        $svg .= $charSvgs;
        $svg .= "</svg>";

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function generateCaptcha()
    {
        $code = strtolower(Str::random(6));
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
        return view('auth.login', compact('settings', 'captchaData'));
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
