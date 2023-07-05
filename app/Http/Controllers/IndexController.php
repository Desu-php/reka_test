<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TodoList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    //

    public function index(Request $request): View
    {
        $todos = TodoList::with('preview')
            ->when($request->filled('search'), fn(Builder $builder) => $builder->search($request->search))
            ->when($request->filled('onlyMy') && auth()->check(), fn(Builder $builder) => $builder->whereUserId(auth()->id()))
            ->when($request->filled('tags'), fn(Builder $builder) => $builder->filter($request->tags))
            ->latest()
            ->paginate()
            ->withQueryString();

        $tags = Tag::has('todoLists')
            ->get();

        return view('index', compact('todos', 'tags'));
    }
}
