<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Post;
use App\Models\Category;
use Livewire\WithPagination;

class SortButton extends Component
{
    use WithPagination;
    #[Url()]
    public $search = '';
    #[Url()]
    public $sortBy = 'created_at';
    #[Url()]
    public $sortDir = 'DESC';


    public function setSortBy($sortByField){

        if($this->sortBy === $sortByField){
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
    }


    #[On('comment-created')]
    public function render()
    {
        $posts = Post::search($this->search)
        ->withCount(['likes', 'comments'])
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate(5);
        $categories = Category::with('posts')->get();
        return view('livewire.sort-button',compact('posts', 'categories'));
    }
}
