<style type="text/css">

    .dt-buttons {

        display: none;

    }

</style>

<section class="content">

        <div class="container-fluid">

            <div class="block-header">

                <h2>Data Remunerasi Pegawai</h2>

            </div>



            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">

                        <?php echo $this->session->flashdata('pesan'); ?>

                        <div class="body">

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">

                                    <thead>

                                        <tr>

                                            <th>Nama Pegawai</th>

                                            <th>Status Karyawan</th>

                                            <th>Bagian</th>

                                            <th>Tanggal Efektif</th>

                                            <th width="40">Aksi</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php foreach ($data_staff as $key => $value) { 

                                            if ($value->is_aktif == 1) {

                                                $value->is_aktif = '<span class="label label-success label-form">Aktif</span>';

                                            }else{

                                                $value->is_aktif = '<span class="label label-danger label-form">Tidak Aktif</span>';

                                            }

                                        ?>

                                            <tr>

                                                <td><?php echo ucwords($value->nama_user);?></td>

                                                <td><?php echo ucwords($value->nama_status_user);?></td>

                                                <td><?php echo ucwords($value->nama_unit);?></td>

                                                <td><?php echo $value->tanggal_efektif;?></td>

                                                <td>

                                                    <button type="button" class="btn btn-danger">Tambah Remunerasi</button>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



<script>

    var urlInsert = "<?php echo base_url('Administrator/jabatan/insert'); ?>";

    var urlUpdate = "<?php echo base_url('Administrator/jabatan/update'); ?>";

    var urlAktif = "<?php echo base_url('Administrator/jabatan/update_status'); ?>";

    var urlDelete = "<?php echo base_url('Administrator/pegawai/delete'); ?>";



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

        $('#example').DataTable();



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