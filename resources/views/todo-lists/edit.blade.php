@extends('layouts.app')
@section('content')
    <form action="{{route('todo-lists.update', $todo)}}" method="post" enctype="multipart/form-data" data-form data-update="true">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" name="name" class="form-control" id="name" value="{{$todo->name}}">
        </div>
        @if($todo->image)
            <div class="img-fluid">
                <img id="imagePreview" src="{{$todo->image->url}}" alt="{{$todo->name}}"  />
            </div>
        @endif
        <div class="mb-3">
            <label for="image" class="form-label">Изображение</label>
            <input type="file" name="image" class="form-control" id="image" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Описание</label>
            <textarea required class="form-control" name="description" id="description">{{$todo->description}}</textarea>
        </div>
        <div class="mb-3">
            <label for="tags" class="form-label">Теги</label>
            <input type="text" name="tags" class="form-control" id="tags" value="{{$todo->tags->implode('name',',')}}">
            <div id="emailHelp" class="form-text">Перечисляйте через запятую</div>
        </div>
        <div class="mb-3 form-check">
            <input @checked($todo->is_completed) type="checkbox" name="is_completed" class="form-check-input" id="is_completed">
            <label class="form-check-label" for="is_completed">Готово?</label>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection

@push('scripts')
    <script>
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');

        let imageResult = null

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.addEventListener('load', function() {
                    imageResult =  reader.result
                });

                reader.readAsDataURL(file);
            } else {
                imageResult = null
            }
        });

        document.querySelector('form[data-form]')
            .addEventListener('success', e => {
                if(imageResult){
                    imagePreview.setAttribute('src', imageResult)
                }
            })
    </script>
@endpush
