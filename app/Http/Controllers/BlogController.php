<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogFilterRequest;
use App\Http\Requests\FormPostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Faker\Provider\HtmlLorem;
use Faker\Provider\Lorem;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class BlogController extends Controller
{
    public function create(){

        $post = new Post();
        return view('blog.create',[
            'post' => $post,
            'categories' => Category::select('id', 'name')->get(),
            'tags' => Tag::select('id', 'name')->get()
        ]);
    }

    public function store(FormPostRequest $request){
        $post = Post::create($this->extractData(new Post(), $request));
        $post->tags()->sync($request->validated('tags'));
        return redirect()->route('blog.show', ['slug' => $post->slug, 'post' => $post->id])->with('success', "L'article a bien été sauvegardé");
    }

    public function edit(Post $post){
        return view('blog.edit',[
            'post' => $post,
            'categories' => Category::select('id', 'name')->get(),
            'tags' => Tag::select('id', 'name')->get()

        ]);
    }

    public function update(Post $post, FormPostRequest $request){

        $post->update($this->extractData($post, $request));
        $post->tags()->sync($request->validated('tags'));
        return redirect()->route('blog.show', ['slug' => $post->slug, 'post' => $post->id])->with('success', "L'article a bien été modifié");
    }

    public function extractData(Post $post, FormPostRequest $request): array{
        $data = $request->validated();
        /** @var UploadedFile!null $image */
        $image = $request->validated('image');
        if($image === null || $image->getError()){
            return $data;
        }
        if($post->image){
            Storage::disk('public')->delete($post->image);
        }
        $data['image'] = $image->store('blog', 'public');
        return $data;
    }

    public function index(): View{
        return view('blog.index', [
            'posts' => Post::with('tags', 'category')->paginate(10)
        ]);
    }

    public function show(string $slug,Post $post): RedirectResponse | view{
        if($post->slug !== $slug){
            return to_route('blog.show', ['slug'=> $post->slug, 'id'=> $post->id]);
        }
        return view('blog.show', [
            'post' => $post
        ]);
    }

    public function destroy(Post $post){
        $post->delete();
        return to_route('blog.index')->with('success', 'Le bien a bien été supprimé');
    }
}
