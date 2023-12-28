<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0 text-dark">
        Data List Pegawai
      </h1>

      <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <?php echo $this->session->flashdata('pesan'); ?>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="table">
                  <thead>
                    <tr>
                      <th>NIP</th>
                      <th>Nama Staff</th>
                      <th>Jabatan</th>
                      <th>Unit</th>
                      <th>Jam masuk</th>
                      <th>Status Kehadiran</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php echo $pegawai;?>    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#table').DataTable();
  });
</script>