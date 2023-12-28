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
                                                        <th>Status Kehadiran</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row"><?php echo $tanggal;?></th>
                                                        <td><?php echo $datang;?></td>
                                                        <td><?php echo $istirahat;?></td>
                                                        <td><?php echo $kembali;?></td>
                                                        <td><?php echo $pulang;?></td>
                                                        <td><?php echo $status;?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <p>Lokasi Jaringan saat melakikan absen berada di titik <b class="col-teal"> <?php echo $lat;?>, <?php echo $lng;?></b> dengan nama lokasi <b class="col-teal"><?php echo $lokasi;?></b>. Jaringan yang di gunakan adalah <b class="col-teal"><?php echo $ssid;?></b> (<?php echo $mac;?>).</p>
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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&callback=initMap"></script>
<script>
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
