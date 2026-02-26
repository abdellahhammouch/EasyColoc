<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    private function assertOwner(Colocation $colocation): void
    {
        abort_unless($colocation->owner_id === auth()->id(), 403);
    }

    public function index(Colocation $colocation)
    {
        $this->assertOwner($colocation);

        $categories = $colocation->categories()
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('colocation', 'categories'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        $this->assertOwner($colocation);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('categories', 'name')->where(fn ($q) =>
                    $q->where('colocation_id', $colocation->id)
                ),
            ],
        ]);

        Category::create([
            'colocation_id' => $colocation->id,
            'name' => $request->name,
        ]);

        return back()->with('status', 'Catégorie ajoutée.');
    }

    public function update(Request $request, Colocation $colocation, Category $category)
    {
        $this->assertOwner($colocation);

        abort_unless($category->colocation_id === $colocation->id, 404);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('categories', 'name')
                    ->where(fn ($q) => $q->where('colocation_id', $colocation->id))
                    ->ignore($category->id),
            ],
        ]);

        $category->update(['name' => $request->name]);

        return back()->with('status', 'Catégorie mise à jour.');
    }
}