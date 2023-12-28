<style type="text/css">

    .dt-buttons {

        display: none;

    }

</style>

<section class="content">

        <div class="container-fluid">

            <div class="block-header">

                <h2>Data Staff</h2>

            </div>



            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">

                        <div class="header">

                            <div class="row">

                                <div class="col-md-8">

                                    <a href="<?php echo base_url();?>Administrator/pegawai/add" class="btn btn-warning">

                                        <i class="material-icons">add</i>

                                        <span>Tambah Staff</span>

                                    </a>

                                </div>

                                <div class="col-md-3">

                                    <form action="<?php echo base_url();?>Administrator/pegawai/search" method="get">

                                        <div class="form-group float-right">

                                        <div class="form-line">

                                            <input class="form-control" id="src" name="src" placeholder="Nip / Nama Pegawai" type="text" value="" />

                                        </div>

                                       

                                    </div>

                                </div>

                                <div class="col-md-1">

                                    <button id="btn_search" type="submit" class="btn btn-danger" value="Search" />Cari</button>

                                </div>

                                </form>



                            </div>

                        <!-- cari = <?php echo $src;?> -->

                            

                        </div>

                        <?php echo $this->session->flashdata('pesan'); ?>



                        <div class="body">

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped table-hover">

                                    <tr>

                                            <th>No</th>

                                            <th>NIP</th>

                                            <th>Nama Staff</th>

                                            <th>Jabatan</th>

                                            <th>Unit Kerja</th>

                                            <th>Tipe</th>

                                            <th width="120">Aksi</th>

                                    </tr>

                                    <?php
                                         $sess = $this->session->userdata('user');
                                     $id_channel = $sess['id_channel'];
                                        $host = 'localhost'; // Nama hostnya

                                        $username = 'folkatech'; // Username

                                        $password = 'hgTHiC&=+zOy'; // Password (Isi jika menggunakan password)

                                        $database = 'folkatech'; // Nama databasenya



                                        // Koneksi ke MySQL dengan PDO

                                        $pdo = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);

                                        ?>

                                    <?php

                                    // Include / load file koneksi.php

                                   

                                    // Cek apakah terdapat data pada page URL

                                    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;



                                    $limit = 10; // Jumlah data per halamanya



                                    // Buat query untuk menampilkan daa ke berapa yang akan ditampilkan pada tabel yang ada di database

                                    $limit_start = ($page - 1) * $limit;

                                    

                                    $src=$_GET['src'];



                                    // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan

                                    if ($src == null ||$src == '' ) {

                                        $sql = $pdo->prepare("SELECT * FROM  tb_user  j join tb_unit k where j.id_unit = k.id_unit and id_channel = ".$id_channel." LIMIT ".$limit_start.",".$limit);

                                    }else{

                                        $sql = $pdo->prepare("SELECT * FROM  tb_user  j join tb_unit k where j.id_unit = k.id_unit and id_channel = ".$id_channel." and nama_user like '%$src%' or nip like '%$src%' LIMIT ".$limit_start.",".$limit);

                                    } 



                                    // echo json_encode($sql);exit();

                                    

                                    $sql->execute(); // Eksekusi querynya



                                    $no = $limit_start + 1; // Untuk penomoran tabel

                                    while ($data = $sql->fetch()) { // Ambil semua data dari hasil eksekusi $sql

                                        $selectJabatan = $this->Db_select->select_where('tb_jabatan','id_jabatan = '.$data['jabatan']);



                                            if ($selectJabatan) {

                                                $jabatan = $selectJabatan->nama_jabatan;

                                            }else{

                                                $jabatan = "";

                                            }

                                        $selectTipe = $this->Db_select->select_where('tb_status_user','id_status_user = '. $data['status_user']);



                                            if ($selectTipe) {

                                                $status_user = $selectTipe->nama_status_user;

                                            }else{

                                                $status_user = "";

                                            }

                                             $selectUnit = $this->Db_select->select_where('tb_unit','id_unit = '. $data['id_unit']);



                                            if ($selectTipe) {

                                                $unit = $selectUnit->nama_unit;

                                            }else{

                                                $unit = "";

                                            }

                                    ?>

                                        <!-- <?php echo $src; ?> -->

                                        <tr>

                                            <td class="align-middle text-center"><?php echo $no; ?></td>

                                            <td class="align-middle"><?php echo $data['nip']; ?></td>

                                            <td class="align-middle"><?php echo $data['nama_user']; ?></td>

                                            <td class="align-middle"><?php echo $jabatan; ?></td>

                                            <td class="align-middle"><?php echo $unit; ?></td>

                                            <td class="align-middle"><?php echo $status_user; ?></td>

                                            <td>

                                                    <a href="<?php echo base_url();?>Administrator/pegawai/edit/<?php echo $data['nip']; ?>" style="color: grey"><span class="material-icons">mode_edit</span></a>

                                                    <a href="<?php echo base_url();?>Administrator/pegawai/detail/<?php echo $data['nip']; ?>" style="color: grey"><span class="material-icons">visibility</span></a>

                                                        <a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus(<?php echo $data['nip']; ?>)"><span class="material-icons">delete</span></a>

                                                </td>

                                        </tr>

                                    <?php

                                    $no++; // Tambah 1 setiap kali looping

                                    }

                                    ?>

                                </table>

                            </div>

                            <div class="row">

                               <ul class="pagination float-right">

                                <!-- LINK FIRST AND PREV -->

                                <?php

                                if ($page == 1) { // Jika page adalah pake ke 1, maka disable link PREV

                                ?>

                                    <li class="disabled"><a href="#">First</a></li>

                                    <li class="disabled"><a href="#">&laquo;</a></li>

                                <?php

                                } else { // Jika buka page ke 1

                                    $link_prev = ($page > 1) ? $page - 1 : 1;

                                ?>

                                    <li><a href="<?php echo base_url();?>Administrator/pegawai/search?src=<?php echo $_GET['src'];?>&page=1">First</a></li>

                                    <li><a href="<?php echo base_url();?>Administrator/pegawai/search?src=<?php echo $_GET['src'];?>&page=<?php echo $link_prev; ?>">&laquo;</a></li>

                                <?php

                                }

                                ?>



                                <!-- LINK NUMBER -->

                                <?php

                                // Buat query untuk menghitung semua jumlah data

                                $sql2 = $pdo->prepare("SELECT COUNT(*) AS jumlah FROM  tb_user  j join tb_unit k where j.id_unit = k.id_unit and id_channel = ".$id_channel." and nama_user like '%$src%' or nip like '%$src%'");

                                $sql2->execute(); // Eksekusi querynya

                                $get_jumlah = $sql2->fetch();



                                $jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah http_cache_last_modified()anya

                                $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif

                                $start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1; // Untuk awal link member

                                $end_number = ($page < ($jumlah_page - $jumlah_number)) ? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number



                                for ($i = $start_number; $i <= $end_number; $i++) {

                                    $link_active = ($page == $i) ? 'class="active"' : '';

                                ?>

                                    <li <?php echo $link_active; ?>><a href="<?php echo base_url();?>Administrator/pegawai/search?src=<?php echo $_GET['src'];?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>

                                <?php

                                }

                                ?>

                                <!-- LINK NEXT AND LAST -->

                                <?php

                                // Jika page sama dengan jumlah page, maka disable link NEXT nya

                                // Artinya page tersebut adalah page terakhir

                                if ($page == $jumlah_page) { // Jika page terakhir

                                ?>

                                    <li class="disabled"><a href="#">&raquo;</a></li>

                                    <li class="disabled"><a href="#">Last</a></li>

                                <?php

                                } else { // Jika bukan page terakhir

                                    $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;

                                ?>

                                    <li><a href="<?php echo base_url();?>Administrator/pegawai/search?src=<?php echo $_GET['src'];?>&page=<?php echo $link_next; ?>">&raquo;</a></li>

                                    <li><a href="<?php echo base_url();?>Administrator/pegawai/search?src=<?php echo $_GET['src'];?>&page=<?php echo $jumlah_page; ?>">Last</a></li>

                                <?php

                                }

                                ?>

                            </ul> 

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
                swal("Warning!", "Terjadi kesalahan sistem.", "warning");
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