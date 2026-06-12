<?php

namespace App\Http\Controllers\Api\Blog\Admin;


use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;

use App\Http\Resources\Api\Blog\Admin\PostResource;

use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;

use App\Models\BlogPost;
use App\Http\Requests\BlogPostCreateRequest;


// use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use DispatchesJobs;


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
        // Отримуємо пагіновані дані з репозиторія
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        // Обгортаємо пагінацію в API Ресурс
        return PostResource::collection($paginator);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPostCreateRequest $request)
    {
                
        $data = $request->input(); //отримаємо масив даних, які надійшли з форми
        // return $data;

        $item = (new BlogPost())->create($data); //створюємо об'єкт і додаємо в БД
        // return $item;
        
        if ($item) {
            $job = new BlogPostAfterCreateJob($item);
            BlogPostAfterCreateJob::dispatch($item);
            return ['success' => 'Успішно збережено'];
        } else {
            return ['msg' => 'Помилка збереження'];
        }
    }

    public function show(string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return ['message' => "Запис не знайдено"];
        }
        // return PostResource::collection($paginator);
        return new PostResource($item);
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
        
        // if (empty($data['slug'])) { //якщо псевдонім порожній
        //     $data['slug'] = Str::slug($data['title']); //генеруємо псевдонім
        // }
        // if (empty($item->published_at) && $data['is_published']) { //якщо поле published_at порожнє і нам прийшло 1 в ключі is_published, то
        //     $data['published_at'] = Carbon::now(); //генеруємо поточну дату
        // }        
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
          $result = BlogPost::destroy($id); //софт деліт, запис лишається

        //$result = BlogPost::find($id)->forceDelete(); //повне видалення з БД

        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return ['message' => 'Успішно видалено', 'success' => true];
        } else {
            return ['message' => 'Помилка видалення', 'success' => false];
        }
 
        //
    }
}
