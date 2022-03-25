@php
    $SEOurl = url()->current();
    $SEOtitle = $title ?? get_platform_title();
    $SEOdescription = config('seo.meta.description');
    $SEOimage = filter_var(config('seo.meta.image'), FILTER_VALIDATE_URL) ? config('seo.meta.image') : get_storage_file_url('logo.png', 'full');
    $SEOkeywords = config('seo.meta.keywords');

    // For Products
    if(isset($item)) {
        $SEOtitle = $item->meta_title ?? $item->title;
        $SEOdescription = $item->meta_description ?? substr($item->description, 0, config('seo.meta.description_character_limit', 160));
        $SEOimage = get_product_img_src($item, 'full');
        $SEOkeywords = implode(', ', $item->tags->pluck('name')->toArray());
    }
    // For Categories
    elseif(Request::is('categories/*') || Request::is('categorygrp/*') || Request::is('category/*')) {
        $category = $category ?? $categorySubGroup ?? $categoryGroup;
        $SEOtitle = $category->meta_title ?? $SEOtitle;
        $SEOdescription = $category->meta_description ?? $SEOdescription;
    }
    // For blogs
    elseif(isset($blog)) {
        $SEOtitle = $blog->title;
        $SEOdescription = substr($blog->excerpt, 0, config('seo.meta.description_character_limit', 160));
        $SEOimage = get_storage_file_url(optional($blog->image)->path, 'blog');
        $SEOkeywords = implode(', ', $blog->tags->pluck('name')->toArray());
    }
    // For pages
    elseif(isset($page)) {
        $SEOtitle = $page->title;
        $SEOdescription = substr($page->content, 0, config('seo.meta.description_character_limit', 160));
        $SEOimage = get_storage_file_url(optional($page->image)->path, 'page');
        // $SEOkeywords = implode(', ', $page->tags->pluck('name')->toArray());
    }

    $SEOtitle = strip_tags($SEOtitle);
    $SEOdescription = strip_tags($SEOdescription);
@endphp

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, shrink-to-fit=no">
<meta name="author" content="Incevio | incevio.com">
<meta name="format-detection" content="telephone=no">

@if (config('seo.enabled'))
    <!-- Standard SEO -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="referrer" content="{{ $referrer ?? config('seo.meta.referrer') }}">
    <meta name="robots" content="{{ $robots ?? config('seo.meta.robots') }}">
    <meta name="revisit-after" content="{{ config('seo.meta.revisit_after', '7 days') }}" />
    <meta name="description" content="{!! $SEOdescription !!}">
    <meta name="image" content="{{ $SEOimage }}">
    <meta name="keywords" content="{!! $SEOkeywords !!}">

    <!-- Geo loacation -->
    @if(config('seo.meta.geo_region') !== '')
        <meta name="geo.region" content="{{ config('seo.meta.geo_region') }}">
        <meta name="geo.placename" content="{{  config('seo.meta.geo_region') }}">
    @endif
    @if(config('seo.meta.geo_position') !== '')
        <meta name="geo.position" content="{{ config('seo.meta.geo_position') }}">
        <meta name="ICBM" content="{{ config('seo.meta.geo_position') }}">
    @endif

    <!-- Dublin Core basic info -->
    <meta name="dcterms.Format" content="text/html">
    <meta name="dcterms.Type" content="text/html">
    <meta name="dcterms.Language" content="{{ config('app.locale') }}">
    <meta name="dcterms.Identifier" content="{{ $SEOurl }}">
    <meta name="dcterms.Relation" content="{{  get_platform_title() }}">
    <meta name="dcterms.Publisher" content="{{  get_platform_title() }}">
    <meta name="dcterms.Coverage" content="{{ $SEOurl }}">
    <meta name="dcterms.Contributor" content="{{ $author ?? get_platform_title() }}">
    <meta name="dcterms.Title" content="{!! $SEOtitle !!}">
    <meta name="dcterms.Subject" content="{!! $SEOkeywords !!}">
    <meta name="dcterms.Description" content="{!! $SEOdescription !!}">

    <!-- Facebook OpenGraph -->
    <meta property="og:locale" content="{{ config('app.locale') }}">
    <meta property="og:url" content="{{ $SEOurl }}">
    <meta property="og:site_name" content="{{  get_platform_title() }}">
    <meta property="og:title" content="{!! $SEOtitle !!}">
    <meta property="og:description" content="{!! $SEOdescription !!}">

    @if(isset($item))

        <meta property="og:type" content="product">
        <meta name="product:availability" content="{{ $item->stock_quantity > 0 ? trans('theme.in_stock') : trans('theme.out_of_stock') }}">
        <meta name="product:price:currency" content="{{ get_system_currency() }}">
        <meta name="product:price:amount" content="{!! get_formated_currency($item->current_sale_price(), config('system_settings.decimals', 2)) !!}">
        <meta name="product:brand" content="{!! $item->product->manufacturer->name !!}">

        @php
            $item_images = $item->images->count() ? $item->images : $item->product->images;

            if(isset($variants)){
                // Remove images of current items from the variants imgs
                $other_images = $variants->pluck('images')->flatten(1)->filter(
                    function ($value, $key) use ($item) {
                        return $value->imageable_id != $item->id;
                    });
                $item_images = $item_images->concat($other_images);
            }
        @endphp

        @foreach($item_images as $img)
            @continue(!$img->path)

            <meta property="og:image" content="{{ get_storage_file_url($img->path, 'full') }}">
        @endforeach

    @else

        <meta property="og:type" content="{{ 'website' }}">
        <meta property="og:image" content="{{ $SEOimage }}">

    @endif

    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    @if(config('seo.meta.video') !== '')
        <meta name="og:video" content="{{ $video ?? config('seo.meta.video') }}">
    @endif

    @if(config('seo.meta.fb_app_id') !== '')
        <meta property="fb:app_id" content="{{ config('seo.meta.fb_app_id') }}"/>
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:title" content="{!! $SEOtitle !!}">
    <meta name="twitter:description" content="{!! $SEOdescription !!}">
    <meta name="twitter:image" content="{{ $SEOimage }}">
    <meta name="twitter:image:alt" content="{!! $SEOtitle !!}">

    @if(isset($item))
        <meta name="twitter:card" content="product">
        <meta name="twitter:label1" content="price">
        <meta name="twitter:data1" content="{!! get_formated_currency($item->current_sale_price(), config('system_settings.decimals', 2)) !!}">
        <meta name="twitter:label2" content="availability">
        <meta name="twitter:data2" content="{{ $item->stock_quantity > 0 ? trans('theme.in_stock') : trans('theme.out_of_stock') }}">
        <meta name="twitter:label3" content="currency">
        <meta name="twitter:data3" content="{{ get_system_currency() }}">
        <meta name="twitter:label4" content="brand">
        <meta name="twitter:data4" content="{!! $item->product->manufacturer->name !!}">
        <meta name="twitter:label4" content="seller">
        <meta name="twitter:data4" content="{!! $item->shop->name !!}">

    @elseif(config('seo.meta.twitter_card') !== '')
        <meta name="twitter:card" content="{{ config('seo.meta.twitter_card') }}">
    @endif

    @if(config('seo.meta.twitter_site') !== '')
        <meta name="twitter:site" content="{{ config('seo.meta.twitter_site') }}">
    @endif

    @if(isset($item))
        <!-- Microdata Product Page-->
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Product",
                "name": "{!! $SEOtitle !!}",
                "description": "{!! $SEOdescription !!}",
                "image": "{!! $SEOimage !!}",
                "brand": {
                    "@type": "Brand",
                    "name": "{!! $item->product->manufacturer->name !!}"
                },
                "sku" : "{{ $item->sku }}",
                @if($item->product->gtin_type && $item->product->gtin )
                    "{{ $item->product->gtin_type }}": "{{ $item->product->gtin }}",
                @endif
                "offers": {
                    "@type": "Offer",
                    "url": "{{ $SEOurl }}",
                    "availability": "http://schema.org/InStock",
                    "priceCurrency": "{{ get_system_currency() }}",
                    "price": "{!! get_formated_decimal($item->current_sale_price(), true, config('system_settings.decimals', 2)) !!}"
                },
                @if($item->feedbacks_count > 0)
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "{{ get_formated_decimal($item->feedbacks->avg('rating'), true , 1) }}",
                    "bestRating": "5",
                    "worstRating": "1",
                    "reviewCount": "{{ $item->feedbacks_count }}"
                }
                @endif
            }
        </script>
    @endif

@endif

<title>{!! $SEOtitle !!}</title>
<link rel="icon" href="{{ get_storage_file_url('icon.png', 'full') }}" type="image/x-icon" />
{{-- <link rel="manifest" href="{{ asset('site.webmanifest') }}"/> --}}
<link rel="apple-touch-icon" href="{{ get_storage_file_url('icon.png', 'full') }}"/>
