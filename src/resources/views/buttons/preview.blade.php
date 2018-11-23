{{-- This button is deprecated and will be removed in CRUD 3.5 --}}

@if ($crud->hasAccess('show'))
	<a href="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-xs btn-default"><i class="fa fa-eye"></i> {{ trans('sone::crud.preview') }}</a>
@endif