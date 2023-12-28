<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1 class="m-0 text-dark">Detail Data Absensi</h1>
                </div>
                <div class="col-md-6">
                    <a href="<?php echo base_url('Administrator/data_absensi/export_rekap_detail/'.$user_id.'?start_date='.$start_date.'&end_date='.$end_date);?>" target="_blank" class="float-right btn btn-primary"><span class="fa fa-file-excel"></span> Export Excel</a>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        foreach ($list as $key => $value) { ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="media">
                                                <a href="javascript:void(0);">
                                                    <img class="media-object" src="<?php echo $value['foto'];?>" width="160" height="160">
                                                </a>
                                                <div class="media-body ml-3">
                                                    <h4><?php echo $nama;?></h4>
                                                    <table class="table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Tanggal</th>
                                                                <th>Masuk</th>
                                                                <th>Istirahat</th>
                                                                <th>Kembali</th>
                                                                <th>keluar</th>
                                                                <th>Total Jam</th>
                                                                <th>Status Kehadiran</th>
                                                                <th>Tagging</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row"><?php echo $value['tanggal'];?></th>
                                                                <td><?php echo $value['datang'];?></td>
                                                                <td><?php echo $value['istirahat'];?></td>
                                                                <td><?php echo $value['kembali'];?></td>
                                                                <td><?php echo $value['pulang'];?></td>
                                                                <td><?php echo $value['total_jam'] ? $value['total_jam'] : '-';?></td>
                                                                <td><?php echo $value['status'];?></td>
                                                                <td>
                                                                    <?php if ($value['strStatus'] != 'Tidak Hadir') { ?>
                                                                        <span class="badge badge-info">
                                                                            <?php echo $value['tagging'] == 'WFO' ? $value['tagging']." - ".$value['location_tagging'] : $value['tagging'];?>
                                                                        </span>
                                                                    <?php } else { ?>
                                                                        -
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($value['strStatus'] != 'Tidak Hadir') { ?>
                                                                        <button class="btn btn-sm btn-primary" data-toggle="collapse" href="#map-component<?php echo $key;?>" role="button" aria-expanded="false" aria-controls="map-component<?php echo $key;?>"><span class="fa fa-map-marker-alt"></span> Maps</button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse" id="map-component<?php echo $key;?>">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="map<?php echo $key;?>" style="width:100%;height:300px"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&callback=initMap"></script>
<script>
    var allData = <?php echo json_encode($list);?>;
    $(document).ready(function() {
        $('#start_date').on('change', function() {
            $('#end_date').prop('disabled', false);
            var input = document.getElementById("end_date");
            input.setAttribute("min", this.value);
        });
    });

    function initMap() {
        for (let index = 0; index < allData.length; index++) {
            const element = allData[index];
            if (element.lat) {
                // The location of Uluru
                var uluru = {lat: Number(element.lat), lng: Number(element.lng)};
                // The map, centered at Uluru
                var map = new google.maps.Map(document.getElementById('map'+index), {zoom: 18, center: uluru});
                // The marker, positioned at Uluru
                var marker = new google.maps.Marker({position: uluru, map: map});
            }
        }
    }
</script>
