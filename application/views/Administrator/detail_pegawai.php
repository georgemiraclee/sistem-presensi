<style>
  .form-group {
    margin-bottom: 0px !important;
  }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Detail Pegawai</h1>
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
                            <div class="row"> 
                                <div class="col-md-4"> 
                                  <img src="<?php echo $item->foto_user;?>" class="img-fluid img-thumbnail"> 
                                  <a href="<?php echo base_url();?>Administrator/pegawai/edit/<?php echo $item->user_id;?>" class="btn btn-block btn-lg btn-primary mt-3">Ubah Data</a> 
                                </div> 
                                <div class="col-md-8">
                                  <div class="form-group row">
                                    <label for="nip" class="col-sm-3 col-form-label">NIP</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="nip" value="<?php echo $item->nip;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="nama_user" class="col-sm-3 col-form-label">Nama Pegawai</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="nama_user" value="<?php echo $item->nama_user;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="nama_unit" class="col-sm-3 col-form-label">Unit</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="nama_unit" value="<?php echo $item->nama_unit;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="jabatan" value="<?php echo $item->jabatan;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="status_user" class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="status_user" value="<?php echo $item->status_user;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="tanggal_lahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="tanggal_lahir" value="<?php echo date('d M Y', strtotime($item->tanggal_lahir));?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="agama" class="col-sm-3 col-form-label">Agama</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="agama" value="<?php echo $item->agama;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="jenis_kelamin" value="<?php echo $item->jenis_kelamin == 'l' ? "Laki-Laki" : "Perempuan";?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="status_pernikahan" class="col-sm-3 col-form-label">Status Pernikahan</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="status_pernikahan" value="<?php echo ucfirst($item->status_pernikahan);?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="pendidikan_terakhir" class="col-sm-3 col-form-label">Pendidikan Terakhir</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="pendidikan_terakhir" value="<?php echo $item->pendidikan_terakhir;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="email_user" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="email_user" value="<?php echo $item->email_user;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="telp_user" class="col-sm-3 col-form-label">Telepon</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="telp_user" value="<?php echo $item->telp_user;?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="alamat_user" class="col-sm-3 col-form-label">Alamat</label>
                                    <div class="col-sm-1">:</div>
                                    <div class="col-sm-7">
                                      <input type="text" disabled class="form-control-plaintext" id="alamat_user" value="<?php echo $item->alamat_user;?>">
                                    </div>
                                  </div>
                                </div> 
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>