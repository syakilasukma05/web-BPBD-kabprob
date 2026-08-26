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
use App\Models\PageDocument;
use App\Models\ContactMessage;
use App\Models\PublicDocument;
use App\Models\MediaItem;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function($q) {
                $q->orderBy('order', 'asc');
            }])
            ->orderBy('order', 'asc')
            ->get();
            
        $sliders = HeroSlider::where('is_active', true)->orderBy('order', 'asc')->get();
        $latestNews = NewsItem::orderBy('published_at', 'desc')->take(4)->get();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();
        $relatedLinks = RelatedLink::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.index', compact(
            'settings', 'navMenus', 'sliders', 'latestNews', 'sidebarWidgets', 'relatedLinks'
        ));
    }

    public function page($slug)
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('order', 'asc')
            ->get();
            
        $page = Page::where('slug', $slug)->with(['documents', 'links'])->first();
        if (!$page) {
            $page = Page::where('slug', 'like', '%' . Str::slug($slug) . '%')->with(['documents', 'links'])->first();
        }
        if (!$page) {
            $page = Page::firstOrCreate(
                ['slug' => $slug],
                ['title' => ucwords(str_replace(['-', '_'], ' ', $slug)), 'content' => '<p class="text-center">Halaman sedang dalam pembaruan konten.</p>']
            );
            $page->load(['documents', 'links']);
        }

        $latestNews = NewsItem::orderBy('published_at', 'desc')->take(3)->get();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.page', compact('settings', 'navMenus', 'page', 'latestNews', 'sidebarWidgets'));
    }

    public function pageDocumentView($id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $document = PageDocument::findOrFail($id);
        $path = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . e($document->file_name) . '"'
        ]);
    }

    public function pageDocumentDownload($id)
    {
        $id = trim(str_replace(['"', "'", '\\'], '', $id));
        $document = PageDocument::findOrFail($id);
        $path = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        return response()->download($path, $document->file_name, [
            'Content-Type' => 'application/pdf'
        ]);
    }


    public function newsDetail($slug)
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('order', 'asc')
            ->get();
            
        $news = NewsItem::where('slug', $slug)->firstOrFail();
        $news->increment('views');

        $latestNews = NewsItem::where('id', '!=', $news->id)->orderBy('published_at', 'desc')->take(3)->get();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.news_detail', compact('settings', 'navMenus', 'news', 'latestNews', 'sidebarWidgets'));
    }

    public function informasi()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('order', 'asc')
            ->get();

        $newsList = NewsItem::orderBy('published_at', 'desc')->paginate(6);
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.informasi', compact('settings', 'navMenus', 'newsList', 'sidebarWidgets'));
    }

    public function surveyForm()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('order', 'asc')
            ->get();

        return view('public.survey', compact('settings', 'navMenus'));
    }

    public function submitSurvey(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $rating = $request->rating ?? 'Puas';
        $subject = 'Survei SKM (' . $rating . ')';

        ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $subject,
            'message' => "Tingkat Kepuasan: {$rating}\n\nUlasan/Saran:\n" . $request->message,
            'status' => 'baru',
        ]);

        return back()->with('success_survey', 'Terima kasih! Survei kepuasan Anda telah berhasil dikirim.');
    }

    public function documents()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('order', 'asc')
            ->get();

        $page = Page::where('slug', 'dokumen')->with(['documents', 'links'])->first();
        if (!$page) {
            $page = Page::where('slug', 'like', '%dokumen%')->with(['documents', 'links'])->first();
        }

        $documents = PublicDocument::orderBy('created_at', 'desc')->paginate(10);
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.documents', compact('settings', 'navMenus', 'page', 'documents', 'sidebarWidgets'));
    }

    public function contact()
    {
        return $this->surveyForm();
    }

    public function galeri(Request $request)
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $navMenus = NavigationMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('order', 'asc')
            ->get();

        $type = $request->get('type', 'semua');
        $query = MediaItem::where('is_active', true)->orderBy('order', 'asc')->orderBy('created_at', 'desc');

        if ($type && in_array($type, ['foto', 'video'])) {
            $query->where('type', $type);
        }

        $mediaList = $query->paginate(12)->withQueryString();
        $sidebarWidgets = SidebarWidget::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('public.galeri', compact('settings', 'navMenus', 'mediaList', 'sidebarWidgets', 'type'));
    }
}
