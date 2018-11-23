@extends('sone::layout')

@section('content-header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
        <small>{{ ucfirst(trans('sone::crud.preview')).' '.$crud->entity_name }}.</small>
      </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('sone.base.route_prefix'), 'dashboard') }}">{{ trans('sone::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('sone::crud.preview') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
	@if ($crud->hasAccess('list'))
		<a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('sone::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
	@endif

	<!-- Default box -->
	  <div class="box">
	    <div class="box-header with-border">
	    	<span class="pull-right m-l-20 m-r-20 m-t-5"><a href="javascript: window.print();"><i class="fa fa-print"></i></a></span>

          @if ($crud->model->translationEnabled())
			    	<!-- Single button -->
					<div class="btn-group pull-right">
					  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    {{trans('sone::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
					  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
						  	<li><a href="{{ url($crud->route.'/'.$entry->getKey()) }}?locale={{ $key }}">{{ $locale }}</a></li>
					  	@endforeach
					  </ul>
					</div>
					<h3 class="box-title" style="line-height: 30px;">{{ trans('sone::crud.preview') .' '. $crud->entity_name }}</h3>
				@else
					<h3 class="box-title">{{ trans('sone::crud.preview') .' '. $crud->entity_name }}</h3>
				@endif
	    </div>
	    <div class="box-body no-padding">
			<table class="table table-striped table-bordered">
		        <tbody>
		        @foreach ($crud->columns as $column)
		            <tr>
		                <td>
		                    <strong>{{ $column['label'] }}</strong>
		                </td>
                        <td>
							@if (!isset($column['type']))
		                      @include('crud::columns.text')
		                    @else
		                      @if(view()->exists('vendor.sone.crud.columns.'.$column['type']))
		                        @include('vendor.sone.crud.columns.'.$column['type'])
		                      @else
		                        @if(view()->exists('crud::columns.'.$column['type']))
		                          @include('crud::columns.'.$column['type'])
		                        @else
		                          @include('crud::columns.text')
		                        @endif
		                      @endif
		                    @endif
                        </td>
		            </tr>
		        @endforeach
				@if ($crud->buttons->where('stack', 'line')->count())
					<tr>
						<td><strong>{{ trans('sone::crud.actions') }}</strong></td>
						<td>
							@include('crud::inc.button_stack', ['stack' => 'line'])
						</td>
					</tr>
				@endif
		        </tbody>
			</table>
	    </div><!-- /.box-body -->
	  </div><!-- /.box -->

@endsection


@section('after_styles')
	<link rel="stylesheet" href="{{ asset('vendor/sone/crud/css/crud.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/sone/crud/css/show.css') }}">
@endsection

@section('after_scripts')
	<script src="{{ asset('vendor/sone/crud/js/crud.js') }}"></script>
	<script src="{{ asset('vendor/sone/crud/js/show.js') }}"></script>
@endsection
