<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Detail Data Absensi</h1>
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
                            <span class="float-left badge badge-info"><?php echo $manual_absen ? 'Manual Absensi' : '';?></span>
                            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#rekapAbsensiModal"><span class="fa fa-file-export"></span> Rekap Absensi</button>
                        </div>
                        <div class="card-body">
                            <?php echo $this->session->flashdata('pesan'); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="media">
                                        <a href="javascript:void(0);">
                                            <img class="media-object" src="<?php echo $foto;?>" width="160" height="160">
                                        </a>
                                        <div class="media-body ml-3">
                                            <h4><?php echo $nama;?></h4>
                                            <table class="table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Jam Masuk</th>
                                                        <th>Jam Istirahat</th>
                                                        <th>Jam Kembali</th>
                                                        <th>Jam keluar</th>
                                                        <th>Total Jam Kerja</th>
                                                        <th>Status Kehadiran</th>
                                                        <th>Tagging</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row"><?php echo $tanggal;?></th>
                                                        <td><?php echo $datang;?></td>
                                                        <td><?php echo $istirahat;?></td>
                                                        <td><?php echo $kembali;?></td>
                                                        <td><?php echo $pulang;?></td>
                                                        <td><?php echo $total_jam;?></td>
                                                        <td><?php echo $status;?></td>
                                                        <td>
                                                            <?php if ($strStatus != 'Tidak Hadir') { ?>
                                                                <span class="badge badge-info">
                                                                    <?php echo $tagging == 'WFO' ? $tagging." - ".$location_tagging : $tagging;?>
                                                                </span>
                                                            <?php } else { ?>
                                                                -
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <?php 
                                              if ($datang != '-') { 
                                                if ($lokasi) {
                                                  $lokasi = 'dengan nama lokasi '.$lokasi;
                                                } else {
                                                  $lokasi = '';
                                                }
                                                if ($ssid) {
                                                  $jaringan = 'Jaringan yang di gunakan adalah '.$ssid.' ('.$mac.')';
                                                } else {
                                                  $jaringan = '';
                                                }
                                            ?>
                                              <p>Lokasi saat melakikan absen berada di titik 
                                                <b class="col-teal"> <?php echo $lat;?>, <?php echo $lng;?></b> <?php echo $lokasi;?>. <?php echo $jaringan;?>
                                              </p>
                                            <?php }; ?>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="map" style="width:100%;height:300px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="rekapAbsensiModal" tabindex="-1" aria-labelledby="rekapAbsensiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rekapAbsensiModalLabel">Rekap Absensi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo base_url('Administrator/data_absensi/rekap_detail/'.$user_id);?>" id="formInsert" method="GET">
          <div class="modal-body">
            <div class="form-group">
                <label for="start_date"><span class="text-danger">*</span> Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date"><span class="text-danger">*</span> End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" disabled required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal"><span class="fa fa-ban"></span> Close</button>
            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Submit</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&callback=initMap"></script>
<script>
    $(document).ready(function() {
        $('#start_date').on('change', function() {
            $('#end_date').prop('disabled', false);
            var input = document.getElementById("end_date");
            input.setAttribute("min", this.value);
        });
    });

    function initMap() {
        // The location of Uluru
        var uluru = {lat: <?php echo $lat;?>, lng: <?php echo $lng;?>};
        // The map, centered at Uluru
        var map = new google.maps.Map(
            document.getElementById('map'), {zoom: 18, center: uluru});
        // The marker, positioned at Uluru
        var marker = new google.maps.Marker({position: uluru, map: map});
    }
</script>
