<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Detail Pengajuan Cuti</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <?php echo $this->session->flashdata('pesan'); ?>
                        <div class="body">
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="thumbnail">
                                        <img src="<?php echo $foto;?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <h5>NIP:</h5>
                                    <h5>Nama Pegawai:</h5>
                                    <h5>Unit:</h5>
                                    <h5>Jabatan:</h5>
                                    <h5>Status:</h5>
                                    <h5>Email:</h5>
                                    <h5>Telepon:</h5>
                                    <h5>Alamat:</h5> 
                                </div>
                                <div class="col-md-6">
                                    <?php echo $list;?>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                       <?php echo $cuti;?>
                                    </div>
                                    <div class="col-md-4">
                                      <?php echo $tombol;?>
                                    </div>
                                </div>
                                
                                
                               

                            </div>
                           

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
    var urlACC = "<?php echo base_url('SO/cuti/update_status'); ?>";
    var urlreject = "<?php echo base_url('SO/cuti/update_status2'); ?>";

    function acc(id) {
        var x = document.getElementById("acc"+id);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            $.ajax({
                method  : 'POST',
                url     : urlACC,
                async   : true,
                data    : {
                    id: id
                },
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
            // x.style.display = "none";
        }
    }
    function reject(id) {
        var x = document.getElementById("acc"+id);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            $.ajax({
                method  : 'POST',
                url     : urlreject,
                async   : true,
                data    : {
                    id: id
                },
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
            // x.style.display = "none";
        }
    }

</script>