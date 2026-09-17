<?php

namespace App\Livewire\Category;

use App\Models\Genre;
use Livewire\Component;

class CategoryIndex extends Component
{
    public function render()
    {
        // Ambil semua parent kategori (parent_id is null)
        $parentCategories = Genre::whereNull('parent_id')->get();

        return view('livewire.category.category-index', [
            'parentCategories' => $parentCategories,
        ])->layout('layouts.app');
    }
}
