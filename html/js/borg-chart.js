(function($){


  // document ready callback
  $(function() {
    var $borgTable = $('#borg-employees').DataTable({
      // customize labelling
      oLanguage: { sSearch: 'Filter by employee name:' },
    })
      // pagination: 100 results/page
      .page.len(100)
      .draw();

    $('.dataTables_filter input').on('change keyup', function() {
      // configure search to only match employee name
      $borgTable
        .columns(0)
        .search( $(this).val() )
        // for strict matches only, disable "smart" search:
        //.search( $(this).val(), false, false )
        .draw();
    });


  });


})(jQuery);
