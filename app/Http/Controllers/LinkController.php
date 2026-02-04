<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        // US-06: Filtering & Search
        $query = Link::query()->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        $links = $query->with(['category', 'tags'])->latest()->get();
        $categories = Auth::user()->categories;
        $tags = Tag::all(); // Assuming tags are global or we can limit to user if needed, US-05 doesn't specify ownership for tags but implies system or shared. Let's assume global allowed tags for simplicity.

        return view('links.index', compact('links', 'categories', 'tags'));
    }

    public function create()
    {
        $categories = Auth::user()->categories;
        $tags = Tag::all();
        return view('links.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Security check: ensure category belongs to user
        $category = Category::findOrFail($request->category_id);
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized category');
        }

        $link = Link::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'url' => $request->url,
        ]);

        if ($request->has('tags')) {
            $link->tags()->attach($request->tags);
        }

        return redirect()->route('links.index')->with('success', 'Link created successfully.');
    }

    public function edit(Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = Auth::user()->categories;
        $tags = Tag::all();
        return view('links.edit', compact('link', 'categories', 'tags'));
    }

    public function update(Request $request, Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
        ]);

        // Security check: ensure category belongs to user
        $category = Category::findOrFail($request->category_id);
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized category');
        }

        $link->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'url' => $request->url,
        ]);

        if ($request->has('tags')) {
            $link->tags()->sync($request->tags);
        } else {
            $link->tags()->detach();
        }

        return redirect()->route('links.index')->with('success', 'Link updated successfully.');
    }

    public function destroy(Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }

        $link->delete();
        return redirect()->route('links.index')->with('success', 'Link deleted successfully.');
    }
}
