<div class="">
    <div class="card z-index-2" style="max-height: 200px; overflow: hidden;" >
        <p class="h4 text-bold text-center mt-2">Announcements</p>
        @foreach ($announcements as $announcement)
            <a href="/announcement/{{ $announcement->id }}">
                <div class="card-header pb-0 pt-0 bg-transparent">
                    <h4 class="text-capitalize">
                        {{ $announcement->title }}
                    </h4>
                    <p class="text-sm mb-0">
                        <i class="fa fa-clock text-success"></i>
                        <span class="font-weight-bold">{{ $announcement->created_at->diffForHumans() }}</span>
                    </p>
                </div>
                <div class="card-footer p-3">
                    <small>Click here for info.</small>
                </div>
    </div>
    </a>
    <br>
    @endforeach
</div>