@extends('sone::layout')

@section('header')
  <section class="content-header">
    <h1>
        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
        <small>{{ trans('sone::crud.revisions') }}.</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url(config('sone.base.route_prefix'),'dashboard') }}">{{ trans('sone::crud.admin') }}</a></li>
      <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
      <li class="active">{{ trans('sone::crud.revisions') }}</li>
    </ol>
  </section>
@endsection

@section('content')
<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <!-- Default box -->
    @if ($crud->hasAccess('list'))
      <a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('sone::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
    @endif

    @if(!count($revisions))
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('sone::crud.no_revisions') }}</h3>
        </div>
      </div>
    @else
      @include('crud::inc.revision_timeline')
    @endif
  </div>
</div>
@endsection


@section('after_styles')
  <link rel="stylesheet" href="{{ asset('vendor/sone/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/sone/crud/css/revisions.css') }}">
@endsection

@section('after_scripts')
  <script src="{{ asset('vendor/sone/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/sone/crud/js/revisions.js') }}"></script>
@endsection