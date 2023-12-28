<style type="text/css">
    .disabledbutton {
        pointer-events: none;
        opacity: 0.4;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Pengaturan Pengurangan Saldo
                        </h2>
                    </div>
                    <div class="body">
                        <div class="tidakHadir">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>1. Konfigurasi Potongan <i>Tidak Hadir</i> ?<h5>
                                </div>
                            </div>
                            <div class="body table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tipe</th>
                                            <th>Besar Potongan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($komponen_tidakhadir as $key => $komponen) { ?>
                                            <tr>
                                                <td><?php echo $komponen->nama_tipe;?></td>
                                                <td><?php echo $komponen->besar_potongan;?>%</td>
                                                <td>
                                                    <a href="#" style="color: grey;" data-toggle="modal" data-target="#updateModal3<?php echo $komponen->id_mangkir;?>" data-placement="top" title="Edit"><i class="material-icons">mode_edit</i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="terlambat">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>2. Konfigurasi Potongan <i>Keterlambatan</i> ?<h5>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#defaultModal2">
                                        <i class="material-icons">add</i>
                                        <span>Tambah Komponen</span>
                                    </button>
                                </div>
                            </div>
                            <div class="body table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Durasi Keterlambatan</th>
                                            <th>Besar Potongan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($komponen_keterlambatan as $key => $komponen2) { ?>
                                            <tr>
                                                <td><?php echo $komponen2->durasi_keterlambatan;?></td>
                                                <td><?php echo $komponen2->potongan_keterlambatan;?>%</td>
                                                <td>
                                                    <a href="#" style="color: grey;" data-toggle="modal" data-target="#updateModal2<?php echo $komponen2->id_keterlambatan;?>" data-placement="top" title="Edit"><i class="material-icons">mode_edit</i></a>
                                                        <a href="#" style="color: grey;" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="hapusKeterlambatan(<?php echo $komponen2->id_keterlambatan;?>)"><i class="material-icons">delete</i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>  
                        </div>
                        <div class="pulangCepat">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>3. Konfigurasi Potongan <i>Pulang cepat atau meninggalkan kantor</i> ?<h5>
                                </div>
                            </div>
                            <div class="body table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tipe</th>
                                            <th>Besar Potongan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($komponen_keluar as $key => $komponen) { ?>
                                            <tr>
                                                <td><?php echo $komponen->nama_tipe;?></td>
                                                <td><?php echo $komponen->besar_potongan;?>%</td>
                                                <td>
                                                    <a href="#" style="color: grey;" data-toggle="modal" data-target="#updateModal4<?php echo $komponen->id_meninggalkan_kantor;?>" data-placement="top" title="Edit"><i class="material-icons">mode_edit</i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="tidakApel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>4. Konfigurasi Potongan <i>Tidak mengikuti upacara hari besar atau apel gabungan</i> ?<h5>
                                </div>
                            </div>
                            <div class="body table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tipe</th>
                                            <th>Besar Potongan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($komponenApel as $key => $komponenApel1) { ?>
                                            <tr>
                                                <td><?php echo $komponenApel1->nama_tipe;?></td>
                                                <td><?php echo $komponenApel1->besar_potongan;?>%</td>
                                                <td>
                                                    <a href="#" style="color: grey;" data-toggle="modal" data-target="#updateModal5<?php echo $key;?>" data-placement="top" title="Edit"><i class="material-icons">mode_edit</i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>  
                        </div>
                        <div id="batalAbsen">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>
                                        5. Konfigurasi Potongan <i>Pembatalan Absensi</i> ?
                                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="font-size: 20px; color: grey;">mode_edit</i></a>
                                    <h5>
                                </div>
                            </div>
                            <div class="body table-responsive">
                                <div class="collapse" id="collapseExample">
                                    <div class="well">
                                        <form class="form-horizontal" action="javascript:void(0);" method="POST" id="form-input">
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <label for="email_address_2">Besar Potongan</label>
                                                    <div class="input-group input-group-sm">
                                                        <div class="form-line">
                                                            <input type="text" name="besar_potonganNew" class="form-control" value="<?php echo $komponenPotongan->besar_potongan;?>">
                                                        </div>
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5">
                                                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                                    <button type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="btn btn-warning"><span class="fa fa-ban"></span> Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div id="batalAbsen">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>
                                        6. Konfigurasi Perhitungan <i>Lembur</i> ?
                                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2"><i class="material-icons" style="font-size: 20px; color: grey;">mode_edit</i></a>
                                    <h5>
                                </div>
                            </div>
                            <div class="body table-responsive">
                                <div class="collapse" id="collapseExample2">
                                    <div class="well">
                                        <form class="form-horizontal" action="javascript:void(0);" method="POST" id="form-input2">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label for="email_address_2">Perhitungan Default</label>
                                                    <div class="input-group input-group-sm">
                                                        <div class="demo-switch">
                                                        <div class="switch">
                                                            <?php 
                                                                if ($komponenLembur->is_custom == 0) {
                                                                    $cek = 'checked=""';
                                                                    $sty = 'style="visibility: hidden;"';
                                                                }else{
                                                                    $cek = '';
                                                                    $sty = 'style="visibility: visible;"';
                                                                }
                                                            ?>
                                                            <label>OFF<input id="swit" onclick="myFunction()" type="checkbox" <?php echo $cek;?> ><span class="lever"></span>ON</label>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <small>* Jika anda mengaktifkan default lembur maka perhitungan lembur akan menggunakan aturan pemerintah sesuai UU</small>
                                                    <input type="hidden" name="is_custom" id="is_custom">

                                                    <div id="nomi" <?php echo $sty;?> >
                                                        <label for="email_address_2">Nominal lembur </label>
                                                        <div class="input-group input-group-sm">
                                                            <div class="form-line">
                                                                <input type="text" id="nominal" name="nominal" class="form-control" value="<?php echo $komponenLembur->nominal;?>">
                                                                
                                                            </div>
                                                            <span class="input-group-addon">/jam</span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-lg-3">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5">
                                                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="defaultModal2" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="add-form1" action="javascript:void(0);" method="post">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                    <label for="email_address_2">Durasi Keterlambatan</label>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-2 col-xs-3">
                                    <div class="input-group input-group-sm">
                                        <div class="form-line demo-masked-input">
                                            <input type="text" name="durasi" class="form-control time" placeholder="07:30" size="1" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                    <label for="email_address_2">Besar Potongan</label>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-2 col-xs-3">
                                    <div class="input-group input-group-sm">
                                        <div class="form-line">
                                            <input type="text" name="potongan" class="form-control">
                                        </div>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach ($komponen_keterlambatan as $key => $valuez) { ?>
            <div class="modal fade" id="updateModal2<?php echo $valuez->id_keterlambatan;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="add-form1<?php echo $key;?>" action="javascript:void(0);" method="post">
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Durasi Keterlambatan</label>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-2 col-xs-3">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line demo-masked-input">
                                                <input type="text" name="durasi" class="form-control time" placeholder="07:30" size="1" value="<?php echo $valuez->durasi_keterlambatan;?>" required>
                                                <input type="hidden" name="id_keterlambatan" value="<?php echo $valuez->id_keterlambatan;?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Besar Potongan</label>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-2 col-xs-3">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="text" name="potongan" class="form-control" value="<?php echo $valuez->potongan_keterlambatan;?>">
                                            </div>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php foreach ($komponen_tidakhadir as $key => $valuez) { ?>
            <div class="modal fade" id="updateModal3<?php echo $valuez->id_mangkir;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="add-form3<?php echo $key;?>" action="javascript:void(0);" method="post">
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Tipe</label>
                                    </div>
                                    <div class="col-lg-6 col-md-7 col-sm-5 col-xs-6">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line demo-masked-input">
                                                <p><?php echo $valuez->nama_tipe;?></p>
                                                <input type="hidden" name="id_mangkir" value="<?php echo $valuez->id_mangkir;?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Besar Potongan</label>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-2 col-xs-3">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="text" name="potongan" class="form-control" value="<?php echo $valuez->besar_potongan;?>">
                                            </div>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php foreach ($komponen_keluar as $key => $valuez) { ?>
            <div class="modal fade" id="updateModal4<?php echo $valuez->id_meninggalkan_kantor;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="add-form4<?php echo $key;?>" action="javascript:void(0);" method="post">
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Tipe</label>
                                    </div>
                                    <div class="col-lg-6 col-md-7 col-sm-5 col-xs-6">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line demo-masked-input">
                                                <p><?php echo $valuez->nama_tipe;?></p>
                                                <input type="hidden" name="id_meninggalkan_kantor" value="<?php echo $valuez->id_meninggalkan_kantor;?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Besar Potongan</label>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-2 col-xs-3">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="text" name="potongan" class="form-control" value="<?php echo $valuez->besar_potongan;?>">
                                            </div>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php foreach ($komponenApel as $key => $valuez) { ?>
            <div class="modal fade" id="updateModal5<?php echo $key;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="add-form6<?php echo $key;?>" action="javascript:void(0);" method="post">
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Tipe</label>
                                    </div>
                                    <div class="col-lg-6 col-md-7 col-sm-5 col-xs-6">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line demo-masked-input">
                                                <p><?php echo $valuez->nama_tipe;?></p>
                                                <input type="hidden" name="id_potongan_apel" value="<?php echo $valuez->id_potongan_apel;?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Besar Potongan</label>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-2 col-xs-3">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="text" name="potongan" class="form-control" value="<?php echo $valuez->besar_potongan;?>">
                                            </div>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<script type="text/javascript">
    function hapusKeterlambatan(id) {
      swal({
          title: "Apakah anda yakin ?",
          text: "Ketika data telah dihapus, tidak bisa dikembalikan lagi!",
          icon: "info",
          buttons: true,
          dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) {
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/potongan_saldo/deleteKeterlambatan'); ?>",
                async   : true,
                data    : {
                    id_keterlambatan: id
                },
                success: function(data, status, xhr) {
                    location.reload();
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                    location.reload();
                }
            });
          }
      });
    }
    $(document).ready(function() {
        $('#form-input').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/potongan_saldo/insertPembatalan'); ?>",
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    try {
                        var result = JSON.parse(xhr.responseText);
                        if (result.status == true) {
                          swal(result.message, {
                            icon: "success",
                          }).then((acc) => {
                            window.location='<?php echo base_url();?>Administrator/akun';
                          });
                        } else {
                            swal("Warning!", result.message, "warning");
                        }
                    } catch (e) {
                      swal("Warning!", "Sistem error.", "warning");
                    }
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));
        $('#form-input2').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/potongan_saldo/insertLembur'); ?>",
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    try {
                        var result = JSON.parse(xhr.responseText);
                        if (result.status == true) {
                          swal(result.message, {
                            icon: "success",
                          }).then((acc) => {
                            window.location='<?php echo base_url();?>Administrator/akun';
                          });
                        } else {
                            swal("Warning!", result.message, "warning");
                        }
                    } catch (e) {
                      swal("Warning!", "Sistem error.", "warning");
                    }
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));
        <?php foreach ($komponen_pendapatan as $key => $value) { ?>
            $('#update-form<?php echo $key;?>').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : "<?php echo base_url('Administrator/potongan_saldo/update'); ?>",
                    data    : formData,
                    contentType: false,
                    processData: false,
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status == true) {
                              swal(result.message, {
                                icon: "success",
                              }).then((acc) => {
                                location.reload();
                              });
                            } else {
                              swal("Warning!", result.message, "warning");
                            }
                        } catch (e) {
                          swal("Warning!", "Sistem error.", "warning");
                        }
                    },
                    error: function(data) {
                      swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                    }
                });
            }));
        <?php } ?>
        $('#add-form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/potongan_saldo/insert'); ?>",
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    try {
                        var result = JSON.parse(xhr.responseText);
                        if (result.status == true) {
                          swal(result.message, {
                            icon: "success",
                          }).then((acc) => {
                            window.location='<?php echo base_url();?>Administrator/akun';
                          });
                        } else {
                            swal("Warning!", result.message, "warning");
                        }
                    } catch (e) {
                      swal("Warning!", "Sistem error.", "warning");
                    }
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));
        $('#add-form1').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/potongan_saldo/insertKeterlambatan'); ?>",
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    try {
                        var result = JSON.parse(xhr.responseText);
                        if (result.status == true) {
                          swal(result.message, {
                            icon: "success",
                          }).then((acc) => {
                            location.reload();
                          });
                        } else {
                            swal("Warning!", result.message, "warning");
                        }
                    } catch (e) {
                      swal("Warning!", "Sistem error.", "warning");
                    }
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));
        <?php foreach ($komponen_keterlambatan as $key => $value) { ?>
            $('#add-form1<?php echo $key;?>').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : "<?php echo base_url('Administrator/potongan_saldo/update2'); ?>",
                    data    : formData,
                    contentType: false,
                    processData: false,
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status == true) {
                              swal(result.message, {
                                icon: "success",
                              }).then((acc) => {
                                location.reload();
                              });
                            } else {
                              swal("Warning!", result.message, "warning");
                            }
                        } catch (e) {
                          swal("Warning!", "Sistem error.", "warning");
                        }
                    },
                    error: function(data) {
                      swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                    }
                });
            }));
        <?php } ?>
        <?php foreach ($komponen_tidakhadir as $key => $value) { ?>
            $('#add-form3<?php echo $key;?>').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : "<?php echo base_url('Administrator/potongan_saldo/update3'); ?>",
                    data    : formData,
                    contentType: false,
                    processData: false,
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status == true) {
                              swal(result.message, {
                                icon: "success",
                              }).then((acc) => {
                                location.reload();
                              });
                            } else {
                              swal("Warning!", result.message, "warning");
                            }
                        } catch (e) {
                          swal("Warning!", "Sistem error.", "warning");
                        }
                    },
                    error: function(data) {
                      swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                    }
                });
            }));
        <?php } ?>
        <?php foreach ($komponen_keluar as $key => $value) { ?>
            $('#add-form4<?php echo $key;?>').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : "<?php echo base_url('Administrator/potongan_saldo/update4'); ?>",
                    data    : formData,
                    contentType: false,
                    processData: false,
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status == true) {
                              swal(result.message, {
                                icon: "success",
                              }).then((acc) => {
                                location.reload();
                              });
                            } else {
                              swal("Warning!", result.message, "warning");
                            }
                        } catch (e) {
                          swal("Warning!", "Sistem error.", "warning");
                        }
                    },
                    error: function(data) {
                      swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                    }
                });
            }));
        <?php } ?>
        <?php foreach ($komponenApel as $key => $value) { ?>
            $('#add-form6<?php echo $key;?>').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : "<?php echo base_url('Administrator/potongan_saldo/update6'); ?>",
                    data    : formData,
                    contentType: false,
                    processData: false,
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status == true) {
                              swal(result.message, {
                                icon: "success",
                              }).then((acc) => {
                                location.reload();
                              });
                            } else {
                              swal("Warning!", result.message, "warning");
                            }
                        } catch (e) {
                          swal("Warning!", "Sistem error.", "warning");
                        }
                    },
                    error: function(data) {
                      swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                    }
                });
            }));
        <?php } ?>
    });
</script>
<script>
function myFunction() {
  var checkBox = document.getElementById("swit");
  var text = document.getElementById("nomi");
  var cs = document.getElementById("is_custom");
  var nm = document.getElementById("nominal");

  if (checkBox.checked == true){
    text.style.visibility = "hidden";
    cs.value = "0";
    nm.value = "0";

  } else {
     text.style.visibility = "visible";
    cs.value = "1";
  }
}
</script>