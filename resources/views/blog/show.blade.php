@extends('base')

@section('title', $post->title)

@section('content')
    <article>
        <h1>{{ $post->title }}</h1>
        <p class="small">
            @if ($post->category)
                Catégorie : {{ $post->category?->name }} @if (!$post->tags->isEmpty())
                    |
                @endif
            @endif
            @if (!$post->tags->isEmpty())
                Tags :
                @foreach ($post->tags as $tag)
                    <span class="badge bg-secondary">{{ $tag->name }}</span>
                @endforeach
            @endif
        </p>
        <p>{{ $post->content }}</p>
        @if ($post->image)
            <img style="width:100%; height:100%; object-fit:cover" src="{{ $post->imageUrl() }}" alt="">
        @endif
    </article>
@endsection
