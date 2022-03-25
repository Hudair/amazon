<div class="container">
  <header class="page-header mt-3">
    <div class="row">
      <div class="col-md-12">
        <ol class="breadcrumb nav-breadcrumb">
          @include('theme::headers.lists.home')
          @include('theme::headers.lists.categories')
          @include('theme::headers.lists.category_grp', ['category' => $categorySubGroup->group])
          <li class="active">{!! $categorySubGroup->name !!}</li>
        </ol>
      </div>
    </div>
  </header>
</div>
