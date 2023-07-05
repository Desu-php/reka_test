@extends('layouts.app')
@section('content')
    <div class="mb-3">
        <form action="{{route('index')}}" class="d-flex">
            <input class="form-control me-2" name="search" type="search" placeholder="Поиск" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Поиск</button>
        </form>
    </div>
    <div class="row">
        <div class="col-md-3">
            <form action="{{route('index')}}">
                @foreach($tags as $tag)
                    <div class="mb-3 form-check">
                        <input @checked(in_array($tag->id, request()->input('tags', []))) type="checkbox" name="tags[]"
                               class="form-check-input" id="tag{{$tag->id}}" value="{{$tag->id}}">
                        <label class="form-check-label" for="tag{{$tag->id}}">{{$tag->name}}</label>
                    </div>
                @endforeach
                <button class="btn btn-primary" type="submit">Фильтр</button>
            </form>
        </div>
        <div class="col-md-9 row">
            @foreach($todos as $todo)
                <div class="col-sm-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            @if($todo->user_id == auth()->id())
                                <div class="d-flex justify-content-between">
                                    <div class="dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink"
                                           role="button"
                                           data-bs-toggle="dropdown" aria-expanded="false">
                                            Действие
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="{{route('todo-lists.edit', $todo)}}">Редактировать</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <span
                                        class="badge bg-{{$todo->is_completed ? 'success': 'danger'}}">{{$todo->is_completed ? 'Готово': 'В работе'}}</span>
                                </div>
                            @endif

                            <div class="text-center">
                                <h5 class="card-title">{{$todo->name}}</h5>
                            </div>

                            <div class="row">
                                <a href="{{route('todo-lists.show', $todo)}}" class="col-md-3">
                                    <img src="{{$todo->preview->url}}" alt="{{$todo->name}}">
                                </a>
                                <div class="col-md-9">
                                    <p class="card-text">{{$todo->description}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-3">
        {!! $todos->links() !!}
    </div>
@endsection
