<section>
  <div class="container text-center mb-5 mt-0">
    <div class="row thumb-lists">
      @foreach ($brands as $brand)
        <div class="col-6 col-md-2 my-5">
          <span class="vertical-helper"></span>
          <a href="{{ route('show.brand', $brand->slug) }}" class="">
            <img src="{{ get_storage_file_url(optional($brand->logoImage)->path, 'logo_lg') }}">
            <p>{{ $brand->name }}</p>
          </a>
        </div>
      @endforeach
    </div><!-- /.row -->

    <div class="row pagenav-wrapper mt-4">
      {{ $brands->links('theme::layouts.pagination') }}
    </div>
  </div><!-- /.container -->
</section>
