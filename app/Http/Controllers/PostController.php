<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;                                   

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //prendimo tutti i tag
        $tags = Tag::all();

        return view('posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //get from data
        $data = $request->all();
        //dd($data);

        // VALIDATION
        $request->validate($this->ruleValidation());

        //set post slug
        $data['slug'] = Str::slug($data['title'], '-');

        //se img è presente
        if (!empty($data['path_img'])) {
            $data['path_img'] = Storage::disk('public')->put('images', $data['path_img']);
        }

        // salvataggio al db
        $newPost = new Post();
        $newPost->fill($data);
        $saved = $newPost->save();

        if($saved) {
            if (!empty($data['tags'])) {
                $newPost->tags()->attach($data['tags']);

                //cerca la pivot di questa relazione posts - tags
                
            }

            return redirect()->route('posts.index'); //('newPost.show,$newPost->id');
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //return $slug;

        $post = Post::where('slug', $slug)->first();
        //dump($post);

        //facciamo un check per vedere se esiste un post
        if (empty($post)) {
            abort(404);
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
       $post = Post::where('slug', $slug)->first();
       $tags = Tag::all();

       if (empty($post)) {
        abort(404);
    }
       
       return view('posts.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // get data from form
        $data = $request->all();

        // validation
        $request->validate($this->ruleValidation());

         //get post to update
         $post = Post::find($id);

         //generare slug se ne viene creato uno nuovo, stessa coa fatta in store
         $data['slug'] = Str::slug($data['title'], '-');

         //se cambia l'img
         if (!empty($data['path_img'])) {    //ci chiediamo se abbiamo  una nuova img
             if(!empty($post->path_img)) {   //ci chiediamo se ce ne fosse una in precedenza
                 Storage::disk('public')->delete($post->path_img);  //cancella completamente la vecchia se presente
             }
             $data['path_img'] = Storage::disk('public')->put('images', $data['path_img']);  //aggiungiamo la nuova img
         }

         //aggiornare/update db
         $updated = $post->update($data); //ha bisogno di fillable nel model(già messo)

         //verifichiamo se update è andato a buon fine (guardare i return)
         if ($updated) {
            if (!empty($data['tags'])) { 
                $post->tags()->sync($data['tags']);    //metodi se editando cambiamo le preferenze in checkbox
            }else {
                $post->tags()->detach();  //o le cancelliamo ttt
            }

            return redirect()->route('posts.show', $post->slug);
         } else {
            return redirect()->route('home');
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //$post = Post::find($id); commentata perche usiamo la versione short (Post $post)à

        $title = $post->title;  //assegnamo il titolo ad una variabile $title
        $image = $post->path_img;  //assegnamo ad img ad una variabile $image

        $post->tags()->detach();  //cancellare la relazine tra posts e tags
        $deleted = $post->delete();  //metodo per cancellare il titolo

        if ($deleted) {   //verifica se c'è un post associato
            if (!empty($image)) {
                Storage::disk('public')->delete($image);
            }
            return redirect()->route('posts.index')->with('post-deleted', $title);
        } else {
            return redirect()->route('home');
        }
    }

    // funzione rule validation
    private function ruleValidation() {
        return [
            'title' => 'required',
            'body' => 'required',
            'path_img' => 'mimes:jpg'
        ];
    }
}
