  <!-- DATA TABLES SCRIPT -->
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js"></script>

  <script>
    var crud = {
      exportButtons: JSON.parse('{!! json_encode($crud->export_buttons) !!}'),
      functionsToRunOnDataTablesDrawEvent: [],
      addFunctionToDataTablesDrawEventQueue: function (functionName) {
          if (this.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {
          this.functionsToRunOnDataTablesDrawEvent.push(functionName);
        }
      },
      responsiveToggle: function(dt) {
          $(dt.table().header()).find('th').toggleClass('all');
          dt.responsive.rebuild();
          dt.responsive.recalc();
      },
      executeFunctionByName: function(str, args) {
        var arr = str.split('.');
        var fn = window[ arr[0] ];

        for (var i = 1; i < arr.length; i++)
        { fn = fn[ arr[i] ]; }
        fn.apply(window, args);
      },
      dataTableConfiguration: {

        @if ($crud->getResponsiveTable())
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        // show the content of the first column
                        // as the modal header
                        var data = row.data();
                        return data[0];
                    }
                } ),
                renderer: function ( api, rowIdx, columns ) {
                  var data = $.map( columns, function ( col, i ) {
                      var allColumnHeaders = $("#crudTable thead>tr>th");

                      if ($(allColumnHeaders[col.columnIndex]).attr('data-visible-in-modal') == 'false') {
                        return '';
                      }

                      return '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td style="vertical-align:top;"><strong>'+col.title.trim()+':'+'<strong></td> '+
                                '<td style="padding-left:10px;padding-bottom:10px;">'+col.data+'</td>'+
                              '</tr>';
                  } ).join('');

                  return data ?
                      $('<table class="table table-striped table-condensed m-b-0">').append( data ) :
                      false;
                },
            }
        },
        @else
        responsive: false,
        scrollX: true,
        @endif

        autoWidth: false,
        pageLength: {{ $crud->getDefaultPageLength() }},
        lengthMenu: @json($crud->getPageLengthMenu()),
        /* Disable initial sort */
        aaSorting: [],
        language: {
              "emptyTable":     "{{ trans('sone::crud.emptyTable') }}",
              "info":           "{{ trans('sone::crud.info') }}",
              "infoEmpty":      "{{ trans('sone::crud.infoEmpty') }}",
              "infoFiltered":   "{{ trans('sone::crud.infoFiltered') }}",
              "infoPostFix":    "{{ trans('sone::crud.infoPostFix') }}",
              "thousands":      "{{ trans('sone::crud.thousands') }}",
              "lengthMenu":     "{{ trans('sone::crud.lengthMenu') }}",
              "loadingRecords": "{{ trans('sone::crud.loadingRecords') }}",
              "processing":     "<img src='{{ asset('vendor/sone/crud/img/ajax-loader.gif') }}' alt='{{ trans('sone::crud.processing') }}'>",
              "search":         "{{ trans('sone::crud.search') }}",
              "zeroRecords":    "{{ trans('sone::crud.zeroRecords') }}",
              "paginate": {
                  "first":      "{{ trans('sone::crud.paginate.first') }}",
                  "last":       "{{ trans('sone::crud.paginate.last') }}",
                  "next":       "<span class='hidden-xs hidden-sm'>{{ trans('sone::crud.paginate.next') }}</span><span class='hidden-md hidden-lg'>></span>",
                  "previous":   "<span class='hidden-xs hidden-sm'>{{ trans('sone::crud.paginate.previous') }}</span><span class='hidden-md hidden-lg'><</span>"
              },
              "aria": {
                  "sortAscending":  "{{ trans('sone::crud.aria.sortAscending') }}",
                  "sortDescending": "{{ trans('sone::crud.aria.sortDescending') }}"
              },
              "buttons": {
                  "copy":   "{{ trans('sone::crud.export.copy') }}",
                  "excel":  "{{ trans('sone::crud.export.excel') }}",
                  "csv":    "{{ trans('sone::crud.export.csv') }}",
                  "pdf":    "{{ trans('sone::crud.export.pdf') }}",
                  "print":  "{{ trans('sone::crud.export.print') }}",
                  "colvis": "{{ trans('sone::crud.export.column_visibility') }}"
              },
          },
          processing: true,
          serverSide: true,
          ajax: {
              "url": "{!! url($crud->route.'/search').'?'.Request::getQueryString() !!}",
              "type": "POST"
          },
          dom:
            "<'row'<'col-sm-6 hidden-xs'l><'col-sm-6 hidden-print'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-2'B><'col-sm-5 hidden-print'p>>",
      }
  }
  </script>

  @include('crud::inc.export_buttons')

  <script type="text/javascript">
    jQuery(document).ready(function($) {

      crud.table = $("#crudTable").DataTable(crud.dataTableConfiguration);

      // override ajax error message
      $.fn.dataTable.ext.errMode = 'none';
      $('#crudTable').on('error.dt', function(e, settings, techNote, message) {
          new PNotify({
              type: "error",
              title: "{{ trans('sone::crud.ajax_error_title') }}",
              text: "{{ trans('sone::crud.ajax_error_text') }}"
          });
      });

      // make sure AJAX requests include XSRF token
      $.ajaxPrefilter(function(options, originalOptions, xhr) {
          var token = $('meta[name="csrf_token"]').attr('content');

          if (token) {
                return xhr.setRequestHeader('X-XSRF-TOKEN', token);
          }
      });

      // on DataTable draw event run all functions in the queue
      // (eg. delete and details_row buttons add functions to this queue)
      $('#crudTable').on( 'draw.dt',   function () {
         crud.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
            crud.executeFunctionByName(functionName);
         });
      } ).dataTable();

      // when datatables-colvis (column visibility) is toggled
      // rebuild the datatable using the datatable-responsive plugin
      $('#crudTable').on( 'column-visibility.dt',   function (event) {
         crud.table.responsive.rebuild();
      } ).dataTable();

      @if ($crud->getResponsiveTable())
        // when columns are hidden by reponsive plugin,
        // the table should have the has-hidden-columns class
        crud.table.on( 'responsive-resize', function ( e, datatable, columns ) {
            if (crud.table.responsive.hasHidden()) {
              $("#crudTable").removeClass('has-hidden-columns').addClass('has-hidden-columns');
             } else {
              $("#crudTable").removeClass('has-hidden-columns');
             }
        } );
      @else
        // make sure the column headings have the same width as the actual columns
        // after the user manually resizes the window
        var resizeTimer;
        function resizeCrudTableColumnWidths() {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(function() {
            // Run code here, resizing has "stopped"
            crud.table.columns.adjust();
          }, 250);
        }
        $(window).on('resize', function(e) {
          resizeCrudTableColumnWidths();
        });
        $(document).on('expanded.pushMenu', function(e) {
          resizeCrudTableColumnWidths();
        });
        $(document).on('collapsed.pushMenu', function(e) {
          resizeCrudTableColumnWidths();
        });
      @endif

    });
  </script>

  @include('crud::inc.details_row_logic')
