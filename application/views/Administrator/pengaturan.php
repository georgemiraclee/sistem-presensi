<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Pengaturan Aplikasi</h2>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="body">
                            
                                <?php echo $this->session->flashdata('pesan'); ?>
                                <div class="row">
                                     <?php foreach ($data_setting as $key => $value) { ?>
                                    <div class="col-md-4">
                                        <img src="<?php echo base_url();?>assets/images/icon/<?php echo $value->icon;?>" class="img-responsive thumbnail">
                                    </div>
                                    <div class="col-md-8">
                                       

                                        <h4> Nama Aplikasi :</h4>
                                        <h5><?php echo $value->nama_app?></h5>
                                        <hr>
                                        <h4>Deskripsi :</h4>
                                        <h5><?php echo $value->deskripsi?> </h5>
                                        <hr>
                                           <?php
                                             }
                                        ?>
                                          <button class="btn btn-primary"data-toggle="modal" data-target="#defaultModal">Ubah Data</button>
                                    </div> 
                                </div>
                                <!-- Default Size -->
                                    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <form enctype="multipart/form-data" method="POST" action="<?php echo base_url();?>Administrator/pengaturan/update/<?php echo $value->id_setting?>">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <input type="hidden" class="form-control" name="id_setting" value="<?php echo $value->id_setting?>" required>
                                                    <div class="form-group form-float">
                                                        <label class="form-label">Nama Aplikasi</label>
                                                        <div class="form-line">
                                                            <input type="text" class="form-control" name="nama_app" value="<?php echo $value->nama_app?>" required>
                                                        </div>
                                                        <label class="form-label">Deskripsi Aplikasi</label>
                                                        <div class="form-line">
                                                           <textarea rows="4" class="form-control no-resize" name="deskripsi"><?php echo $value->deskripsi?></textarea>
                                                        </div>
                                                         <label class="form-label">Logo Aplikasi</label>
                                                        <div class="form-line">
                                                            <input type="file" name="userfile">
                                                           <i class="col-red">Abaikan jika tidak ingin mengganti logo</i>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                               
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
