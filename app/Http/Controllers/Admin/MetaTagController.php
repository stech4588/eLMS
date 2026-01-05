<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetaTag;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MetaTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagesWithMetaTags = Page::whereHas('metatag')->with('metatag')->get();

        return Inertia::render('Admin/MetaTags/Index', [
            'pages' => $pagesWithMetaTags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = Page::whereDoesntHave('metatag')->get();
        return Inertia::render('Admin/MetaTags/Create', [
            'pages' => $pages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id|unique:metatags,page_id',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        MetaTag::create([
            'page_id' => $request->page_id,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect('/metatags');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Page::with('metatag')->findOrFail($id);
        $pages = Page::all();
        return Inertia::render('Admin/MetaTags/Edit', [
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $metatag = MetaTag::where('page_id', $id)->firstOrFail();

        $validated = $request->validate([
            'page_id' => 'required|exists:pages,id|unique:metatags,page_id,'.$metatag->id,
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);
        
        $metatag->update($validated);

        return redirect('/metatags');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::findOrFail($id);
        if ($page->metatag) {
            $page->metatag->delete();
        }
        return redirect('/metatags');
    }
}
