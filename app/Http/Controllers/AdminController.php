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
use App\Models\ActivityLog;
use App\Models\MediaItem;
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
        $request->validate(['title' => 'required|string|max:255']);
        
        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'slider_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sliders'), $filename);
            $imageUrl = url('uploads/sliders/' . $filename);
        }

        HeroSlider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_url' => $imageUrl,
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
        
        $imageUrl = $request->image_url ?: $slider->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'slider_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sliders'), $filename);
            $imageUrl = url('uploads/sliders/' . $filename);
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
        return back()->with('success', 'Banner Slider berhasil diperbarui!');
    }

    public function sliderDestroy($id)
    {
        HeroSlider::findOrFail($id)->delete();
        return back()->with('success', 'Banner Slider berhasil dihapus!');
    }

    public function menus()
    {
        $menus = NavigationMenu::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();
        $allParents = NavigationMenu::whereNull('parent_id')->orderBy('order', 'asc')->get();
        $pages = Page::orderBy('title', 'asc')->get();
        $pagesMap = $pages->keyBy('slug');

        $attachPageContent = function($menu) use ($pagesMap) {
            $menu->page_content = '';
            $menu->image_url = '';
            if ($menu->url && Str::contains($menu->url, '/halaman/')) {
                $slug = Str::after($menu->url, '/halaman/');
                if (isset($pagesMap[$slug])) {
                    $menu->page_content = $pagesMap[$slug]->content;
                    $menu->image_url = $pagesMap[$slug]->image_url;
                } else {
                    $pageMatch = Page::where('slug', 'like', '%' . Str::slug($slug) . '%')->first();
                    if ($pageMatch) {
                        $menu->page_content = $pageMatch->content;
                        $menu->image_url = $pageMatch->image_url;
                    }
                }
            }
        };

        foreach ($menus as $menu) {
            $attachPageContent($menu);
            if ($menu->children) {
                foreach ($menu->children as $child) {
                    $attachPageContent($child);
                }
            }
        }

        return view('admin.menus', compact('menus', 'allParents', 'pages'));
    }

    private function formatPageContent($content)
    {
        if (empty($content)) return '';
        if (Str::contains($content, ['<h1', '<h2', '<h3', '<div', '<ul', '<ol'])) {
            return $content;
        }

        // Auto split numbered items if merged (e.g. 1. 2. 3.)
        $content = preg_replace('/(\d+[\.\)])\s*/', "\n$1 ", $content);
        $content = str_replace(["Fungsi BPBD", "Tugas Pokok BPBD", "Tugas dan Fungsi"], ["\nFungsi BPBD\n", "\nTugas Pokok BPBD\n", "\nTugas dan Fungsi\n"], $content);

        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($content)));
        $formatted = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (preg_match('/^(visi|v i s i)$/i', $line)) {
                $formatted[] = '<h2 class="text-xl font-bold tracking-[0.25em] text-[#23325c] mb-4 mt-4">V I S I</h2>';
            } elseif (preg_match('/^(misi|m i s i)$/i', $line)) {
                $formatted[] = '<h2 class="text-xl font-bold tracking-[0.25em] text-[#23325c] mb-6 mt-8">M I S I</h2>';
            } elseif (preg_match('/^(tugas|tugas pokok|tugas pokok bpbd)$/i', $line)) {
                $formatted[] = '<h2 class="text-xl font-bold tracking-[0.25em] text-[#23325c] mb-4 mt-6">T U G A S</h2>';
            } elseif (preg_match('/^(fungsi|fungsi bpbd)$/i', $line)) {
                $formatted[] = '<h2 class="text-xl font-bold tracking-[0.25em] text-[#23325c] mb-4 mt-8">F U N G S I</h2>';
            } else {
                $formatted[] = '<p class="mb-3 leading-relaxed text-slate-700">' . e($line) . '</p>';
            }
        }

        return implode("\n", $formatted);
    }

    public function menuStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        
        $url = trim($request->url ?? '');

        // Process image URL or Uploaded file
        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'page_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pages'), $filename);
            $imageUrl = url('uploads/pages/' . $filename);
        }

        // Determine slug from URL or title
        $slug = null;
        if ($url && Str::contains($url, '/halaman/')) {
            $slug = Str::after($url, '/halaman/');
        }
        if (!$slug) {
            $slug = Str::slug($request->title);
        }

        // If page_content or image_url is provided, automatically create/update Page record
        if ($request->filled('page_content') || !empty($imageUrl)) {
            $formattedContent = $this->formatPageContent($request->page_content);
            
            $pageData = ['title' => $request->title];
            if ($request->filled('page_content')) {
                $pageData['content'] = $formattedContent;
            }
            if (!empty($imageUrl)) {
                $pageData['image_url'] = $imageUrl;
            }

            Page::updateOrCreate(['slug' => $slug], $pageData);

            if (empty($url)) {
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
        $menu = NavigationMenu::findOrFail($id);
        $request->validate(['title' => 'required|string|max:255']);

        // Always prioritize explicitly requested URL, or fallback to current menu URL
        $url = $request->filled('url') ? trim($request->url) : $menu->url;

        // Process image URL or Uploaded file
        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'page_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pages'), $filename);
            $imageUrl = url('uploads/pages/' . $filename);
        }

        // Detect existing page slug from current/requested URL or title
        $targetUrl = $url ?: $menu->url;
        $slug = null;
        if ($targetUrl && Str::contains($targetUrl, '/halaman/')) {
            $slug = Str::after($targetUrl, '/halaman/');
        }
        if (!$slug) {
            $slug = Str::slug($request->title);
        }

        // If page_content or image_url is provided, create/update Page record
        if ($request->filled('page_content') || !empty($imageUrl)) {
            $formattedContent = $this->formatPageContent($request->page_content);

            $pageData = ['title' => $request->title];
            if ($request->filled('page_content')) {
                $pageData['content'] = $formattedContent;
            }
            if (!empty($imageUrl)) {
                $pageData['image_url'] = $imageUrl;
            }

            // Find existing page by slug
            $page = Page::where('slug', $slug)->first();
            if (!$page) {
                $page = Page::where('slug', Str::slug($request->title))->first();
            }

            if ($page) {
                $page->update($pageData);
            } else {
                $pageData['slug'] = $slug;
                Page::create($pageData);
            }
        }

        if (empty($url)) {
            $url = '#';
        }

        $menu->update([
            'title' => $request->title,
            'url' => $url,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'target' => $request->target ?? '_self',
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Menu Navigasi & Konten Halaman berhasil diperbarui!');
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
        
        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'news_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/news'), $filename);
            $imageUrl = url('uploads/news/' . $filename);
        }

        NewsItem::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'summary' => $request->summary ?? Str::limit(strip_tags($request->content), 120),
            'content' => $request->content,
            'image_url' => $imageUrl,
            'link_url' => $request->link_url,
            'category' => $request->category ?? 'Peringatan Dini',
            'published_at' => $request->published_at ?? now(),
        ]);
        return back()->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function newsUpdate(Request $request, $id)
    {
        $news = NewsItem::findOrFail($id);
        
        $imageUrl = $request->image_url ?: $news->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'news_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/news'), $filename);
            $imageUrl = url('uploads/news/' . $filename);
        }

        $news->update([
            'title' => $request->title,
            'summary' => $request->summary ?? Str::limit(strip_tags($request->content), 120),
            'content' => $request->content,
            'image_url' => $imageUrl,
            'link_url' => $request->link_url,
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
        return back()->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function documentUpdate(Request $request, $id)
    {
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
        return back()->with('success', 'Dokumen berhasil diperbarui!');
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
        $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string']);
        Page::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'is_published' => true,
        ]);
        return back()->with('success', 'Halaman berhasil dibuat!');
    }

    public function pageUpdate(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return back()->with('success', 'Halaman berhasil diperbarui!');
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
        $request->validate(['title' => 'required|string|max:255']);
        
        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'widget_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/widgets'), $filename);
            $imageUrl = url('uploads/widgets/' . $filename);
        }

        SidebarWidget::create([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'target_url' => $request->target_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Widget Sidebar berhasil ditambahkan!');
    }

    public function widgetUpdate(Request $request, $id)
    {
        $widget = SidebarWidget::findOrFail($id);
        
        $imageUrl = $request->image_url ?: $widget->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'widget_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/widgets'), $filename);
            $imageUrl = url('uploads/widgets/' . $filename);
        }

        $widget->update([
            'title' => $request->title,
            'image_url' => $imageUrl,
            'target_url' => $request->target_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'Widget Sidebar berhasil diperbarui!');
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
        
        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'link_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
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
        $link = RelatedLink::findOrFail($id);
        
        $imageUrl = $request->image_url ?: $link->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'link_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
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

        return view('admin.media', compact('mediaItems', 'stats', 'type'));
    }

    public function mediaStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:foto,video',
        ]);

        $fileUrl = $request->file_url;
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $filename = 'media_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/media'), $filename);
            $fileUrl = url('uploads/media/' . $filename);
        }

        $item = MediaItem::create([
            'title' => $request->title,
            'type' => $request->type,
            'caption' => $request->caption,
            'file_url' => $fileUrl,
            'video_url' => $request->video_url,
            'category' => $request->category ?? 'Kegiatan BPBD',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::record('Tambah Galeri Media', "Menambahkan item media '{$item->title}' (Tipe: {$item->type}).");

        return back()->with('success', 'Item Media baru berhasil ditambahkan!');
    }

    public function mediaUpdate(Request $request, $id)
    {
        $media = MediaItem::findOrFail($id);
        $request->validate(['title' => 'required|string|max:255']);

        $fileUrl = $request->file_url ?: $media->file_url;
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $filename = 'media_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/media'), $filename);
            $fileUrl = url('uploads/media/' . $filename);
        }

        $media->update([
            'title' => $request->title,
            'type' => $request->type ?? $media->type,
            'caption' => $request->caption,
            'file_url' => $fileUrl,
            'video_url' => $request->video_url,
            'category' => $request->category ?? $media->category,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::record('Update Galeri Media', "Memperbarui data media '{$media->title}'.");

        return back()->with('success', 'Item Media berhasil diperbarui!');
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
        $fileKeys = [
            'logo_frontend_file' => 'logo_frontend',
            'logo_backend_file' => 'logo_backend',
            'logo_berakhlak_file' => 'logo_berakhlak',
        ];

        $uploadedKeys = [];

        foreach ($fileKeys as $fileInput => $settingKey) {
            if ($request->hasFile($fileInput)) {
                $file = $request->file($fileInput);
                $filename = $settingKey . '_' . time() . '_' . Str::random(4) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                SiteSetting::set($settingKey, url('uploads/settings/' . $filename));
                $uploadedKeys[] = $settingKey;
            }
        }

        $inputs = $request->except(array_merge(['_token'], array_keys($fileKeys), $uploadedKeys));

        foreach ($inputs as $key => $value) {
            if ($value !== null && $value !== '') {
                SiteSetting::set($key, $value);
            }
        }

        ActivityLog::record('Update Pengaturan', 'Memperbarui konfigurasi utama website dan identitas instansi.');

        return back()->with('success', 'Pengaturan Website & Logo berhasil disimpan!');
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
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return back()->with('success', 'Catatan log berhasil dihapus!');
    }
}
