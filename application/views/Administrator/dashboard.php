<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
          <div class="col-sm-6">
              <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
              <button class="btn bg-indigo btn-sm float-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                  <span class="fas fa-filter"></span> FILTER
              </button>
          </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
      <div class="container-fluid">
          <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="collapse" id="collapseExample">
                      <div class="well" id="accOneColThree">
                          <form method="post" id="formFilter" action="javascript:void(0);">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class='form-group form-group-sm'>
                                          <a href="#" type="button" id="reportrange" class="btn btn-info">
                                              <span class="fa fa-calendar-day"></span>
                                          </a>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <input type="hidden" name="dari" id="dari">
                                  <input type="hidden" name="sampai" id="sampai">
                                  <div class="col-md-4">
                                      <h6>Unit Kerja</h6>
                                      <div style="height: 200px; overflow: auto;">
                                          <?php foreach ($skpd as $key => $value) {
                                            $nama_unit = (strlen($value->nama_unit) > 25) ? substr($value->nama_unit,0,25).'...' : $value->nama_unit;
                                          ?>
                                          <div class="demo-checkbox">
                                                  <input type="checkbox" id="unit_kerja_<?= $value->id_unit;?>" name="skpd[]" value="<?= $value->id_unit;?>" />
                                                  <label for="unit_kerja_<?= $value->id_unit;?>"> <?= ucwords($nama_unit);?></label>
                                              </div>
                                          <?php } ?>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <h6>JABATAN</h6>
                                      <div style="height: 200px; overflow: auto;">
                                          <?php foreach ($jabatan as $key => $value) {?>
                                          <div class="demo-checkbox">
                                                  <input type="checkbox" id="jabatan_<?= $value->id_jabatan;?>" name="jabatan[]" value="<?= $value->id_jabatan;?>" />
                                                  <label for="jabatan_<?= $value->id_jabatan;?>"> <?= ucwords($value->nama_jabatan);?></label>
                                              </div>
                                          <?php } ?>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <h6>STATUS</h6>
                                      <div style="height: 200px; overflow: auto;">
                                          <div class="demo-checkbox">
                                              <input type="checkbox" id="status_tepat_waktu" name="status[]"  value="Tepat Waktu"/>
                                              <label for="status_tepat_waktu">Tepat Waktu</label>
                                          </div>
                                          <div class="demo-checkbox">
                                              <input type="checkbox" id="status_terlambat" name="status[]"  value="Terlambat"/>
                                              <label for="status_terlambat">Terlambat</label>
                                          </div>
                                          <div class="demo-checkbox">
                                              <input type="checkbox" id="status_tidak_hadir" name="status[]"  value="Tidak hadir"/>
                                              <label for="status_tidak_hadir">Tidak Hadir</label>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="row mb-3">
                                  <div class="col-md-12">
                                      <input type="submit" value="Filter" name="" class="btn btn-primary btn float-right">
                                      <a href="javascript:void(0);" class="btn btn-danger btn float-right mr-2" id="reset" onclick="setNull()"><span class="fa fa-undo-alt"></span> Reset</a>
                                      <!-- <button >Filter</button> -->
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>

          <?php if ($id_channel == 14) { ?>
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-3" id="hadir" data-toggle="modal" data-target="#myModal" style="cursor: pointer;">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"><i class="material-icons" style="font-size: 50px;">alarm_on</i></span>
  
                        <div class="info-box-content">
                            <span class="info-box-text">Hadir</span>
                            <span class="info-box-number"><?= $tepat_waktu+$terlambat;?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>  
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
  
                <div class="col-lg-4 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="tidak_hadirs">
                    <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="material-icons" style="font-size: 50px;">alarm_off</i></span>
  
                    <div class="info-box-content">
                        <span class="info-box-text">TIDAK HADIR</span>
                        <span class="info-box-number"><?= $tidakhadir;?></span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <a href="<?= base_url();?>Administrator/pegawai" class="col-lg-4 col-sm-6 col-md-3" style="text-decoration: none; color: black;">
                    <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1"><i class="material-icons" style="font-size: 50px;">check</i></span>
  
                    <div class="info-box-content">
                        <span class="info-box-text">USER AKTIF</span>
                        <span class="info-box-number"><?= $aktifUser;?></span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="sakitModal">
                    <div class="info-box">
                        <span class="info-box-icon bg-purple elevation-1"><i class="material-icons" style="font-size: 50px;">local_hospital</i></span>
  
                        <div class="info-box-content">
                            <span class="info-box-text">SAKIT</span>
                            <span class="info-box-number"><?= $count_sakit;?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-lg-4 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="izinModal">
                    <div class="info-box mb-3">
                    <span class="info-box-icon bg-teal elevation-1"><i class="material-icons text-white" style="font-size: 50px;">business_center</i></span>
  
                    <div class="info-box-content">
                        <span class="info-box-text">IZIN</span>
                        <span class="info-box-number"><?= $count_izin;?></span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
  
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
  
                <div class="col-lg-4 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="cutiModal">
                    <div class="info-box mb-3">
                    <span class="info-box-icon bg-indigo elevation-1"><i class="material-icons" style="font-size: 50px;">flag</i></span>
  
                    <div class="info-box-content">
                        <span class="info-box-text">CUTI</span>
                        <span class="info-box-number"><?= $count_cuti;?></span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
          <?php } else { ?>
              <div class="row">
                  <div class="col-lg-3 col-sm-6 col-md-3" id="tepat_waktu" data-toggle="modal" data-target="#myModal" style="cursor: pointer;">
                      <div class="info-box">
                          <span class="info-box-icon bg-success elevation-1"><i class="material-icons" style="font-size: 50px;">alarm_on</i></span>
    
                          <div class="info-box-content">
                              <span class="info-box-text">TEPAT WAKTU</span>
                              <span class="info-box-number"><?= $tepat_waktu;?></span>
                          </div>
                          <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                  </div>
                  <!-- /.col -->
                  <div class="col-lg-3 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="terlambats">
                      <div class="info-box mb-3">
                      <span class="info-box-icon bg-warning elevation-1"><i class="material-icons text-white" style="font-size: 50px;">alarm_add</i></span>
    
                      <div class="info-box-content">
                          <span class="info-box-text">TERLAMBAT</span>
                          <span class="info-box-number"><?= $terlambat;?></span>
                      </div>
                      <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                  </div>
                  <!-- /.col -->
    
                  <!-- fix for small devices only -->
                  <div class="clearfix hidden-md-up"></div>
    
                  <div class="col-lg-3 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="tidak_hadirs">
                      <div class="info-box mb-3">
                      <span class="info-box-icon bg-danger elevation-1"><i class="material-icons" style="font-size: 50px;">alarm_off</i></span>
    
                      <div class="info-box-content">
                          <span class="info-box-text">TIDAK HADIR</span>
                          <span class="info-box-number"><?= $tidakhadir;?></span>
                      </div>
                      <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                  </div>
                  <!-- /.col -->
                  <a href="<?= base_url();?>Administrator/pegawai" class="col-lg-3 col-sm-6 col-md-3" style="text-decoration: none; color: black;">
                      <div class="info-box mb-3">
                      <span class="info-box-icon bg-info elevation-1"><i class="material-icons" style="font-size: 50px;">check</i></span>
    
                      <div class="info-box-content">
                          <span class="info-box-text">USER AKTIF</span>
                          <span class="info-box-number"><?= $aktifUser;?></span>
                      </div>
                      <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                  </a>
                  <!-- /.col -->
              </div>
              <div class="row">
                  <div class="col-lg-3 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="sakitModal">
                      <div class="info-box">
                          <span class="info-box-icon bg-purple elevation-1"><i class="material-icons" style="font-size: 50px;">local_hospital</i></span>
    
                          <div class="info-box-content">
                              <span class="info-box-text">SAKIT</span>
                              <span class="info-box-number"><?= $count_sakit;?></span>
                          </div>
                          <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                  </div>
                  <!-- /.col -->
                  <div class="col-lg-3 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="izinModal">
                      <div class="info-box mb-3">
                      <span class="info-box-icon bg-teal elevation-1"><i class="material-icons text-white" style="font-size: 50px;">business_center</i></span>
    
                      <div class="info-box-content">
                          <span class="info-box-text">IZIN</span>
                          <span class="info-box-number"><?= $count_izin;?></span>
                      </div>
                      <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                  </div>
                  <!-- /.col -->
    
                  <!-- fix for small devices only -->
                  <div class="clearfix hidden-md-up"></div>
    
                  <div class="col-lg-3 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="cutiModal">
                      <div class="info-box mb-3">
                      <span class="info-box-icon bg-indigo elevation-1"><i class="material-icons" style="font-size: 50px;">flag</i></span>
    
                      <div class="info-box-content">
                          <span class="info-box-text">CUTI</span>
                          <span class="info-box-number"><?= $count_cuti;?></span>
                      </div>
                      <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                  </div>
                  <!-- /.col -->
                  <a href="<?= base_url();?>Administrator/history_pengunduran_diri" class="col-lg-3 col-sm-6 col-md-3" style="text-decoration: none; color: black;">
                      <div class="info-box mb-3">
                      <span class="info-box-icon bg-danger elevation-1"><i class="material-icons" style="font-size: 50px;">close</i></span>
    
                      <div class="info-box-content">
                          <span class="info-box-text">USER NONAKTIF</span>
                          <span class="info-box-number"><?= $nonaktifUser;?></span>
                      </div>
                      <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                  </a>
                  <!-- /.col -->
              </div>
          <?php } ?>
          <!-- Info boxes -->
          <!-- /.row -->
      </div><!--/. container-fluid -->

      <div class="container-fluid">
          <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="card card-default" style="height: 500px; overflow: auto;">
                      <div class="card-header">
                          <div class="card-title-box">
                              <h3 class="card-title">Pengajuan <i style="font-size: 10px;">(Belum Diproses)</i></h3>
                          </div>
                      </div>
                      <div class="card-body card-body-table">
                          <?= $persetujuan;?>
                      </div>
                  </div>
              </div>
              <div class="col-lg-8 col-md-8 col-sm-8">                            
                  <!-- START PROJECTS BLOCK -->
                  <div class="card card-default" style="height: 500px;">
                      <div class="card-header">
                          <div class="card-title-box">
                              <h3 class="card-title">Statistik Jam Masuk</h3>
                          </div>
                      </div>
                      <div class="card-body card-body-table">
                          <div id="container2"></div>                                    
                      </div>
                  </div>
                  <!-- END PROJECTS BLOCK -->
              </div>
          </div>
      </div>

      <div class="container-fluid">
          <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6">                            
                  <!-- START PROJECTS BLOCK -->
                  <div class="card card-default">
                      <div class="card-header">
                          <div class="card-title-box">
                              <h3 class="card-title">Absensi Berdasarkan Unit Kerja</h3>
                          </div>
                      </div>
                      <div class="card-body card-body-table">
                          <div id="statistikSKPD"></div>                                    
                      </div>
                  </div>
                  <!-- END PROJECTS BLOCK -->
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6">
                  <div class="card card-default">
                      <div class="card-header">
                          <h3 class="card-title">Data Posisi Absen</h3>
                          <div class="float-right" style="width: 200px;">
                              <input type="hidden" id="target" class="form-control"/>
                          </div>                                
                      </div>
                      <div class="card-body card-body-map">
                          <div id="new_google_search_map" style="width: 100%; height: 398px;"></div>
                      </div>
                  </div>
              </div>
          </div> 
      </div>
  </section>
  <!-- /.content -->
</div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- START MODAL -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal-body"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL -->

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&libraries=places"></script>
    <script>
        var path = '<?= base_url(); ?>';

        var status = '<?= $this->input->get("status", true);?>';
        if(status=="Tepat Waktu"){
            $("#status_tepat_waktu").prop('checked', true);
        } else if(status=="Terlambat"){
            $("#status_terlambat").prop('checked', true);
        } else if (status=="Tidak hadir"){
            $("#status_tidak_hadir").prop('checked', true);
        }
        
        document.getElementById("reset").addEventListener("click", function(){
         var checkboxes = document.querySelectorAll("input[type='checkbox']");
        for(var i = 0; i < checkboxes.length; i++){
            checkboxes[i].checked = false;
          }
        });
        
        var jabatan ='<?= $this->input->get("jabatan", true);?>';
        jabatan.split(",").forEach(element => {
            $("#jabatan_"+element).prop('checked', true);
        });
        
        var skpd ='<?= $this->input->get("skpd", true);?>';
        skpd.split(",").forEach(element => {
            $("#unit_kerja_"+element).prop('checked', true);
        });

        $('#tepat_waktu').click(function(){
            var kepala = "Tepat Waktu";
            var url = "Administrator/dashboard/listAbsensi?skpd=<?= $this->input->get("skpd", true);?>&jabatan=<?= $this->input->get("jabatan", true);?>&jenkel=<?= $this->input->get("jenkel", true);?>&status=<?= $this->input->get("status", true);?>&dari=<?= $this->input->get("dari", true);?>&sampai=<?= $this->input->get("sampai", true);?>";
            var status = "Tepat Waktu";
            loadModal(kepala, url, status);
        });

        $('#hadir').click(function(){
            var kepala = "Hadir";
            var url = "Administrator/dashboard/listAbsensi?skpd=<?= $this->input->get("skpd", true);?>&jabatan=<?= $this->input->get("jabatan", true);?>&jenkel=<?= $this->input->get("jenkel", true);?>&status=<?= $this->input->get("status", true);?>&dari=<?= $this->input->get("dari", true);?>&sampai=<?= $this->input->get("sampai", true);?>";
            var status = "Hadir";
            loadModal(kepala, url, status);
        });

        $('#terlambats').click(function(){
            var kepala = "Terlambat";
            var url = "Administrator/dashboard/listAbsensi?skpd=<?= $this->input->get("skpd", true);?>&jabatan=<?= $this->input->get("jabatan", true);?>&jenkel=<?= $this->input->get("jenkel", true);?>&status=<?= $this->input->get("status", true);?>&dari=<?= $this->input->get("dari", true);?>&sampai=<?= $this->input->get("sampai", true);?>";
            var status = "Terlambat";
            loadModal(kepala, url, status);
        });

        $('#tidak_hadirs').click(function(){
            var kepala = "Tidak Hadir";
            var url = "Administrator/dashboard/listAbsensi?skpd=<?= $this->input->get("skpd", true);?>&jabatan=<?= $this->input->get("jabatan", true);?>&jenkel=<?= $this->input->get("jenkel", true);?>&status=<?= $this->input->get("status", true);?>&dari=<?= $this->input->get("dari", true);?>&sampai=<?= $this->input->get("sampai", true);?>";
            var status = "Tidak Hadir";
            loadModal(kepala, url, status);
        });

        $('#sakitModal').click(function(){
            var kepala = "Izin Sakit";
            var url = "Administrator/dashboard/listPengajuan?skpd=<?= $this->input->get("skpd", true);?>&jabatan=<?= $this->input->get("jabatan", true);?>&jenkel=<?= $this->input->get("jenkel", true);?>&status=<?= $this->input->get("status", true);?>&dari=<?= $this->input->get("dari", true);?>&sampai=<?= $this->input->get("sampai", true);?>";
            var status = "sakit";
            loadModal(kepala, url, status);
        });

        $('#izinModal').click(function(){
            var kepala = "Izin Sakit";
            var url = "Administrator/dashboard/listPengajuan?skpd=<?= $this->input->get("skpd", true);?>&jabatan=<?= $this->input->get("jabatan", true);?>&jenkel=<?= $this->input->get("jenkel", true);?>&status=<?= $this->input->get("status", true);?>&dari=<?= $this->input->get("dari", true);?>&sampai=<?= $this->input->get("sampai", true);?>";
            var status = "izin";
            loadModal(kepala, url, status);
        });

        $('#cutiModal').click(function(){
            var kepala = "Izin Sakit";
            var url = "Administrator/dashboard/listPengajuan?skpd=<?= $this->input->get("skpd", true);?>&jabatan=<?= $this->input->get("jabatan", true);?>&jenkel=<?= $this->input->get("jenkel", true);?>&status=<?= $this->input->get("status", true);?>&dari=<?= $this->input->get("dari", true);?>&sampai=<?= $this->input->get("sampai", true);?>";
            var status = "cuti";
            loadModal(kepala, url, status);
        });

        function dataPengajuan(id) {
            var kepala = "Pengajuan Yang Belum Ditindaklanjuti";
            var url = "Administrator/dashboard/pengajuanIzin?skpd=<?= $this->input->get("skpd", true);?>&jabatan=<?= $this->input->get("jabatan", true);?>&jenkel=<?= $this->input->get("jenkel", true);?>&status=<?= $this->input->get("status", true);?>&dari=<?= $this->input->get("dari", true);?>&sampai=<?= $this->input->get("sampai", true);?>";
            var status = id;
            loadModal(kepala, url, status);
        }

        function loadModal(kepala, url, status) {
            var penandaStatus = status;
            $('#modal-title').html(kepala);
            $('#modal-body').html('');

            $.ajax({
                method  : 'POST',
                url     : path+url,
                async   : true,
                data    : {
                    status: status
                },
                success: function(data, status, xhr){
                    if (data) {
                        var recs = JSON.parse(xhr.responseText);
                        var dataList = "";
                        for (var i = 0; i < recs.length; i++) {
                            if (recs[i].id_absensi) {
                                if (Number.isInteger(penandaStatus) || penandaStatus == "sakit" || penandaStatus == "cuti" || penandaStatus == "izin") {
                                    if (penandaStatus == 901) {
                                        var detail = '<td><a href="<?= base_url();?>Administrator/lembur" class="btn btn-secondary btn-sm"><span class="fa fa-search"></span></a></td>';
                                    }else{
                                        var detail = '<td><a href="<?= base_url();?>Administrator/cuti/detail_cuti/'+recs[i].id_absensi+'" class="btn btn-secondary btn-sm"><span class="fa fa-search"></span></a></td>';
                                    }
                                } else {
                                    var detail = '<td><a href="<?= base_url();?>Administrator/data_absensi/detail/'+recs[i].id_absensi+'" class="btn btn-secondary btn-sm"><span class="fa fa-search"></span></a></td>';
                                }
                            }else{
                                var detail = '<td><span class="badge bg-red">Tidak Hadir</span></td>';
                            }
                            dataList += '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+recs[i].nip+'</td>'+
                                '<td>'+recs[i].nama_user+'</td>'+
                                '<td>'+recs[i].nama_unit+'</td>'+
                                detail+
                            '</tr>';
                        }
                        $('#modal-body').append('<div class="table-responsive">'+
                            '<table class="table" id="table2">'+
                                '<thead>'+
                                    '<tr>'+
                                    '<th>#</th>'+
                                    '<th>NIP</th>'+
                                    '<th>Nama Pegawai</th>'+
                                    '<th>Unit Kerja</th>'+
                                    '<th>Detail</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>'+
                                dataList+
                            '</table>'+
                        '</div><script type="text/javascript">$("#table2").DataTable({});<\/script>');
                    }
                }
            });
        }
    </script>

    <script type="text/javascript">
        var table;
        $(document).ready(function() {
            //datatables
            table = $('#table').DataTable({   
                select: true,
            });
            table2 = $('#table2').DataTable({   
                select: true,
            });
            table3 = $('#table3').DataTable({   
                select: true,
            });
            table4 = $('#table4').DataTable({   
                select: true,
            });
        });
    </script>

    <script type="text/javascript">
        var dari = "<?= $this->input->get('dari'); ?>";
        var sampai = "<?= $this->input->get('sampai'); ?>";

        function cb(start, end) {
            if (start._d == "Invalid Date" && end._d == "Invalid Date") {
                $('#dari').val('');
                $('#sampai').val('');
            } else {
                dari = start.format('YYYY-MM-DD');
                sampai = end.format('YYYY-MM-DD');
                $('#dari').val(dari);
                $('#sampai').val(sampai);
            }
        }

        $(document).ready(function(){
            if (dari != "" && sampai != "") {
                var start = moment(dari);
                var end = moment(sampai);            
                $('#reportrange span').html(start.format('D-M-YYYY') + ' sampai ' + end.format('D-M-YYYY'));
            } else {
                var today = moment().format('MM/DD/YYYY');
                var newtoday = moment().subtract(6, 'days');
                newtoday = newtoday.format('MM/DD/YYYY');
                var start = today;
                var end = newtoday;
            }
            $('#reportrange').daterangepicker({
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Data 7 Hari': [moment().subtract(6, 'days'), moment()],
                    'Data 30 Hari': [moment().subtract(29, 'days'), moment()],
                    'Data Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Data Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                "locale": {
                    "direction": "ltr",
                    "format": "MM/DD/YYYY",
                    "separator": " - ",
                    "applyLabel": "Apply",
                    "cancelLabel": "Cancel",
                    "fromLabel": "From",
                    "toLabel": "To",
                    "customRangeLabel": "Custom",
                    "daysOfWeek": [
                        "Su",
                        "Mo",
                        "Tu",
                        "We",
                        "Th",
                        "Fr",
                        "Sa"
                    ],
                    "monthNames": [
                        "Januari",
                        "Februari",
                        "Maret",
                        "April",
                        "Mei",
                        "Juni",
                        "Juli",
                        "Agustus",
                        "September",
                        "Oktober",
                        "November",
                        "Desember"
                    ],
                    "firstDay": 1
                },
                "startDate": start,
                "endDate": end
            }, cb);
        });
    </script>

    <?= $line_chart;?>

    <script>
        var data = <?= $map;?>;
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#formFilter').on('submit',(function(e) {
                var url = "<?= base_url(); ?>";
                // Get SKPD
                var skpd = document.getElementsByName("skpd[]");
                var newSkpd = "";
                for (var i=0, n=skpd.length;i<n;i++) {
                    if (skpd[i].checked) {
                        newSkpd += ","+skpd[i].value;
                    }
                }
                if (newSkpd) newSkpd = newSkpd.substring(1);
                //Get JABATAN
                var jabatan = document.getElementsByName("jabatan[]");
                var newjabatan = "";
                for (var i=0, n=jabatan.length;i<n;i++) {
                    if (jabatan[i].checked) {
                        newjabatan += ","+jabatan[i].value;
                    }
                }
                if (newjabatan) newjabatan = newjabatan.substring(1);
                //Get JENKEL
                var jenkel = document.getElementsByName("jenkel[]");
                var newjenkel = "";
                for (var i=0, n=jenkel.length;i<n;i++) {
                    if (jenkel[i].checked) {
                        newjenkel += ","+jenkel[i].value;
                    }
                }
                if (newjenkel) newjenkel = newjenkel.substring(1);
                //Get STATUS
                var status = document.getElementsByName("status[]");
                var newstatus = "";
                for (var i=0, n=status.length;i<n;i++) {
                    if (status[i].checked) {
                        newstatus += ","+status[i].value;
                    }
                }
                if (newstatus) newstatus = newstatus.substring(1);
                var newDari = document.getElementById("dari").value;
                var newSampai = document.getElementById("sampai").value;
                url += 'Administrator/dashboard'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
                window.location = url;
            }));
            var urlSelect = "<?= base_url('Administrator/dashboard/daftarHadir');?>";
            <?php if ($show == 'true') { ?>
                urlSelect += '?skpd=<?= $this->input->get("skpd");?>&jabatan=<?= $this->input->get("jabatan");?>&jenkel=<?= $this->input->get("jenkel");?>&status=<?= $this->input->get("status");?>';
            <?php } ?>
            setInterval(dataAbsensi, 10000); //300000 MS == 5 minutes
            $.ajax({
                method  : 'POST',
                url     : urlSelect,
                success: function(data, status, xhr) {
                    var result = JSON.parse(xhr.responseText);
                    $('#jumlah_hadir').html(result.jumlah_hadir);
                    $('#tidak_hadir').html(result.tidak_hadir);
                    $('#terlambat').html(result.terlambat);
                },
                error: function(data) {
                }
            });

            function dataAbsensi() {
                $.ajax({
                    method  : 'POST',
                    url     : urlSelect,
                    success: function(data, status, xhr) {
                        var result = JSON.parse(xhr.responseText);
                        $('#jumlah_hadir').html(result.jumlah_hadir);
                        $('#tidak_hadir').html(result.tidak_hadir);
                        $('#terlambat').html(result.terlambat);
                    },
                    error: function(data) {
                    }
                });
            }
        });
    </script>

    <!-- MAPS SCRIPT -->
    <script>
        $(document).ready(function(){
            var cords, marker;
            var markers = [];
            var infowindow = [];

            var map = new google.maps.Map(document.getElementById('new_google_search_map'), {
                zoom: 12,
                center: {
                    lat: -6.893817,
                    lng: 107.609212
                },
                mapTypeId: google.maps.MapTypeId.TERRAIN,
                styles:[{
                    "featureType": "landscape",
                    "stylers": [
                    {
                    "hue": "#FFBB00"
                    },
                    {
                    "saturation": 43.400000000000006
                    },
                    {
                    "lightness": 37.599999999999994
                    },
                    {
                    "gamma": 1
                    }
                ]},{
                    "featureType": "road.highway",
                    "stylers": [{
                        "hue": "#FFC200"
                    },{
                        "saturation": -61.8
                    },{
                        "lightness": 45.599999999999994
                    },{
                        "gamma": 1
                    }]
                },{
                    "featureType": "road.arterial",
                    "stylers": [{
                        "hue": "#FF0300"
                    },{
                        "saturation": -100
                    },{
                        "lightness": 51.19999999999999
                    },{
                        "gamma": 1
                    }]
                },{
                    "featureType": "road.local",
                    "stylers": [{
                        "hue": "#FF0300"
                    },{
                        "saturation": -100
                    },{
                        "lightness": 52
                    },{
                        "gamma": 1
                    }]
                },{
                    "featureType": "water",
                    "stylers": [{
                        "hue": "#0078FF"
                    },{
                        "saturation": -13.200000000000003
                    },{
                        "lightness": 2.4000000000000057
                    },{
                        "gamma": 1
                    }]
                },{
                    "featureType": "poi",
                    "stylers": [{
                        "hue": "#00FF6A"
                    },{
                        "saturation": -1.0989010989011234
                    },{
                        "lightness": 11.200000000000017
                    },{
                        "gamma": 1
                    }]
                }]
            });

            for (var i = 0; i < data.length; i++) {
                var dataPhoto = data[i];
                cords = new google.maps.LatLng(dataPhoto.lat, dataPhoto.lng);
                var marker = new google.maps.Marker({
                    position: cords, 
                    map: map, 
                    title: dataPhoto.nama_user+' ('+dataPhoto.created_history_absensi+')',
                    clickable: true
                });
                markers.push(marker);

                var contentString = '<center><img src="<?= base_url();?>/assets/images/member-photos/default_photo.jpg" style="width:100px"><br><b>NIK : </b>' + dataPhoto.nip + '<br><b>Unit Kerja : </b>' + dataPhoto.nama_unit + '<br><b>Nama : </b> ' + dataPhoto.nama_user + '<br><b>Kedatangan : </b> ' + dataPhoto.waktu_datang + '</center>';

                var j = markers.length - 1;

                infowindow[j] = new google.maps.InfoWindow({
                    content: contentString  
                });

                google.maps.event.addListener(markers[j],'click', (function(marker_window,data_window,info_window){ 
                    return function() {
                        info_window.open(map,marker_window);
                    };
                })(markers[j],dataPhoto.id,infowindow[j]));
            }
        });
    </script>
    <!-- END MAPS SCRIPT -->