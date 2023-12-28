<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Tipe Reimburse</h1>
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
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addData"><i class="fa fa-plus"></i> Tambah Tipe Reimburse</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover dataTable display" id="table">
                                <thead>
                                    <tr>
                                        <th width="1">No</th>
                                        <th width="400">Nama Tipe</th>
                                        <th width="400">Nominal Maximum</th>
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

<div class="modal fade" id="addData" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addDataLabel">Tambah Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="email_address_2">Tipe Reimburse</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="nama_tipe_reimburse" id= "nama_tipe_reimburse" class="form-control" placeholder="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="email_address_2">Nominal Max</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="maximum_amount" id="inputRupiah" class="form-control" placeholder="" required>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
            <button type="button" id="clearbtn" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                </form>
                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
            </div>
        </div>
    </div>
</div>

<?php foreach ($tipe_reimburse as $key => $value) { 
    $no = $key+1;
?>

<div class="modal fade" id="updateModal<?php echo $value->id_tipe_reimburse;?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Ubah Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="update-form<?php echo $value->id_tipe_reimburse;?>" action="javascript:void(0);" method="post">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="email_address_2">Tipe Reimburse</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="nama_tipe_reimburse" value="<?php echo $value->nama_tipe_reimburse;?>" class="form-control" placeholder="" required>
                                    <input type="hidden" name="id_tipe_reimburse" value="<?php echo $value->id_tipe_reimburse;?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="email_address_2">Max Nominal</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="maximum_amount" value="<?php echo $value->maximum_amount;?>" id="uang" class="form-control" placeholder="" required>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                </form>
                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script>
    var urlDelete = "<?php echo base_url('Administrator/tipe_reimburse/delete'); ?>";
    var urlInsert = "<?php echo base_url('Administrator/tipe_reimburse/insert'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/tipe_reimburse/update'); ?>";
    
   

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
                        id_tipe_reimburse: id
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
    var uang = document.getElementById('uang');
		uang.addEventListener('keyup', function(e){
			// tambahkan 'Rp.' pada saat form di ketik
			// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
			uang.value = formatRupiah(this.value, 'Rp. ');
		});

        function formatRupiah(angka, prefix){
	        var number_string = angka.replace(/[^,\d]/g, '').toString(),
	            split= number_string.split(','),
	            sisa= split[0].length % 3,
	            uang = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
 
	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	        if(ribuan){
		        separator = sisa ? '.' : '';
		        uang += separator + ribuan.join('.');
	        }
 
	        uang = split[1] != undefined ? uang + ',' + split[1] : uang;
	            return prefix == undefined ? uang : (uang ? 'Rp. ' + uang : '');
        }

    $(document).ready(function() {
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

        <?php foreach ($tipe_reimburse as $key => $value) { ?>
            $('#update-form<?php echo $value->id_tipe_reimburse;?>').on('submit',(function(e) {
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
                                    title: "Success",
                                    text: "Data saved successfully",
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
                      swal("Warning!", "Sistem error.", "warning");
                    }
                });
            }));
        <?php } ?>

        $('#table').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": "<?php echo site_url('Administrator/tipe_reimburse/getData')?>",
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            
            "columnDefs": [
                { 
                    "targets": [ 3 ], 
                    "orderable": false, 
                },
            ],
        });
    });
</script>
<script> 
    const inputRupiah = document.getElementById("inputRupiah");
    const nama_tipe_reimburse = document.getElementById("nama_tipe_reimburse");
    const clearbtn = document.getElementById("clearbtn");

    inputRupiah.addEventListener("focus", function () {
        inputRupiah.value = formatRupiah(inputRupiah.value);
    });

    inputRupiah.addEventListener("blur", function () {
      inputRupiah.value = unformatRupiah(inputRupiah.value);
    });

    clearbtn.addEventListener("click", function () {
     inputRupiah.value = "";
     nama_tipe_reimburse.value = "";
    });

    inputRupiah.addEventListener('keyup', function(e){
			// tambahkan 'Rp.' pada saat form di ketik
			// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
			inputRupiah.value = formatRupiah(this.value, 'Rp. ');
		});

        function formatRupiah(angka, prefix){
	        var number_string = angka.replace(/[^,\d]/g, '').toString(),
	            split= number_string.split(','),
	            sisa= split[0].length % 3,
	            inputRupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
 
	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	        if(ribuan){
		        separator = sisa ? '.' : '';
		        inputRupiah += separator + ribuan.join('.');
	        }
 
	        inputRupiah= split[1] != undefined ? inputRupiah + ',' + split[1] : inputRupiah;
	            return prefix == undefined ? inputRupiah : (inputRupiah ? 'Rp. ' + inputRupiah : '');
        }
</script>