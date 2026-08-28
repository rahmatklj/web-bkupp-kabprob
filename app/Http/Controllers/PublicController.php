<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\NavigationMenu;
use App\Models\HeroSlider;
use App\Models\NewsItem;
use App\Models\SidebarWidget;
use App\Models\RelatedLink;
use App\Models\Page;
use App\Models\PublicDocument;
use App\Models\UmkmProduct;
use App\Models\MarketPrice;
use App\Models\Service;
use App\Models\ContactMessage;
use App\Models\Gallery;

class PublicController extends Controller
{
    private function getCommonData()
    {
        $settingsRaw = SiteSetting::pluck('value', 'key')->toArray();
        $defaults = [
            'site_title'     => 'Website Resmi | Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian Kabupaten Probolinggo',
            'agency_name'    => 'Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian (DKUPP)',
            'regency_name'   => 'Kabupaten Probolinggo',
            'instagram_url'  => 'https://www.instagram.com/dkuppkabprobolinggo/',
            'facebook_url'   => 'https://www.facebook.com/dkuppkabprobolinggo',
            'tiktok_url'     => 'https://www.tiktok.com/@dkuppkabprobolinggo',
            'whatsapp_url'   => 'https://wa.me/6281234567890',
            'youtube_url'    => 'https://www.youtube.com/@dkuppkabprobolinggo',
            'phone'          => '(0335) 844554',
            'email'          => 'dkupp@probolinggokab.go.id',
            'address'        => 'Jl. Raya Panglima Sudirman No. 134 / Loket MPP Kraksaan, Kabupaten Probolinggo',
            'logo_frontend'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg',
            'logo_backend'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg',
            'logo_berakhlak' => '/uploads/settings/logo_berakhlak.png',
        ];

        $settings = [];
        foreach ($defaults as $k => $defVal) {
            $settings[$k] = (!isset($settingsRaw[$k]) || trim((string)$settingsRaw[$k]) === '') ? $defVal : $settingsRaw[$k];
        }
        foreach ($settingsRaw as $k => $v) {
            if (!isset($settings[$k])) {
                $settings[$k] = $v;
            }
        }

        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function($q) {
                $q->where('is_active', true)->orderBy('order', 'asc');
            }])
            ->orderBy('order', 'asc')
            ->get();

        // 1. WhatsApp Resmi CS DKUPP (Digunakan pada Footer Media Sosial & Halaman Kontak)
        $dkuppPhone = $settings['phone'] ?? '081234567890';
        $dkuppCustomWa = $settingsRaw['dkupp_whatsapp_url'] ?? '';

        if (!empty($dkuppCustomWa) && str_starts_with($dkuppCustomWa, 'http') && !str_contains($dkuppCustomWa, 'lapor.go.id')) {
            $finalDkuppWa = $dkuppCustomWa;
        } else {
            $dkuppClean = preg_replace('/[^0-9]/', '', $dkuppPhone);
            if (str_starts_with($dkuppClean, '0')) {
                $dkuppClean = '62' . substr($dkuppClean, 1);
            }
            $finalDkuppWa = 'https://wa.me/' . ($dkuppClean ?: '6281234567890');
        }
        if (!str_contains($finalDkuppWa, 'text=')) {
            $finalDkuppWa .= (str_contains($finalDkuppWa, '?') ? '&' : '?') . 'text=' . urlencode('Halo DKUPP Kabupaten Probolinggo, saya ingin menyampaikan pengaduan/konsultasi.');
        }
        $settings['dkupp_whatsapp_url'] = $finalDkuppWa;

        // 2. WhatsApp Pengaduan Hallo SAE (Digunakan pada Card Pengaduan HalloSAE Portal 5)
        $halloNum = $settings['whatsapp_number'] ?? '081234567890';
        $halloCustomWa = $settingsRaw['whatsapp_url'] ?? '';
        $halloMsg = $settings['whatsapp_default_message'] ?? 'Halo Lapor Hallo SAE Kabupaten Probolinggo, saya ingin menyampaikan pengaduan.';

        if (!empty($halloCustomWa) && str_starts_with($halloCustomWa, 'http') && !str_contains($halloCustomWa, 'lapor.go.id')) {
            $finalHalloWa = $halloCustomWa;
        } else {
            $halloClean = preg_replace('/[^0-9]/', '', $halloNum);
            if (str_starts_with($halloClean, '0')) {
                $halloClean = '62' . substr($halloClean, 1);
            }
            $finalHalloWa = 'https://wa.me/' . ($halloClean ?: '6281234567890');
        }
        if (!str_contains($finalHalloWa, 'text=')) {
            $finalHalloWa .= (str_contains($finalHalloWa, '?') ? '&' : '?') . 'text=' . urlencode($halloMsg);
        }
        $settings['hallosae_whatsapp_url'] = $finalHalloWa;
        $settings['whatsapp_url'] = $finalHalloWa;

        return compact('settings', 'navMenus');
    }

    public function index()
    {
        $data = $this->getCommonData();

        $sliders = HeroSlider::where('is_active', true)->orderBy('order', 'asc')->get();
        $latestNews = NewsItem::where('is_published', true)->orderBy('published_at', 'desc')->take(2)->get();
        $popularNews = NewsItem::where('is_published', true)->orderBy('views', 'desc')->take(5)->get();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();
        $relatedLinks = RelatedLink::where('is_active', true)->orderBy('order', 'asc')->get();

        // DKUPP specific featured data
        $featuredUmkm = UmkmProduct::where('is_featured', true)->take(6)->get();
        $marketPrices = MarketPrice::orderBy('commodity_name', 'asc')->get();
        $featuredServices = Service::where('is_active', true)->take(6)->get();
        $videos = \App\Models\Gallery::where('type', 'video')->where('is_active', true)->orderBy('created_at', 'desc')->take(4)->get();
        $photoGalleries = \App\Models\Gallery::where('type', 'image')->where('is_active', true)->orderBy('created_at', 'desc')->take(4)->get();

        return view('public.index', array_merge($data, compact(
            'sliders', 'latestNews', 'popularNews', 'sidebarWidgets', 'relatedLinks', 'featuredUmkm', 'marketPrices', 'featuredServices', 'videos', 'photoGalleries'
        )));
    }

    public function page($slug)
    {
        $data = $this->getCommonData();

        if ($slug === 'struktur-organisasi') {
            $page = (object)[
                'title' => 'Struktur Organisasi DKUPP',
                'slug' => 'struktur-organisasi',
                'content' => '',
            ];
            $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();
            $orgMembers = \App\Models\OrgMember::where('is_active', true)->orderBy('order', 'asc')->get();

            return view('public.page', array_merge($data, compact('page', 'sidebarWidgets', 'orgMembers')));
        }

        $page = Page::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();
        $orgMembers = \App\Models\OrgMember::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.page', array_merge($data, compact('page', 'sidebarWidgets', 'orgMembers')));
    }

    public function newsDetail($slug)
    {
        $data = $this->getCommonData();
        $news = NewsItem::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $news->increment('views');

        $latestNews = NewsItem::where('id', '!=', $news->id)->where('is_published', true)->orderBy('published_at', 'desc')->take(4)->get();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.news_detail', array_merge($data, compact('news', 'latestNews', 'sidebarWidgets')));
    }

    public function informasi(Request $request)
    {
        $data = $this->getCommonData();
        $query = NewsItem::where('is_published', true);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('summary', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $newsList = $query->orderBy('published_at', 'desc')->paginate(6)->withQueryString();
        $categories = NewsItem::select('category')->distinct()->pluck('category');
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.informasi', array_merge($data, compact('newsList', 'categories', 'sidebarWidgets')));
    }

    public function umkmKatalog(Request $request)
    {
        $simaduUrl = SiteSetting::get('simadu_sae_url', 'https://simadu.probolinggokab.go.id/');
        if (!empty($simaduUrl) && filter_var($simaduUrl, FILTER_VALIDATE_URL)) {
            return redirect()->away($simaduUrl);
        }

        $data = $this->getCommonData();
        $query = UmkmProduct::query();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%')
                  ->orWhere('owner_name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        $products = $query->orderBy('is_featured', 'desc')->orderBy('name', 'asc')->paginate(9)->withQueryString();
        $categories = UmkmProduct::select('category')->distinct()->pluck('category');
        $districts = UmkmProduct::select('district')->distinct()->pluck('district');

        return view('public.umkm_katalog', array_merge($data, compact('products', 'categories', 'districts')));
    }

    public function umkmDetail($slug)
    {
        $data = $this->getCommonData();
        $product = UmkmProduct::where('slug', $slug)->firstOrFail();
        $relatedProducts = UmkmProduct::where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->take(3)->get();

        return view('public.umkm_detail', array_merge($data, compact('product', 'relatedProducts')));
    }

    public function hargaPasar()
    {
        $data = $this->getCommonData();
        $marketWebUrl = SiteSetting::get('market_price_url', 'https://siskaperbapo.jatimprov.go.id/');
        $marketWebTitle = SiteSetting::get('market_price_title', 'Portal Web Resmi Pemantauan Harga Bahan Pokok (Siskaperbapo)');
        $marketWebDesc = SiteSetting::get('market_price_desc', 'Update harga komoditas pangan harian dari Pasar Kraksaan, Semampir, Paiton, dan Dringu Kabupaten Probolinggo & Jawa Timur.');
        $prices = MarketPrice::orderBy('commodity_name', 'asc')->get();
        $lastUpdate = MarketPrice::max('updated_at');

        return view('public.harga_pasar', array_merge($data, compact('marketWebUrl', 'marketWebTitle', 'marketWebDesc', 'prices', 'lastUpdate')));
    }

    public function layanan(Request $request)
    {
        $data = $this->getCommonData();
        $query = Service::where('is_active', true);

        if ($request->filled('slug')) {
            $query->where('slug', $request->slug);
        } elseif ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        $services = $query->get();
        $allServices = Service::where('is_active', true)->get();
        $categories = Service::where('is_active', true)->select('category')->distinct()->pluck('category');

        $activeSlug = $request->slug;
        $activeCategory = $request->category;

        return view('public.layanan', array_merge($data, compact('services', 'allServices', 'categories', 'activeSlug', 'activeCategory')));
    }

    public function layananDetail($slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->first();
        if ($service && $service->external_url && filter_var($service->external_url, FILTER_VALIDATE_URL)) {
            return redirect()->away($service->external_url);
        }

        return redirect()->route('layanan', ['slug' => $slug]);
    }

    public function dokumen(Request $request)
    {
        $data = $this->getCommonData();
        $query = PublicDocument::where('is_published', true);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $dbCategories = PublicDocument::distinct()->pluck('category')->filter()->values()->toArray();
        $defaultCategories = ['Perencanaan Kinerja', 'Pengukuran Kinerja', 'Pelaporan Kinerja', 'Evaluasi Kinerja'];
        $categories = array_unique(array_merge($defaultCategories, $dbCategories));

        return view('public.dokumen', array_merge($data, compact('documents', 'categories')));
    }

    public function viewDokumen($id)
    {
        $document = PublicDocument::findOrFail($id);
        $fileUrl = trim($document->file_url);

        if (filter_var($fileUrl, FILTER_VALIDATE_URL)) {
            return redirect()->away($fileUrl);
        }

        $cleanPath = ltrim(str_replace('\\', '/', $fileUrl), '/');
        $fullPath = public_path($cleanPath);

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . \Illuminate\Support\Str::slug($document->title) . '.pdf"',
            'X-Frame-Options' => 'ALLOWALL',
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'public, max-age=3600',
        ];

        if (file_exists($fullPath) && is_file($fullPath)) {
            return response()->file($fullPath, $headers);
        }

        $storagePath = storage_path('app/public/' . str_replace('storage/', '', $cleanPath));
        if (file_exists($storagePath) && is_file($storagePath)) {
            return response()->file($storagePath, $headers);
        }

        return redirect()->to(asset($cleanPath));
    }

    public function downloadDokumen($id)
    {
        $document = PublicDocument::findOrFail($id);
        $document->increment('download_count');

        $fileUrl = trim($document->file_url);

        // 1. Check if it's an external HTTP/HTTPS URL
        if (filter_var($fileUrl, FILTER_VALIDATE_URL)) {
            return redirect()->away($fileUrl);
        }

        // 2. Normalize path for local public directory
        $cleanPath = ltrim(str_replace('\\', '/', $fileUrl), '/');
        $fullPath = public_path($cleanPath);

        if (!file_exists($fullPath) || !is_file($fullPath)) {
            $fullPath = storage_path('app/public/' . str_replace('storage/', '', $cleanPath));
        }

        if (file_exists($fullPath) && is_file($fullPath)) {
            $extension = pathinfo($fullPath, PATHINFO_EXTENSION) ?: 'pdf';
            $downloadFileName = \Illuminate\Support\Str::slug($document->title) . '.' . $extension;

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $downloadFileName . '"',
                'Content-Length' => filesize($fullPath),
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'public, max-age=3600',
            ];

            return response()->file($fullPath, $headers);
        }

        // 3. Final fallback: asset URL
        return redirect()->to(asset($cleanPath));
    }

    public function kontak()
    {
        $data = $this->getCommonData();
        return view('public.kontak', $data);
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->only('name', 'email', 'phone', 'subject', 'message'));

        return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim ke DKUPP Kabupaten Probolinggo. Terima kasih!');
    }

    public function lapor()
    {
        $targetUrl = trim((string) SiteSetting::get('lapor_sp4n_url', 'https://www.lapor.go.id/'));
        if (!empty($targetUrl)) {
            if (filter_var($targetUrl, FILTER_VALIDATE_URL)) {
                return redirect()->away($targetUrl);
            } elseif (str_starts_with($targetUrl, '/')) {
                return redirect()->to($targetUrl);
            }
        }
        $data = $this->getCommonData();
        return view('public.lapor', array_merge($data, compact('targetUrl')));
    }

    public function galeri()
    {
        $data = $this->getCommonData();
        $imageGalleries = \App\Models\Gallery::where('type', 'image')->where('is_active', true)->orderBy('created_at', 'desc')->get();
        $videoGalleries = \App\Models\Gallery::where('type', 'video')->where('is_active', true)->orderBy('created_at', 'desc')->get();
        $newsWithImages = NewsItem::whereNotNull('image_url')->take(12)->get();
        return view('public.galeri', array_merge($data, compact('imageGalleries', 'videoGalleries', 'newsWithImages')));
    }
}
