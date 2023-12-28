<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Pengaturan Kebijakan Absensi</h2>       
        </div>
        <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Periode Aktif
                                <small>Menampilkan data Kebijakan Absensi</small>
                            </h2>
                        </div>
                        <div class="body">
                            <h5>1. Jadwal Kerja<h5>
                            <div class="body table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Hari</th>
                                            <th>Jam Kerja</th>
                                            <th>Jam Istirahat</th>
                                        </tr>
                                    </thead>
                                    <form method="post" id="update-form" action="javascript:void(0);">
                                        <input type="hidden" name="dispensasi" value="<?php echo $data->dispensasi;?>">
                                    <tbody class="demo-masked-input" id="tes">
                                        <tr>
                                            <th>Senin</th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="monday_from" class="form-control time24" placeholder="07:30" size="1" value="  <?php echo $data->jam_kerja->monday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="monday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->monday->to; ?>" required>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_monday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->monday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_monday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->monday->to; ?>" required>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Selasa</th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="tuesday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->tuesday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="tuesday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->tuesday->to; ?>" required>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_tuesday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->tuesday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_tuesday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->tuesday->to; ?>" required>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Rabu</th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="wednesday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->wednesday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="wednesday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->wednesday->to; ?>" required>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_wednesday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->wednesday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_wednesday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->wednesday->to; ?>" required>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Kamis</th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="thursday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->thursday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="thursday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->thursday->to; ?>" required>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_thursday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->thursday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_thursday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->thursday->to; ?>" required>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Jumat</th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="friday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->friday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="friday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_kerja->friday->to; ?>" required>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_friday_from" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->friday->from; ?>" required>
                                                </div>
                                                <div class="col-md-1">-</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="new_friday_to" class="form-control time24" placeholder="07:30" size="1" value="<?php echo $data->jam_istirahat->friday->to; ?>" required>
                                                </div>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary" id="button"><span class="fa fa-save"></span> Simpan</button>
                                </form>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>2. Dispensasi Jam Masuk : <span style="color: blue"><?php echo $data->dispensasi; ?> Menit</span></h5>
                               </div>
                               <div class="col-md-6">
                                    <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#smallModal2">Ubah data</button>
                               </div>





                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>3. Kalender</h5>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#smallModal">Tambah Hari Libur</button>
                                </div>
                            </div>
                            <div class="body table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr style="background-color: grey; color: white;">
                                            <th>Tanggal</th>
                                            <th>Nama Event</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="demo-masked-input" id="tes">
                                        <?php foreach ($tanggal as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo date('j F',strtotime($value->tanggal_event));?></td>
                                                <td><?php echo $value->nama_event;?></td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#editModal<?php echo $key;?>"><span class="material-icons col-grey" style="font-size: 20px;">mode_edit</span></a><a href="#" data-toggle="tooltip" data-placement="top"  id="hapus<?php echo $key;?>" title="Hapus Event" data-type="ajax-loader" onclick="hapus(<?php echo $value->id_event;?>)"><span class="material-icons col-grey"  style="font-size: 20px;">delete</span></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>3. Batasan Cuti /Thn : <span style="color: blue"><?php echo $jatah_cuti; ?> Hari</span></h5>
                               </div>
                               <div class="col-md-6">
                                    <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#smallModal3">Ubah data</button>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Tambah Event</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-form" action="javascript:void(0);" method="post">
            <div class="modal-body">
                <div class="form-group form-float form-group-sm">
                    <div class="form-line">
                        <input type="text" class="form-control" name="nama_event">
                        <label class="form-label">Nama Event</label>
                    </div>
                </div>
                <div class="form-group form-float form-group-sm">
                    <div class="form-line">
                        <input type="text" class="new_datepicker form-control" name="tanggal_event">
                        <label class="form-label">Tanggal Event</label>
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
<div class="modal fade" id="smallModal2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Ubah data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="update-form2" action="javascript:void(0);">
            <div class="modal-body">
                <div class="form-group form-float form-group-sm">
                    <div class="form-line">
                        <input type="hidden" name="monday_from" value=" <?php echo $data->jam_kerja->monday->from; ?>">
                        <input type="hidden" name="monday_to" value=" <?php echo $data->jam_kerja->monday->to; ?>">
                        <input type="hidden" name="tuesday_from" value=" <?php echo $data->jam_kerja->tuesday->from; ?>">
                        <input type="hidden" name="tuesday_to" value=" <?php echo $data->jam_kerja->tuesday->to; ?>">
                        <input type="hidden" name="wednesday_from" value=" <?php echo $data->jam_kerja->wednesday->from; ?>">
                        <input type="hidden" name="wednesday_to" value=" <?php echo $data->jam_kerja->wednesday->to; ?>">
                        <input type="hidden" name="thursday_from" value=" <?php echo $data->jam_kerja->thursday->from; ?>">
                        <input type="hidden" name="thursday_to" value=" <?php echo $data->jam_kerja->thursday->to; ?>">
                        <input type="hidden" name="friday_from" value=" <?php echo $data->jam_kerja->friday->from; ?>">
                        <input type="hidden" name="friday_to" value=" <?php echo $data->jam_kerja->friday->to; ?>">
                        <input type="hidden" name="new_monday_from" value=" <?php echo $data->jam_istirahat->monday->from; ?>">
                        <input type="hidden" name="new_monday_to" value=" <?php echo $data->jam_istirahat->monday->to; ?>">
                        <input type="hidden" name="new_tuesday_from" value=" <?php echo $data->jam_istirahat->tuesday->from; ?>">
                        <input type="hidden" name="new_tuesday_to" value=" <?php echo $data->jam_istirahat->tuesday->to; ?>">
                        <input type="hidden" name="new_wednesday_from" value=" <?php echo $data->jam_istirahat->wednesday->from; ?>">
                        <input type="hidden" name="new_wednesday_to" value=" <?php echo $data->jam_istirahat->wednesday->to; ?>">
                        <input type="hidden" name="new_thursday_from" value=" <?php echo $data->jam_istirahat->thursday->from; ?>">
                        <input type="hidden" name="new_thursday_to" value=" <?php echo $data->jam_istirahat->thursday->to; ?>">
                        <input type="hidden" name="new_friday_from" value=" <?php echo $data->jam_istirahat->friday->from; ?>">
                        <input type="hidden" name="new_friday_to" value=" <?php echo $data->jam_istirahat->friday->to; ?>">
                                    <select class="form-control show-tick" name="dispensasi" tabindex="-98">
                                        <option value="0">-- Please select --</option>
                                        <option value="10">10 Menit</option>
                                        <option value="15">15 Menit</option>
                                        <option value="20">20 Menit</option>
                                        <option value="30">30 Menit</option>
                                        <option value="40">40 Menit</option>
                                        <option value="45">45 Menit</option>
                                    </select>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="button"><span class="fa fa-save"></span> Simpan</button>
            </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="smallModal3" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Batasan Cuti</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-form3" action="javascript:void(0);" method="post">
            <div class="modal-body">
                <div class="form-group form-float form-group-sm">
                    <div class="form-line">
                        <input type="number" class="form-control" name="banyak_cuti" min="1">
                        <label class="form-label">Batasan Hari</label>
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
<?php foreach ($tanggal as $key => $value) { ?>
    <div class="modal fade" id="editModal<?php echo $key;?>" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="smallModalLabel">Edit Event</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update-formNew<?php echo $key;?>" action="javascript:void(0);" method="post">
                <div class="modal-body">
                    <div class="form-group form-float form-group-sm">
                        <div class="form-line">
                            <input type="text" class="form-control" name="nama_event" value="<?php echo $value->nama_event;?>">
                            <input type="hidden" class="form-control" name="id_event" value="<?php echo $value->id_event;?>">
                            <label class="form-label">Nama Event</label>
                        </div>
                    </div>
                    <div class="form-group form-float form-group-sm">
                        <div class="form-line">
                            <input type="text" class="new_datepicker form-control" value="<?php echo date('d M Y', strtotime($value->tanggal_event));?>" name="tanggal_event">
                            <label class="form-label">Tanggal Event</label>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#update-form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/kebijakan_absensi/update'); ?>",
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
        <?php foreach ($tanggal as $key => $value) { ?>
            $('#update-formNew<?php echo $key;?>').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : "<?php echo base_url('Administrator/kebijakan_absensi/update_event'); ?>",
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
         $('#update-form2').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : "<?php echo base_url('Administrator/kebijakan_absensi/update'); ?>",
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
        $('#add-form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/kebijakan_absensi/insert_event'); ?>",
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

        $('#add-form3').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/kebijakan_absensi/insert_jatah_cuti'); ?>",
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
    });
 var urlDelete = "<?php echo base_url('Administrator/kebijakan_absensi/delete'); ?>";
    function hapus(id) {
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
                url     : urlDelete,
                async   : true,
                data    : {
                    id_event: id
                },
                success: function(data, status, xhr) {
                  swal(result.message, {
                      icon: "success",
                  }).then((acc) => {
                      location.reload();
                  });
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
          }
      });
    }
</script>
<script>

    $(function () {
        //Textare auto growth
        autosize($('textarea.auto-growth'));
        $('.new_datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            clearButton: true,
            weekStart: 0,
            time: false,
            minDate: moment().startOf('years').format('DD MMMM YYYY'),
            maxDate: moment().endOf('years').format('DD MMMM YYYY')
        });
    });
</script>
