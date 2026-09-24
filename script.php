<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../asett/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../asett/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../asett/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../asett/dist/js/adminlte.js"></script>

<!-- PAGE ../asett/plugins -->
<!-- jQuery Mapael -->
<script src="../asett/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../asett/plugins/raphael/raphael.min.js"></script>
<script src="../asett/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../asett/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="../asett/plugins/chart.js/Chart.min.js"></script>>
<!-- DataTables  & Plugins -->
<script src="../asett/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../asett/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../asett/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../asett/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../asett/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../asett/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../asett/plugins/jszip/jszip.min.js"></script>
<script src="../asett/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../asett/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../asett/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../asett/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../asett/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
