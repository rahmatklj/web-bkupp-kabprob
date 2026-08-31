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
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'image_file.mimes' => '⚠️ HANYA BERKAS GAMBAR BERFORMAT JPG DAN PNG (.jpg, .jpeg, .png) YANG DIPERBOLEHKAN!'
        ]);

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'slider_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/sliders');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $imageUrl = '/uploads/sliders/' . $filename;
        }

        HeroSlider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_url' => $imageUrl ?: '#',
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        \App\Models\ActivityLog::record('CREATE', 'Banner Sliders', 'Menambahkan banner slider baru: ' . $request->title);
        return back()->with('success', 'Banner Slider berhasil ditambahkan!');
    }

    public function sliderUpdate(Request $request, $id)
    {
        $slider = HeroSlider::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'image_file.mimes' => '⚠️ HANYA BERKAS GAMBAR BERFORMAT JPG DAN PNG (.jpg, .jpeg, .png) YANG DIPERBOLEHKAN!'
        ]);

        $imageUrl = $request->hasFile('image_file') ? null : ($request->image_url ?: $slider->image_url);
        if ($request->hasFile('image_file')) {
            \App\Traits\DeletesUploadFiles::deleteUploadFile($slider->image_url);
            $file = $request->file('image_file');
            $filename = 'slider_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/sliders');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $imageUrl = '/uploads/sliders/' . $filename;
        }

        $slider->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_url' => $imageUrl,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        \App\Models\ActivityLog::record('UPDATE', 'Banner Sliders', 'Memperbarui banner slider: ' . $request->title);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string',
            'menu_file' => 'nullable|file|mimes:pdf|max:25600',
        ], [
            'menu_file.mimes' => 'Hanya berkas berformat PDF (.pdf) yang diperbolehkan untuk diunggah!'
        ]);

        $url = $request->url ?: '#';
        $target = $request->target ?? '_self';

        if ($request->hasFile('menu_file')) {
            $file = $request->file('menu_file');
            $filename = 'menu_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/menus');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            $url = '/uploads/menus/' . $filename;
            if (!$request->filled('target') || $request->target === '_self') {
                $target = '_blank';
            }
        }

        $parentId = ($request->filled('parent_id') && $request->parent_id !== '__new__') ? (int)$request->parent_id : null;

        if ($request->filled('new_parent_title')) {
            $newTitle = mb_strtoupper(trim($request->new_parent_title));
            $existingParent = NavigationMenu::whereNull('parent_id')
                ->where('title', 'like', $newTitle)
                ->first();
            if ($existingParent) {
                $parentId = $existingParent->id;
            } else {
                $maxOrder = NavigationMenu::whereNull('parent_id')->max('order') ?? 0;
                $newParent = NavigationMenu::create([
                    'title' => $newTitle,
                    'url' => '#',
                    'parent_id' => null,
                    'order' => $maxOrder + 1,
                    'target' => '_self',
                    'is_active' => true,
                ]);
                $parentId = $newParent->id;
            }
        }

        NavigationMenu::create([
            'title' => $request->title,
            'url' => $url,
            'parent_id' => $parentId,
            'order' => $request->order ?? 0,
            'target' => $target,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Menu Navigasi berhasil ditambahkan!');
    }

    public function menuUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string',
            'menu_file' => 'nullable|file|mimes:pdf|max:25600',
        ], [
            'menu_file.mimes' => 'Hanya berkas berformat PDF (.pdf) yang diperbolehkan untuk diunggah!'
        ]);

        $menu = NavigationMenu::findOrFail($id);
        $url = $request->filled('url') ? $request->url : $menu->url;
        $target = $request->target ?? $menu->target;

        if ($request->hasFile('menu_file')) {
            if ($menu->url && str_contains($menu->url, '/uploads/menus/')) {
                \App\Traits\DeletesUploadFiles::deleteUploadFile($menu->url);
            }
            $file = $request->file('menu_file');
            $filename = 'menu_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/menus');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            $url = '/uploads/menus/' . $filename;
            if (!$request->filled('target') || $request->target === '_self') {
                $target = '_blank';
            }
        }

        $parentId = ($request->filled('parent_id') && $request->parent_id !== '__new__') ? (int)$request->parent_id : $menu->parent_id;

        if ($request->filled('new_parent_title')) {
            $newTitle = mb_strtoupper(trim($request->new_parent_title));
            $existingParent = NavigationMenu::whereNull('parent_id')
                ->where('title', 'like', $newTitle)
                ->first();
            if ($existingParent) {
                $parentId = $existingParent->id;
            } else {
                $maxOrder = NavigationMenu::whereNull('parent_id')->max('order') ?? 0;
                $newParent = NavigationMenu::create([
                    'title' => $newTitle,
                    'url' => '#',
                    'parent_id' => null,
                    'order' => $maxOrder + 1,
                    'target' => '_self',
                    'is_active' => true,
                ]);
                $parentId = $newParent->id;
            }
        }

        $menu->update([
            'title' => $request->title,
            'url' => $url,
            'parent_id' => $parentId,
            'order' => $request->order ?? 0,
            'target' => $target,
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
        $dbCategories = NewsItem::distinct()->pluck('category')->filter()->values()->toArray();
        $defaultCategories = ['Berita Utama', 'Pengumuman', 'Koperasi', 'Usaha Mikro & UMKM', 'Perdagangan', 'Perindustrian', 'Metrologi Legal'];
        $categories = array_values(array_unique(array_merge($defaultCategories, $dbCategories)));

        return view('admin.news', compact('newsList', 'categories'));
    }

    public function newsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'image_file.mimes' => 'Hanya berkas gambar berformat JPG dan PNG (.jpg, .jpeg, .png) yang diperbolehkan untuk diunggah!'
        ]);
        
        $category = $request->custom_category ?: ($request->category ?: 'Berita Utama');
        $imageUrl = $request->image_url;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'news_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/news');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            $imageUrl = '/uploads/news/' . $filename;
        }

        NewsItem::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'summary' => $request->summary ?? Str::limit(strip_tags($request->content), 120),
            'content' => $request->content,
            'image_url' => $imageUrl,
            'category' => $category,
            'published_at' => $request->published_at ?? now(),
            'is_published' => $request->has('is_published') ? (bool)$request->is_published : true,
        ]);

        \App\Models\ActivityLog::record('CREATE', 'Berita & Informasi', 'Mempublikasikan berita baru: ' . $request->title);
        return back()->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function newsUpdate(Request $request, $id)
    {
        $news = NewsItem::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'image_file.mimes' => 'Hanya berkas gambar berformat JPG dan PNG (.jpg, .jpeg, .png) yang diperbolehkan untuk diunggah!'
        ]);

        $category = $request->custom_category ?: ($request->category ?: $news->category);
        $imageUrl = $request->image_url ?: $news->image_url;

        if ($request->hasFile('image_file')) {
            \App\Traits\DeletesUploadFiles::deleteUploadFile($news->image_url);
            $file = $request->file('image_file');
            $filename = 'news_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/news');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            $imageUrl = '/uploads/news/' . $filename;
        }

        $news->update([
            'title' => $request->title,
            'summary' => $request->summary ?? Str::limit(strip_tags($request->content), 120),
            'content' => $request->content,
            'image_url' => $imageUrl,
            'category' => $category,
            'published_at' => $request->published_at,
            'is_published' => $request->has('is_published') ? (bool)$request->is_published : $news->is_published,
        ]);

        \App\Models\ActivityLog::record('UPDATE', 'Berita & Informasi', 'Memperbarui berita: ' . $request->title);
        return back()->with('success', 'Berita berhasil diperbarui!');
    }

    public function newsToggleStatus($id)
    {
        $news = NewsItem::findOrFail($id);
        $news->is_published = !$news->is_published;
        $news->save();
        $statusText = $news->is_published ? 'dipublikasikan (Aktif)' : 'dinonaktifkan (Draft)';
        return back()->with('success', "Status berita '{$news->title}' berhasil {$statusText}!");
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
        $dbCategories = PublicDocument::distinct()->pluck('category')->filter()->values()->toArray();
        $defaultCategories = ['Perencanaan Kinerja', 'Pengukuran Kinerja', 'Pelaporan Kinerja', 'Evaluasi Kinerja'];
        $categories = array_values(array_unique(array_merge($defaultCategories, $dbCategories)));

        return view('admin.documents', compact('documents', 'categories'));
    }

    public function documentStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:25600',
        ], [
            'pdf_file.mimes' => '⚠️ HANYA BERKAS BERFORMAT PDF (.pdf) YANG DIPERBOLEHKAN! Berkas lain selain PDF tidak diperbolehkan.',
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
            'is_published' => $request->has('is_published') ? (bool)$request->is_published : true,
        ]);

        \App\Models\ActivityLog::record('CREATE', 'Dokumen Kinerja', 'Menambahkan dokumen PDF: ' . $request->title);
        return back()->with('success', 'Dokumen PDF Kinerja berhasil diunggah & siap dibaca di website!');
    }

    public function documentUpdate(Request $request, $id)
    {
        $doc = PublicDocument::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:25600',
        ], [
            'pdf_file.mimes' => '⚠️ HANYA BERKAS BERFORMAT PDF (.pdf) YANG DIPERBOLEHKAN! Berkas lain selain PDF tidak diperbolehkan.',
        ]);

        $fileUrl = $request->file_url ?: $doc->file_url;

        if ($request->hasFile('pdf_file')) {
            \App\Traits\DeletesUploadFiles::deleteUploadFile($doc->file_url);
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
            'is_published' => $request->has('is_published') ? (bool)$request->is_published : $doc->is_published,
        ]);

        \App\Models\ActivityLog::record('UPDATE', 'Dokumen Kinerja', 'Memperbarui dokumen PDF: ' . $request->title);
        return back()->with('success', 'Dokumen PDF Kinerja berhasil diperbarui!');
    }

    public function documentToggleStatus($id)
    {
        $doc = PublicDocument::findOrFail($id);
        $doc->is_published = !$doc->is_published;
        $doc->save();
        $statusText = $doc->is_published ? 'dipublikasikan' : 'dinonaktifkan (Draft)';
        return back()->with('success', "Status dokumen '{$doc->title}' berhasil {$statusText}!");
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
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'image_file.mimes' => 'Hanya berkas gambar berformat JPG dan PNG (.jpg, .jpeg, .png) yang diperbolehkan!'
        ]);

        $imagePath = $request->image ?: $page->image;

        if ($request->hasFile('image_file')) {
            \App\Traits\DeletesUploadFiles::deleteUploadFile($page->image);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'image_file.mimes' => '⚠️ HANYA BERKAS GAMBAR BERFORMAT JPG DAN PNG (.jpg, .jpeg, .png) YANG DIPERBOLEHKAN!'
        ]);

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'link_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/links');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $imageUrl = '/uploads/links/' . $filename;
        }

        RelatedLink::create([
            'title' => $request->title,
            'image_url' => $imageUrl ?: '#',
            'url' => $request->url ?? '#',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        \App\Models\ActivityLog::record('CREATE', 'Tautan Terkait', 'Menambahkan tautan terkait logo instansi: ' . $request->title);
        return back()->with('success', 'Tautan Terkait berhasil ditambahkan!');
    }

    public function linkUpdate(Request $request, $id)
    {
        $link = RelatedLink::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'image_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'image_file.mimes' => '⚠️ HANYA BERKAS GAMBAR BERFORMAT JPG DAN PNG (.jpg, .jpeg, .png) YANG DIPERBOLEHKAN!'
        ]);

        $imageUrl = $request->hasFile('image_file') ? null : ($request->image_url ?: $link->image_url);
        if ($request->hasFile('image_file')) {
            \App\Traits\DeletesUploadFiles::deleteUploadFile($link->image_url);
            $file = $request->file('image_file');
            $filename = 'link_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/links');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $imageUrl = '/uploads/links/' . $filename;
        }

        $link->update([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'url' => $request->url ?? '#',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        \App\Models\ActivityLog::record('UPDATE', 'Tautan Terkait', 'Memperbarui tautan terkait logo instansi: ' . $request->title);
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

    // --- 8b. UNIFIED PORTAL LINKS (Harga Pasar, SIMADU SAE, SP4N LAPOR!, WhatsApp, PPID) ---
    public function portalLinks(Request $request)
    {
        $defaultLogo = 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg';

        // 1. Monitoring Harga Pasar
        $marketWebUrl = SiteSetting::get('market_price_url', 'https://siskaperbapo.jatimprov.go.id/');
        $marketLogo = SiteSetting::get('market_price_logo', $defaultLogo);
        $marketWebTitle = SiteSetting::get('market_price_title', 'Portal Web Resmi Pemantauan Harga Bahan Pokok (Simadu)');
        $marketWebDesc = SiteSetting::get('market_price_desc', 'Update harga komoditas pangan harian dari Pasar Kraksaan, Semampir, Paiton, dan Dringu Kabupaten Probolinggo & Jawa Timur.');

        // 2. SIMADU SAE (UMKM)
        $simaduUrl = SiteSetting::get('simadu_sae_url', 'https://simadu.probolinggokab.go.id/');
        $simaduLogo = SiteSetting::get('simadu_sae_logo', $defaultLogo);

        // 3. SP4N LAPOR!
        $laporSp4nUrl = SiteSetting::get('lapor_sp4n_url', 'https://www.lapor.go.id/');
        $laporSp4nLogo = SiteSetting::get('lapor_sp4n_logo', $defaultLogo);
        $laporSp4nTitle = SiteSetting::get('lapor_sp4n_title', 'SP4N LAPOR! Kabupaten Probolinggo');
        $laporSp4nDesc = SiteSetting::get('lapor_sp4n_desc', 'Layanan Pengaduan Pelayanan Publik Nasional - Lapor Pengaduan & Aspirasi Masyarakat secara Resmi.');
        $menuTitle = \App\Models\NavigationMenu::where('url', '/lapor')->orWhere('id', 26)->value('title') ?: 'SP4N LAPOR!';

        // 4. WhatsApp Pengaduan
        $waNumber = SiteSetting::get('whatsapp_number', '081234567890');
        $waUrl = SiteSetting::get('whatsapp_url', 'https://wa.me/6281234567890');
        $waTitle = SiteSetting::get('whatsapp_title', 'Pengaduan WhatsApp');
        $waMessage = SiteSetting::get('whatsapp_default_message', 'Halo DKUPP Kabupaten Probolinggo, saya ingin menyampaikan pengaduan/konsultasi.');
        $waDesc = SiteSetting::get('whatsapp_desc', 'Pengaduan & konsultasi cepat terhubung langsung ke WhatsApp resmi DKUPP.');
        $waLogo = SiteSetting::get('whatsapp_logo', 'fab fa-whatsapp');

        // 5. PPID
        $ppidUrl = SiteSetting::get('ppid_url', '/halaman/ppid-dkupp');
        $ppidLogo = SiteSetting::get('ppid_logo', $defaultLogo);
        $ppidTitle = SiteSetting::get('ppid_title', 'PPID DKUPP Kabupaten Probolinggo');
        $ppidDesc = SiteSetting::get('ppid_desc', 'Layanan Keterbukaan Informasi Publik, Daftar Informasi Publik (DIP), dan Permohonan Informasi Resmi.');

        $activeTab = $request->get('tab', 'harga-pasar');

        return view('admin.portal_links', compact(
            'marketWebUrl', 'marketLogo', 'marketWebTitle', 'marketWebDesc',
            'simaduUrl', 'simaduLogo',
            'laporSp4nUrl', 'laporSp4nLogo', 'laporSp4nTitle', 'laporSp4nDesc', 'menuTitle',
            'waNumber', 'waUrl', 'waTitle', 'waMessage', 'waDesc', 'waLogo',
            'ppidUrl', 'ppidLogo', 'ppidTitle', 'ppidDesc',
            'activeTab'
        ));
    }

    public function sp4nLapor()
    {
        return redirect()->route('admin.portal-links', ['tab' => 'sp4n-lapor']);
    }

    public function sp4nLaporUpdate(Request $request)
    {
        if ($request->filled('lapor_sp4n_url')) {
            SiteSetting::set('lapor_sp4n_url', $request->lapor_sp4n_url);
        }
        if ($request->filled('lapor_sp4n_title')) {
            SiteSetting::set('lapor_sp4n_title', $request->lapor_sp4n_title);
        }
        if ($request->filled('lapor_sp4n_desc')) {
            SiteSetting::set('lapor_sp4n_desc', $request->lapor_sp4n_desc);
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

        if ($request->filled('menu_title')) {
            \App\Models\NavigationMenu::where('url', '/lapor')->orWhere('id', 26)->update(['title' => $request->menu_title]);
        }

        \App\Models\ActivityLog::record('UPDATE', 'SP4N LAPOR!', 'Memperbarui Target Link, Judul, & Logo Pengaduan SP4N LAPOR!: ' . ($request->lapor_sp4n_url ?: 'https://www.lapor.go.id/'));
        \Illuminate\Support\Facades\Cache::flush();
        return redirect()->route('admin.portal-links', ['tab' => 'sp4n-lapor'])->with('success', 'Target Link & Pengaturan SP4N LAPOR! berhasil diperbarui!');
    }

    public function whatsapp()
    {
        return redirect()->route('admin.portal-links', ['tab' => 'whatsapp']);
    }

    public function whatsappUpdate(Request $request)
    {
        if ($request->filled('whatsapp_number')) {
            $num = trim($request->whatsapp_number);
            SiteSetting::set('whatsapp_number', $num);
            
            // Auto construct wa.me URL
            $waClean = preg_replace('/[^0-9]/', '', $num);
            if (str_starts_with($waClean, '0')) {
                $waClean = '62' . substr($waClean, 1);
            }
            $finalWaUrl = 'https://wa.me/' . $waClean;
            SiteSetting::set('whatsapp_url', $finalWaUrl);
        }

        if ($request->filled('whatsapp_url_custom')) {
            SiteSetting::set('whatsapp_url', $request->whatsapp_url_custom);
        }

        if ($request->filled('whatsapp_title')) {
            SiteSetting::set('whatsapp_title', $request->whatsapp_title);
        }

        if ($request->filled('whatsapp_default_message')) {
            SiteSetting::set('whatsapp_default_message', $request->whatsapp_default_message);
        }

        if ($request->filled('whatsapp_desc')) {
            SiteSetting::set('whatsapp_desc', $request->whatsapp_desc);
        }

        if ($request->filled('whatsapp_logo')) {
            SiteSetting::set('whatsapp_logo', $request->whatsapp_logo);
        }

        if ($request->hasFile('whatsapp_logo_file')) {
            $file = $request->file('whatsapp_logo_file');
            $filename = 'logo_wa_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('whatsapp_logo', '/uploads/settings/' . $filename);
        }

        \App\Models\ActivityLog::record('UPDATE', 'WhatsApp Pengaduan', 'Memperbarui Nomor Kontak, Logo, & Pengaturan WhatsApp Pengaduan.');
        \Illuminate\Support\Facades\Cache::flush();
        return redirect()->route('admin.portal-links', ['tab' => 'whatsapp'])->with('success', 'Pengaturan Kontak, Logo & Pengaduan WhatsApp berhasil disimpan!');
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
            'username' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'referral_code' => 'nullable|string|max:50',
            'password' => 'required|min:6',
            'role' => 'required|in:super_admin,anggota',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'referral_code' => $request->referral_code,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
            'role' => $request->role,
        ]);
        return back()->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'referral_code' => 'nullable|string|max:50',
            'role' => 'required|in:super_admin,anggota',
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'referral_code' => $request->referral_code,
            'role' => $request->role,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $data['plain_password'] = $request->password;
        }
        $user->update($data);
        return back()->with('success', 'Data Akun Pengguna berhasil diperbarui!');
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
        $fileFields = [
            'logo_frontend_file' => 'logo_frontend',
            'logo_backend_file' => 'logo_backend',
            'logo_berakhlak_file' => 'logo_berakhlak',
            'qr_code_survey_file' => 'qr_code_survey',
            'kadin_photo_file' => 'kadin_photo',
            'maklumat_file' => 'maklumat_image',
            'qr_file' => 'qr_code_image',
            'market_price_logo_file' => 'market_price_logo',
            'simadu_sae_logo_file' => 'simadu_sae_logo',
            'ppid_logo_file' => 'ppid_logo',
        ];

        $targetDir = public_path('uploads/settings');
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $uploadedKeys = [];

        foreach ($fileFields as $fileInput => $settingKey) {
            if ($request->hasFile($fileInput)) {
                $file = $request->file($fileInput);
                $filename = $settingKey . '_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $filename);
                SiteSetting::set($settingKey, '/uploads/settings/' . $filename);
                $uploadedKeys[] = $settingKey;
            }
        }

        $exceptKeys = array_merge(['_token'], array_keys($fileFields), $uploadedKeys);
        $inputs = $request->except($exceptKeys);
        foreach ($inputs as $key => $value) {
            $val = is_string($value) ? trim($value) : $value;
            SiteSetting::set($key, $val);
        }
        \App\Models\ActivityLog::record('UPDATE', 'Pengaturan Website', 'Memperbarui Konfigurasi Website, Identitas & Logo Branding.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Pengaturan Website & Berkas Logo/Foto Branding berhasil disimpan!');
    }

    // --- 11. SIMADU SAE UMKM PRODUCTS CRUD ---
    public function umkm()
    {
        return redirect()->route('admin.portal-links', ['tab' => 'simadu']);
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
        return redirect()->route('admin.portal-links', ['tab' => 'simadu'])->with('success', 'Target Link & Logo Portal SIMADU SAE berhasil disimpan!');
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
        return redirect()->route('admin.portal-links', ['tab' => 'harga-pasar']);
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
            \App\Models\ActivityLog::record('UPDATE', 'Harga Pasar', 'Memperbarui Link & Logo Website Pemantauan Harga Pasar.');
            return redirect()->route('admin.portal-links', ['tab' => 'harga-pasar'])->with('success', 'Link & Logo Website Pemantauan Harga Pasar berhasil disimpan!');
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
        $dbTypes = \App\Models\OrgMember::distinct()->pluck('type')->filter()->values()->toArray();
        $defaultTypes = ['personel', 'kelompok_fungsional', 'staf_pelaksana', 'tim_kerja'];
        $types = array_values(array_unique(array_merge($defaultTypes, $dbTypes)));

        return view('admin.org_members', compact('members', 'types'));
    }

    public function orgMemberStore(Request $request)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'photo_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'photo_file.mimes' => 'Hanya berkas foto berformat JPG dan PNG (.jpg, .jpeg, .png) yang diperbolehkan!'
        ]);

        $type = $request->custom_type ?: ($request->type ?: 'personel');
        $parentId = $request->parent_id ? (int)$request->parent_id : null;

        if (!$parentId && $request->filled('custom_parent')) {
            $customName = trim($request->custom_parent);
            $existingParent = \App\Models\OrgMember::where('position', 'like', $customName)
                                ->orWhere('name', 'like', $customName)
                                ->first();
            if ($existingParent) {
                $parentId = $existingParent->id;
            } else {
                $newParent = \App\Models\OrgMember::create([
                    'name' => null,
                    'position' => mb_strtoupper($customName),
                    'type' => 'personel',
                    'parent_id' => null,
                    'order' => 0,
                    'is_active' => true,
                ]);
                $parentId = $newParent->id;
            }
        }

        $photo = $request->photo;
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $filename = 'pejabat_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/pejabat');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            $photo = '/uploads/pejabat/' . $filename;
        }

        \App\Models\OrgMember::create([
            'name' => $request->name,
            'position' => mb_strtoupper($request->position),
            'type' => $type,
            'parent_id' => $parentId,
            'photo' => $photo,
            'order' => $request->order ?? 1,
            'is_active' => $request->has('is_active'),
        ]);

        \App\Models\ActivityLog::record('CREATE', 'Struktur Organisasi', 'Menambahkan pejabat/anggota baru: ' . $request->position);
        return back()->with('success', 'Anggota struktur organisasi berhasil ditambahkan!');
    }

    public function orgMemberUpdate(Request $request, $id)
    {
        $member = \App\Models\OrgMember::findOrFail($id);
        $request->validate([
            'position' => 'required|string|max:255',
            'photo_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ], [
            'photo_file.mimes' => 'Hanya berkas foto berformat JPG dan PNG (.jpg, .jpeg, .png) yang diperbolehkan!'
        ]);

        $type = $request->custom_type ?: ($request->type ?: $member->type);
        $parentId = $request->parent_id !== null && $request->parent_id !== '' ? (int)$request->parent_id : null;

        if (!$parentId && $request->filled('custom_parent')) {
            $customName = trim($request->custom_parent);
            $existingParent = \App\Models\OrgMember::where('position', 'like', $customName)
                                ->orWhere('name', 'like', $customName)
                                ->first();
            if ($existingParent) {
                $parentId = $existingParent->id;
            } else {
                $newParent = \App\Models\OrgMember::create([
                    'name' => null,
                    'position' => mb_strtoupper($customName),
                    'type' => 'personel',
                    'parent_id' => null,
                    'order' => 0,
                    'is_active' => true,
                ]);
                $parentId = $newParent->id;
            }
        }

        $photo = $request->photo ?: $member->photo;
        if ($request->hasFile('photo_file')) {
            \App\Traits\DeletesUploadFiles::deleteUploadFile($member->photo);
            $file = $request->file('photo_file');
            $filename = 'pejabat_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/pejabat');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            $photo = '/uploads/pejabat/' . $filename;
        }

        $member->update([
            'name' => $request->name,
            'position' => mb_strtoupper($request->position),
            'type' => $type,
            'parent_id' => $parentId,
            'photo' => $photo,
            'order' => $request->order ?? 1,
            'is_active' => $request->has('is_active'),
        ]);

        \App\Models\ActivityLog::record('UPDATE', 'Struktur Organisasi', 'Memperbarui data anggota: ' . $request->position);
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

        $masterCats = \App\Models\MasterCategory::whereIn('type', ['layanan', 'berita', 'umum'])->pluck('name')->toArray();
        $dbCategories = \App\Models\Gallery::distinct()->pluck('category')->filter()->values()->toArray();
        $defaultCategories = ['Dokumentasi Kegiatan', 'Pelayanan Publik', 'Bazar UMKM', 'Tera Ulang', 'Sosialisasi'];
        $categories = array_values(array_unique(array_merge($defaultCategories, $masterCats, $dbCategories)));

        return view('admin.gallery', compact('galleries', 'tab', 'categories'));
    }

    public function galleryStore(Request $request)
    {
        $mimesRule = $request->type == 'video' ? 'mimes:mp4,mov,avi,webm' : 'mimes:jpg,jpeg,png,webp';
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'youtube_url' => 'nullable|url',
            'file_upload' => "nullable|file|{$mimesRule}|max:20480",
            'photos.*' => "nullable|file|mimes:jpg,jpeg,png,webp|max:20480",
        ], [
            'file_upload.mimes' => $request->type == 'image'
                ? 'Hanya berkas foto/gambar berformat JPG, PNG, WEBP (.jpg, .jpeg, .png, .webp) yang diperbolehkan!'
                : 'Hanya berkas video berformat MP4/MOV/AVI yang diperbolehkan!'
        ]);

        $filePaths = [];

        // Upload multiple photos for album
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $idx => $file) {
                $filename = time() . '_' . $idx . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $targetDir = public_path('uploads/gallery');
                if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
                $file->move($targetDir, $filename);
                $filePaths[] = '/uploads/gallery/' . $filename;
            }
        }

        // Upload single file fallback
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/gallery');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            $filePaths[] = '/uploads/gallery/' . $filename;
        }

        // Manual text URLs or JSON string
        if ($request->filled('file_path')) {
            $rawInput = trim($request->file_path);
            $decodedInput = json_decode($rawInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedInput)) {
                $filePaths = array_merge($filePaths, $decodedInput);
            } else {
                $urls = array_filter(array_map('trim', explode("\n", $rawInput)));
                foreach ($urls as $u) {
                    if (!empty($u)) {
                        $filePaths[] = $u;
                    }
                }
            }
        }

        $filePaths = array_values(array_unique(array_filter($filePaths)));
        $finalFilePath = empty($filePaths) ? null : (count($filePaths) === 1 ? $filePaths[0] : json_encode($filePaths));

        $category = $request->custom_category ?: ($request->category ?: 'Dokumentasi Kegiatan');

        \App\Models\Gallery::create([
            'title' => $request->title,
            'type' => $request->type,
            'category' => $category,
            'file_path' => $finalFilePath,
            'youtube_url' => $request->youtube_url,
            'caption' => $request->caption,
            'is_active' => true,
        ]);

        $typeName = $request->type == 'video' ? 'Video YouTube' : 'Album Foto Galeri (' . count($filePaths) . ' Foto)';
        \App\Models\ActivityLog::record('CREATE', $request->type == 'video' ? 'Galeri Video' : 'Galeri Album Foto', 'Menambahkan ' . $typeName . ' baru: ' . $request->title);

        return back()->with('success', "{$typeName} berhasil ditambahkan!");
    }

    public function galleryUpdate(Request $request, $id)
    {
        $item = \App\Models\Gallery::findOrFail($id);

        $mimesRule = $request->type == 'video' ? 'mimes:mp4,mov,avi,webm' : 'mimes:jpg,jpeg,png,webp';
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'youtube_url' => 'nullable|url',
            'file_upload' => "nullable|file|{$mimesRule}|max:20480",
            'photos.*' => "nullable|file|mimes:jpg,jpeg,png,webp|max:20480",
        ], [
            'file_upload.mimes' => $request->type == 'image'
                ? 'Hanya berkas foto/gambar berformat JPG, PNG, WEBP (.jpg, .jpeg, .png, .webp) yang diperbolehkan!'
                : 'Hanya berkas video berformat MP4/MOV/AVI yang diperbolehkan!'
        ]);

        if ($request->has('replace_photos')) {
            \App\Traits\DeletesUploadFiles::deleteUploadFile($item->file_path);
            $existingPaths = [];
        } else {
            $existingPaths = $item->images;
        }

        // Upload multiple new photos to album
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $idx => $file) {
                $filename = time() . '_' . $idx . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $targetDir = public_path('uploads/gallery');
                if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
                $file->move($targetDir, $filename);
                $existingPaths[] = '/uploads/gallery/' . $filename;
            }
        }

        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/gallery');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            $existingPaths[] = '/uploads/gallery/' . $filename;
        }

        if ($request->filled('file_path')) {
            $rawInput = trim($request->file_path);
            $decodedInput = json_decode($rawInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedInput)) {
                $existingPaths = array_merge($existingPaths, $decodedInput);
            } else {
                $urls = array_filter(array_map('trim', explode("\n", $rawInput)));
                if (!empty($urls)) {
                    $existingPaths = array_merge($existingPaths, $urls);
                }
            }
        }

        $existingPaths = array_values(array_unique(array_filter($existingPaths)));
        $finalFilePath = empty($existingPaths) ? $item->file_path : (count($existingPaths) === 1 ? $existingPaths[0] : json_encode($existingPaths));

        $category = $request->custom_category ?: ($request->category ?: ($item->category ?: 'Dokumentasi Kegiatan'));

        $item->update([
            'title' => $request->title,
            'type' => $request->type,
            'category' => $category,
            'file_path' => $finalFilePath,
            'youtube_url' => $request->youtube_url,
            'caption' => $request->caption,
        ]);

        $typeName = $item->type == 'video' ? 'Video YouTube' : 'Foto Galeri';
        \App\Models\ActivityLog::record('UPDATE', $item->type == 'video' ? 'Galeri Video' : 'Galeri Foto', 'Memperbarui ' . $typeName . ': ' . $item->title);

        return back()->with('success', "{$typeName} berhasil diperbarui!");
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
        return redirect()->route('admin.portal-links', ['tab' => 'ppid']);
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

        return redirect()->route('admin.portal-links', ['tab' => 'ppid'])->with('success', 'Link & Logo Website PPID DKUPP berhasil diperbarui!');
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
        $qrCodeLabel = SiteSetting::get('qr_code_label', 'Scan QR Portal Pelayanan & Hasil SKM');
        $qrCodeImage = SiteSetting::get('qr_code_image', '');
        $skmImage = SiteSetting::get('skm_image', '/uploads/settings/skm_poster.svg');
        return view('admin.qr_code', compact('qrCodeLabel', 'qrCodeImage', 'skmImage'));
    }

    public function qrCodeUpdate(Request $request)
    {
        if ($request->filled('qr_code_label')) {
            SiteSetting::set('qr_code_label', $request->qr_code_label);
        }
        if ($request->filled('qr_code_image')) {
            SiteSetting::set('qr_code_image', $request->qr_code_image);
        }
        if ($request->filled('skm_image')) {
            SiteSetting::set('skm_image', $request->skm_image);
        }
        if ($request->hasFile('qr_file')) {
            $file = $request->file('qr_file');
            $filename = 'qr_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('qr_code_image', '/uploads/settings/' . $filename);
        }
        if ($request->hasFile('skm_file')) {
            $file = $request->file('skm_file');
            $filename = 'skm_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/settings');
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            $file->move($targetDir, $filename);
            SiteSetting::set('skm_image', '/uploads/settings/' . $filename);
        }
        \App\Models\ActivityLog::record('UPDATE', 'Kode QR & Hasil SKM', 'Memperbarui Foto Poster Hasil SKM & Kode QR Footer.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Foto Poster Hasil SKM & Kode QR Footer berhasil diperbarui!');
    }

    // --- 19. LAYANAN PUBLIK CRUD ---
    public function services()
    {
        $services = \App\Models\Service::orderBy('created_at', 'desc')->paginate(10);
        $dbCategories = \App\Models\Service::distinct()->pluck('category')->filter()->values()->toArray();
        $defaultCategories = ['USAHA MIKRO', 'KOPERASI', 'PERDAGANGAN', 'PERINDUSTRIAN', 'METROLOGI LEGAL', 'PELAYANAN UMUM'];
        $masterCategories = array_values(array_unique(array_merge($defaultCategories, array_map('mb_strtoupper', $dbCategories))));

        return view('admin.services', compact('services', 'masterCategories'));
    }

    public function serviceStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'summary' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:25600',
        ], [
            'pdf_file.mimes' => '⚠️ HANYA BERKAS BERFORMAT PDF (.pdf) YANG DIPERBOLEHKAN! Berkas lain selain PDF tidak diperbolehkan.',
        ]);

        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $counter = 1;
        while (\App\Models\Service::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $externalUrl = $request->external_url;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = 'sop_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/services');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $externalUrl = '/uploads/services/' . $filename;
        }

        \App\Models\Service::create([
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'icon' => $request->icon ?: 'fa-handshake',
            'summary' => $request->summary,
            'requirements' => $request->requirements,
            'procedure' => $request->procedure,
            'service_time' => $request->service_time ?: '1-3 Hari Kerja',
            'cost' => $request->cost ?: 'Gratis (Rp 0)',
            'location' => $request->location ?: 'Loket MPP Kraksaan',
            'external_url' => $externalUrl,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        \App\Models\ActivityLog::record('CREATE', 'Layanan Publik', 'Menambahkan layanan publik baru: ' . $request->title);
        return back()->with('success', 'Layanan Publik & Dokumen SOP PDF berhasil ditambahkan!');
    }

    public function serviceUpdate(Request $request, $id)
    {
        $service = \App\Models\Service::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'summary' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:25600',
        ], [
            'pdf_file.mimes' => '⚠️ HANYA BERKAS BERFORMAT PDF (.pdf) YANG DIPERBOLEHKAN! Berkas lain selain PDF tidak diperbolehkan.',
        ]);

        $slug = $service->slug;
        if ($service->title !== $request->title) {
            $baseSlug = Str::slug($request->title);
            $slug = $baseSlug;
            $counter = 1;
            while (\App\Models\Service::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
        }

        $externalUrl = $request->hasFile('pdf_file') ? null : ($request->external_url ?: $service->external_url);
        if ($request->hasFile('pdf_file')) {
            \App\Traits\DeletesUploadFiles::deleteUploadFile($service->external_url);
            $file = $request->file('pdf_file');
            $filename = 'sop_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/services');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $externalUrl = '/uploads/services/' . $filename;
        }

        $service->update([
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'icon' => $request->icon ?: 'fa-handshake',
            'summary' => $request->summary,
            'requirements' => $request->requirements,
            'procedure' => $request->procedure,
            'service_time' => $request->service_time ?: '1-3 Hari Kerja',
            'cost' => $request->cost ?: 'Gratis (Rp 0)',
            'location' => $request->location ?: 'Loket MPP Kraksaan',
            'external_url' => $externalUrl,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $service->is_active,
        ]);

        \App\Models\ActivityLog::record('UPDATE', 'Layanan Publik', 'Memperbarui layanan publik: ' . $request->title);
        return back()->with('success', 'Layanan Publik berhasil diperbarui!');
    }

    public function serviceToggleStatus($id)
    {
        $service = \App\Models\Service::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();
        $statusText = $service->is_active ? 'diaktifkan (PUBLISHED)' : 'dinonaktifkan (DRAFT)';
        return back()->with('success', "Status layanan '{$service->title}' berhasil {$statusText}!");
    }

    public function serviceDestroy($id)
    {
        $service = \App\Models\Service::findOrFail($id);
        $title = $service->title;
        $service->delete();

        \App\Models\ActivityLog::record('DELETE', 'Layanan Publik', 'Menghapus layanan publik: ' . $title);
        return back()->with('success', 'Layanan Publik berhasil dihapus!');
    }

    // --- DEDICATED ALAMAT & KONTAKS SETTINGS ---
    public function contactInfo()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.contact_info', compact('settings'));
    }

    public function contactInfoUpdate(Request $request)
    {
        $keys = [
            'address', 'phone', 'email', 'footer_copyright',
            'google_map_search', 'google_map_embed',
            'facebook_url', 'instagram_url', 'youtube_url', 'tiktok_url', 'whatsapp_url', 'dkupp_whatsapp_url'
        ];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }
        \App\Models\ActivityLog::record('UPDATE', 'Informasi Alamat & Kontak', 'Memperbarui data alamat kantor, telepon, email, dan peta lokasi Google Maps.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Informasi Alamat, Kontak & Peta Lokasi Google Maps berhasil diperbarui!');
    }

    // --- DEDICATED SOCIAL MEDIA SETTINGS ---
    public function socialMedia()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.social_media', compact('settings'));
    }

    public function socialMediaUpdate(Request $request)
    {
        $keys = ['facebook_url', 'instagram_url', 'youtube_url', 'tiktok_url', 'whatsapp_url', 'dkupp_whatsapp_url'];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }
        \App\Models\ActivityLog::record('UPDATE', 'Media Sosial Resmi', 'Memperbarui tautan akun media sosial resmi DKUPP.');
        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Tautan Media Sosial Resmi berhasil disimpan!');
    }

    // --- 20. MASTER KATEGORI CRUD ---
    public function categories(Request $request)
    {
        $type = $request->query('type', 'all');
        $query = \App\Models\MasterCategory::query();
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        $categories = $query->orderBy('type')->orderBy('order', 'asc')->orderBy('name', 'asc')->paginate(15);
        return view('admin.categories', compact('categories', 'type'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:layanan,dokumen,berita,umkm,umum',
        ]);

        \App\Models\MasterCategory::create([
            'type' => $request->type,
            'name' => mb_strtoupper(trim($request->name)),
            'description' => $request->description,
            'icon' => $request->icon ?: 'fa-folder',
            'order' => $request->order ?? 0,
            'is_active' => true,
        ]);

        \App\Models\ActivityLog::record('CREATE', 'Master Kategori', 'Menambahkan Master Kategori baru: ' . $request->name);
        return back()->with('success', 'Master Kategori berhasil ditambahkan!');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $cat = \App\Models\MasterCategory::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:layanan,dokumen,berita,umkm,umum',
        ]);

        $cat->update([
            'type' => $request->type,
            'name' => mb_strtoupper(trim($request->name)),
            'description' => $request->description,
            'icon' => $request->icon ?: 'fa-folder',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        \App\Models\ActivityLog::record('UPDATE', 'Master Kategori', 'Memperbarui Master Kategori: ' . $request->name);
        return back()->with('success', 'Master Kategori berhasil diperbarui!');
    }

    public function categoryDestroy($id)
    {
        $cat = \App\Models\MasterCategory::findOrFail($id);
        $name = $cat->name;
        $cat->delete();
        \App\Models\ActivityLog::record('DELETE', 'Master Kategori', 'Menghapus Master Kategori: ' . $name);
        return back()->with('success', 'Master Kategori berhasil dihapus!');
    }

    public function categoryQuickStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:layanan,dokumen,berita,galeri,umkm,umum',
        ]);

        $catName = mb_strtoupper(trim($request->name));

        $cat = \App\Models\MasterCategory::firstOrCreate(
            ['type' => $request->type, 'name' => $catName],
            [
                'icon' => $request->type === 'berita' ? 'fa-newspaper' : ($request->type === 'dokumen' ? 'fa-file-pdf' : 'fa-handshake'),
                'is_active' => true,
                'order' => 0,
            ]
        );

        \App\Models\ActivityLog::record('CREATE', 'Master Kategori', 'Menambahkan Master Kategori Cepat: ' . $catName);

        return response()->json([
            'success' => true,
            'message' => 'Master Kategori berhasil ditambahkan!',
            'category' => $cat->name
        ]);
    }

    public function categoryQuickDestroy(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $catName = mb_strtoupper(trim($request->name));

        \App\Models\MasterCategory::where('name', $catName)->delete();

        \App\Models\ActivityLog::record('DELETE', 'Master Kategori', 'Menghapus Master Kategori Cepat: ' . $catName);

        return response()->json([
            'success' => true,
            'message' => 'Master Kategori berhasil dihapus!',
            'category' => $catName
        ]);
    }
}
