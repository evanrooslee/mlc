<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(12);
        
        return view('articles.index', [
            'articles' => $articles,
            'metaTitle' => 'Artikel Bimbel Online - Tips Belajar Matematika & Fisika | MLC Online Study',
            'metaDescription' => 'Baca artikel dan tips belajar matematika & fisika dari MLC Online Study. Temukan strategi belajar efektif, motivasi, dan panduan untuk siswa SMP & SMA.',
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        // Increment view count if you have that feature
        // $article->increment('views');
        
        // Get related articles (same category or recent articles)
        $relatedArticles = Article::where('id', '!=', $article->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'metaTitle' => $article->title . ' | MLC Online Study',
            'metaDescription' => \Illuminate\Support\Str::limit(strip_tags($article->content ?? ''), 155) ?: 'Baca artikel tentang ' . $article->title . ' di MLC Online Study.',
            'ogImage' => $article->image ? asset('storage/' . $article->image) : asset('images/mlc-logo-colored.png'),
        ]);
    }
}
