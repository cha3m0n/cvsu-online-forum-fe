<div class="card mx-5">
    <style>
        .custom-button{
            margin: 0;
            position: relative;
            
        }
    </style>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-info custom-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        What's on your mind?
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <br><br>
                    <h1 class="modal-title fs-5 ms-auto" id="staticBackdropLabel">Create a post</h1>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="justify-content-center d-flex align-items-center">
                        <div class="col-11">
                            <form wire:submit.prevent="createPost" class="row g-3 mb-4" action="" wire:ignore>
                                @csrf
                                <div class="row mb-2">
                                    <!-- title -->
                                    <input class="form-control mb-3" rows="3" name="title" id="title"
                                        wire:model="title" placeholder="Post Title. ">
                                    @error('title')
                                        <p class="p text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                    <!-- tags -->
                                    <input class="form-control mb-3" rows="3" name="tags" id="tags"
                                        wire:model="tags" placeholder="Tags(Comma Separated)">
                                    @error('tags')
                                        <p class="p text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                    <!-- categories -->
                                    <select class="form-select mb-3" multiple aria-label="multiple select example"name="selectedCategories" id="selectedCategories" wire:model="selectedCategories" multiple>
                                        <option disabled selected>Select a category...</option>
                                        @foreach (\App\Models\Category::all() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }} </option>
                                        @endforeach
                                    </select>
                                    @error('selectedCategories')
                                        <p class="p text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                </div>
                                <div class="row mb-2">
                                    <!-- Context section -->
                                    <textarea class="form-control mb-3" rows="3" name="body" id="body" wire:model="body"
                                        placeholder="Post Context"></textarea>
                                    @error('body')
                                        <p class="p text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="row mb-2 justify-content-center">
                                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Submit Post</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <h1 class="fs-5 text-danger " id="staticBackdropLabel">Think Before You Click.</h1>
                </div>
            </div>
        </div>
    </div>
</div>

