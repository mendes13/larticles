<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Resources\ArticleResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'DESC')->paginate(5);

        return ArticleResource::collection($articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return ArticleResource
     */
    public function store(Request $request)
    {
        $article = new Article();

        $article->title = $request->input('title');
        $article->body = $request->input('body');
        $article->save();

        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Article $article
     * @return void
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Article $article
     * @return ArticleResource
     */
    public function update(Request $request, Article $article)
    {
        $article->update($request->all());
        $article->save();
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return ArticleResource
     * @throws Exception
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return new ArticleResource($article);
    }
}
