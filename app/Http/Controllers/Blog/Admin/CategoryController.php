<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    // http://localhost/api/admin/blog/categories
    public function index()
    {
        $paginator = BlogCategory::paginate(5);
        return $paginator;
    }

    /**
     * Store a newly created resource in storage.
     */
    // http://localhost/api/admin/blog/categories
 public function store(Request $request)
    {
        $data = $request->all();
        $item = BlogCategory::create($data);
        
        if ($item) {
            return ['success' => 'Успішно збережено', 'item' => $item];
        } else {
            return ['msg' => 'Помилка збереження'];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // dd(__METHOD__);
    }

    /**
     * Update the specified resource in storage.
     */
    
    // http://localhost/api/admin/blog/categories/{id}
    public function update(Request $request, string $id)
    {
        // This was Line 31! The invisible characters are gone now.
        $item = BlogCategory::find($id);
        
        if (empty($item)) { 
            // Changed this to a proper JSON response for APIs instead of a web redirect
            return response()->json(['error' => "Запис id=[{$id}] не знайдено"], 404);
        }

        $data = $request->all();
        
        if (empty($data['slug'])) { 
            $data['slug'] = Str::slug($data['title']); 
        }

        $result = $item->update($data);  

        if ($result) {
            return ['success' => 'Успішно збережено', 'item' => $item];
        } else {
            return ['msg' => 'Помилка збереження'];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd(__METHOD__);
    }
}