    <div class="row">
        <span class="navbar-brand custom-navbar-brand font-weight-bold">Preview Images</span>
        <span class="col-md-12 pl-0">
            <div id="product-images" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @if(count(json_decode($entry->images)) < 1)
                        <li data-target="#product-images" data-slide-to="0" class="active"></li>
                    @else
                        @foreach(json_decode($entry->images) as $key => $item)
                            <li data-target="#product-images" data-slide-to="0" class="pb-0 {{ $key == 0 ? 'active' : '' }}"></li>
                        @endforeach
                    @endif
                </ol>
                <div class="carousel-inner">
                    @if(count(json_decode($entry->images)) < 1)
                        <div class="carousel-item active">
                            <img class="rounded-bottom d-block w-100" src="{{ asset(config('const.filePath.default_image')) }}" alt="First slide">
                        </div>
                    @else
                        @foreach(json_decode($entry->images) as $key => $item)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img class="rounded-bottom d-block w-100" src="{{ asset(config('const.filePath.original').$item) }}" alt="First slide">
                            </div>
                        @endforeach
                    @endif
                </div>
                <div>
                    <a class="carousel-control-prev" href="#product-images" role="button" data-slide="prev">
                        <span class="text-primary" aria-hidden="true"><em class="las la-chevron-left la-2x"></em></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#product-images" role="button" data-slide="next">
                        <span class="text-primary" aria-hidden="true"><em class="las la-chevron-right la-2x"></em></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </span>
    </div>
