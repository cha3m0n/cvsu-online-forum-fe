<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\User;
use App\Http\Controllers\CommentsController;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Post;
use App\Livewire\SortButton;
use Livewire\WithPagination;
class PostController extends Controller
{
    use WithPagination;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $announcements = Announcement::all();
        $categories = Category::all();
        $allposts = Post::all();
        $mostUpvotes = Post::withCount('likes')
        ->orderByDesc('likes_count')
        ->first();
        $mostComments = Post::all()
        ->sortByDesc('comments_count')
        ->first();
        $topRep = User::orderByDesc('reputation')->take(3)->get();
        // dd($topRep);

        return view('pages.dashboard', compact('allposts','mostUpvotes','mostComments','announcements','categories', 'topRep'));
    }

    public function AnnouncementShow(Announcement $announcement){
        $announcements = Announcement::all();
        $users = User::all();
        $announcement = Announcement::with('author')->find($announcement->id);


        return view('pages.announcement', compact('users', 'announcement', 'announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function CategoryShow(Category $category)
    {
        $announcements = Announcement::all();
        $categories = Category::with('posts')->find($category->id);
        $categories1 = Category::all();
        $mostUpvotes = Post::withCount('likes')
        ->orderByDesc('likes_count')
        ->first();
        $mostComments = Post::all()
        ->sortByDesc('comments_count')
        ->first();
        return view('pages.category', compact('mostUpvotes','mostComments','announcements','categories','categories1'));
    }

    public function AllCategories(Category $category)
    {
        $categories = Category::all();
        return view('pages.categories', compact('categories'));
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    public function show(Post $post)
    {
        $post1 = Post::with('categories')->find($post->id);
        $users = User::all();
        $post = Post::with('author')->find($post->id);
        $announcements = Announcement::all();
        $categories1 = Category::all();
        $postIds = Comment::pluck('post_id')->unique()->toArray();

        $comments = Comment::with('post')  // Assuming your relationship is named 'post'
            ->where('post_id', $postIds)   // Replace $postId with the actual post ID
            ->get();

        return view('pages.show', compact('users','comments', 'post', 'post1', 'announcements','categories1'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function archives(Request $request)
    {
        $announcements = Announcement::all();
        $allposts = Post::all();
        return view('pages.archives', compact('announcements', 'allposts'));
    }
    public function firstLogin(Request $request)
    {
        $organizations = Organization::all();
        $user = auth()->user();
        return view('pages.firstLogin', compact('user', 'organizations'));
    }
    public function firstLoginUpdate(Request $request, User $user)
    {
        $attributes = $request->validate([
            'phone' => ['required','max:11', 'min:11'],
            'address' => ['max:255'],
            'bio' => ['max:255']
        ]);

        auth()->user()->update([
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'bio' => $request->get('bio'),
        ]);
        $user = auth()->user();
        return view('pages.firstLogin', compact('user'));
    }



}
