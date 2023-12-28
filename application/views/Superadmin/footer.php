<!-- Main Footer -->
<footer class="main-footer">
            <strong>Copyright &copy; 2020-<?php echo date('Y');?> <a href="https://pressensi.com">Pressensi.com</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- Bootstrap -->
    <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="<?php echo base_url();?>assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url();?>assets/admin/js/adminlte.js"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="<?php echo base_url();?>assets/admin/js/demo.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/raphael/raphael.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/daterangepicker/moment.min.js"></script>
    <!-- ChartJS -->
    <script src="<?php echo base_url();?>assets/admin/plugins/chart.js/Chart.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/highchart.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/highchart_3d.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/highcharts/code/modules/exporting.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

    <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/daterangepicker/moment.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/multi-select/js/jquery.multi-select.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/forms/form-validation.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/multi-select/js/jquery.multi-select.js"></script>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://js.arcgis.com/4.21/"></script>

    <script>
        function logout() {
            swal({
                title: "Logout!",
                text: "Are you sure ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location = '<?php echo base_url();?>Administrator/login/logout';
                }
            });
        }
    </script>
</body>
</html>
