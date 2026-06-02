<?php

namespace App\Http\Controllers\Api\Blog\Admin;


use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function __construct(private BlogPostRepository $blogPostRepository)
    {
        //parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
   $paginator = $this->blogPostRepository->getAllWithPaginate();

        return $paginator;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPostUpdateRequest  $blogPostRepository, string $id)
    {
     $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) { //якщо ід не знайдено
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $blogPostRepository->all(); //отримаємо масив даних, які надійшли з форми
        
        if (empty($data['slug'])) { //якщо псевдонім порожній
            $data['slug'] = Str::slug($data['title']); //генеруємо псевдонім
        }
        if (empty($item->published_at) && $data['is_published']) { //якщо поле published_at порожнє і нам прийшло 1 в ключі is_published, то
            $data['published_at'] = Carbon::now(); //генеруємо поточну дату
        }        
        $result = $item->update($data); //оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return [
            'data' => $data,
            'success' => true,
            'message' => 'Успішно збережено'
        ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
