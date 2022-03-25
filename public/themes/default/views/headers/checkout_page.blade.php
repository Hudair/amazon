<div class="container">
  <header class="page-header mt-3">
    <div class="row">
      <div class="col-md-12">
        <ol class="breadcrumb nav-breadcrumb">
          @include('theme::headers.lists.home')
          @include('theme::headers.lists.cart')
          <li class="active">{{ trans('theme.checkout') }}</li>
        </ol>
      </div>
    </div>
  </header>
</div>
