<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\NavigationMenu;
use App\Models\HeroSlider;
use App\Models\NewsItem;
use App\Models\SidebarWidget;
use App\Models\RelatedLink;
use App\Models\PublicDocument;
use App\Models\Page;
use App\Models\UmkmProduct;
use App\Models\MarketPrice;
use App\Models\Service;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users (Admin DKUPP)
        User::updateOrCreate(
            ['email' => 'admin@dkupp.probolinggokab.go.id'],
            [
                'name' => 'Administrator DKUPP',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staf@dkupp.probolinggokab.go.id'],
            [
                'name' => 'Staf Pelayanan DKUPP',
                'password' => Hash::make('password'),
                'role' => 'anggota',
            ]
        );

        // 2. Site Settings DKUPP Kabupaten Probolinggo
        $settings = [
            'site_title' => 'Website Resmi | Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian Kabupaten Probolinggo',
            'site_description' => 'Website Resmi DKUPP Kabupaten Probolinggo - Portal Layanan Koperasi, Pemberdayaan UMKM SIMADU SAE, Perlindungan Konsumen, Metrologi Legal, dan Stabilisasi Harga Pasar.',
            'agency_name' => 'Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian (DKUPP)',
            'regency_name' => 'Kabupaten Probolinggo',
            'logo_frontend' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg',
            'logo_backend' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg',
            'logo_berakhlak' => 'https://diskominfo.probolinggokab.go.id/frontend/images/img-berakhlak.png',
            'qr_code_survey' => 'https://diskominfo.probolinggokab.go.id/backend/gambar/qr_code_kominfo.png',
            'address' => 'Jl. Raya Panglima Sudirman No. 134 / Loket MPP Kraksaan, Kabupaten Probolinggo, Jawa Timur 67282',
            'phone' => '(0335) 844554 / WhatsApp: 0812-3456-7890',
            'email' => 'dkupp@probolinggokab.go.id',
            'survey_url' => 'https://sukma.jatimprov.go.id/',
            'instagram_url' => 'https://www.instagram.com/dkuppkabprobolinggo/',
            'facebook_url' => 'https://www.facebook.com/dkuppkabprobolinggo',
            'tiktok_url' => 'https://www.tiktok.com/@dkuppkabprobolinggo',
            'whatsapp_url' => 'https://wa.me/6281234567890',
            'copyright_text' => 'DKUPP - Kabupaten Probolinggo © 2026. All Rights Reserved.',
            'skm_score' => '88.75',
            'skm_grade' => 'Sangat Baik (A)',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // 3. Navigation Menus DKUPP (Matching Diskominfo style)
        NavigationMenu::truncate();

        $home = NavigationMenu::create(['title' => 'HOME', 'url' => '/', 'order' => 1]);
        
        $profil = NavigationMenu::create(['title' => 'PROFIL', 'url' => '#', 'order' => 2]);
        NavigationMenu::create(['title' => 'Struktur Organisasi', 'url' => '/halaman/struktur-organisasi', 'parent_id' => $profil->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Visi Misi', 'url' => '/halaman/visi-misi', 'parent_id' => $profil->id, 'order' => 2]);
        NavigationMenu::create(['title' => 'Tugas dan Fungsi', 'url' => '/halaman/tugas-dan-fungsi', 'parent_id' => $profil->id, 'order' => 3]);
        NavigationMenu::create(['title' => 'Survei Kepuasan Masyarakat', 'url' => '/halaman/survei-kepuasan-masyarakat', 'parent_id' => $profil->id, 'order' => 4]);

        $layanan = NavigationMenu::create(['title' => 'LAYANAN', 'url' => '#', 'order' => 3]);
        NavigationMenu::create(['title' => 'Portal UMKM SIMADU SAE', 'url' => '/katalog-umkm', 'parent_id' => $layanan->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Standar Pelayanan Publik', 'url' => '/layanan', 'parent_id' => $layanan->id, 'order' => 2]);
        NavigationMenu::create(['title' => 'Pelayanan Metrologi Legal & Uji Tera', 'url' => '/layanan/metrologi-legal', 'parent_id' => $layanan->id, 'order' => 3]);
        NavigationMenu::create(['title' => 'Pendampingan & Perizinan Koperasi', 'url' => '/layanan/pendampingan-koperasi', 'parent_id' => $layanan->id, 'order' => 4]);
        NavigationMenu::create(['title' => 'Fasilitasi Legalitas & NIB UMKM Gratis', 'url' => '/layanan/legalitas-umkm', 'parent_id' => $layanan->id, 'order' => 5]);
        NavigationMenu::create(['title' => 'Sistem Resi Gudang (SRG)', 'url' => '/layanan/sistem-resi-gudang', 'parent_id' => $layanan->id, 'order' => 6]);

        $dokumen = NavigationMenu::create(['title' => 'DOKUMEN', 'url' => '#', 'order' => 4]);
        NavigationMenu::create(['title' => 'Perencanaan Kinerja', 'url' => '/dokumen?category=Perencanaan Kinerja', 'parent_id' => $dokumen->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Pengukuran Kinerja', 'url' => '/dokumen?category=Pengukuran Kinerja', 'parent_id' => $dokumen->id, 'order' => 2]);
        NavigationMenu::create(['title' => 'Pelaporan Kinerja', 'url' => '/dokumen?category=Pelaporan Kinerja', 'parent_id' => $dokumen->id, 'order' => 3]);
        NavigationMenu::create(['title' => 'Evaluasi Kinerja', 'url' => '/dokumen?category=Evaluasi Kinerja', 'parent_id' => $dokumen->id, 'order' => 4]);

        $informasi = NavigationMenu::create(['title' => 'INFORMASI', 'url' => '#', 'order' => 5]);
        NavigationMenu::create(['title' => 'Berita DKUPP', 'url' => '/berita', 'parent_id' => $informasi->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Monitoring Harga Pasar Pangan', 'url' => '/harga-pasar', 'parent_id' => $informasi->id, 'order' => 2]);
        NavigationMenu::create(['title' => 'Katalog UMKM Unggulan', 'url' => '/katalog-umkm', 'parent_id' => $informasi->id, 'order' => 3]);
        NavigationMenu::create(['title' => 'PPID DKUPP', 'url' => 'https://ppid.probolinggokab.go.id/', 'parent_id' => $informasi->id, 'order' => 4, 'target' => '_blank']);
        NavigationMenu::create(['title' => 'Galeri Foto & Video', 'url' => '/galeri', 'parent_id' => $informasi->id, 'order' => 5]);

        $hubungi = NavigationMenu::create(['title' => 'HUBUNGI', 'url' => '#', 'order' => 6]);
        NavigationMenu::create(['title' => 'SP4N LAPOR!', 'url' => '/lapor', 'parent_id' => $hubungi->id, 'order' => 1]);
        NavigationMenu::create(['title' => 'Kontak Kami', 'url' => '/kontak', 'parent_id' => $hubungi->id, 'order' => 2]);

        NavigationMenu::create(['title' => 'LOGIN', 'url' => '/login', 'order' => 7]);

        // 4. Hero Sliders DKUPP
        HeroSlider::truncate();
        HeroSlider::create([
            'title' => 'Selamat Datang di Portal Resmi DKUPP',
            'subtitle' => 'Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian Kabupaten Probolinggo - Mengabdi untuk Ekonomi Rakyat Sejahtera & Mandiri',
            'image_url' => 'https://images.unsplash.com/photo-1556740758-90de374c12ad?q=80&w=1600&auto=format&fit=crop',
            'button_text' => 'Jelajahi SIMADU SAE UMKM',
            'button_url' => '/katalog-umkm',
            'order' => 1,
            'is_active' => true,
        ]);
        HeroSlider::create([
            'title' => 'SIMADU SAE - Sistem Manajemen & Katalog UMKM',
            'subtitle' => 'Pemasaran Digital Terpadu & Pendampingan Legalitas Usaha Produk Lokal Kabupaten Probolinggo',
            'image_url' => 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?q=80&w=1600&auto=format&fit=crop',
            'button_text' => 'Cek Harga Bahan Pokok',
            'button_url' => '/harga-pasar',
            'order' => 2,
            'is_active' => true,
        ]);
        HeroSlider::create([
            'title' => 'Pelayanan Metrologi Legal & Perlindungan Konsumen',
            'subtitle' => 'Menjamin Keabsahan Takaran, Timbangan, dan Ukuran Demi Perdagangan yang Adil dan Jujur',
            'image_url' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=1600&auto=format&fit=crop',
            'button_text' => 'Lihat Standar Pelayanan',
            'button_url' => '/layanan',
            'order' => 3,
            'is_active' => true,
        ]);

        // 5. News Items DKUPP
        NewsItem::truncate();
        NewsItem::create([
            'title' => 'DKUPP Kabupaten Probolinggo Gelar Pelatihan Digital Marketing dan Fasilitasi Legalitas NIB Gratis Bagi 100 UMKM',
            'slug' => 'dkupp-gelar-pelatihan-digital-marketing-dan-fasilitasi-nib-gratis-umkm',
            'summary' => 'Upaya memperluas jangkauan pasar UMKM lokal melalui integrasi ke platform digital SIMADU SAE dan fasilitasi legalitas gratis.',
            'content' => 'Kraksaan - Dinas Koperasi, Usaha Mikro, Perdagangan dan Perindustrian (DKUPP) Kabupaten Probolinggo terus berkomitmen memperkuat daya saing UMKM lokal. Sebanyak 100 pelaku UMKM mengikuti bimbingan teknis pemasaran digital dan pengurusan Nomor Induk Berusaha (NIB) secara gratis di Mal Pelayanan Publik (MPP)...',
            'image_url' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=800&auto=format&fit=crop',
            'category' => 'Usaha Mikro',
            'published_at' => '2026-08-02',
            'views' => 450,
            'is_featured' => true,
        ]);
        NewsItem::create([
            'title' => 'Pantau Stabilitas Pangan, Tim Metrologi dan Perdagangan DKUPP Gelar Sidang Tera Ulang di Pasar Semampir Kraksaan',
            'slug' => 'tim-metrologi-dkupp-gelar-sidang-tera-ulang-pasar-semampir',
            'summary' => 'Sidang tera ulang alat ukur, takar, timbang, dan perlengkapannya (UTTP) untuk mewujudkan Pasar Tertib Ukur.',
            'content' => 'Kraksaan - Guna menjamin perlindungan konsumen dan memastikan keakuratan timbangan pedagang, Unit Metrologi Legal DKUPP Kabupaten Probolinggo menerjunkan tim penera di Pasar Semampir Kraksaan...',
            'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=800&auto=format&fit=crop',
            'category' => 'Perdagangan',
            'published_at' => '2026-07-29',
            'views' => 380,
            'is_featured' => true,
        ]);
        NewsItem::create([
            'title' => 'Semarak Hari Koperasi Nasional: DKUPP Berikan Penghargaan Kepada Koperasi Sehat dan Berprestasi 2026',
            'slug' => 'semarak-harkopnas-dkupp-berikan-penghargaan-koperasi-sehat-2026',
            'summary' => 'Penilaian tata kelola keuangan dan kepatuhan RAT menjadi tolok ukur utama pemberian apresiasi koperasi terbaik.',
            'content' => 'Probolinggo - Dalam rangka memperingati Hari Koperasi Nasional, DKUPP Kabupaten Probolinggo menyerahkan piagam penghargaan kepada 10 Koperasi dengan predikat Sehat...',
            'image_url' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=800&auto=format&fit=crop',
            'category' => 'Koperasi',
            'published_at' => '2026-07-20',
            'views' => 295,
            'is_featured' => false,
        ]);
        NewsItem::create([
            'title' => 'Optimalisasi Sistem Resi Gudang (SRG) Guna Menjaga Harga Stabil Hasil Pertanian Komoditas Bawang Merah',
            'slug' => 'optimalisasi-sistem-resi-gudang-srg-bawang-merah-probolinggo',
            'summary' => 'Fasilitasi pembiayaan petani melalui pemanfaatan gudang SRG saat harga panen raya fluktuatif.',
            'content' => 'Dringu - Kepala DKUPP Kabupaten Probolinggo meninjau fasilitas Sistem Resi Gudang (SRG) untuk penampungan komoditas unggulan daerah seperti bawang merah...',
            'image_url' => 'https://images.unsplash.com/photo-1589923188900-85dae523342b?q=80&w=800&auto=format&fit=crop',
            'category' => 'Perindustrian',
            'published_at' => '2026-07-12',
            'views' => 210,
            'is_featured' => false,
        ]);
        NewsItem::create([
            'title' => 'DKUPP Salurkan Bantuan Peralatan Produksi Modern Bagi Sentra IKM Kerajinan dan Pangan',
            'slug' => 'dkupp-salurkan-bantuan-peralatan-produksi-modern-ikm',
            'summary' => 'Peningkatan kapasitas produksi IKM olahan pangan dan kerajinan tangan melalui modernisasi mesin pakan dan kemasan.',
            'content' => 'Kraksaan - Penyerahan bantuan hibah mesin kemasan dan alat pengolahan produk pangan kepada 15 kelompok usaha bersama (KUB) IKM di Probolinggo...',
            'image_url' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800&auto=format&fit=crop',
            'category' => 'Perindustrian',
            'published_at' => '2026-07-05',
            'views' => 310,
            'is_featured' => false,
        ]);
        NewsItem::create([
            'title' => 'Fasilitasi Sertifikasi Halal Gratis: DKUPP Dampingi 150 Produk UMKM Tembus Pasar Ritel Modern',
            'slug' => 'fasilitasi-sertifikasi-halal-gratis-dkupp-dampingi-150-produk-umkm',
            'summary' => 'Dukungan penuh sertifikasi halal Self-Declare dan Reguler untuk menjamin keamanan serta standar produk olahan UMKM.',
            'content' => 'Probolinggo - DKUPP bekerja sama dengan BPJPH Kementerian Agama menyerahkan 150 sertifikat halal gratis kepada pelaku usaha mikro...',
            'image_url' => 'https://images.unsplash.com/photo-1588964895597-cfccd6e2dbf9?q=80&w=800&auto=format&fit=crop',
            'category' => 'Usaha Mikro',
            'published_at' => '2026-06-28',
            'views' => 490,
            'is_featured' => true,
        ]);
        NewsItem::create([
            'title' => 'Gelar Operasi Pasar Murah: DKUPP Distribusikan 10 Ton Beras dan Minyak Goreng Subsidi TPID',
            'slug' => 'gelar-operasi-pasar-murah-dkupp-distribusikan-10-ton-beras-subsidized',
            'summary' => 'Upaya konkret penanganan inflasi dan stabilisasi harga bahan pokok masyarakat di 5 kecamatan sasaran.',
            'content' => 'Dringu - Ribuan warga antusias mendatangi Operasi Pasar Murah yang digelar DKUPP bekerjasama dengan Perum BULOG Cabang Probolinggo...',
            'image_url' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?q=80&w=800&auto=format&fit=crop',
            'category' => 'Perdagangan',
            'published_at' => '2026-06-18',
            'views' => 520,
            'is_featured' => true,
        ]);
        NewsItem::create([
            'title' => 'Digitalisasi Koperasi: DKUPP Mendorong Penerapan E-RAT dan Aplikasi Keuangan Koperasi Berbasis Cloud',
            'slug' => 'digitalisasi-koperasi-dkupp-dorong-e-rat-dan-aplikasi-cloud',
            'summary' => 'Transformasi digital tata kelola koperasi di Kabupaten Probolinggo menuju efisiensi dan transparansi akuntabel.',
            'content' => 'Kraksaan - Sosialisasi aplikasi E-RAT dan akuntansi digital berbasis cloud bagi para pengurus Koperasi Pegawai Republik Indonesia (KPRI)...',
            'image_url' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop',
            'category' => 'Koperasi',
            'published_at' => '2026-06-10',
            'views' => 260,
            'is_featured' => false,
        ]);

        // 6. Sidebar Widgets
        SidebarWidget::truncate();
        SidebarWidget::create([
            'title' => 'Portal Layanan SIMADU SAE UMKM',
            'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=600&auto=format&fit=crop',
            'target_url' => '/katalog-umkm',
            'order' => 1,
            'is_active' => true,
        ]);
        SidebarWidget::create([
            'title' => 'Monitoring Harga Bahan Pokok Hari Ini',
            'image_url' => 'https://images.unsplash.com/photo-1610348725531-843dff563e2c?q=80&w=600&auto=format&fit=crop',
            'target_url' => '/harga-pasar',
            'order' => 2,
            'is_active' => true,
        ]);

        // 7. Related Links
        RelatedLink::truncate();
        RelatedLink::create([
            'title' => 'Kementerian Koperasi & UKM',
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg',
            'url' => 'https://kemenkopukm.go.id/',
            'order' => 1,
            'is_active' => true,
        ]);
        RelatedLink::create([
            'title' => 'Kementerian Perdagangan RI',
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg',
            'url' => 'https://kemendag.go.id/',
            'order' => 2,
            'is_active' => true,
        ]);
        RelatedLink::create([
            'title' => 'Pemkab Probolinggo',
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg',
            'url' => 'https://probolinggokab.go.id/',
            'order' => 3,
            'is_active' => true,
        ]);
        RelatedLink::create([
            'title' => 'Diskominfo Probolinggo',
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Lambang_Kabupaten_Probolinggo.jpg/440px-Lambang_Kabupaten_Probolinggo.jpg',
            'url' => 'https://diskominfo.probolinggokab.go.id/',
            'order' => 4,
            'is_active' => true,
        ]);

        // 8. UMKM Products (SIMADU SAE)
        UmkmProduct::truncate();
        UmkmProduct::create([
            'name' => 'Batik Tulis Motif Probolinggoan',
            'slug' => 'batik-tulis-motif-probolinggoan',
            'owner_name' => 'Batik Griya Kraksaan',
            'category' => 'Fashion',
            'district' => 'Kraksaan',
            'description' => 'Kain Batik Tulis berkualitas tinggi dengan motif khas Anggur & Mangga khas Kabupaten Probolinggo.',
            'price' => 250000.00,
            'price_unit' => 'lembar',
            'phone' => '081234567891',
            'image' => 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?q=80&w=600&auto=format&fit=crop',
            'is_featured' => true,
            'is_verified' => true,
        ]);
        UmkmProduct::create([
            'name' => 'Keripik Mangga Probolinggo Super',
            'slug' => 'keripik-mangga-probolinggo-super',
            'owner_name' => 'Oleh-Oleh Mangga Sejahtera',
            'category' => 'Kuliner',
            'district' => 'Paiton',
            'description' => 'Olahan keripik mangga segar kualitas ekspor tanpa bahan pengawet.',
            'price' => 25000.00,
            'price_unit' => 'pouch 150g',
            'phone' => '081234567892',
            'image' => 'https://images.unsplash.com/photo-1599490659213-e2b9527bd087?q=80&w=600&auto=format&fit=crop',
            'is_featured' => true,
            'is_verified' => true,
        ]);
        UmkmProduct::create([
            'name' => 'Kopi Arabika Tengger Krucil',
            'slug' => 'kopi-arabika-tengger-krucil',
            'owner_name' => 'KWT Gunung Argopuro',
            'category' => 'Minuman',
            'district' => 'Krucil',
            'description' => 'Kopi Arabika organik hasil petik merah pegunungan Krucil dengan cita rasa manis fruity alami.',
            'price' => 45000.00,
            'price_unit' => 'pack 200g',
            'phone' => '081234567893',
            'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?q=80&w=600&auto=format&fit=crop',
            'is_featured' => true,
            'is_verified' => true,
        ]);
        UmkmProduct::create([
            'name' => 'Bawang Goreng Crispy Probolinggo',
            'slug' => 'bawang-goreng-crispy-probolinggo',
            'owner_name' => 'Bawang Berkah Dringu',
            'category' => 'Kuliner',
            'district' => 'Dringu',
            'description' => 'Bawang goreng gurih renyah dari varietas bawang merah super Probolinggo.',
            'price' => 30000.00,
            'price_unit' => 'toples 250g',
            'phone' => '081234567894',
            'image' => 'https://images.unsplash.com/photo-1618160702438-9b02ab6515c9?q=80&w=600&auto=format&fit=crop',
            'is_featured' => true,
            'is_verified' => true,
        ]);
        UmkmProduct::create([
            'name' => 'Soto Kraksaan Khas Probolinggo',
            'slug' => 'soto-kraksaan-khas-probolinggo',
            'owner_name' => 'Warung Soto Kraksaan Pak Sholeh',
            'category' => 'Kuliner',
            'district' => 'Kraksaan',
            'description' => 'Soto Kraksaan legit gurih lengkap dengan koya kelapa renyah dan kuah rempah santan khas Kabupaten Probolinggo.',
            'price' => 20000.00,
            'price_unit' => 'porsi',
            'phone' => '081234567895',
            'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?q=80&w=600&auto=format&fit=crop',
            'is_featured' => true,
            'is_verified' => true,
        ]);

        // 9. Market Prices (Pemantauan Harga Bahan Pokok)
        MarketPrice::truncate();
        MarketPrice::create([
            'commodity_name' => 'Soto Kraksaan Khas Probolinggo',
            'unit' => 'Porsi',
            'price_today' => 20000,
            'price_yesterday' => 20000,
            'status' => 'stabil',
            'market_location' => 'Pusat Kuliner Kraksaan',
            'updated_date' => now()->toDateString(),
        ]);
        MarketPrice::create([
            'commodity_name' => 'Beras Medium',
            'unit' => 'Kg',
            'price_today' => 12500,
            'price_yesterday' => 12500,
            'status' => 'stabil',
            'market_location' => 'Pasar Kraksaan & Semampir',
            'updated_date' => now()->toDateString(),
        ]);
        MarketPrice::create([
            'commodity_name' => 'Beras Premium',
            'unit' => 'Kg',
            'price_today' => 14800,
            'price_yesterday' => 15000,
            'status' => 'turun',
            'market_location' => 'Pasar Kraksaan',
            'updated_date' => now()->toDateString(),
        ]);
        MarketPrice::create([
            'commodity_name' => 'Minyak Goreng Minyakita',
            'unit' => 'Liter',
            'price_today' => 15500,
            'price_yesterday' => 15500,
            'status' => 'stabil',
            'market_location' => 'Pasar Paiton',
            'updated_date' => now()->toDateString(),
        ]);
        MarketPrice::create([
            'commodity_name' => 'Gula Pasir',
            'unit' => 'Kg',
            'price_today' => 16500,
            'price_yesterday' => 16000,
            'status' => 'naik',
            'market_location' => 'Pasar Dringu',
            'updated_date' => now()->toDateString(),
        ]);
        MarketPrice::create([
            'commodity_name' => 'Cabai Rawit Merah',
            'unit' => 'Kg',
            'price_today' => 38000,
            'price_yesterday' => 42000,
            'status' => 'turun',
            'market_location' => 'Pasar Kraksaan',
            'updated_date' => now()->toDateString(),
        ]);
        MarketPrice::create([
            'commodity_name' => 'Bawang Merah Probolinggo',
            'unit' => 'Kg',
            'price_today' => 28000,
            'price_yesterday' => 27000,
            'status' => 'naik',
            'market_location' => 'Pasar Dringu',
            'updated_date' => now()->toDateString(),
        ]);
        MarketPrice::create([
            'commodity_name' => 'Daging Sapi Fresh',
            'unit' => 'Kg',
            'price_today' => 115000,
            'price_yesterday' => 115000,
            'status' => 'stabil',
            'market_location' => 'Pasar Kraksaan',
            'updated_date' => now()->toDateString(),
        ]);

        // 10. Services (Layanan Utama DKUPP)
        Service::truncate();
        Service::create([
            'title' => 'Portal Layanan UMKM SIMADU SAE',
            'slug' => 'simadu-sae-umkm',
            'category' => 'Usaha Mikro',
            'icon' => 'fa-store',
            'summary' => 'Sistem Manajemen Pengembangan UMKM Terpadu untuk katalog produk, kemitraan, dan promosi digital.',
            'requirements' => '1. KTP Kabupaten Probolinggo<br>2. Produk Usaha Mikro Lokal<br>3. Foto Produk Berkualitas Baik',
            'procedure' => '1. Kunjungi Portal Resmi SIMADU SAE<br>2. Daftar / Masuk Akun Pelaku Usaha<br>3. Upload Foto Produk & Kontak<br>4. Verifikasi oleh Tim DKUPP',
            'service_time' => '1 Hari Kerja',
            'cost' => 'Gratis (Rp 0)',
            'external_url' => 'https://simadu.probolinggokab.go.id/',
            'is_active' => true,
        ]);
        Service::create([
            'title' => 'Pelayanan Uji Tera & Tera Ulang Metrologi Legal',
            'slug' => 'metrologi-legal',
            'category' => 'Perdagangan',
            'icon' => 'fa-balance-scale',
            'summary' => 'Pengujian dan pengesahan alat-alat ukur, takar, timbang, dan perlengkapannya (UTTP) di pasar dan SPBU.',
            'requirements' => '1. Permohonan Tera/Tera Ulang UTTP<br>2. Peralatan UTTP siap uji<br>3. Lokasi usaha di Kabupaten Probolinggo',
            'procedure' => '1. Mengajukan surat permohonan ke Loket MPP / DKUPP<br>2. Penjadwalan Sidang Tera / Peneraan di Tempat<br>3. Uji Teknis oleh Penera<br>4. Pembubuhan Cap Tanda Tera (CTT)',
            'service_time' => '1-2 Hari Kerja',
            'cost' => 'Sesuai Retribusi Daerah',
            'external_url' => null,
            'is_active' => true,
        ]);
        Service::create([
            'title' => 'Pendampingan & Perizinan Koperasi',
            'slug' => 'pendampingan-koperasi',
            'category' => 'Koperasi',
            'icon' => 'fa-users',
            'summary' => 'Fasilitasi pembentukan, pemeriksaan kesehatan koperasi, pendampingan RAT, serta perizinan usaha KPRI & Koperasi Daerah.',
            'requirements' => '1. Berkas Akta Pendirian Koperasi / Perubahan Anggaran Dasar<br>2. Susunan Pengurus & Pengawas Koperasi<br>3. Laporan Keuangan Tahunan (RAT)',
            'procedure' => '1. Konsultasi & Pengajuan ke Bidang Koperasi DKUPP<br>2. Verifikasi Lapangan & Kelayakan Usaha<br>3. Penerbitan Rekomendasi / Sertifikat Kesehatan Koperasi',
            'service_time' => '3-5 Hari Kerja',
            'cost' => 'Gratis (Rp 0)',
            'external_url' => null,
            'is_active' => true,
        ]);
        Service::create([
            'title' => 'Fasilitasi NIB & Sertifikasi Halal Gratis UMKM',
            'slug' => 'legalitas-umkm',
            'category' => 'Usaha Mikro',
            'icon' => 'fa-certificate',
            'summary' => 'Pendampingan pembuatan NIB (Nomor Induk Berusaha) via OSS RBA serta Sertifikasi Halal Self Declare.',
            'requirements' => '1. KTP & NPWP (jika ada)<br>2. Email & Nomor WhatsApp Aktif<br>3. Rincian Produk & Alamat Usaha',
            'procedure' => '1. Datang ke Loket DKUPP di Mal Pelayanan Publik (MPP)<br>2. Petugas melakukan pendampingan input data OSS RBA<br>3. NIB & Sertifikat diterbitkan',
            'service_time' => 'Langsung Jadi (30 Menit)',
            'cost' => 'Gratis (Rp 0)',
            'external_url' => null,
            'is_active' => true,
        ]);
        Service::create([
            'title' => 'Sistem Resi Gudang (SRG) Komoditas Pangan',
            'slug' => 'sistem-resi-gudang',
            'category' => 'Perindustrian & Perdagangan',
            'icon' => 'fa-warehouse',
            'summary' => 'Fasilitasi penyimpanan hasil pertanian (bawang merah/gabah) di gudang SRG untuk menjaga stabilitas harga panen.',
            'requirements' => '1. KTP Petani / Kelompok Tani (Poktan)<br>2. Komoditas pertanian memenuhi standar mutu pengujian SRG<br>3. Surat Pengantar dari Dinas / Poktan',
            'procedure' => '1. Penyerahan komoditas ke Pengelola Gudang SRG<br>2. Pengujian Mutu Komoditas oleh Penguji Terakreditasi<br>3. Penerbitan Dokumen Resi Gudang untuk agunan pembiayaan',
            'service_time' => '1 Hari Kerja',
            'cost' => 'Sesuai Ketentuan SRG',
            'external_url' => null,
            'is_active' => true,
        ]);

        // 11. Public Documents
        PublicDocument::truncate();
        PublicDocument::create([
            'title' => 'Rencana Strategis (RENSTRA) DKUPP Kabupaten Probolinggo Tahun 2024-2026',
            'category' => 'Perencanaan Kinerja',
            'file_url' => '/uploads/documents/renstra_dkupp_2024_2026.pdf',
            'download_count' => 312,
        ]);
        PublicDocument::create([
            'title' => 'Indikator Kinerja Utama (IKU) DKUPP Kabupaten Probolinggo Tahun 2026',
            'category' => 'Pengukuran Kinerja',
            'file_url' => '/uploads/documents/iku_dkupp_2026.pdf',
            'download_count' => 195,
        ]);
        PublicDocument::create([
            'title' => 'Laporan Kinerja Instansi Pemerintah (LKjIP / SAKIP) DKUPP Tahun 2025',
            'category' => 'Pelaporan Kinerja',
            'file_url' => '/uploads/documents/lkjip_dkupp_2025.pdf',
            'download_count' => 240,
        ]);
        PublicDocument::create([
            'title' => 'Laporan Hasil Evaluasi Kinerja dan SAKIP Internal DKUPP Tahun 2025',
            'category' => 'Evaluasi Kinerja',
            'file_url' => '/uploads/documents/evaluasi_sakip_dkupp_2025.pdf',
            'download_count' => 142,
        ]);

        // 12. Pages DKUPP
        Page::truncate();
        Page::create([
            'title' => 'Visi Misi DKUPP',
            'slug' => 'visi-misi',
            'content' => '<h2>Visi & Misi DKUPP Kabupaten Probolinggo</h2><p><strong>Visi:</strong> Terwujudnya Kabupaten Probolinggo yang Sejahtera, Mandiri, Berdaya Saing Melalui Penguatan Ekonomi Rakyat, Koperasi, UMKM, Perdagangan dan Perindustrian Berkelanjutan.</p><p><strong>Misi:</strong><br>1. Meningkatkan daya saing dan kelembagaan Koperasi.<br>2. Mengembangkan Usaha Mikro melalui teknologi digital SIMADU SAE.<br>3. Menjamin kelancaran distribusi barang dan stabilitas harga bahan pokok.<br>4. Memperkuat perlindungan konsumen melalui Metrologi Legal.</p>',
            'is_published' => true,
        ]);
        Page::create([
            'title' => 'Tugas dan Fungsi DKUPP',
            'slug' => 'tugas-dan-fungsi',
            'content' => '<h2>Tugas dan Fungsi DKUPP</h2><p>DKUPP mempunyai tugas membantu Bupati melaksanakan urusan pemerintahan yang menjadi kewenangan Daerah di bidang Koperasi, Usaha Mikro, Perdagangan, dan Perindustrian serta tugas pembantuan yang diberikan kepada Daerah.</p>',
            'is_published' => true,
        ]);
        Page::create([
            'title' => 'Survei Kepuasan Masyarakat (SKM)',
            'slug' => 'survei-kepuasan-masyarakat',
            'content' => '<h2>Hasil Survei Kepuasan Masyarakat (SKM) 2025</h2><p>Nilai Indeks Kepuasan Masyarakat (IKM) DKUPP Kabupaten Probolinggo Tahun 2025 memperoleh skor <strong>88.75</strong> dengan Mutu Pelayanan <strong>A (Sangat Baik)</strong>.</p>',
            'is_published' => true,
        ]);

        // 13. Org Members (Struktur Organisasi)
        \App\Models\OrgMember::truncate();
        $kadin = \App\Models\OrgMember::create([
            'name' => 'Drs. H. Taufik Alami, M.Si',
            'position' => 'KEPALA DINAS',
            'type' => 'personel',
            'parent_id' => null,
            'photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop',
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\OrgMember::create([
            'name' => '-',
            'position' => 'KELOMPOK JABATAN FUNGSIONAL',
            'type' => 'kelompok_fungsional',
            'parent_id' => $kadin->id,
            'photo' => null,
            'order' => 1,
            'is_active' => true,
        ]);

        $sekretaris = \App\Models\OrgMember::create([
            'name' => 'Drs. Wahyu Hidayat, M.Si',
            'position' => 'SEKRETARIS DINAS',
            'type' => 'personel',
            'parent_id' => $kadin->id,
            'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=600&auto=format&fit=crop',
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Models\OrgMember::create([
            'name' => 'Sri Hidayati, SE',
            'position' => 'Kasubag Umum dan Kepegawaian',
            'type' => 'personel',
            'parent_id' => $sekretaris->id,
            'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop',
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\OrgMember::create([
            'name' => 'Hasyim Ashari, SH. MM',
            'position' => 'Kasubag Perencanaan dan Keuangan',
            'type' => 'personel',
            'parent_id' => $sekretaris->id,
            'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=300&auto=format&fit=crop',
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Models\OrgMember::create([
            'name' => 'Dody Kasman, S.Sos',
            'position' => 'KEPALA BIDANG KOPERASI',
            'type' => 'personel',
            'parent_id' => $kadin->id,
            'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=400&auto=format&fit=crop',
            'order' => 3,
            'is_active' => true,
        ]);

        \App\Models\OrgMember::create([
            'name' => 'Rahadi SK., S.Kom., M.Eng.',
            'position' => 'KEPALA BIDANG USAHA MIKRO',
            'type' => 'personel',
            'parent_id' => $kadin->id,
            'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=400&auto=format&fit=crop',
            'order' => 4,
            'is_active' => true,
        ]);

        \App\Models\OrgMember::create([
            'name' => 'Dodik Budianto, S.Sos, M.Si',
            'position' => 'KEPALA BIDANG PERDAGANGAN',
            'type' => 'personel',
            'parent_id' => $kadin->id,
            'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop',
            'order' => 5,
            'is_active' => true,
        ]);

        \App\Models\OrgMember::create([
            'name' => 'Hj. Fitriani, ST., MM',
            'position' => 'KEPALA BIDANG PERINDUSTRIAN',
            'type' => 'personel',
            'parent_id' => $kadin->id,
            'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop',
            'order' => 6,
            'is_active' => true,
        ]);
    }
}
