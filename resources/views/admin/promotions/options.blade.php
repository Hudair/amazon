@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.promotions') }}</h3>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-stripe">
        <thead>
          <tr>
            <th width="45%">@lang('app.options')</th>
            <th>@lang('app.values')</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>
              <h4>@lang('app.promotional_tagline')</h4>
              <small class="text-muted">
                {{ trans('help.promotional_tagline') }}
              </small>
            </th>
            <td>
              {{ trans('app.form.text') . ' : ' }}<strong>{{ empty($tagline['text']) ? '' : $tagline['text'] }}</strong>
              <br />
              {{ trans('app.action_url') . ' : ' }}<strong>{{ !empty($tagline['action_url']) ? $tagline['action_url'] : '' }}</strong>
            </td>
            <td class="text-right">
              <a href="javascript:void(0)" data-link="{{ route('admin.promotion.tagline') }}" class="ajax-modal-btn btn btn-sm btn-link flat"><i class="fa fa-edit"></i> @lang('app.edit')</a>
            </td>
          </tr>

          <tr>
            <th>
              <h4>@lang('app.best_finds_under')</h4>
              <small class="text-muted">
                {!! trans('help.best_finds_under') !!}
              </small>
            </th>
            <td>
              @unless(empty(get_from_option_table('best_finds_under', 99)))
                <strong>
                  {{ get_formated_currency(get_from_option_table('best_finds_under')) }}
                </strong>
              @endunless
            </td>
            <td class="text-right">
              <a href="javascript:void(0)" data-link="{{ route('admin.promotion.bestFindsUnder') }}" class="ajax-modal-btn btn btn-sm btn-link flat"><i class="fa fa-edit"></i> @lang('app.edit')</a>
            </td>
          </tr>

          <tr>
            <th>
              <h4>@lang('app.deal_of_the_day')</h4>
              <small class="text-muted">
                {!! trans('help.deal_of_the_day') !!}
              </small>
            </th>
            <td>
              @if ($deal_of_the_day)
                <span class="label label-outline">{{ $deal_of_the_day->title . ' | ' . $deal_of_the_day->sku . ' | ' . get_formated_currency($deal_of_the_day->current_sale_price()) }}</span>
              @endif
            </td>
            <td class="text-right">
              <a href="javascript:void(0)" data-link="{{ route('admin.promotion.dealOfTheDay') }}" class="ajax-modal-btn btn btn-sm btn-link flat"><i class="fa fa-edit"></i> @lang('app.edit')</a>
            </td>
          </tr>

          <tr>
            <th>
              <h4>@lang('app.featured_categories')</h4>
              <small class="text-muted">
                {!! trans('help.featured_categories') !!}
              </small>
            </th>
            <td>
              @foreach ($featured_categories as $category)
                <span class="label label-outline">{{ $category }}</span>
              @endforeach
            </td>
            <td class="text-right">
              <a href="javascript:void(0)" data-link="{{ route('admin.promotion.featuredCategories.edit') }}" class="ajax-modal-btn btn btn-sm btn-link flat"><i class="fa fa-edit"></i> @lang('app.edit')</a>
            </td>
          </tr>

          <tr>
            <th>
              <h4>@lang('app.featured_items')</h4>
              <small class="text-muted">
                {!! trans('help.featured_items') !!}
              </small>
            </th>
            <td>
              @if ($featured_items)
                @foreach ($featured_items as $item)
                  <span class="label label-outline">{!! $item->title . ' | ' . $item->sku . ' | ' . get_formated_currency($item->current_sale_price()) !!}</span>
                @endforeach
              @endif
            </td>
            <td class="text-right">
              <a href="javascript:void(0)" data-link="{{ route('admin.featuredItems.edit') }}" class="ajax-modal-btn btn btn-sm btn-link flat"><i class="fa fa-edit"></i> @lang('app.edit')</a>
            </td>
          </tr>

          <tr>
            <th>
              <h4>@lang('app.trending_now_categories')</h4>
              <small class="text-muted">
                {!! trans('help.trending_now_categories') !!}
              </small>
            </th>
            <td>
              @foreach ($trending_categories as $category)
                <span class="label label-outline">{{ $category }}</span>
              @endforeach
            </td>
            <td class="text-right">
              <a href="javascript:void(0)" data-link="{{ route('admin.promotion.trendingNow.edit') }}" class="ajax-modal-btn btn btn-sm btn-link flat"><i class="fa fa-edit"></i> @lang('app.edit')</a>
            </td>
          </tr>

          <tr>
            <th>
              <h4>@lang('app.featured_brands')</h4>
              <small class="text-muted">
                {!! trans('help.featured_brands') !!}
              </small>
            </th>
            <td>
              @foreach ($featured_brands as $brand)
                <span class="label label-outline">{{ $brand->name }}</span>
              @endforeach
            </td>
            <td class="text-right">
              <a href="javascript:void(0)" data-link="{{ route('admin.featuredBrands.edit') }}" class="ajax-modal-btn btn btn-sm btn-link flat"><i class="fa fa-edit"></i> @lang('app.edit')</a>

              {{-- <a href="javascript:void(0)" data-link="{{ route('admin.appearance.featuredBrands') }}" class="ajax-modal-btn btn btn-sm btn-default flat"><i class="fa fa-edit"></i> @lang('app.edit')</a> --}}
            </td>
          </tr>
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection
