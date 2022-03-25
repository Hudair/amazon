<section class="category-banner-img-wrapper mb-0">
  <div class="banner banner-o-hid cover-img-wrapper" style="background-image:url( {{ get_cover_img_src($category, 'category') }} );">
    <div class="page-cover-caption">
      <h5 class="page-cover-title">{{ $category->name }}</h5>
      <p class="page-cover-desc">{!! $category->description !!}</p>
    </div>
  </div>
</section>
