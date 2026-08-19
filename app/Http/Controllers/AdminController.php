<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\NavigationMenu;
use App\Models\HeroSlider;
use App\Models\NewsItem;
use App\Models\SidebarWidget;
use App\Models\RelatedLink;
use App\Models\PublicDocument;
use App\Models\Page;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'sliders' => HeroSlider::count(),
            'menus' => NavigationMenu::count(),
            'news' => NewsItem::count(),
            'documents' => PublicDocument::count(),
            'pages' => Page::count(),
            'widgets' => SidebarWidget::count(),
            'links' => RelatedLink::count(),
            'messages' => ContactMessage::count(),
            'total_views' => NewsItem::sum('views'),
        ];

        $latestNews = NewsItem::orderBy('created_at', 'desc')->take(4)->get();
        $latestDocs = PublicDocument::orderBy('created_at', 'desc')->take(4)->get();

        return view('admin.dashboard', compact('stats', 'latestNews', 'latestDocs'));
    }

    // --- 1. HERO SLIDERS CRUD ---
    public function sliders()
    {
        $sliders = HeroSlider::orderBy('order', 'asc')->get();
        return view('admin.sliders', compact('sliders'));
    }

    public function sliderStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255', 'image_url' => 'required|string']);
        HeroSlider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_url' => $request->image_url,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Banner Slider berhasil ditambahkan!');
    }

    public function sliderUpdate(Request $request, $id)
    {
        $slider = HeroSlider::findOrFail($id);
        $slider->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_url' => $request->image_url,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Banner Slider berhasil diperbarui!');
    }

    public function sliderToggleStatus($id)
    {
        $slider = HeroSlider::findOrFail($id);
        $slider->is_active = !$slider->is_active;
        $slider->save();
        $statusText = $slider->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Banner '{$slider->title}' berhasil {$statusText}!");
    }

    public function sliderDestroy($id)
    {
        HeroSlider::findOrFail($id)->delete();
        return back()->with('success', 'Banner Slider berhasil dihapus!');
    }

    // --- 2. NAVIGATION MENUS CRUD ---
    public function menus()
    {
        $menus = NavigationMenu::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();
        $allParents = NavigationMenu::whereNull('parent_id')->orderBy('order', 'asc')->get();
        return view('admin.menus', compact('menus', 'allParents'));
    }

    public function menuStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255', 'url' => 'required|string']);
        NavigationMenu::create([
            'title' => $request->title,
            'url' => $request->url,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'target' => $request->target ?? '_self',
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Menu Navigasi berhasil ditambahkan!');
    }

    public function menuUpdate(Request $request, $id)
    {
        $menu = NavigationMenu::findOrFail($id);
        $menu->update([
            'title' => $request->title,
            'url' => $request->url,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'target' => $request->target ?? '_self',
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Menu Navigasi berhasil diperbarui!');
    }

    public function menuDestroy($id)
    {
        NavigationMenu::findOrFail($id)->delete();
        return back()->with('success', 'Menu Navigasi berhasil dihapus!');
    }

    // --- 3. NEWS & INFORMASI CRUD ---
    public function news()
    {
        $newsList = NewsItem::orderBy('published_at', 'desc')->paginate(10);
        return view('admin.news', compact('newsList'));
    }

    public function newsStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        NewsItem::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'summary' => $request->summary ?? Str::limit(strip_tags($request->content), 120),
            'content' => $request->content,
            'image_url' => $request->image_url,
            'category' => $request->category ?? 'Peringatan Dini',
            'published_at' => $request->published_at ?? now(),
        ]);
        return back()->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function newsUpdate(Request $request, $id)
    {
        $news = NewsItem::findOrFail($id);
        $news->update([
            'title' => $request->title,
            'summary' => $request->summary ?? Str::limit(strip_tags($request->content), 120),
            'content' => $request->content,
            'image_url' => $request->image_url,
            'category' => $request->category,
            'published_at' => $request->published_at,
        ]);
        return back()->with('success', 'Berita berhasil diperbarui!');
    }

    public function newsDestroy($id)
    {
        NewsItem::findOrFail($id)->delete();
        return back()->with('success', 'Berita berhasil dihapus!');
    }

    // --- 4. PUBLIC DOCUMENTS CRUD ---
    public function documents()
    {
        $documents = PublicDocument::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.documents', compact('documents'));
    }

    public function documentStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
        ]);

        $fileUrl = $request->file_url;

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'dokumen_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/documents');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $fileUrl = '/uploads/documents/' . $filename;
        }

        PublicDocument::create([
            'title' => $request->title,
            'category' => $request->category,
            'file_url' => $fileUrl ?: '#',
        ]);
        return back()->with('success', 'Dokumen Kinerja berhasil ditambahkan!');
    }

    public function documentUpdate(Request $request, $id)
    {
        $doc = PublicDocument::findOrFail($id);
        $fileUrl = $request->file_url ?: $doc->file_url;

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'dokumen_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/documents');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $fileUrl = '/uploads/documents/' . $filename;
        }

        $doc->update([
            'title' => $request->title,
            'category' => $request->category,
            'file_url' => $fileUrl,
        ]);
        return back()->with('success', 'Dokumen Kinerja berhasil diperbarui!');
    }

    public function documentDestroy($id)
    {
        PublicDocument::findOrFail($id)->delete();
        return back()->with('success', 'Dokumen berhasil dihapus!');
    }

    // --- 5. PAGES CRUD ---
    public function pages()
    {
        $pages = Page::orderBy('updated_at', 'desc')->get();
        return view('admin.pages', compact('pages'));
    }

    public function pageStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx|max:10240',
        ]);

        $imagePath = $request->image;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/pages');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $imagePath = '/uploads/pages/' . $filename;
        }

        Page::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => $request->has('is_published') ? (bool)$request->is_published : true,
        ]);

        \App\Models\ActivityLog::record('CREATE', 'Halaman Statis', 'Membuat Halaman Statis baru: ' . $request->title);

        return back()->with('success', 'Halaman berhasil dibuat!');
    }

    public function pageUpdate(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx|max:10240',
        ]);

        $imagePath = $request->image ?: $page->image;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/pages');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $imagePath = '/uploads/pages/' . $filename;
        }

        $page->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => $request->has('is_published') ? (bool)$request->is_published : $page->is_published,
        ]);

        \App\Models\ActivityLog::record('UPDATE', 'Halaman Statis', 'Memperbarui Halaman Statis & Lampiran File: ' . $request->title);

        return back()->with('success', 'Halaman berhasil diperbarui!');
    }

    public function pageToggleStatus($id)
    {
        $page = Page::findOrFail($id);
        $page->is_published = !$page->is_published;
        $page->save();
        $statusText = $page->is_published ? 'dipublikasikan' : 'dinonaktifkan (Draft)';
        return back()->with('success', "Status halaman '{$page->title}' berhasil {$statusText}!");
    }

    public function pageDestroy($id)
    {
        Page::findOrFail($id)->delete();
        return back()->with('success', 'Halaman berhasil dihapus!');
    }

    // --- 6. SIDEBAR WIDGETS CRUD ---
    public function widgets()
    {
        $widgets = SidebarWidget::orderBy('order', 'asc')->get();
        return view('admin.widgets', compact('widgets'));
    }

    public function widgetStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255', 'image_url' => 'required|string']);
        SidebarWidget::create([
            'title' => $request->title,
            'image_url' => $request->image_url,
            'target_url' => $request->target_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Widget Sidebar berhasil ditambahkan!');
    }

    public function widgetUpdate(Request $request, $id)
    {
        $widget = SidebarWidget::findOrFail($id);
        $widget->update([
            'title' => $request->title,
            'image_url' => $request->image_url,
            'target_url' => $request->target_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Widget Sidebar berhasil diperbarui!');
    }

    public function widgetToggleStatus($id)
    {
        $widget = SidebarWidget::findOrFail($id);
        $widget->is_active = !$widget->is_active;
        $widget->save();
        $statusText = $widget->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Widget '{$widget->title}' berhasil {$statusText}!");
    }

    public function widgetDestroy($id)
    {
        SidebarWidget::findOrFail($id)->delete();
        return back()->with('success', 'Widget Sidebar berhasil dihapus!');
    }

    // --- 7. RELATED LINKS CRUD ---
    public function links()
    {
        $links = RelatedLink::orderBy('order', 'asc')->get();
        return view('admin.links', compact('links'));
    }

    public function linkStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255', 'image_url' => 'required|string']);
        RelatedLink::create([
            'title' => $request->title,
            'image_url' => $request->image_url,
            'url' => $request->url ?? '#',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Tautan Terkait berhasil ditambahkan!');
    }

    public function linkUpdate(Request $request, $id)
    {
        $link = RelatedLink::findOrFail($id);
        $link->update([
            'title' => $request->title,
            'image_url' => $request->image_url,
            'url' => $request->url ?? '#',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Tautan Terkait berhasil diperbarui!');
    }

    public function linkDestroy($id)
    {
        RelatedLink::findOrFail($id)->delete();
        return back()->with('success', 'Tautan Terkait berhasil dihapus!');
    }

    // --- 8. CONTACT MESSAGES & SP4N LAPOR! CRUD ---
    public function messages()
    {
        $defaultLogo = 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg';
        $laporSp4nUrl = SiteSetting::get('lapor_sp4n_url', 'https://www.lapor.go.id/');
        $laporSp4nLogo = SiteSetting::get('lapor_sp4n_logo', $defaultLogo);
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.messages', compact('laporSp4nUrl', 'laporSp4nLogo', 'messages'));
    }

    public function laporSp4nSettingsStore(Request $request)
    {
        if ($request->filled('lapor_sp4n_url')) {
            SiteSetting::set('lapor_sp4n_url', $request->lapor_sp4n_url);
        }
        if ($request->filled('lapor_sp4n_logo')) {
            SiteSetting::set('lapor_sp4n_logo', $request->lapor_sp4n_logo);
        }
        if ($request->hasFile('lapor_sp4n_logo_file')) {
            $file = $request->file('lapor_sp4n_logo_file');
            $filename = 'logo_lapor_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('lapor_sp4n_logo', '/uploads/settings/' . $filename);
        }
        \App\Models\ActivityLog::record('UPDATE', 'SP4N LAPOR!', 'Memperbarui Target Link & Logo Pengaduan SP4N LAPOR!.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Target Link & Logo Pengaduan SP4N LAPOR! berhasil disimpan!');
    }

    public function messageStatus(Request $request, $id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->update(['status' => $request->status]);
        return back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    public function messageDestroy($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return back()->with('success', 'Laporan berhasil dihapus!');
    }

    // --- 9. USERS & ROLES CRUD ---
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:super_admin,anggota',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return back()->with('success', 'Data User berhasil diperbarui!');
    }

    public function userDestroy($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }
        User::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    // --- 10. SITE SETTINGS ---
    public function settings()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        if ($request->hasFile('maklumat_file')) {
            $file = $request->file('maklumat_file');
            $filename = 'maklumat_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $file->move($targetDir, $filename);
            SiteSetting::set('maklumat_image', '/uploads/settings/' . $filename);
        }

        if ($request->hasFile('qr_file')) {
            $file = $request->file('qr_file');
            $filename = 'qr_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $file->move($targetDir, $filename);
            SiteSetting::set('qr_code_image', '/uploads/settings/' . $filename);
        }

        if ($request->hasFile('market_price_logo_file')) {
            $file = $request->file('market_price_logo_file');
            $filename = 'logo_market_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('market_price_logo', '/uploads/settings/' . $filename);
        }

        if ($request->hasFile('simadu_sae_logo_file')) {
            $file = $request->file('simadu_sae_logo_file');
            $filename = 'logo_simadu_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('simadu_sae_logo', '/uploads/settings/' . $filename);
        }

        if ($request->hasFile('ppid_logo_file')) {
            $file = $request->file('ppid_logo_file');
            $filename = 'logo_ppid_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('ppid_logo', '/uploads/settings/' . $filename);
        }

        $inputs = $request->except(['_token', 'maklumat_file', 'qr_file', 'market_price_logo_file', 'simadu_sae_logo_file', 'ppid_logo_file']);
        foreach ($inputs as $key => $value) {
            $val = is_string($value) ? trim($value) : $value;
            SiteSetting::set($key, $val);
        }
        \App\Models\ActivityLog::record('UPDATE', 'Pengaturan Website', 'Memperbarui Konfigurasi Website, Identitas & Kode QR Footer.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Pengaturan Website, Maklumat & Kode QR berhasil disimpan!');
    }

    // --- 11. SIMADU SAE UMKM PRODUCTS CRUD ---
    public function umkm()
    {
        $defaultLogo = 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg';
        $simaduUrl = SiteSetting::get('simadu_sae_url', 'https://simadu.probolinggokab.go.id/');
        $simaduLogo = SiteSetting::get('simadu_sae_logo', $defaultLogo);
        $products = \App\Models\UmkmProduct::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.umkm', compact('simaduUrl', 'simaduLogo', 'products'));
    }

    public function umkmSettingsStore(Request $request)
    {
        if ($request->filled('simadu_sae_url')) {
            SiteSetting::set('simadu_sae_url', $request->simadu_sae_url);
        }
        if ($request->filled('simadu_sae_logo')) {
            SiteSetting::set('simadu_sae_logo', $request->simadu_sae_logo);
        }
        if ($request->hasFile('simadu_sae_logo_file')) {
            $file = $request->file('simadu_sae_logo_file');
            $filename = 'logo_simadu_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('simadu_sae_logo', '/uploads/settings/' . $filename);
        }
        \App\Models\ActivityLog::record('UPDATE', 'SIMADU SAE', 'Memperbarui Target Link & Logo SIMADU SAE.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Target Link & Logo Portal SIMADU SAE berhasil disimpan!');
    }

    public function umkmStore(Request $request)
    {
        $request->validate([
            'website_url' => 'required|url',
        ]);

        $url = $request->website_url;
        $host = parse_url($url, PHP_URL_HOST) ?: $url;
        $hostClean = preg_replace('/^www\./i', '', $host);

        $name = $request->name ?: 'Katalog Web ' . ucfirst($hostClean);

        \App\Models\UmkmProduct::create([
            'name' => $name,
            'slug' => Str::slug($name) . '-' . time(),
            'owner_name' => 'Mitra UMKM Probolinggo',
            'category' => 'Katalog Web',
            'district' => 'Kab. Probolinggo',
            'description' => 'Website Resmi Katalog Produk UMKM Kabupaten Probolinggo (' . $hostClean . ')',
            'price' => 0,
            'price_unit' => 'unit',
            'phone' => null,
            'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=600&auto=format&fit=crop',
            'website_url' => $url,
            'is_featured' => $request->has('is_featured'),
            'is_verified' => true,
        ]);

        return back()->with('success', 'Link Website UMKM berhasil diposting!');
    }

    public function umkmUpdate(Request $request, $id)
    {
        $product = \App\Models\UmkmProduct::findOrFail($id);

        $url = $request->website_url ?: $product->website_url;
        $host = parse_url($url, PHP_URL_HOST) ?: $url;
        $hostClean = preg_replace('/^www\./i', '', $host);

        $name = $request->name ?: ($product->name ?: 'Katalog Web ' . ucfirst($hostClean));

        $product->update([
            'name' => $name,
            'website_url' => $url,
            'is_featured' => $request->has('is_featured'),
            'is_verified' => true,
        ]);

        return back()->with('success', 'Link Website UMKM berhasil diperbarui!');
    }

    public function umkmDestroy($id)
    {
        \App\Models\UmkmProduct::findOrFail($id)->delete();
        return back()->with('success', 'Produk UMKM berhasil dihapus!');
    }

    // --- 12. MARKET PRICES CRUD ---
    public function marketPrices()
    {
        $defaultLogo = 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg';
        $marketWebUrl = \App\Models\SiteSetting::get('market_price_url', 'https://siskaperbapo.jatimprov.go.id/');
        $marketLogo = \App\Models\SiteSetting::get('market_price_logo', $defaultLogo);
        $marketWebTitle = \App\Models\SiteSetting::get('market_price_title', 'Portal Web Resmi Pemantauan Harga Bahan Pokok (Siskaperbapo)');
        $marketWebDesc = \App\Models\SiteSetting::get('market_price_desc', 'Update harga komoditas pangan harian dari Pasar Kraksaan, Semampir, Paiton, dan Dringu Kabupaten Probolinggo & Jawa Timur.');
        $prices = \App\Models\MarketPrice::orderBy('commodity_name', 'asc')->get();

        return view('admin.market_prices', compact('marketWebUrl', 'marketLogo', 'marketWebTitle', 'marketWebDesc', 'prices'));
    }

    public function marketPricesStore(Request $request)
    {
        if ($request->has('website_url') && !$request->has('commodity_name')) {
            $request->validate([
                'website_url' => 'required',
            ]);
            \App\Models\SiteSetting::set('market_price_url', $request->website_url);
            if ($request->filled('title')) {
                \App\Models\SiteSetting::set('market_price_title', $request->title);
            }
            if ($request->filled('description')) {
                \App\Models\SiteSetting::set('market_price_desc', $request->description);
            }
            if ($request->filled('market_price_logo')) {
                \App\Models\SiteSetting::set('market_price_logo', $request->market_price_logo);
            }
            if ($request->hasFile('market_price_logo_file')) {
                $file = $request->file('market_price_logo_file');
                $filename = 'logo_market_' . time() . '.' . $file->getClientOriginalExtension();
                $targetDir = public_path('uploads/settings');
                if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
                $file->move($targetDir, $filename);
                \App\Models\SiteSetting::set('market_price_logo', '/uploads/settings/' . $filename);
            }
            \App\Models\ActivityLog::record('UPDATE', 'Harga Pasar', 'Memperbarui Link & Logo Website Siskaperbapo.');
            return back()->with('success', 'Link & Logo Website Pemantauan Harga Pasar (Siskaperbapo) berhasil disimpan!');
        }

        $request->validate([
            'commodity_name' => 'required|string',
            'price_today' => 'required|numeric',
        ]);

        \App\Models\MarketPrice::create([
            'commodity_name' => $request->commodity_name,
            'unit' => $request->unit ?? 'Kg',
            'price_today' => $request->price_today,
            'price_yesterday' => $request->price_yesterday ?? $request->price_today,
            'status' => $request->status ?? 'stabil',
            'market_location' => $request->market_location ?? 'Pasar Kraksaan',
            'website_url' => $request->website_url ?: 'https://siskaperbapo.jatimprov.go.id/',
            'updated_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Data Harga Komoditas berhasil ditambahkan!');
    }

    public function marketPricesUpdate(Request $request, $id)
    {
        $price = \App\Models\MarketPrice::findOrFail($id);
        $price->update([
            'commodity_name' => $request->commodity_name,
            'unit' => $request->unit,
            'price_today' => $request->price_today,
            'price_yesterday' => $request->price_yesterday,
            'status' => $request->status,
            'market_location' => $request->market_location,
            'updated_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Data Harga Komoditas berhasil diperbarui!');
    }

    public function marketPricesDestroy($id)
    {
        \App\Models\MarketPrice::findOrFail($id)->delete();
        return back()->with('success', 'Data Harga Komoditas berhasil dihapus!');
    }

    // --- 13. STRUKTUR ORGANISASI CRUD ---
    public function orgMembers()
    {
        $members = \App\Models\OrgMember::with('parent')->orderBy('order', 'asc')->get();
        return view('admin.org_members', compact('members'));
    }

    public function orgMemberStore(Request $request)
    {
        $request->validate([
            'position' => 'required|string|max:255',
        ]);

        \App\Models\OrgMember::create([
            'name' => $request->name,
            'position' => $request->position,
            'type' => $request->type ?? 'personel',
            'parent_id' => $request->parent_id ? (int)$request->parent_id : null,
            'photo' => $request->photo,
            'order' => $request->order ?? 1,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Anggota struktur organisasi berhasil ditambahkan!');
    }

    public function orgMemberUpdate(Request $request, $id)
    {
        $member = \App\Models\OrgMember::findOrFail($id);
        $request->validate([
            'position' => 'required|string|max:255',
        ]);

        $member->update([
            'name' => $request->name,
            'position' => $request->position,
            'type' => $request->type ?? 'personel',
            'parent_id' => $request->parent_id ? (int)$request->parent_id : null,
            'photo' => $request->photo,
            'order' => $request->order ?? 1,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Anggota struktur organisasi berhasil diperbarui!');
    }

    public function orgMemberToggleStatus($id)
    {
        $member = \App\Models\OrgMember::findOrFail($id);
        $member->update(['is_active' => !$member->is_active]);
        $statusText = $member->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status anggota {$member->position} berhasil {$statusText}!");
    }

    public function orgMemberDestroy($id)
    {
        \App\Models\OrgMember::findOrFail($id)->delete();
        return back()->with('success', 'Anggota struktur organisasi berhasil dihapus!');
    }

    // --- 14. GALERI FOTO & VIDEO CRUD ---
    public function gallery(Request $request)
    {
        $tab = $request->query('tab', 'image');
        $galleries = \App\Models\Gallery::where('type', $tab)->orderBy('created_at', 'desc')->get();
        return view('admin.gallery', compact('galleries', 'tab'));
    }

    public function galleryStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'youtube_url' => 'nullable|url',
            'file_upload' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:20480',
        ]);

        $filePath = $request->file_path;

        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gallery'), $filename);
            $filePath = '/uploads/gallery/' . $filename;
        }

        \App\Models\Gallery::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $filePath,
            'youtube_url' => $request->youtube_url,
            'caption' => $request->caption,
            'is_active' => true,
        ]);

        $typeName = $request->type == 'video' ? 'Video YouTube' : 'Foto Galeri';
        \App\Models\ActivityLog::record('CREATE', $request->type == 'video' ? 'Galeri Video' : 'Galeri Foto', 'Menambahkan ' . $typeName . ' baru: ' . $request->title);

        return back()->with('success', "{$typeName} berhasil ditambahkan!");
    }

    public function galleryDestroy($id)
    {
        $item = \App\Models\Gallery::findOrFail($id);
        $title = $item->title;
        $type = $item->type == 'video' ? 'Video' : 'Foto';
        $item->delete();

        \App\Models\ActivityLog::record('DELETE', 'Galeri ' . $type, 'Menghapus item galeri: ' . $title);
        return back()->with('success', 'Item galeri berhasil dihapus!');
    }

    // --- 15. LOG AKTIVITAS SYSTEM ---
    public function activityLogs(Request $request)
    {
        $query = \App\Models\ActivityLog::query();

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->q . '%')
                  ->orWhere('user_name', 'like', '%' . $request->q . '%')
                  ->orWhere('module', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.activity_logs', compact('logs'));
    }

    public function activityLogsClear()
    {
        \App\Models\ActivityLog::truncate();
        \App\Models\ActivityLog::record('DELETE', 'Log Sistem', 'Seluruh data log aktivitas sistem telah dibersihkan oleh Super Admin.');
        return back()->with('success', 'Seluruh data log aktivitas berhasil dibersihkan!');
    }

    // --- 16. PPID LINK WEB CRUD ---
    public function ppid()
    {
        $defaultLogo = 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg';
        $ppidUrl = SiteSetting::get('ppid_url', '/halaman/ppid-dkupp');
        $ppidLogo = SiteSetting::get('ppid_logo', $defaultLogo);
        $ppidTitle = SiteSetting::get('ppid_title', 'PPID DKUPP Kabupaten Probolinggo');
        $ppidDesc = SiteSetting::get('ppid_desc', 'Layanan Keterbukaan Informasi Publik, Daftar Informasi Publik (DIP), dan Permohonan Informasi Resmi.');
        return view('admin.ppid', compact('ppidUrl', 'ppidLogo', 'ppidTitle', 'ppidDesc'));
    }

    public function ppidUpdate(Request $request)
    {
        $request->validate([
            'ppid_url' => 'required|string',
            'ppid_title' => 'required|string',
        ]);

        SiteSetting::set('ppid_url', $request->ppid_url);
        SiteSetting::set('ppid_title', $request->ppid_title);
        SiteSetting::set('ppid_desc', $request->ppid_desc);

        if ($request->filled('ppid_logo')) {
            SiteSetting::set('ppid_logo', $request->ppid_logo);
        }

        if ($request->hasFile('ppid_logo_file')) {
            $file = $request->file('ppid_logo_file');
            $filename = 'logo_ppid_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('ppid_logo', '/uploads/settings/' . $filename);
        }

        \App\Models\ActivityLog::record('UPDATE', 'Link Web PPID', 'Memperbarui Link & Logo Website PPID DKUPP: ' . $request->ppid_url);
        \Illuminate\Support\Facades\Cache::flush();

        return back()->with('success', 'Link & Logo Website PPID DKUPP berhasil diperbarui!');
    }

    // --- 17. MAKLUMAT PELAYANAN CRUD ---
    public function maklumat()
    {
        $defaultText = 'DENGAN INI, KAMI MENYATAKAN SANGGUP MENYELENGGARAKAN PELAYANAN SESUAI STANDAR PELAYANAN YANG TELAH DITETAPKAN DAN APABILA TIDAK MENEPATI JANJI, KAMI SIAP MENERIMA SANKSI SESUAI PERATURAN PERUNDANG-UNDANGAN YANG BERLAKU.';
        $maklumatText = SiteSetting::get('maklumat_text', $defaultText);
        $maklumatImage = SiteSetting::get('maklumat_image', '');
        return view('admin.maklumat', compact('maklumatText', 'maklumatImage'));
    }

    public function maklumatUpdate(Request $request)
    {
        if ($request->filled('maklumat_text')) {
            SiteSetting::set('maklumat_text', $request->maklumat_text);
        }
        if ($request->filled('maklumat_image')) {
            SiteSetting::set('maklumat_image', $request->maklumat_image);
        }
        if ($request->hasFile('maklumat_file')) {
            $file = $request->file('maklumat_file');
            $filename = 'maklumat_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('maklumat_image', '/uploads/settings/' . $filename);
        }
        \App\Models\ActivityLog::record('UPDATE', 'Maklumat Pelayanan', 'Memperbarui Teks & Foto Poster Scan Maklumat Pelayanan.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Teks Pernyataan & Poster Scan Maklumat Pelayanan berhasil disimpan!');
    }

    // --- 18. KODE QR & SKM CRUD ---
    public function qrCode()
    {
        $qrCodeLabel = SiteSetting::get('qr_code_label', 'Scan QR Portal Pelayanan & Survei SKM');
        $qrCodeImage = SiteSetting::get('qr_code_image', '');
        return view('admin.qr_code', compact('qrCodeLabel', 'qrCodeImage'));
    }

    public function qrCodeUpdate(Request $request)
    {
        if ($request->filled('qr_code_label')) {
            SiteSetting::set('qr_code_label', $request->qr_code_label);
        }
        if ($request->filled('qr_code_image')) {
            SiteSetting::set('qr_code_image', $request->qr_code_image);
        }
        if ($request->hasFile('qr_file')) {
            $file = $request->file('qr_file');
            $filename = 'qr_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('qr_code_image', '/uploads/settings/' . $filename);
        }
        \App\Models\ActivityLog::record('UPDATE', 'Kode QR & SKM', 'Memperbarui Label & Foto Kode QR Footer & SKM.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Label & Foto Kode QR Footer berhasil disimpan!');
    }
}
