@extends('sone::layout')

@section('header')
<section class="content-header">
    <h1>
        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
        <small>{{ trans('sone::crud.reorder').' '.$crud->entity_name_plural }}.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url(config('sone.base.route_prefix'), 'dashboard') }}">{{ trans('sone::crud.admin') }}</a></li>
        <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
        <li class="active">{{ trans('sone::crud.reorder') }}</li>
    </ol>
</section>
@endsection

@section('content')
<?php
function tree_element($entry, $key, $all_entries, $crud)
{
    if (!isset($entry->tree_element_shown)) {
        // mark the element as shown
        $all_entries[$key]->tree_element_shown = true;
        $entry->tree_element_shown = true;

        // show the tree element
        echo '<li id="list_'.$entry->getKey().'">';
        echo '<div><span class="disclose"><span></span></span>'.object_get($entry, $crud->reorder_label).'</div>';

        // see if this element has any children
        $children = [];
        foreach ($all_entries as $key => $subentry) {
            if ($subentry->parent_id == $entry->getKey()) {
                $children[] = $subentry;
            }
        }

        $children = collect($children)->sortBy('lft');

        // if it does have children, show them
        if (count($children)) {
            echo '<ol>';
            foreach ($children as $key => $child) {
                $children[$key] = tree_element($child, $child->getKey(), $all_entries, $crud);
            }
            echo '</ol>';
        }
        echo '</li>';
    }

    return $entry;
}

?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
    @if ($crud->hasAccess('list'))
        <a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('sone::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
    @endif

        <!-- Default box -->
        <div class="box">

            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('sone::crud.reorder').' '.$crud->entity_name_plural }}</h3>
            </div>

            <div class="box-body">

                <p>{{ trans('sone::crud.reorder_text') }}</p>

                <ol class="sortable">
                <?php
                    $all_entries = collect($entries->all())->sortBy('lft')->keyBy($crud->getModel()->getKeyName());
                    $root_entries = $all_entries->filter(function ($item) {
                        return $item->parent_id == 0;
                    });
                    foreach ($root_entries as $key => $entry){
                        $root_entries[$key] = tree_element($entry, $key, $all_entries, $crud);
                    }
                ?>
                </ol>

                <button id="toArray" class="btn btn-success ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-save"></i> {{ trans('sone::crud.save') }}</span></button>

            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
@endsection


@section('after_styles')
<link rel="stylesheet" href="{{ asset('vendor/sone/nestedSortable/nestedSortable.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/sone/crud/css/crud.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/sone/crud/css/reorder.css') }}">
@endsection

@section('after_scripts')
<script src="{{ asset('vendor/sone/crud/js/crud.js') }}"></script>
<script src="{{ asset('vendor/sone/crud/js/reorder.js') }}"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" type="text/javascript"></script>
<script src="{{ url('vendor/sone/nestedSortable/jquery.mjs.nestedSortable2.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {

    // initialize the nested sortable plugin
    $('.sortable').nestedSortable({
        forcePlaceholderSize: true,
        handle: 'div',
        helper: 'clone',
        items: 'li',
        opacity: .6,
        placeholder: 'placeholder',
        revert: 250,
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div',
        maxLevels: {{ $crud->reorder_max_level ?? 3 }},

        isTree: true,
        expandOnHover: 700,
        startCollapsed: false
    });

    $('.disclose').on('click', function() {
        $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
    });

    $('#toArray').click(function(e){
        // get the current tree order
        arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});

        // log it
        //console.log(arraied);

        // send it with POST
        $.ajax({
            url: '{{ Request::url() }}',
            type: 'POST',
            data: { tree: arraied },
        })
        .done(function() {
            //console.log("success");
            new PNotify({
                        title: "{{ trans('sone::crud.reorder_success_title') }}",
                        text: "{{ trans('sone::crud.reorder_success_message') }}",
                        type: "success"
                    });
          })
        .fail(function() {
            //console.log("error");
            new PNotify({
                        title: "{{ trans('sone::crud.reorder_error_title') }}",
                        text: "{{ trans('sone::crud.reorder_error_message') }}",
                        type: "danger"
                    });
          })
        .always(function() {
            console.log("complete");
        });

    });

    $.ajaxPrefilter(function(options, originalOptions, xhr) {
        var token = $('meta[name="csrf_token"]').attr('content');

        if (token) {
            return xhr.setRequestHeader('X-XSRF-TOKEN', token);
        }
    });

});
</script>
@endsection
