<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;
use App\Notifications\CommentNotif;
use Livewire\Attributes\Url;

class CommentSection extends Component
{
    public Post $post;

    public $comment_body = '';
    public $search = '';

    public function createComment()
    {
        $this->validate([
            'comment_body' => 'required | min:2',
        ]);
        $comments2 = Comment::where('post_id', $this->post->id)->get();
        if ($comments2->where('user_id', auth()->user()->id)->count() > 0) {
            toastr()->error('You already commented on this post.');
        } else {
            $comment = Comment::create([
                'user_id' => auth()->user()->id,
                'post_id' => $this->post->id,
                'comment_body' => $this->comment_body,
            ]);
            $this->dispatch('comment-created', $comment);
            if (auth()->user()->email != $this->post->author->email) {
                $this->post->author->notify(new CommentNotif($comment));
            }
            $this->comment_body = '';
            toastr()->success('Comment posted successfully!');
        }
    }
    #[On('comment-created')]
    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')->get();
        $comments = Comment::all();
        $flag = false;
        return view('livewire.comment-section', compact('comments', 'flag', 'users'));
    }
}
