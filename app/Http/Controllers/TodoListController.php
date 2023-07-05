<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoList\TodoListStoreRequest;
use App\Http\Requests\TodoList\TodoListUpdateRequest;
use App\Models\Tag;
use App\Models\TodoList;
use App\Services\ImageService;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class TodoListController extends Controller
{
    public function create(): View
    {
        return view('todo-lists.create');
    }

    public function store(TodoListStoreRequest $request, ImageService $service): JsonResponse
    {
        $todoList = TodoList::create(
            $request->validated() + ['user_id' => auth()->id()]
        );

        $this->syncTags($todoList, $request->tags);

        $service->upload($todoList, $request->file('image'))
            ->save()
            ->createPreview();

        return response()->json([
            'message' => 'Успешно добавлен'
        ]);
    }

    public function edit(int $id): View
    {
        $todo = auth()->user()
            ->todoLists()
            ->findOrFail($id);

        return view('todo-lists.edit', compact('todo'));
    }

    public function update(TodoListUpdateRequest $request, ImageService $service, int $id): JsonResponse
    {
        $todoList = auth()->user()
            ->todoLists()
            ->findOrFail($id);

        $todoList->update($request->validated());

        $this->syncTags($todoList, $request->tags);

        if ($request->hasFile('image')) {
            $todoList->attachments()->delete();

            $service->upload($todoList, $request->file('image'))
                ->save()
                ->createPreview();
        }

        return response()->json([
            'message' => 'Успешно обновлен'
        ]);
    }

    public function show(TodoList $todoList): View
    {
        return view('todo-lists.show', compact('todoList'));
    }

    private function syncTags(TodoList $todoList, ?string $tags): void
    {
        $tagIds = [];

        if (!empty($tags)) {
            $tags = explode(',', $tags);

            foreach ($tags as $tag) {
                $tagIds[] = Tag::firstOrCreate([
                    'name' => $tag
                ])->id;
            }
        }

        $todoList->tags()->sync($tagIds);
    }
}
