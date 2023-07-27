@extends('base')

@section('title', 'Accueil du blog')

@section('content')
    <h1>Mon blog</h1>
    @foreach ($posts as $post)
        <article>
            <h2>{{ $post->title }}</h2>
            <p class="small">
                @if ($post->category)
                    Catégorie : {{ $post->category?->name }}  @if (!$post->tags->isEmpty()) | @endif
                @endif
                @if (!$post->tags->isEmpty())
                    Tags :
                    @foreach ($post->tags as $tag)
                        <span class="badge bg-secondary">{{$tag->name}}</span>
                    @endforeach
                @endif
            </p>
            @if($post->image)
                <img style="width:50%; height:100%; object-fit:cover" src="{{ $post->imageUrl() }}" alt="">
            @endif
            <p>{{ $post->content }}</p>
            <p>
                <a href="{{ route('blog.show', ['slug' => $post->slug, 'post' => $post->id]) }}" class="btn btn-primary">Lire la suite</a>
                <a href="{{ route('blog.edit', ['slug' => $post->slug, 'post' => $post->id]) }}" class="btn btn-primary">Modifier l'article</a>
                <form action="{{ route('blog.destroy', $post) }}" method="post">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger">Supprimer l'article</button>
                </form>
            </p>
        </article>
    @endforeach

    {{ $posts->links() }}
@endsection
