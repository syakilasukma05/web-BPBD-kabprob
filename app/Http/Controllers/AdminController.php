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
use App\Models\PageDocument;
use App\Models\PageLink;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\MediaItem;
use App\Models\VisitorStatistic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


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
        $request->validate(['title' => 'required|string|max:255']);
        
        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                return back()->withErrors(['image_file' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
            }
            $filename = 'slider_' . time() . '_' . Str::random(5) . '.' . $ext;
            $destinationDir = public_path('uploads/sliders');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0777, true);
            }
            $destination = $destinationDir . '/' . $filename;

            $this->processBannerLandscapeImage($file->getPathname(), $destination);
            $imageUrl = '/uploads/sliders/' . $filename;
        }

        HeroSlider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_url' => $imageUrl,
            'button_text' => $request->button_text ?? null,
            'button_url' => $request->button_url ?? null,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Banner Slider berhasil ditambahkan!');
    }

    public function sliderUpdate(Request $request, $id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $slider = HeroSlider::findOrFail($id);
        
        $imageUrl = $slider->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                return back()->withErrors(['image_file' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
            }
            $filename = 'slider_' . time() . '_' . Str::random(5) . '.' . $ext;
            $destinationDir = public_path('uploads/sliders');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0777, true);
            }
            $destination = $destinationDir . '/' . $filename;

            $this->processBannerLandscapeImage($file->getPathname(), $destination);
            $imageUrl = '/uploads/sliders/' . $filename;

            // Hapus file lama jika ada dan tersimpan di uploads/sliders
            if ($slider->image_url && str_contains($slider->image_url, '/uploads/sliders/')) {
                $oldFile = public_path(ltrim(parse_url($slider->image_url, PHP_URL_PATH), '/'));
                if (file_exists($oldFile) && $oldFile !== $destination) {
                    @unlink($oldFile);
                }
            }
        }

        $slider->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_url' => $imageUrl,
            'button_text' => $request->button_text ?? $slider->button_text,
            'button_url' => $request->button_url ?? $slider->button_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Banner Slider berhasil diperbarui!');
    }

    private function processBannerLandscapeImage($filePath, $destinationPath, $targetWidth = 1920, $targetHeight = 800)
    {
        list($srcW, $srcH, $type) = @getimagesize($filePath);
        if (!$srcW || !$srcH) {
            @move_uploaded_file($filePath, $destinationPath);
            return;
        }

        $srcImg = null;
        if ($type === IMAGETYPE_JPEG) {
            $srcImg = @imagecreatefromjpeg($filePath);
        } elseif ($type === IMAGETYPE_PNG) {
            $srcImg = @imagecreatefrompng($filePath);
        }

        if (!$srcImg) {
            @copy($filePath, $destinationPath);
            return;
        }

        $targetRatio = $targetWidth / $targetHeight;
        $srcRatio = $srcW / $srcH;

        $dstImg = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($type === IMAGETYPE_PNG) {
            imagealphablending($dstImg, false);
            imagesavealpha($dstImg, true);
            $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
            imagefilledrectangle($dstImg, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        if ($srcRatio > $targetRatio) {
            // Landscape photo wider than target ratio
            $cropH = $srcH;
            $cropW = (int)($srcH * $targetRatio);
            $srcX = (int)(($srcW - $cropW) / 2);
            $srcY = 0;
        } else {
            // Portrait or square photo
            $cropW = $srcW;
            $cropH = (int)($srcW / $targetRatio);
            $srcX = 0;
            // Bias Y crop slightly towards upper-center (20% from top) so main subjects are preserved
            $srcY = (int)(($srcH - $cropH) * 0.2);
            if ($srcY < 0) $srcY = 0;
            if ($srcY + $cropH > $srcH) $srcY = $srcH - $cropH;
        }

        imagecopyresampled($dstImg, $srcImg, 0, 0, $srcX, $srcY, $targetWidth, $targetHeight, $cropW, $cropH);

        if ($type === IMAGETYPE_PNG) {
            imagepng($dstImg, $destinationPath, 8);
        } else {
            imagejpeg($dstImg, $destinationPath, 90);
        }

        imagedestroy($srcImg);
        imagedestroy($dstImg);
    }

    public function sliderDestroy($id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $slider = HeroSlider::findOrFail($id);
        if ($slider->image_url && str_contains($slider->image_url, '/uploads/sliders/')) {
            $filePath = public_path(ltrim(parse_url($slider->image_url, PHP_URL_PATH), '/'));
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        $slider->delete();
        return back()->with('success', 'Banner Slider berhasil dihapus!');
    }

    private function isSystemAuthMenu($menu)
    {
        if (!$menu) return true;
        $url = strtolower(trim($menu->url ?? ''));
        $title = strtoupper(trim($menu->title ?? ''));
        return in_array($url, ['/login', '/logout']) || in_array($title, ['LOGIN']);
    }

    // --- 4. STRUKTUR NAVIGASI WEBSITE (TREE HIRARKI MENU & SUBMENU) ---
    public function navigation()
    {
        $allMenus = NavigationMenu::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();
        $menus = $allMenus->reject(function($m) {
            return $this->isSystemAuthMenu($m);
        })->values();

        foreach ($menus as $m) {
            if ($m->children) {
                $m->setRelation('children', $m->children->reject(function($c) {
                    return $this->isSystemAuthMenu($c);
                })->values());
            }
        }

        $allParents = $menus;
        return view('admin.navigation', compact('menus', 'allParents'));
    }

    public function navigationEdit($id)
    {
        $id = $this->sanitizeId($id);
        $menuItem = NavigationMenu::findOrFail($id);
        $allMenus = NavigationMenu::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();
        $menus = $allMenus->reject(function($m) {
            return $this->isSystemAuthMenu($m);
        })->values();

        foreach ($menus as $m) {
            if ($m->children) {
                $m->setRelation('children', $m->children->reject(function($c) {
                    return $this->isSystemAuthMenu($c);
                })->values());
            }
        }

        $allParents = $menus;
        return view('admin.navigation', compact('menus', 'allParents', 'menuItem'));
    }

    public function navigationStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        
        $rawUrl = $request->url;
        if (is_array($rawUrl)) {
            $rawUrl = end($rawUrl);
        }
        $url = trim(str_replace(['"', "'", '\\'], '', $rawUrl ?? ''));

        if (empty($url)) {
            $url = '#';
        }

        $type = $request->type ?? (preg_match('/^https?:\/\//i', $url) ? 'external' : 'internal');

        // If URL points to /halaman/slug and type is internal, auto-create initial Page record if missing
        if ($type === 'internal' && Str::contains($url, '/halaman/')) {
            $slug = Str::after($url, '/halaman/');
            if ($slug) {
                Page::firstOrCreate(
                    ['slug' => $slug],
                    ['title' => $request->title, 'content' => '', 'is_published' => true]
                );
            }
        }

        NavigationMenu::create([
            'title' => $request->title,
            'url' => $url,
            'type' => $type,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'target' => $request->target ?? '_self',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.navigation')->with('success', 'Struktur Menu Navigasi berhasil ditambahkan!');
    }

    public function navigationUpdate(Request $request, $id)
    {
        $id = $this->sanitizeId($id);
        $menu = NavigationMenu::findOrFail($id);
        $request->validate(['title' => 'required|string|max:255']);

        $rawUrl = $request->url;
        if (is_array($rawUrl)) {
            $rawUrl = end($rawUrl);
        }
        $url = trim(str_replace(['"', "'", '\\'], '', $rawUrl ?? ''));
        $url = !empty($rawUrl) ? $rawUrl : $menu->url;

        $type = $request->type ?? (preg_match('/^https?:\/\//i', $url) ? 'external' : ($menu->type ?? 'internal'));

        // If URL points to /halaman/slug and type is internal, auto-create initial Page record if missing
        if ($type === 'internal' && Str::contains($url, '/halaman/')) {
            $slug = Str::after($url, '/halaman/');
            if ($slug) {
                Page::firstOrCreate(
                    ['slug' => $slug],
                    ['title' => $request->title, 'content' => '', 'is_published' => true]
                );
            }
        }

        $menu->update([
            'title' => $request->title,
            'url' => $url,
            'type' => $type,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'target' => $request->target ?? '_self',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.navigation')->with('success', 'Struktur Menu Navigasi berhasil diperbarui!');
    }

    public function navigationDestroy($id)
    {
        $id = $this->sanitizeId($id);
        $menu = NavigationMenu::findOrFail($id);
        
        // Delete submenus if parent is deleted
        NavigationMenu::where('parent_id', $menu->id)->delete();
        $menu->delete();

        return redirect()->route('admin.navigation')->with('success', 'Struktur Menu Navigasi berhasil dihapus!');
    }

    public function menus()
    {
        $allMenus = NavigationMenu::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();
        
        $menus = $allMenus->reject(function($m) {
            return $this->isSystemAuthMenu($m);
        })->values();

        $pages = Page::with(['documents', 'links'])->orderBy('title', 'asc')->get();
        $pagesMap = $pages->keyBy('slug');

        $attachPageContent = function($menu) use ($pagesMap) {
            $menu->page_content = '';
            $menu->image_url = '';
            $menu->page_id = null;
            $menu->documents = [];
            $menu->links = [];
            $menu->is_external = false;
            $menu->is_editable_page = true;

            $url = trim($menu->url ?? '');
            $title = trim($menu->title ?? '');
            $titleLower = strtolower($title);
            $isSpecialInternalPage = Str::contains($titleLower, ['kajian risiko', 'krb', 'rencana penanggulangan', 'rpb', 'peringatan dini', 'ews', 'cuaca', 'bmkg']);

            // External link flag check
            if (!$isSpecialInternalPage && ($menu->type === 'external' || preg_match('/^https?:\/\//i', $url))) {
                $menu->is_external = true;
                $menu->type = 'external';
            } else {
                $menu->type = 'internal';
                $menu->is_external = false;
            }
            $menu->is_editable_page = true;
            
            $titleSlug = Str::slug($title);
            $cleanTitle = trim(preg_replace('/\s*\(.*?\)\s*/', '', $title));
            $cleanTitleSlug = Str::slug($cleanTitle);
            $cleanUrl = ltrim($url, '/');
            $urlSlug = null;
            if (Str::startsWith($cleanUrl, 'halaman/')) {
                $urlSlug = Str::after($cleanUrl, 'halaman/');
            } elseif (Str::contains($url, '/halaman/')) {
                $urlSlug = Str::after($url, '/halaman/');
            }

            // Search for associated page by title-slug, url-slug, clean title, or keywords
            $page = null;
            if ($urlSlug && isset($pagesMap[$urlSlug])) {
                $page = $pagesMap[$urlSlug];
            } elseif ($titleSlug && isset($pagesMap[$titleSlug])) {
                $page = $pagesMap[$titleSlug];
            } elseif ($cleanTitleSlug && isset($pagesMap[$cleanTitleSlug])) {
                $page = $pagesMap[$cleanTitleSlug];
            } else {
                $page = Page::with(['documents', 'links'])
                    ->where(function($q) use ($titleSlug, $urlSlug, $title, $cleanTitle, $cleanTitleSlug) {
                        if ($titleSlug) {
                            $q->orWhere('slug', $titleSlug)->orWhere('slug', 'like', '%' . $titleSlug . '%');
                        }
                        if ($cleanTitleSlug) {
                            $q->orWhere('slug', $cleanTitleSlug)->orWhere('slug', 'like', '%' . $cleanTitleSlug . '%');
                        }
                        if ($urlSlug) {
                            $q->orWhere('slug', $urlSlug);
                        }
                        if ($cleanTitle) {
                            $q->orWhere('title', 'like', '%' . $cleanTitle . '%');
                        }
                    })->first();
            }

            // If page exists, attach content & image details & page_id & documents & links
            if ($page) {
                $menu->page_id = $page->id;
                $menu->page_content = $page->content;
                $menu->image_url = $page->image_url;
                $menu->image_name = $page->image_name;
                $menu->image_size = $page->image_size;
                $menu->documents = $page->documents;
                $menu->links = $page->links;
            }
        };

        foreach ($menus as $menu) {
            $menu->is_parent_container = ($menu->url === '#' || ($menu->children && count($menu->children) > 0));
            $attachPageContent($menu);
            
            if ($menu->children) {
                $filteredChildren = $menu->children->reject(function($c) {
                    return $this->isSystemAuthMenu($c);
                })->values();

                foreach ($filteredChildren as $child) {
                    $attachPageContent($child);
                }
                $menu->setRelation('children', $filteredChildren);
            }
        }

        return view('admin.menus', compact('menus', 'pages'));
    }

    private function formatPageContent($content)
    {
        if (empty($content)) return '';

        $cleaned = $content;

        // Clean up any old double-escaped HTML entities (e.g. &lt;p&gt;) from previous corrupt saves
        while (Str::contains($cleaned, ['&lt;', '&gt;', '&amp;'])) {
            $decoded = htmlspecialchars_decode($cleaned, ENT_QUOTES);
            if ($decoded === $cleaned) break;
            $cleaned = $decoded;
        }

        // Regex callback to sanitize all href attributes (tel:, mailto:, http:, https:)
        $cleaned = preg_replace_callback('/href=["\']([^"\']+)["\']/i', function ($matches) {
            $url = trim($matches[1]);
            
            // Clean leading slashes and quotes before protocol (e.g. /"tel: or /tel: -> tel:)
            $url = preg_replace('/^\/+(["\']?)(tel|mailto|https?):/i', '$2:', $url);
            $url = preg_replace('/^["\']?(tel|mailto|https?):/i', '$1:', $url);
            
            // Clean tel: URLs (strip trailing slashes, quotes, spaces)
            if (preg_match('/^tel:/i', $url)) {
                $url = preg_replace('/\/+$/', '', $url);
                $url = trim($url, '"\'');
            }
            
            // Clean mailto: URLs
            if (preg_match('/^mailto:/i', $url)) {
                $url = preg_replace('/\/+$/', '', $url);
                $url = trim($url, '"\'');
            }

            // Clean http / https URLs
            if (preg_match('/^https?:\/\//i', $url)) {
                $url = trim($url, '"\'');
            }

            return 'href="' . $url . '"';
        }, $cleaned);

        // Strip unwanted internal CKEditor classes if present, preserving user HTML tags & attributes
        $cleaned = preg_replace('/\s*class=["\'](?:ck-[^\'\"]*|\s*)*["\']/i', '', $cleaned);

        // If content contains HTML tags (e.g. from CKEditor or raw HTML), preserve raw HTML directly
        if (preg_match('/<[a-z0-9]+/i', $cleaned)) {
            return trim($cleaned);
        }

        // Fallback for plain text input: wrap lines in <p> without double encoding
        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($cleaned)));
        $formatted = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $formatted[] = '<p>' . $line . '</p>';
        }

        return implode("\n", $formatted);
    }

    private function validateAndStorePageImage(Request $request, $fieldName = 'image_file')
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        $file = $request->file($fieldName);

        if (!$file->isValid()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => 'File yang diunggah tidak valid atau mengalami kerusakan.'
            ]);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = strtolower($file->getMimeType());

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimetypes = ['image/jpeg', 'image/png', 'image/pjpeg', 'image/x-png'];

        if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimetypes)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => 'Format file tidak valid. Upload hanya JPG, JPEG, atau PNG.'
            ]);
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => 'Ukuran file gambar tidak boleh melebihi 5 MB.'
            ]);
        }

        $originalName = $file->getClientOriginalName();
        $safeFileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;

        $imagePath = $file->storeAs('images/pages', $safeFileName, 'public');

        return [
            'image_url' => asset('storage/' . $imagePath),
            'image_path' => $imagePath,
            'image_name' => $originalName,
            'image_type' => $mimeType,
            'image_size' => $file->getSize(),
        ];
    }

    private function validateAndStorePageDocument(Request $request, $pageId, $fieldName = 'document_file')
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        $file = $request->file($fieldName);

        if (!$file->isValid()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => 'File dokumen yang diunggah tidak valid atau mengalami kerusakan.'
            ]);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = strtolower($file->getMimeType());

        $allowedExtensions = ['pdf'];
        $allowedMimetypes = ['application/pdf', 'application/x-pdf', 'application/acrobat', 'applications/vnd.pdf', 'text/pdf', 'text/x-pdf'];

        if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimetypes)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => 'Format dokumen tidak valid. Hanya file PDF yang diperbolehkan.'
            ]);
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => 'Ukuran file terlalu besar. Maksimal 10 MB.'
            ]);
        }

        $originalName = $file->getClientOriginalName();
        $safeFileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.pdf';

        $filePath = $file->storeAs('documents', $safeFileName, 'public');

        return PageDocument::create([
            'page_id' => $pageId,
            'file_name' => $originalName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => $file->getSize(),
        ]);
    }


    public function menuStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $imageData = $this->validateAndStorePageImage($request, 'image_file');
        
        $rawUrl = $request->url;
        if (is_array($rawUrl)) {
            $rawUrl = end($rawUrl);
        }
        $url = trim(str_replace(['"', "'", '\\'], '', $rawUrl ?? ''));

        // Process image URL or Uploaded file
        $imageUrl = $imageData ? $imageData['image_url'] : $request->image_url;

        // Determine slug from URL or title
        $slug = null;
        if ($url && Str::contains($url, '/halaman/')) {
            $slug = Str::after($url, '/halaman/');
        }
        if (!$slug) {
            $slug = Str::slug($request->title);
        }

        // If page_content or image_url or document_file is provided, automatically create/update Page record
        if ($request->filled('page_content') || !empty($imageUrl) || $request->hasFile('document_file')) {
            $formattedContent = $this->formatPageContent($request->page_content);
            
            $pageData = ['title' => $request->title];
            if ($request->filled('page_content')) {
                $pageData['content'] = $formattedContent;
            }
            if ($imageData) {
                $pageData = array_merge($pageData, $imageData);
            } elseif (!empty($imageUrl)) {
                $pageData['image_url'] = $imageUrl;
            }

            $page = Page::updateOrCreate(['slug' => $slug], $pageData);

            if ($request->hasFile('document_file')) {
                $this->validateAndStorePageDocument($request, $page->id, 'document_file');
            }

            if (empty($url) || Str::contains($url, 'tel:')) {
                $url = '/halaman/' . $slug;
            }
        }

        if (empty($url)) {
            $url = '#';
        }

        NavigationMenu::create([
            'title' => $request->title,
            'url' => $url,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'target' => $request->target ?? '_self',
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Menu Navigasi & Konten Halaman berhasil ditambahkan!');
    }

    public function menuUpdate(Request $request, $id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $menu = NavigationMenu::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $imageData = $this->validateAndStorePageImage($request, 'image_file');

        $rawUrl = $request->url;
        if (is_array($rawUrl)) {
            $rawUrl = end($rawUrl);
        }
        $rawUrl = trim(str_replace(['"', "'", '\\'], '', $rawUrl ?? ''));
        $url = !empty($rawUrl) ? $rawUrl : $menu->url;
        $title = trim($request->title ?? $menu->title ?? '');
        $titleLower = strtolower($title);
        $isSpecialInternalPage = Str::contains($titleLower, ['kajian risiko', 'krb', 'rencana penanggulangan', 'rpb', 'peringatan dini', 'ews', 'cuaca', 'bmkg']);

        $type = $request->type ?? ($menu->type ?? (preg_match('/^https?:\/\//i', $url) ? 'external' : 'internal'));
        if (!$isSpecialInternalPage && (preg_match('/^https?:\/\//i', $url) || $type === 'external')) {
            $type = 'external';
        } elseif ($isSpecialInternalPage) {
            $type = 'internal';
        }

        // If menu is external link type (submitted from external link form without page_content), update navigation_menu directly
        if ($type === 'external' && !$request->has('page_content') && !$request->hasFile('document_file')) {
            $isActive = $menu->is_active;
            if ($request->has('is_active')) {
                $isActive = $request->boolean('is_active');
            } elseif ($request->has('status')) {
                $isActive = ($request->status === 'aktif' || $request->status == '1');
            }

            $menu->update([
                'title' => $request->title,
                'url' => $url,
                'type' => 'external',
                'target' => $request->target ?? '_blank',
                'is_active' => $isActive,
            ]);

            return back()->with('success', 'Konten Tautan Eksternal berhasil diperbarui!');
        }

        // If existing menu points to a page (/halaman/...), protect route from invalid/tel links
        if ($menu->url && Str::contains($menu->url, '/halaman/') && (empty($url) || Str::contains($url, 'tel:') || $url === '#')) {
            $existingSlug = Str::after($menu->url, '/halaman/');
            $url = '/halaman/' . $existingSlug;
        }

        // Process image URL or Uploaded file
        $imageUrl = $imageData ? $imageData['image_url'] : $request->image_url;

        // Detect existing page slug from current/requested URL or title
        $targetUrl = $url ?: $menu->url;
        $cleanTargetUrl = ltrim($targetUrl, '/');
        $urlSlug = null;
        if (Str::startsWith($cleanTargetUrl, 'halaman/')) {
            $urlSlug = Str::after($cleanTargetUrl, 'halaman/');
        } elseif (Str::contains($targetUrl, '/halaman/')) {
            $urlSlug = Str::after($targetUrl, '/halaman/');
        }
        $titleSlug = Str::slug($request->title);

        // If page_content or image_url or document_file is provided, create/update Page record
        if ($request->has('page_content') || !empty($imageUrl) || $request->hasFile('document_file')) {
            $incomingContent = $request->input('page_content');
            $formattedContent = !is_null($incomingContent) ? $this->formatPageContent($incomingContent) : null;

            // Find existing page by urlSlug, titleSlug, or title
            $page = Page::where(function($q) use ($titleSlug, $urlSlug, $request, $menu) {
                if ($urlSlug) {
                    $q->orWhere('slug', $urlSlug);
                }
                if ($titleSlug) {
                    $q->orWhere('slug', $titleSlug);
                }
                $q->orWhere('title', $request->title)->orWhere('title', $menu->title);
            })->first();

            $pageData = ['title' => $request->title];
            if ($request->has('page_content')) {
                // If incoming content is empty, but page already has content in DB, keep existing content!
                if ($page && !empty($page->content) && empty(trim(strip_tags($formattedContent ?? ''))) && !str_contains($formattedContent ?? '', '<img') && !str_contains($formattedContent ?? '', '<iframe')) {
                    $pageData['content'] = $page->content;
                } else {
                    $pageData['content'] = $formattedContent;
                }
            }
            if ($imageData) {
                $pageData = array_merge($pageData, $imageData);
            } elseif (!empty($imageUrl)) {
                $pageData['image_url'] = $imageUrl;
            }

            if ($page) {
                $page->update($pageData);
            } else {
                $pageData['slug'] = $urlSlug ?: $titleSlug;
                $page = Page::create($pageData);
            }

            if ($request->hasFile('document_file')) {
                $this->validateAndStorePageDocument($request, $page->id, 'document_file');
            }
        }




        // Preserve existing navigation structure fields (parent_id, order, target, is_active) so saving content never breaks navigation hierarchy
        $parentId = $menu->parent_id;
        if ($request->has('parent_id') && !is_null($request->parent_id) && $request->parent_id !== '') {
            $parentId = $request->parent_id;
        }

        $order = $request->has('order') ? $request->order : $menu->order;
        $target = $request->has('target') ? $request->target : $menu->target;

        $isActive = $menu->is_active;
        if ($request->has('is_active')) {
            $isActive = $request->boolean('is_active');
        } elseif ($request->has('status')) {
            $isActive = ($request->status === 'aktif' || $request->status == '1');
        }

        $menu->update([
            'title' => $request->title ?? $menu->title,
            'url' => $url,
            'parent_id' => $parentId,
            'order' => $order,
            'target' => $target,
            'is_active' => $isActive,
        ]);
        return back()->with('success', 'Menu Navigasi & Konten Halaman berhasil diperbarui!');
    }

    public function menuDestroy($id)
    {
        NavigationMenu::findOrFail($id)->delete();
        return back()->with('success', 'Menu Navigasi berhasil dihapus!');
    }

    // --- 3. NEWS & INFORMASI CRUD ---
    public function news(Request $request)
    {
        $query = NewsItem::query();

        if ($request->has('status') && in_array($request->status, ['aktif', 'nonaktif'])) {
            $query->where('status', $request->status);
        }

        $newsList = $query->orderBy('order', 'asc')->orderBy('published_at', 'desc')->paginate(10)->appends($request->query());
        $defaultCategories = ['Peringatan Dini', 'Tanggap Darurat', 'Mitigasi Bencana', 'Kegiatan Posko'];
        $dbCategories = NewsItem::distinct()->pluck('category')->filter()->toArray();
        $categories = array_values(array_unique(array_merge($defaultCategories, $dbCategories)));
        $currentStatus = $request->status ?? 'semua';
        $maxOrder = (int) NewsItem::max('order');
        $nextOrder = $maxOrder > 0 ? $maxOrder + 1 : 1;

        return view('admin.news', compact('newsList', 'categories', 'currentStatus', 'nextOrder'));
    }

    public function newsStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        
        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            $mime = strtolower($file->getClientMimeType());
            $allowedExts = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/pjpeg', 'image/x-png'];

            if (!in_array($ext, $allowedExts) || !in_array($mime, $allowedMimes)) {
                return back()->withErrors(['image_file' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
            }
            $filename = 'news_' . time() . '_' . Str::random(5) . '.' . $ext;
            $file->move(public_path('uploads/news'), $filename);
            $imageUrl = url('uploads/news/' . $filename);
        }

        $category = !empty($request->custom_category) ? trim($request->custom_category) : ($request->category ?? 'Peringatan Dini');
        if (empty($category) || $category === '__custom__') {
            $category = 'Peringatan Dini';
        }

        $formattedContent = $this->formatPageContent($request->content);

        NewsItem::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'summary' => $request->summary ?? Str::limit(strip_tags($formattedContent), 120),
            'content' => $formattedContent,
            'image_url' => $imageUrl,
            'link_url' => $request->link_url,
            'category' => $category,
            'status' => $request->status ?? 'aktif',
            'order' => (int) ($request->order ?? 1),
            'published_at' => $request->published_at ?? now(),
        ]);

        ActivityLog::record('Tambah Berita', 'Mempublikasikan berita baru: ' . $request->title);

        return back()->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function newsUpdate(Request $request, $id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $news = NewsItem::findOrFail($id);
        
        $imageUrl = $news->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            $mime = strtolower($file->getClientMimeType());
            $allowedExts = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/pjpeg', 'image/x-png'];

            if (!in_array($ext, $allowedExts) || !in_array($mime, $allowedMimes)) {
                return back()->withErrors(['image_file' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
            }
            $filename = 'news_' . time() . '_' . Str::random(5) . '.' . $ext;
            $file->move(public_path('uploads/news'), $filename);
            $imageUrl = url('uploads/news/' . $filename);
        }

        $category = !empty($request->custom_category) ? trim($request->custom_category) : ($request->category ?? $news->category);
        if (empty($category) || $category === '__custom__') {
            $category = $news->category ?? 'Peringatan Dini';
        }

        $formattedContent = $this->formatPageContent($request->content);

        $news->update([
            'title' => $request->title,
            'summary' => $request->summary ?? Str::limit(strip_tags($formattedContent), 120),
            'content' => $formattedContent,
            'image_url' => $imageUrl,
            'link_url' => $request->link_url,
            'category' => $category,
            'status' => $request->status ?? ($news->status ?? 'aktif'),
            'order' => (int) ($request->order ?? $news->order ?? 1),
            'published_at' => $request->published_at,
        ]);

        ActivityLog::record('Edit Berita', 'Mengedit data berita: ' . $request->title);

        return back()->with('success', 'Berita berhasil diperbarui!');
    }

    public function newsDestroy($id)
    {
        $news = NewsItem::findOrFail($id);
        $title = $news->title;
        $news->delete();

        ActivityLog::record('Hapus Berita', 'Menghapus berita: ' . $title);

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
        $request->validate(['title' => 'required|string|max:255']);
        
        $fileUrl = $request->file_url;
        $fileSize = $request->file_size ?: '1.5 MB';
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $bytes = $file->getSize();
            if ($bytes >= 1048576) {
                $fileSize = number_format($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes >= 1024) {
                $fileSize = number_format($bytes / 1024, 0) . ' KB';
            } else {
                $fileSize = $bytes . ' bytes';
            }
            $filename = 'doc_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/documents'), $filename);
            $fileUrl = url('uploads/documents/' . $filename);
        }

        PublicDocument::create([
            'title' => $request->title,
            'category' => $request->category,
            'year' => $request->year ?: date('Y'),
            'file_size' => $fileSize,
            'description' => $request->description,
            'file_url' => $fileUrl,
        ]);

        ActivityLog::record('Upload Dokumen', 'Mengunggah dokumen kinerja baru: ' . $request->title);

        return back()->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function documentUpdate(Request $request, $id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $doc = PublicDocument::findOrFail($id);
        
        $fileUrl = $request->file_url ?: $doc->file_url;
        $fileSize = $request->file_size ?: ($doc->file_size ?: '1.5 MB');
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $bytes = $file->getSize();
            if ($bytes >= 1048576) {
                $fileSize = number_format($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes >= 1024) {
                $fileSize = number_format($bytes / 1024, 0) . ' KB';
            } else {
                $fileSize = $bytes . ' bytes';
            }
            $filename = 'doc_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/documents'), $filename);
            $fileUrl = url('uploads/documents/' . $filename);
        }

        $doc->update([
            'title' => $request->title,
            'category' => $request->category,
            'year' => $request->year ?: ($doc->year ?: date('Y')),
            'file_size' => $fileSize,
            'description' => $request->description,
            'file_url' => $fileUrl,
        ]);

        ActivityLog::record('Edit Dokumen', 'Mengubah dokumen kinerja: ' . $request->title);

        return back()->with('success', 'Dokumen berhasil diperbarui!');
    }

    public function documentDestroy($id)
    {
        $doc = PublicDocument::findOrFail($id);
        $title = $doc->title;
        $doc->delete();

        ActivityLog::record('Hapus Dokumen', 'Menghapus dokumen kinerja: ' . $title);

        return back()->with('success', 'Dokumen berhasil dihapus!');
    }

    // --- 5. PAGES & PAGE DOCUMENTS CRUD ---
    public function pages()
    {
        $pages = Page::with(['documents', 'links'])->orderBy('updated_at', 'desc')->get();
        return view('admin.pages', compact('pages'));
    }

    public function pageStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string']);
        $imageData = $this->validateAndStorePageImage($request, 'image_file');

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'is_published' => true,
        ];
        if ($imageData) {
            $data = array_merge($data, $imageData);
        }

        Page::create($data);
        return back()->with('success', 'Halaman berhasil dibuat!');
    }

    public function pageUpdate(Request $request, $id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $page = Page::findOrFail($id);
        $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string']);

        $imageData = $this->validateAndStorePageImage($request, 'image_file');

        $data = [
            'title' => $request->title,
            'content' => $request->content,
        ];
        if ($imageData) {
            $data = array_merge($data, $imageData);
        }

        $page->update($data);
        return back()->with('success', 'Halaman berhasil diperbarui!');
    }


    public function pageDestroy($id)
    {
        Page::findOrFail($id)->delete();
        return back()->with('success', 'Halaman berhasil dihapus!');
    }

    public function pageImageDestroy($id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $page = Page::findOrFail($id);

        if (!empty($page->image_path) && Storage::disk('public')->exists($page->image_path)) {
            Storage::disk('public')->delete($page->image_path);
        }

        $page->update([
            'image_url' => null,
            'image_path' => null,
            'image_name' => null,
            'image_type' => null,
            'image_size' => null,
        ]);

        return back()->with('success', 'Gambar headline halaman berhasil dihapus!');
    }


    public function pageDocumentStore(Request $request, $pageId)
    {
        $pageId = trim(str_replace(['"', "'", '\\'], '', $pageId));
        $page = Page::findOrFail($pageId);

        $this->validateAndStorePageDocument($request, $page->id, 'document_file');

        return back()->with('success', 'Dokumen PDF pendukung berhasil diunggah!');
    }


    public function pageDocumentDestroy($id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $document = PageDocument::findOrFail($id);

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Dokumen PDF pendukung berhasil dihapus!');
    }

    private function isValidPageUrl($url)
    {
        if (empty($url)) {
            return false;
        }
        $url = trim($url);
        
        // Reject javascript:, file:, data:, or any protocol other than http:// or https://
        if (preg_match('/^(javascript|file|data):/i', $url)) {
            return false;
        }
        
        // Must start with http:// or https://
        if (!preg_match('/^https?:\/\//i', $url)) {
            return false;
        }
        
        // Validate URL format
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        
        return true;
    }

    public function pageLinkStore(Request $request, $pageId)
    {
        $pageId = trim(str_replace(['"', "'", '\\'], '', $pageId));
        $page = Page::findOrFail($pageId);

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string',
            'target' => 'nullable|in:_blank,_self',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        if (!$this->isValidPageUrl($request->url)) {
            return back()->with('error', 'Format URL tidak valid.')->withErrors(['url' => 'Format URL tidak valid.'])->withInput();
        }

        PageLink::create([
            'page_id' => $page->id,
            'title' => $request->title,
            'url' => trim($request->url),
            'description' => $request->description,
            'target' => $request->target ?? '_blank',
            'status' => $request->status ?? 'aktif',
        ]);

        return back()->with('success', 'Link URL halaman berhasil ditambahkan!');
    }

    public function pageLinkUpdate(Request $request, $id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $link = PageLink::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string',
            'target' => 'nullable|in:_blank,_self',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        if (!$this->isValidPageUrl($request->url)) {
            return back()->with('error', 'Format URL tidak valid.')->withErrors(['url' => 'Format URL tidak valid.'])->withInput();
        }

        $link->update([
            'title' => $request->title,
            'url' => trim($request->url),
            'description' => $request->description,
            'target' => $request->target ?? '_blank',
            'status' => $request->status ?? 'aktif',
        ]);

        return back()->with('success', 'Link URL halaman berhasil diperbarui!');
    }

    public function pageLinkDestroy($id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $link = PageLink::findOrFail($id);
        $link->delete();

        return back()->with('success', 'Link URL halaman berhasil dihapus!');
    }


    // --- 6. SIDEBAR WIDGETS CRUD ---
    public function widgets()
    {
        $widgets = SidebarWidget::orderBy('order', 'asc')->get();
        return view('admin.widgets', compact('widgets'));
    }

    public function widgetStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        
        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                return back()->withErrors(['image_file' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
            }
            $filename = 'widget_' . time() . '_' . Str::random(5) . '.' . $ext;
            $destinationDir = public_path('uploads/widgets');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0777, true);
            }
            $file->move($destinationDir, $filename);
            $imageUrl = url('uploads/widgets/' . $filename);
        }

        SidebarWidget::create([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'target_url' => $request->target_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return redirect()->route('admin.widgets')->with('success', 'Widget Sidebar berhasil ditambahkan!');
    }

    public function widgetUpdate(Request $request, $id)
    {
        $id = $this->sanitizeId($id);
        $widget = SidebarWidget::findOrFail($id);
        
        $request->validate(['title' => 'required|string|max:255']);

        $imageUrl = $widget->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                return back()->withErrors(['image_file' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
            }
            $filename = 'widget_' . time() . '_' . Str::random(5) . '.' . $ext;
            $destinationDir = public_path('uploads/widgets');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0777, true);
            }
            $file->move($destinationDir, $filename);
            $imageUrl = url('uploads/widgets/' . $filename);
        }

        $widget->update([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'target_url' => $request->target_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return redirect()->route('admin.widgets')->with('success', 'Widget Sidebar berhasil diperbarui!');
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
        $request->validate(['title' => 'required|string|max:255']);
        
        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                return back()->withErrors(['image_file' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
            }
            $filename = 'link_' . time() . '_' . Str::random(5) . '.' . $ext;
            $file->move(public_path('uploads/links'), $filename);
            $imageUrl = url('uploads/links/' . $filename);
        }

        RelatedLink::create([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'url' => $request->url ?? '#',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Tautan Terkait berhasil ditambahkan!');
    }

    public function linkUpdate(Request $request, $id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $link = RelatedLink::findOrFail($id);
        
        $imageUrl = $link->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                return back()->withErrors(['image_file' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
            }
            $filename = 'link_' . time() . '_' . Str::random(5) . '.' . $ext;
            $file->move(public_path('uploads/links'), $filename);
            $imageUrl = url('uploads/links/' . $filename);
        }

        $link->update([
            'title' => $request->title,
            'image_url' => $imageUrl,
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

    // --- 7.5 GALERI MEDIA (FOTO & VIDEO) CRUD ---
    public function media(Request $request)
    {
        $type = $request->get('type', 'foto');
        $query = MediaItem::orderBy('order', 'asc')->orderBy('created_at', 'desc');

        if ($type && in_array($type, ['foto', 'video'])) {
            $query->where('type', $type);
        }

        $mediaItems = $query->paginate(12)->withQueryString();

        $stats = [
            'total_foto' => MediaItem::where('type', 'foto')->count(),
            'total_video' => MediaItem::where('type', 'video')->count(),
        ];

        $categories = MediaItem::distinct()->whereNotNull('category')->pluck('category')->toArray();
        if (empty($categories)) {
            $categories = ['Kegiatan BPBD', 'Evakuasi Bencana', 'Edukasi & Mitigasi', 'Simulasi Kebencanaan'];
        }

        return view('admin.media', compact('mediaItems', 'stats', 'type', 'categories'));
    }

    public function mediaStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:foto,video',
        ]);

        $fileUrl = null;
        $photos = [];

        if ($request->type === 'foto') {
            if ($request->hasFile('media_files')) {
                $files = $request->file('media_files');
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        return back()->withErrors(['media_files' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
                    }
                }

                foreach ($files as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $filename = 'media_' . time() . '_' . Str::random(5) . '.' . $ext;
                    $file->move(public_path('uploads/media'), $filename);
                    $photos[] = url('uploads/media/' . $filename);
                }
            } elseif ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    return back()->withErrors(['media_files' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
                }
                $filename = 'media_' . time() . '_' . Str::random(5) . '.' . $ext;
                $file->move(public_path('uploads/media'), $filename);
                $photos[] = url('uploads/media/' . $filename);
            }

            if (!empty($photos)) {
                $fileUrl = $photos[0];
            }
        }

        $item = MediaItem::create([
            'title' => $request->title,
            'type' => $request->type,
            'caption' => $request->caption,
            'file_url' => $fileUrl,
            'photos' => !empty($photos) ? $photos : null,
            'video_url' => $request->video_url,
            'category' => $request->category ?: 'Kegiatan BPBD',
            'order' => (int) ($request->order ?? 0),
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::record('Tambah Galeri Media', "Menambahkan item media '{$item->title}' (Tipe: {$item->type}).");

        return back()->with('success', 'Album / Item Media baru berhasil ditambahkan!');
    }

    public function mediaUpdate(Request $request, $id)
    {
        $id = $this->sanitizeId($id);
        $media = MediaItem::findOrFail($id);
        $request->validate(['title' => 'required|string|max:255']);

        $photos = $media->photos ?: [];
        if (is_string($photos)) {
            $decoded = json_decode($photos, true);
            $photos = is_array($decoded) ? $decoded : [$photos];
        }
        if (!is_array($photos) && !empty($media->file_url)) {
            $photos = [$media->file_url];
        }
        $photos = array_values(array_filter((array) $photos));

        if ($request->type === 'foto') {
            if ($request->hasFile('media_files')) {
                $files = $request->file('media_files');
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        return back()->withErrors(['media_files' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
                    }
                }

                $newPhotos = [];
                foreach ($files as $file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $filename = 'media_' . time() . '_' . Str::random(5) . '.' . $ext;
                    $file->move(public_path('uploads/media'), $filename);
                    $newPhotos[] = url('uploads/media/' . $filename);
                }
                $photos = $newPhotos;
            } elseif ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    return back()->withErrors(['media_files' => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
                }
                $filename = 'media_' . time() . '_' . Str::random(5) . '.' . $ext;
                $file->move(public_path('uploads/media'), $filename);
                $photos = [url('uploads/media/' . $filename)];
            }
        }

        $fileUrl = !empty($photos[0]) ? $photos[0] : $media->file_url;
        $formattedCaption = $request->caption ? $this->formatPageContent($request->caption) : null;

        $media->update([
            'title' => $request->title,
            'type' => $request->type ?? $media->type,
            'caption' => $formattedCaption,
            'file_url' => $fileUrl,
            'photos' => !empty($photos) ? array_values($photos) : null,
            'video_url' => $request->video_url,
            'category' => $request->category ?: ($media->category ?: 'Kegiatan BPBD'),
            'order' => (int) ($request->order ?? $media->order ?? 0),
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::record('Update Galeri Media', "Memperbarui data media '{$media->title}'.");

        return redirect()->route('admin.media', ['type' => $media->type])->with('success', 'Item Media berhasil diperbarui!');
    }

    public function mediaDestroy($id)
    {
        $media = MediaItem::findOrFail($id);
        $title = $media->title;
        $media->delete();

        ActivityLog::record('Hapus Galeri Media', "Menghapus item media '{$title}'.");

        return back()->with('success', 'Item Media berhasil dihapus!');
    }

    // --- 8. CONTACT MESSAGES & LAPOR BENCANA CRUD ---
    public function messages()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.messages', compact('messages'));
    }

    public function messageStatus(Request $request, $id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->update(['status' => $request->status]);
        return back()->with('success', 'Status laporan berhasil diubah!');
    }

    public function messageDestroy($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return back()->with('success', 'Pesan berhasil dihapus!');
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
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        ActivityLog::record('Tambah Pengguna', "Menambahkan pengguna baru '{$newUser->name}' ({$newUser->email}) sebagai {$newUser->role}.");

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function userUpdate(Request $request, $id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
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

        ActivityLog::record('Update Pengguna', "Perbarui data pengguna '{$user->name}' ({$user->email}).");

        return back()->with('success', 'Data User berhasil diperbarui!');
    }

    public function userDestroy($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }
        $user = User::findOrFail($id);
        $name = $user->name;
        $email = $user->email;
        $user->delete();

        ActivityLog::record('Hapus Pengguna', "Menghapus akun pengguna '{$name}' ({$email}).");

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
        if ($request->filled('hero_sp4n_url')) {
            $url = trim($request->hero_sp4n_url);
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return back()->withErrors(['hero_sp4n_url' => 'Format URL SP4N Lapor tidak valid. Contoh: https://www.lapor.go.id/'])->withInput();
            }
        }

        if ($request->filled('hero_contact_url')) {
            $url = trim($request->hero_contact_url);
            if (!filter_var($url, FILTER_VALIDATE_URL) && !Str::contains($url, ['wa.me', 'tel:', 'whatsapp'])) {
                return back()->withErrors(['hero_contact_url' => 'Format URL Hubungi Kami / WhatsApp tidak valid. Contoh: https://wa.me/628123456789'])->withInput();
            }
        }

        $fileKeys = [
            'logo_frontend_file' => 'logo_frontend',
            'logo_backend_file' => 'logo_backend',
            'logo_berakhlak_file' => 'logo_berakhlak',
            'top_header_banner_file' => 'top_header_banner',
        ];

        foreach ($fileKeys as $fileInput => $settingKey) {
            if ($request->hasFile($fileInput)) {
                $file = $request->file($fileInput);
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    return back()->withErrors([$fileInput => 'Format file tidak didukung. Silakan upload gambar JPG atau PNG.'])->withInput();
                }
                $filename = $settingKey . '_' . time() . '_' . Str::random(4) . '.' . $ext;
                $file->move(public_path('uploads/settings'), $filename);
                SiteSetting::set($settingKey, url('uploads/settings/' . $filename));
            }
        }

        // Handle SKM QR Code upload with strict validation
        if ($request->hasFile('qr_code_file')) {
            $file = $request->file('qr_code_file');

            if (!$file->isValid()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'qr_code_file' => 'File QR Code yang diunggah tidak valid atau mengalami kerusakan.'
                ]);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = strtolower($file->getMimeType());

            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $allowedMimetypes = ['image/png', 'image/jpeg', 'image/pjpeg', 'image/x-png'];

            if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimetypes)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'qr_code_file' => 'Format QR Code tidak valid. Upload hanya PNG/JPG/JPEG.'
                ]);
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'qr_code_file' => 'Ukuran file QR Code tidak boleh melebihi 5 MB.'
                ]);
            }

            // Remove old QR code file if present in storage
            $oldPath = SiteSetting::get('qr_image_path');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $originalName = $file->getClientOriginalName();
            $safeFileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            $qrPath = $file->storeAs('images/qrcode', $safeFileName, 'public');

            SiteSetting::set('qr_image_path', $qrPath);
            SiteSetting::set('qr_image_name', $originalName);
            SiteSetting::set('qr_image_size', $file->getSize());
            SiteSetting::set('qr_code_survey', asset('storage/' . $qrPath));

            $uploadedKeys[] = 'qr_code_survey';
            $uploadedKeys[] = 'qr_image_path';
            $uploadedKeys[] = 'qr_image_name';
            $uploadedKeys[] = 'qr_image_size';
        }

        $inputs = $request->except(array_merge(['_token', 'qr_code_file'], array_keys($fileKeys), $uploadedKeys));

        foreach ($inputs as $key => $value) {
            if ($value !== null && $value !== '') {
                SiteSetting::set($key, $value);
            }
        }

        ActivityLog::record('Update Pengaturan', 'Memperbarui konfigurasi utama website dan identitas instansi.');

        return back()->with('success', 'Pengaturan Website, Logo & QR Code SKM berhasil disimpan!');
    }

    // --- 11. ACTIVITY LOGS (LOG AKTIVITAS) ---
    public function logs(Request $request)
    {
        $query = ActivityLog::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(15)->withQueryString();

        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', now()->today())->count(),
            'unique_users' => ActivityLog::whereNotNull('user_id')->distinct('user_id')->count('user_id'),
            'last_activity' => ActivityLog::latest()->first(),
        ];

        $actionTypes = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.logs', compact('logs', 'stats', 'actionTypes'));
    }

    public function logsClear()
    {
        ActivityLog::truncate();
        ActivityLog::record('Pembersihan Log', 'Menghapus seluruh catatan log aktivitas sistem.');

        return back()->with('success', 'Seluruh catatan Log Aktivitas telah berhasil dibersihkan!');
    }

    public function logDestroy($id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return back()->with('success', 'Catatan log berhasil dihapus!');
    }

    public function stafLogs(Request $request)
    {
        $staffRoles = ['staf', 'staff', 'anggota'];

        $query = ActivityLog::whereIn('user_role', $staffRoles)
            ->orWhere(function($q) use ($staffRoles) {
                $q->whereNotIn('user_role', ['super_admin', 'admin', 'administrator'])
                  ->where('user_role', '!=', 'super_admin');
            })
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(15)->withQueryString();

        $stats = [
            'total_logs' => ActivityLog::whereIn('user_role', $staffRoles)->count(),
            'today_logs' => ActivityLog::whereIn('user_role', $staffRoles)->whereDate('created_at', now()->today())->count(),
            'unique_staf' => ActivityLog::whereIn('user_role', $staffRoles)->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
            'last_activity' => ActivityLog::whereIn('user_role', $staffRoles)->latest()->first(),
        ];

        $actionTypes = ActivityLog::whereIn('user_role', $staffRoles)->select('action')->distinct()->pluck('action');

        return view('admin.staf_logs', compact('logs', 'stats', 'actionTypes'));
    }

    // --- EDIT METHODS & PARAMETER SANITIZATION ---
    private function sanitizeId($id)
    {
        return trim(str_replace(['"', "'", '\\'], '', $id));
    }

    public function documentEdit($id)
    {
        $id = $this->sanitizeId($id);
        $doc = PublicDocument::findOrFail($id);
        $documents = PublicDocument::latest()->get();
        return view('admin.documents', compact('documents', 'doc'));
    }

    public function newsEdit($id)
    {
        $id = $this->sanitizeId($id);
        $newsItem = NewsItem::findOrFail($id);
        $newsList = NewsItem::latest()->paginate(10);
        $defaultCategories = ['Peringatan Dini', 'Tanggap Darurat', 'Mitigasi Bencana', 'Kegiatan Posko'];
        $dbCategories = NewsItem::distinct()->pluck('category')->filter()->toArray();
        $categories = array_values(array_unique(array_merge($defaultCategories, $dbCategories)));

        return view('admin.news', compact('newsList', 'newsItem', 'categories'));
    }

    public function sliderEdit($id)
    {
        $id = $this->sanitizeId($id);
        $sliderItem = HeroSlider::findOrFail($id);
        $sliders = HeroSlider::orderBy('order', 'asc')->get();
        return view('admin.sliders', compact('sliders', 'sliderItem'));
    }

    public function menuEdit($id)
    {
        $id = $this->sanitizeId($id);
        $menuItem = NavigationMenu::findOrFail($id);
        
        $pages = Page::with(['documents', 'links'])->orderBy('title', 'asc')->get();
        $pagesMap = $pages->keyBy('slug');

        $attachPageContent = function($menu) use ($pagesMap) {
            $menu->page_content = '';
            $menu->image_url = '';
            $menu->page_id = null;
            $menu->documents = [];
            $menu->links = [];
            $menu->is_external = false;
            $menu->is_editable_page = false;

            $url = trim($menu->url ?? '');
            $title = trim($menu->title ?? '');
            $titleLower = strtolower($title);
            $isSpecialInternalPage = Str::contains($titleLower, ['kajian risiko', 'krb', 'rencana penanggulangan', 'rpb', 'peringatan dini', 'ews', 'cuaca', 'bmkg']);

            if (!$isSpecialInternalPage && ($menu->type === 'external' || $menu->type === 'external_link' || preg_match('/^https?:\/\//i', $url))) {
                $menu->is_external = true;
                $menu->type = 'external';
            } else {
                $menu->type = 'internal';
                $menu->is_external = false;
            }
            $menu->is_editable_page = true;
            
            $titleSlug = Str::slug($title);
            $cleanTitle = trim(preg_replace('/\s*\(.*?\)\s*/', '', $title));
            $cleanTitleSlug = Str::slug($cleanTitle);
            $cleanUrl = ltrim($url, '/');
            $urlSlug = null;
            if (Str::startsWith($cleanUrl, 'halaman/')) {
                $urlSlug = Str::after($cleanUrl, 'halaman/');
            } elseif (Str::contains($url, '/halaman/')) {
                $urlSlug = Str::after($url, '/halaman/');
            }

            // Search for associated page by title-slug, url-slug, clean title, or keywords
            $page = null;
            if ($urlSlug && isset($pagesMap[$urlSlug])) {
                $page = $pagesMap[$urlSlug];
            } elseif ($titleSlug && isset($pagesMap[$titleSlug])) {
                $page = $pagesMap[$titleSlug];
            } elseif ($cleanTitleSlug && isset($pagesMap[$cleanTitleSlug])) {
                $page = $pagesMap[$cleanTitleSlug];
            } else {
                $page = Page::with(['documents', 'links'])
                    ->where(function($q) use ($titleSlug, $urlSlug, $title, $cleanTitle, $cleanTitleSlug) {
                        if ($titleSlug) {
                            $q->orWhere('slug', $titleSlug)->orWhere('slug', 'like', '%' . $titleSlug . '%');
                        }
                        if ($cleanTitleSlug) {
                            $q->orWhere('slug', $cleanTitleSlug)->orWhere('slug', 'like', '%' . $cleanTitleSlug . '%');
                        }
                        if ($urlSlug) {
                            $q->orWhere('slug', $urlSlug);
                        }
                        if ($cleanTitle) {
                            $q->orWhere('title', 'like', '%' . $cleanTitle . '%');
                        }
                    })->first();
            }

            if ($page) {
                $menu->page_id = $page->id;
                $menu->page_content = $page->content;
                $menu->image_url = $page->image_url;
                $menu->image_name = $page->image_name;
                $menu->image_size = $page->image_size;
                $menu->documents = $page->documents;
                $menu->links = $page->links;
            }
        };

        $attachPageContent($menuItem);

        $allMenus = NavigationMenu::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();
        $menus = $allMenus->reject(function($m) {
            return $this->isSystemAuthMenu($m);
        })->values();

        foreach ($menus as $m) {
            $m->is_parent_container = ($m->url === '#' || ($m->children && count($m->children) > 0));
            $attachPageContent($m);
            if ($m->children) {
                $filteredChildren = $m->children->reject(function($c) {
                    return $this->isSystemAuthMenu($c);
                })->values();
                foreach ($filteredChildren as $child) {
                    $attachPageContent($child);
                }
                $m->setRelation('children', $filteredChildren);
            }
        }

        return view('admin.menus', compact('menus', 'pages', 'menuItem'));
    }

    public function pageEdit($id)
    {
        $id = $this->sanitizeId($id);
        $pageItem = Page::findOrFail($id);
        $pages = Page::latest()->get();
        return view('admin.pages', compact('pages', 'pageItem'));
    }

    public function widgetEdit($id)
    {
        $id = $this->sanitizeId($id);
        $widgetItem = SidebarWidget::findOrFail($id);
        $widgets = SidebarWidget::orderBy('order', 'asc')->get();
        return view('admin.widgets', compact('widgets', 'widgetItem'));
    }

    public function linkEdit($id)
    {
        $id = $this->sanitizeId($id);
        $linkItem = RelatedLink::findOrFail($id);
        $links = RelatedLink::orderBy('order', 'asc')->get();
        return view('admin.links', compact('links', 'linkItem'));
    }

    public function mediaEdit(Request $request, $id)
    {
        $id = $this->sanitizeId($id);
        $mediaItem = MediaItem::findOrFail($id);
        $type = $request->get('type', $mediaItem->type ?? 'foto');
        $query = MediaItem::orderBy('order', 'asc')->orderBy('created_at', 'desc');

        if ($type && in_array($type, ['foto', 'video'])) {
            $query->where('type', $type);
        }

        $mediaItems = $query->paginate(12)->withQueryString();

        $stats = [
            'total_foto' => MediaItem::where('type', 'foto')->count(),
            'total_video' => MediaItem::where('type', 'video')->count(),
        ];

        $categories = MediaItem::distinct()->whereNotNull('category')->pluck('category')->toArray();
        if (empty($categories)) {
            $categories = ['Kegiatan BPBD', 'Evakuasi Bencana', 'Edukasi & Mitigasi', 'Simulasi Kebencanaan'];
        }

        return view('admin.media', compact('mediaItems', 'stats', 'type', 'categories', 'mediaItem'));
    }

    public function userEdit($id)
    {
        $id = $this->sanitizeId($id);
        $userItem = User::findOrFail($id);
        $users = User::latest()->get();
        return view('admin.users', compact('users', 'userItem'));
    }

    public function visitorStats()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        $stats = [
            'visit_today' => $settings['visit_today'] ?? 124,
            'visit_month' => $settings['visit_month'] ?? 2840,
            'visit_year'  => $settings['visit_year'] ?? 34510,
            'hits_today'  => $settings['hits_today'] ?? 380,
            'hits_month'  => $settings['hits_month'] ?? 8920,
        ];

        return view('admin.visitor_stats', compact('stats'));
    }

    public function visitorStatStore(Request $request)
    {
        $request->validate([
            'visit_today' => 'required|numeric|min:0',
            'visit_month' => 'required|numeric|min:0',
            'visit_year'  => 'required|numeric|min:0',
            'hits_today'  => 'required|numeric|min:0',
            'hits_month'  => 'required|numeric|min:0',
        ]);

        SiteSetting::set('visit_today', (int) $request->visit_today);
        SiteSetting::set('visit_month', (int) $request->visit_month);
        SiteSetting::set('visit_year', (int) $request->visit_year);
        SiteSetting::set('hits_today', (int) $request->hits_today);
        SiteSetting::set('hits_month', (int) $request->hits_month);

        return redirect()->route('admin.visitor_stats')->with('success', 'Data Statistik Pengunjung berhasil diperbarui dan tersimpan!');
    }

    public function visitorStatEdit($id)
    {
        return $this->visitorStats();
    }

    public function visitorStatUpdate(Request $request, $id = null)
    {
        return $this->visitorStatStore($request);
    }

    public function visitorStatDestroy($id)
    {
        return redirect()->route('admin.visitor_stats')->with('success', 'Data statistik pengunjung berhasil diperbarui.');
    }

    // --- 11. PERINGATAN AKTIF (EMERGENCY ALERT MANAGEMENT) ---
    public function activeAlertEdit()
    {
        $status = SiteSetting::get('active_alert_status', 'aktif');
        $title = SiteSetting::get('active_alert_title', 'PERINGATAN AKTIF');
        $message = SiteSetting::get('active_alert_message', '');
        $link = SiteSetting::get('active_alert_link', '');

        return view('admin.active_alert', compact('status', 'title', 'message', 'link'));
    }

    public function activeAlertUpdate(Request $request)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif',
            'title' => 'required|string|max:100',
            'link' => 'nullable|string|max:500',
            'message' => 'nullable|string',
        ]);

        SiteSetting::set('active_alert_status', $request->status);
        SiteSetting::set('active_alert_title', $request->title);
        SiteSetting::set('active_alert_message', $request->message ?? '');
        SiteSetting::set('active_alert_link', $request->link ?? '');

        ActivityLog::record(
            'Update Peringatan Aktif',
            'Memperbarui pengaturan peringatan aktif: ' . $request->title . ' (Status: ' . $request->status . ')'
        );

        return redirect()->route('admin.active_alert')->with('success', 'Konten dan status Peringatan Aktif berhasil disimpan!');
    }
}

