<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0 text-dark">
        Dashboard
        <span class="float-right text-md">
          <?php echo date("d M Y, l"); ?>
        </span>
      </h1>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row clearfix">
        <div class="col-md-12">
          <div class="card bg-secondary">
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <h5>Welcome back Admin!</h5>
                  <p>Selamat beraktivitas...</p>
                </div>
                <div class="col-md-4">
                  <div class="text-center">
                    <img width="200" src="<?php echo base_url();?>assets/images/icon/productivity.svg" alt="icon-productivity">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row clearfix">
        <div class="col-lg-4 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo $channel_active;?></h3>
              <p>Client Active</p>
            </div>
            <div class="icon">
              <i class="fa fa-briefcase"></i>
            </div>
            <a href="<?php echo base_url('Superadmin/channel');?>" class="small-box-footer">More info <span class="fas fa-arrow-circle-right"></span></a>
          </div>
        </div>
        <div class="col-lg-4 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?php echo $channel_inactive; ?></h3>
              <p>Client Inactive</p>
            </div>
            <div class="icon">
              <i class="fa fa-ban"></i>
            </div>
            <a href="<?php echo base_url('Superadmin/channel');?>" class="small-box-footer">More info <span class="fas fa-arrow-circle-right"></span></a>
          </div>
        </div>
        <div class="col-lg-4 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3><?php echo $all_user;?></h3>
              <p>Total User</p>
            </div>
            <div class="icon">
              <i class="fa fa-ban"></i>
            </div>
            <a href="#" class="small-box-footer">&nbsp;</a>
          </div>
        </div>
      </div>
    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>