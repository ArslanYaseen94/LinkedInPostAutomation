<?php

namespace App\Http\Controllers;

use App\Models\PostTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostTemplateController extends Controller
{
    public function index()
    {
        $templates = PostTemplate::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('posts.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('posts.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:3000',
            'description' => 'nullable|string|max:500',
        ]);

        PostTemplate::create([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully!');
    }

    public function edit(PostTemplate $template)
    {
        $this->authorize('update', $template);

        return view('posts.templates.edit', compact('template'));
    }

    public function update(Request $request, PostTemplate $template)
    {
        $this->authorize('update', $template);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:3000',
            'description' => 'nullable|string|max:500',
        ]);

        $template->update($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Template updated successfully!');
    }

    public function destroy(PostTemplate $template)
    {
        $this->authorize('delete', $template);

        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template deleted successfully!');
    }

    public function use(PostTemplate $template)
    {
        $this->authorize('view', $template);

        $template->incrementUsage();

        return redirect()->route('posts.create')
            ->with('template_content', $template->content)
            ->with('success', 'Template loaded! You can now edit it.');
    }
}
