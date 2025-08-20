<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use App\Models\Article;

class ArticleManagementComponent extends Component
{
    public $articles = [];
    public $placeholders = [];
    public $isUpdating = false;

    public function mount()
    {
        $this->loadArticles();
    }

    /**
     * Load exactly three articles with placeholder handling
     */
    public function loadArticles()
    {
        // Get all articles ordered by creation date
        $articles = Article::orderBy('created_at', 'desc')->take(3)->get();
        $this->articles = $articles->toArray();

        // Create placeholders if we have fewer than 3 articles
        $placeholderCount = 3 - count($this->articles);
        $this->placeholders = array_fill(0, max(0, $placeholderCount), null);
    }

    /**
     * Open edit modal for an article
     */
    public function editArticle($articleId)
    {
        // Use the wire-elements-modal component to open the edit modal
        $this->dispatch('openModal', 'admin.components.edit-article-modal', ['articleId' => $articleId]);
    }

    /**
     * Toggle star status for an article with single-star constraint logic
     */
    public function toggleStar($articleId)
    {
        $this->isUpdating = true;

        try {
            $article = Article::find($articleId);

            if (!$article) {
                session()->flash('error', 'Artikel tidak ditemukan.');
                $this->isUpdating = false;
                return;
            }

            if ($article->is_starred) {
                // If article is already starred, just unstar it
                $article->is_starred = false;
                $article->save();
                session()->flash('message', 'Artikel berhasil dihapus dari daftar unggulan.');
            } else {
                // If article is not starred, use the setStarred method to ensure only one article is starred
                Article::setStarred($articleId);
                session()->flash('message', 'Artikel berhasil ditandai sebagai unggulan.');
            }

            // Reload articles to reflect changes
            $this->loadArticles();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->isUpdating = false;
    }

    protected function getListeners()
    {
        return [
            'articleUpdated' => 'handleArticleUpdated',
        ];
    }

    /**
     * Handle article updated event from the modal
     */
    public function handleArticleUpdated($data)
    {
        // Reload articles from database to show updated data
        $this->loadArticles();

        // Show success message if provided
        if (isset($data['message'])) {
            session()->flash('success', $data['message']);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.article-management-component');
    }
}
