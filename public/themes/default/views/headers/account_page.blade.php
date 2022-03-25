<div class="container">
  <header class="page-header">
    <div class="row">
      <div class="col-md-12">
        <ol class="breadcrumb nav-breadcrumb">
          @include('theme::headers.lists.home')
          @include('theme::headers.lists.account')
          <li class="active">@lang('theme.'. $tab)</li>
        </ol>
      </div>
    </div>
  </header>
</div>
