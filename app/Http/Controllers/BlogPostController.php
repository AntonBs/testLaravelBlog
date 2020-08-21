<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Http\Requests\BlogPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use App\Item;




class BlogPostController extends Controller
{

  public function  __construct()
  {
    $this->middleware('auth')->except('index','show');
  }

  /**
   * Display a listing of the resource.
   *
  @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if ($request->search) {
      $posts = BlogPost::join('users', 'author_id', '=', 'users.id')
        ->where('title','like','%'.$request->search.'%')
        ->orWhere('description','like','%'.$request->search.'%')
        ->orWhere('name','like','%'.$request->search.'%')
        ->orderBy('blog_posts.created_at', 'desc')
        ->get();
      return view('posts.index', compact('posts'));
    }

    $posts = BlogPost::join('users', 'author_id', '=', 'users.id')->orderBy('blog_posts.created_at', 'desc')->paginate(4);
    return view('posts.index', compact('posts'));
  }

  /**
   * Show the form for creating a new resource.
   *
  @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('posts.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
  @return \Illuminate\Http\Response
   */
  public function store(BlogPostRequest $request)
  {
    $post = new BlogPost();
    $post->title = $request->title;
    $post->short_title = Str::length($request->title)>30 ? Str::substr($request->title, 0, 30). '...' : $request->title;
    $post->description = $request->description;
    $post->author_id = \Auth::user()->id;

    if ($request->file('img')) {
      $path = Storage::putFile('public', $request->file('img'));
      $url = Storage::url($path);
      $post->img = $url;
    }

    $post->save();
    return redirect()->route('post.index')->with('success', 'Пост успешно создан!');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
  @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $post = BlogPost::select('users.id', 'users.name', 'blog_posts.*')
      ->join('users', 'author_id', '=','users.id')
      ->find($id);

    if (!$post){
      return redirect()->route('post.index')->withErrors('Браток, ты не туда попал');
    }

    return view('posts.show',compact('post'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
  @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $post = BlogPost::find($id);
    if (!$post){
      return redirect()->route('post.index')->withErrors('Браток, ты не туда попал');
    }

    if($post->author_id != \Auth::user()->$id){
      return  redirect()->route('post.index')->withErrors('Вы не можете редактировать данный пост');
    }
    return view('posts.edit',compact('post'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
  @return \Illuminate\Http\Response
   */
  public function update(BlogPostRequest $request, $id)
  {
    $post = BlogPost::find($id);

    if (!$post){
      return redirect()->route('post.index')->withErrors('Не туда попал');
    }

    if ($post->author_id !=\Auth::user()->id){
      return redirect()->route('post.index')->withErrors('Вы не можете редактировать данный пост');
    }

    $post->title = $request->title;
    $post->short_title = Str::length($request->title)>30 ? Str::substr($request->title, 0, 30). '...' : $request->title;
    $post->description = $request->description;

    if ($request->file('img')) {
      $path = Storage::putFile('public', $request->file('img'));
      $url = Storage::url($path);
      $post->img = $url;
    }

    $post->update();
    $id = $post->post_id;
    return redirect()->route('post.show', $id)->with('success', 'Пост успешно отредактирован!');

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
  @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $post = BlogPost::find($id);

    if (!$post){
      return redirect()->route('post.index')->withErrors('Не туда попал');
    }

    if ($post->author_id !=\Auth::user()->id){
      return redirect()->route('post.index')->withErrors('Вы не можете удалить данный пост');
    }
    $post->delete();
    return redirect()->route('post.index')->with('success', 'Пост успешно удален!');
  }
}
