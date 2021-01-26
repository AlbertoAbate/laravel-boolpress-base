<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
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
        return view('posts.create');
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
       
       return view('posts.edit', compact('post'));
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

         //verifichiamo se update è andato a buon fine
         if ($updated) {
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
    public function destroy($id)
    {
        //
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
