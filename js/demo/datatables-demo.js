// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    } );
  var table = $('#dataTable').DataTable({
    scrollY:        '50vh',
    scrollX:        true,
    scrollCollapse: true,
    paging:         true,
    fixedHeader:    true
    });
  var table1 = $('#dataTable1').DataTable({
    scrollY:        '50vh',
    scrollX:        true,
    scrollCollapse: true,
    paging:         true,
    fixedHeader:    true
  });
});