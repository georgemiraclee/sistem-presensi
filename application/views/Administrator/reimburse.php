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
                    <h1 class="m-0 text-dark">Data Reimburse Pegawai</h1>
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
                                <div class="col-12">
                                    <div class="mb-3 float-right">
                                        <button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            <span class="fa fa-filter"></span> FILTER
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row my-3">
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
                                                                $nama_unit = (strlen($value->nama_unit) > 20) ? substr($value->nama_unit, 0, 20) . '...' : $value->nama_unit;
                                                            ?>
                                                                <div class="demo-checkbox">
                                                                    <input type="checkbox" id="unit_kerja_<?php echo $value->id_unit; ?>" name="departemen[]" value="<?php echo $value->id_unit; ?>" />
                                                                    <label for="unit_kerja_<?php echo $value->id_unit; ?>"> <?php echo ucwords($nama_unit); ?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="height: 200px; overflow: auto;">
                                                        <h6>JABATAN</h6>
                                                        <div style=" overflow: hidden;">
                                                            <?php
                                                            foreach ($jabatan as $key => $value) {
                                                                $nama_jabatan = (strlen($value->nama_jabatan) > 20) ? substr($value->nama_jabatan, 0, 20) . '...' : $value->nama_jabatan;
                                                            ?>
                                                                <div class="demo-checkbox">
                                                                    <input type="checkbox" id="jabatan_<?php echo $value->id_jabatan; ?>" name="jabatan[]" value="<?php echo $value->id_jabatan; ?>" />
                                                                    <label for="jabatan_<?php echo $value->id_jabatan; ?>"> <?php echo ucwords($nama_jabatan); ?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>TIPE PEGAWAI</h6>
                                                        <div style=" overflow: hidden;">
                                                            <?php foreach ($tipe as $key => $value) { ?>
                                                                <div class="demo-checkbox">
                                                                    <input type="checkbox" id="tipe_pegawai_<?php echo $value->id_status_user; ?>" name="tipe[]" value="<?php echo $value->id_status_user; ?>" />
                                                                    <label for="tipe_pegawai_<?php echo $value->id_status_user; ?>"> <?php echo ucwords($value->nama_status_user); ?></label>
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
                            <table class="table" id="myTable2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama Staff</th>
                                        <th>Accepted</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th width="120">Aksi</th>
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
    var urlDelete = "<?= base_url('Administrator/jabatan/delete'); ?>";
    var urlInsert = "<?= base_url('Administrator/Reimburse/insert'); ?>";

    var newSkpd = "<?= $this->input->get('departemen'); ?>";
    var newjabatan = "<?= $this->input->get('jabatan'); ?>";
    var newjenkel = "<?= $this->input->get('jenkel'); ?>";
    var newstatus = "<?= $this->input->get('tipe'); ?>";


    var departemen = newSkpd;
    departemen.split(",").forEach(element => {
        $("#unit_kerja_" + element).prop('checked', true);
    });

    var jabatan = newjabatan;
    jabatan.split(",").forEach(element => {
        $("#jabatan_" + element).prop('checked', true);
    });

    var tipe = newstatus;
    tipe.split(",").forEach(element => {
        $("#tipe_pegawai_" + element).prop('checked', true);
    });


    $(document).ready(function() {
        $('#myTable2').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [
                [4, "desc"]
            ],
            "ajax": {
                "url": '<?php echo site_url('Administrator/reimburse/getData') ?>?departemen=' + newSkpd + '&jabatan=' + newjabatan + '&jenkel=' + newjenkel + '&tipe=' + newstatus,
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": 3
                },
                {
                    "orderable": false,
                    "targets": 5
                }
            ],
            "columns": [{
                    "data": "id_reimburse"
                },
                {
                    "data": "nip"
                },
                {
                    "data": "nama_user"
                },
                {
                    "data": "jumlah_acc"
                },
                {
                    "data": "status"
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "aksi"
                },
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            "dom": 'Bfrtip',
            "responsive": true,
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        $('#formFilter').on('submit', (function(e) {
            var url = "<?php echo base_url(); ?>";
            // Get SKPD
            var skpd = document.getElementsByName("departemen[]");
            var newSkpd = "";
            for (var i = 0, n = skpd.length; i < n; i++) {
                if (skpd[i].checked) {
                    newSkpd += "," + skpd[i].value;
                }
            }
            if (newSkpd) newSkpd = newSkpd.substring(1);
            //Get JABATAN
            var jabatan = document.getElementsByName("jabatan[]");
            var newjabatan = "";
            for (var i = 0, n = jabatan.length; i < n; i++) {
                if (jabatan[i].checked) {
                    newjabatan += "," + jabatan[i].value;
                }
            }
            if (newjabatan) newjabatan = newjabatan.substring(1);
            //Get JENKEL
            var jenkel = document.getElementsByName("jenkel[]");
            var newjenkel = "";
            for (var i = 0, n = jenkel.length; i < n; i++) {
                if (jenkel[i].checked) {
                    newjenkel += "," + jenkel[i].value;
                }
            }
            if (newjenkel) newjenkel = newjenkel.substring(1);
            //Get STATUS
            var status = document.getElementsByName("tipe[]");
            var newstatus = "";
            for (var i = 0, n = status.length; i < n; i++) {
                if (status[i].checked) {
                    newstatus += "," + status[i].value;
                }
            }
            if (newstatus) newstatus = newstatus.substring(1);
            //Get STATUS User
            url += 'Administrator/reimburse' + '?departemen=' + newSkpd + '&jabatan=' + newjabatan + '&jenkel=' + newjenkel + '&tipe=' + newstatus;
            // url2 += 'Administrator/data_absensi/get_data_user'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
            window.location = url;
        }));
    });
</script>