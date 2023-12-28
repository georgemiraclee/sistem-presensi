<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Tambah Pola Kerja</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Pilih Target</h5>
            </div>
            <form class="form-horizontal" id="add-form" method="post" action="javascript:void(0);">
              <div class="card-body">
                <div class="row">
                  <a href="<?php echo base_url('Administrator/pola_kerkar/add?type=1');?>" class="col-lg-4 col-sm-6 col-md-4">
                    <div class="info-box">
                      <span class="info-box-icon bg-danger"><i class="fa fa-users"></i></span>
                      <div class="info-box-content">
                        <h4 class="ml-2">Seluruh User</h4>
                      </div>
                    </div>
                  </a>
                  <a href="<?php echo base_url('Administrator/pola_kerkar/add?type=2');?>" class="col-lg-4 col-sm-6 col-md-4">
                    <div class="info-box">
                      <span class="info-box-icon bg-primary"><i class="fa fa-landmark"></i></span>
                      <div class="info-box-content">
                        <h4 class="ml-2">Unit Kerja Terpilih</h4>
                      </div>
                    </div>
                  </a>
                  <a href="<?php echo base_url('Administrator/pola_kerkar/add?type=3');?>" class="col-lg-4 col-sm-6 col-md-4">
                    <div class="info-box">
                      <span class="info-box-icon bg-success"><i class="fa fa-user"></i></span>
                      <div class="info-box-content">
                        <h4 class="ml-2">Pegawai Terpilih</h4>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
</script>
