<!-- sort mid -->
<div>
    <style>
        .custom-button {
            margin: 0;
        }
    </style>
    <!-- search section -->
    <div class="container-fluid d-flex justify-content-center">
        <div x-data="{ query: '' }" id="search-box" class="container-fluid py-auto">
            <div class="input-group">
                <input class="form-control" x-model="query" type="text" wire:model.live.debounce.300ms="search"
                    name="search" id="search" placeholder="Search here...">
                <button x-on:click="$dispatch('search', { search: query })" type="button"
                    class="btn btn-success py-auto custom-button">
                    Search
                </button>
            </div>
        </div>
    </div>
    <!-- search section -->
    <br>
    <!-- category button section -->
    <div class="container-fluid d-flex justify-content-center ">
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check " name="btnradio" id="btnradio1" autocomplete="off" checked>
            <label class="btn btn-outline-success" for="btnradio1" wire:click="setSortBy('created_at')">Latest</label>

            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
            <label class="btn btn-outline-dark border border-success" for="btnradio2" wire:click="setSortBy('likes_count')">Upvotes</label>

            <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
            <label class="btn btn-outline-dark border border-success" for="btnradio3"
                wire:click="setSortBy('comments_count')">Comments</label>
        </div>
    </div>
    <!-- category button section -->

    <!-- middle section -->
    <div class="card">
        <div class="card-body">
            @foreach ($posts as $post)
                @if ($post->is_archived == 0 && $post->is_approved)
                    <a href="/post/{{ $post->id }}">
                            <div class="card z-index-2" style="max-height: 200px; overflow: hidden;">
                                <div class="card-header pb-0 pt-3 bg-transparent d-flex justify-content-start mx-3">
                                <!-- user pfp & name -->
                                    <p class="text-capitalize text-bold">
                                        <img class="img-fluid rounded-circle" style="width: 2rem; height: 2rem;"
                                            src="{{ !empty($post->author->photo) ? url($post->author->photo) : url('/img/no-image.png') }}"
                                            alt="profile">
                                        {{ $post->author->name }}
                                    </p>
                                </div>
                                <div class="card-body d-flex justify-content-between mx-4 mb-4" style="max-height: 100px; overflow: hidden; margin-bottom: 0; margin-left: 0; margin-right: 0;">
                                    <div>
                                        <!-- content of post -->
                                        <p class="text-uppercase fw-bold">
                                            {{ \Illuminate\Support\Str::limit(explode('å', $post->title)[0], $limit = 40, $end = '...') }}</p>
                                    </div>
                                    <div >
                                        <!-- like comment -->
                                        <p>
                                        <i class="fa fa-arrow-up text-success me-3"></i>
                                        <span class="font-weight-bold">{{ $post->likes()->count() }}</span>
                                        <i class="fa fa-comment text-success ms-3 me-3"></i>
                                        <span class="font-weight-bold">{{ $post->comments->count() }}</span>
                                    </p>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-start p-3 mx-4 my-2" style="max-height: 100px; padding-top: 0; margin-top: 0;">
                                    <!-- time of post -->
                                    <p class="text-sm mb-0">
                                        <i class="fa fa-clock text-success"></i>
                                        <span class="font-weight-bold"> {{ $post->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                            </div>
                    </a>
                    <br>
                @endif
            @endforeach
        </div>
        
        <div class="card-footer">
            <!-- previous next button -->
            {{ $posts->withQueryString()->links('pagination::custom') }}
        </div>
    </div>
    @livewireScripts
</div>
