<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Rekap Absensi <span style="font-size: 12px;">(export data absensi)</span></h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="<?php echo base_url('Administrator/data_absensi/rekap');?>" method="GET" id="getRekap">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="type"><span class="text-danger">*</span> Jenis Kebutuhan Rekap Data</label>
                                            <select name="type" id="type" required class="form-control">
                                                <option value="">-- Pilih jenis rekap data --</option>
                                                <option value="1">Rekap Absensi Harian</option>
                                                <option value="2">Rekap Jumlah Absensi</option>
                                                <option value="3">Rekap Absensi Pertanggal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date"><span class="text-danger">*</span> Mulai Dari</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date"><span class="text-danger">*</span> Sampai</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" required disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </section>
</div>

<script>
    $(document).ready(function() {
        $('#start_date').on('change', function() {
            $('#end_date').prop('disabled', false);
            var input = document.getElementById("end_date");
            input.setAttribute("min", this.value);
        });
    });
</script>