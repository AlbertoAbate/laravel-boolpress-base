@extends('layout.main')

@section('content')
    <div class="container mb-5">
        <h1>Blog</h1>

        @forelse ($posts as $post)
            <article class="mb-6">
                <h2>{{ $post->title }}</h2>
                <h5>{{ $post->created_at->format('d/m/Y') }}</h5>
                <p>{{ $post->body }}</p>
                <a href="{{ route('posts.show', $post->slug) }}">read more</a>
            </article>
        @empty
            <p>no post found <a href="">create a new one</a></p>
        @endforelse
    </div>
@endsection