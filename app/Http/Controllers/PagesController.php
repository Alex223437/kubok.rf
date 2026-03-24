<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\OptionsService;

class PagesController extends Controller
{
    private OptionsService $options;

    public function __construct()
    {
        $this->options = app(OptionsService::class);
    }

    private array $relations = [
        'img',
        'picture',
        'banner',
        'infosActive',
        'charitiesActive',
        'eventsActive',
        'momentsActive',
        'tablesActive',
    ];

    public function index(Request $request): View
    {
        $pages = $this->cached('pages', function () {
            return Page::with(['img', 'banner'])->where('active', true)->get();
        });

        $defaultMeta = $this->options->getDefaultMeta();

        \Illuminate\Support\Facades\View::share($defaultMeta);

        return view('public.index', ['pages' => $pages]);
    }


    public function page(string $code): View
    {
        $page = $this->cached('page_' . $code, function () use ($code) {
            $page = Page::where('code', $code)->firstOrFail();
            $page->load($this->relations);
            return $page;
        });

        $defaultMeta = $this->options->getDefaultMeta();
        $pageMeta = array_filter([
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'meta_keywords' => $page->meta_keywords,
        ]);
        $meta = array_merge($defaultMeta, $pageMeta);
        \Illuminate\Support\Facades\View::share($meta);

        return view('public.page', ['page' => $page]);
    }

    private function cached(string $key, callable $callback)
    {
        $cacheEnabled = $this->options->isCacheEnabled();
        return $cacheEnabled ? \Cache::store('file')->remember($key, 3600, $callback) : $callback();
    }

}
