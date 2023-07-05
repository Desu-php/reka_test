@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column align-items-center">
        <h1 class="mt-5">{{$todoList->name}}</h1>
        <div class="d-flex flex-wrap gap-2">
            @foreach($todoList->tags as $tag)
                <span class="badge bg-primary">{{$tag->name}}</span>
            @endforeach
        </div>
        <div class="my-2">
            <img src="{{$todoList->image->url}}" class="img-fluid" alt="{{$todoList->name}}">
        </div>
        <div class="lead">
            {{$todoList->description}}
        </div>
    </div>
@endsection
