<div class="container">
  <header class="page-header mt-3">
    <div class="row">
      <div class="col-md-12">
        <ol class="breadcrumb nav-breadcrumb">
          @include('theme::headers.lists.home')
          @include('theme::headers.lists.categories')
          <li class="active">{!! $category->name !!}</li>
        </ol>
      </div>
    </div>
  </header>
</div>
