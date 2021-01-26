@extends('layout.main')

@section('content')
    <div class="container mb-5">
        <h1>Edit {{ $post->title }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif
        

        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
              <label for="title">title</label>
              <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $post->title) }}">
            </div>

            <div class="form-group">
              <label for="body">Description</label>
              <textarea name="body" class="form-control" id="body">{{ old('body', $post->body) }}</textarea>
            </div>

            <div class="form-group">
              <label for="path_img">post image</label>
              @isset($post->path_img)
                  <div class="wrap-image">
                     <img width="200" src="{{ asset('storage/' . $post->path_img) }}" alt="{{ $post->title }}">
                  </div>
              @endisset
              <input type="file" name="path_img" class="form-control" id="path_img" accept="image/*">
            </div>

            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Update">
            </div>

              
        </form>

    </div>
@endsection