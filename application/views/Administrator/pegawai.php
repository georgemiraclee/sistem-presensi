<style>
  ::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 7px;
  }

  ::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: rgba(0, 0, 0, .5);
    box-shadow: 0 0 1px rgba(255, 255, 255, .5);
  }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Staff</h1>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $tombol;?>
                                    <button class="btn btn-info btn-sm float-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        <span class="fa fa-filter"></span> FILTER
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="collapse" id="collapseExample">
                                        <div class="well" id="accOneColThree">
                                            <form method="post" id="formFilter" action="javascript:void(0);">
                                                <div class="row"> 
                                                    <div class="col-md-4" style="height: 200px; overflow: auto;">
                                                        <h6>Unit Kerja</h6>
                                                        <div style=" overflow: hidden;">
                                                            <?php 
                                                              foreach ($departemen as $key => $value) {
                                                                $nama_unit = (strlen($value->nama_unit) > 20) ? substr($value->nama_unit,0,20).'...' : $value->nama_unit;
                                                            ?>
                                                            <div class="demo-checkbox">
                                                                    <input type="checkbox" id="unit_kerja_<?= $value->id_unit;?>" name="departemen[]" value="<?= $value->id_unit;?>" />
                                                                    <label for="unit_kerja_<?= $value->id_unit;?>"> <?= ucwords($nama_unit);?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>                                               
                                                    <div class="col-md-4" style="height: 200px; overflow: auto;">
                                                        <h6>JABATAN</h6>
                                                        <div style=" overflow: hidden;">
                                                            <?php 
                                                              foreach ($jabatan as $key => $value) {
                                                                $nama_jabatan = (strlen($value->nama_jabatan) > 20) ? substr($value->nama_jabatan,0,20).'...' : $value->nama_jabatan;
                                                            ?>
                                                            <div class="demo-checkbox">
                                                                    <input type="checkbox" id="jabatan_<?= $value->id_jabatan;?>" name="jabatan[]" value="<?= $value->id_jabatan;?>" />
                                                                    <label for="jabatan_<?= $value->id_jabatan;?>"> <?= ucwords($nama_jabatan);?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>TIPE PEGAWAI</h6>
                                                        <div style=" overflow: hidden;">
                                                            <?php foreach ($tipe as $key => $value) {?>
                                                                <div class="demo-checkbox">
                                                                    <input type="checkbox" id="tipe_pegawai_<?= $value->id_status_user;?>" name="tipe[]" value="<?= $value->id_status_user;?>" />
                                                                    <label for="tipe_pegawai_<?= $value->id_status_user;?>"> <?= ucwords($value->nama_status_user);?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary float-right">Filter</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?= $this->session->flashdata('pesan'); ?>
                            <table id="tbl_staff" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama Staff</th>
                                        <th>Jabatan</th>
                                        <th>Unit Kerja</th>
                                        <th>Tipe</th>
                                        <th>Role</th>
                                        <th width="200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    var urlInsert = "<?= base_url('Administrator/jabatan/insert'); ?>";
    var urlUpdate = "<?= base_url('Administrator/jabatan/update'); ?>";
    var urlAktif = "<?= base_url('Administrator/jabatan/update_status'); ?>";
    var urlDelete = "<?= base_url('Administrator/pegawai/delete'); ?>";

    var departemen ='<?= $this->input->get("departemen", true);?>';
    departemen.split(",").forEach(element => {
        $("#unit_kerja_"+element).prop('checked', true);
    });
    
    var jabatan ='<?= $this->input->get("jabatan", true);?>';
    jabatan.split(",").forEach(element => {
        $("#jabatan_"+element).prop('checked', true);
    });
    
    var tipe ='<?= $this->input->get("tipe", true);?>';
    tipe.split(",").forEach(element => {
        $("#tipe_pegawai_"+element).prop('checked', true);
    });

    function limit() {
      swal("Warning!", "Total user melebihi batas, harap kontak kami untuk menambah kapasitas user.", "warning");
    }
    function hapus(id) {
        swal({
            title: "Apakah anda yakin ?",
            text: "Data yang sudah di hapus, tidak dapat di kembalikan lagi.",
            icon: "warning",
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
                        nip: id
                    },
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status) {
                                swal(result.message, {
                                    icon: "success",
                                }).then((acc) => {
                                    location.reload();
                                });
                            } else {
                                swal("Warning!", "Terjadi kesalahan sistem", "warning");
                            }
                        } catch (e) {
                            swal("Warning!", "Terjadi kesalahan sistem", "warning");
                        }
                    },
                    error: function(data) {
                        swal("Warning!", "Terjadi kesalahan sistem", "warning");
                    }
                });
            }
        });
    }

    function is_aktif() {
        var checkedValue = 0; 
        var inputElements = document.getElementById('is_aktif');
        var nip = document.getElementById('is_aktif').value;
        if(inputElements.checked){
            checkedValue = 1;
        }
        $.ajax({
            method  : 'POST',
            url     : urlAktif,
            async   : true,
            data    : {
                is_aktif: checkedValue,
                nip: nip
            },
            success: function(data, status, xhr) {
            },
            error: function(data) {
              swal("Warning!", "Terjadi kesalahan sistem", "warning");
            }
        });
    }
    $(document).ready(function() {
        // $('#example').DataTable();
        $('#add-form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : urlInsert,
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
        $('#update-form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : urlUpdate,
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
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#formFilter').on('submit',(function(e) {
            var url = "<?= base_url(); ?>";
            // Get SKPD
            var skpd = document.getElementsByName("departemen[]");
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
            var status = document.getElementsByName("tipe[]");
            var newstatus = "";
            for (var i=0, n=status.length;i<n;i++) {
                if (status[i].checked) {
                    newstatus += ","+status[i].value;
                }
            }
            if (newstatus) newstatus = newstatus.substring(1);
            //Get STATUS User
            url += 'Administrator/Pegawai'+'?departemen='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&tipe='+newstatus;
            // url2 += 'Administrator/data_absensi/get_data_user'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
             window.location = url;
        }));
       
    });
</script>

<script type="text/javascript">
    var table;
    var newSkpd = "<?= $this->input->get('departemen');?>";
    var newjabatan = "<?= $this->input->get('jabatan');?>";
    var newjenkel = "<?= $this->input->get('jenkel');?>";    
    var newstatus = "<?= $this->input->get('tipe');?>";  
    $(document).ready( function () {
        table = $('#tbl_staff').DataTable({
            "processing": true, 
            "serverSide": true, 
            "order" : [[0,"desc"]],
            "ajax": {
                "url": '<?= site_url('Administrator/pegawai/getData')?>?departemen='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&tipe='+newstatus,
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ],
            "columns" : [
                {"data": "nip"},
                {"data": "nama_user"},
                {"data": "jabatan"},
                {"data": "departemen"},
                {"data": "tipe"},
                {"data": "role"},
                {"data": "aksi"},
            ],
            "lengthMenu": [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            // "dom": 'Bfrtip',
            "responsive": true,
            // "buttons": [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ]
        });
    });
</script>