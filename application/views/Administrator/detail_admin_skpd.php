<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Detail Admin SKPD</h2>
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
                                     <a href="<?php echo base_url();?>Administrator/admin_skpd/edit/<?php echo $idp;?>" style="color: grey">
                                        <button class="btn btn-block btn-lg btn-primary">Ubah Data</button>
                                     </a>
                                    <!--  <button class="btn btn-block btn-lg btn-warning">Hapus Data</button> -->
                                </div>
                                <div class="col-md-8">
                                    <?php echo $list;?>
                                </div>                           
                                
                               

                            </div>
                           

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
    var urlACC = "<?php echo base_url('Administrator/cuti/update_status'); ?>";
    var urlreject = "<?php echo base_url('Administrator/cuti/update_status2'); ?>";

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