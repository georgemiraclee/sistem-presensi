<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>DATA DETAIL PEMBAYARAN</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu float-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form id="wizard_with_validation" method="POST">
                                <h3>Informasi Akun</h3>
                                <fieldset>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="username" value="<?php echo $nama_user; ?>" readonly>
                                            <label class="form-label">Nama Pengguna *</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="email" value="<?php echo $email_user; ?>" readonly>
                                            <label class="form-label">Email*</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="password" class="form-control" value="<?php echo $telp_user; ?>" name="confirm" readonly>
                                            <label class="form-label">Telepon*</label>
                                        </div>
                                    </div>
                                </fieldset>

                                <h3>Informasi Pembayaran</h3>
                                <fieldset>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" value="<?php echo $id_transaksi; ?>" readonly>
                                            <label class="form-label">ID Transaksi*</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="surname" class="form-control" value="<?php echo $order_id; ?>" readonly>
                                            <label class="form-label">Order ID*</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="Tipe" class="form-control" value="<?php echo $payment_type; ?>" readonly>
                                            <label class="form-label">Tipe pembayaran*</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="Bank" class="form-control" value="<?php echo $bank; ?>" readonly>
                                            <label class="form-label">Bank*</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="Status" class="form-control" value="<?php echo $transaction_status; ?>" readonly>
                                            <label class="form-label">Status Transaksi*</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="Tanggal" class="form-control" value="<?php echo $transaction_time;?>" readonly>
                                            <label class="form-label">Tanggal  transaksi*</label>
                                        </div>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>