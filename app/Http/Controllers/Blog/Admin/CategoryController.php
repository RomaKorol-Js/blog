<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Models\BlogCategory;
use Illuminate\Support\Str;
// use Illuminate\Http\Request;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;

class CategoryController extends BaseController
{
     public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();
     
    }
    /**
     * Display a listing of the resource.
     */
    // http://localhost/api/admin/blog/categories
    public function index()
    {
        // $paginator = BlogCategory::paginate(5);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);
        return $paginator;
    }

    /**
     * Store a newly created resource in storage.
     */
    // http://localhost/api/admin/blog/categories
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input(); 
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
 
        $item = (new BlogCategory())->create($data);

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
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
         $item = $this->blogCategoryRepository->getEdit($id);

        
        if (empty($item)) { 
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