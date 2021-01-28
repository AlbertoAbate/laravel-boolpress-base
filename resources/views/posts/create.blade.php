@extends('layout.main')

@section('content')
    <div class="container mb-5">
        <h1>CREATE NEW POST</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif
        

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="form-group">
              <label for="title">title</label>
              <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
            </div>

            <div class="form-group">
              <label for="body">Description</label>
              <input name="body" class="form-control" id="body" value="{{ old('body') }}">
            </div>

            <div class="form-group">
              <label for="path_img">post image</label>
              <input type="file" name="path_img" class="form-control" id="path_img" accept="image/*">
            </div>

            {{-- TAGS  --}}
            <div class="form-group">
              @foreach ($tags as $tag)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}">
                    <label for="tag-{{ $tag->id }}">
                      {{ $tag->name }}
                    </label>
                  </div>
              @endforeach
              
            </div>

            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Create post">
            </div>

              
        </form>

    </div>
@endsection