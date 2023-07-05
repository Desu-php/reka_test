@extends('layouts.app')
@section('content')
    <form action="{{route('todo-lists.store')}}" method="post" enctype="multipart/form-data" data-form>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Изображение</label>
            <input type="file" name="image" class="form-control" id="image" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Описание</label>
            <textarea required class="form-control" name="description" id="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="tags" class="form-label">Теги</label>
            <input type="text" name="tags" class="form-control" id="tags">
            <div id="emailHelp" class="form-text">Перечисляйте через запятую</div>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection
