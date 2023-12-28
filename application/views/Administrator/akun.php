<section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <img class="thumbnail" width="150px" height="150px" src="<?php echo base_url();?>assets/images/channel/<?php echo $akun->icon_channel;?>">  
                                    </div>
                                    <div class="col-md-10">
                                        <h1 style="margin-top: 0px;"><?php echo ucwords($akun->nama_channel);?>
                                            <a href="<?php echo base_url();?>Administrator/akun/edit" data-toggle="tooltip" data-placement="top" title="Ubah Data">
                                                <span class="material-icons col-grey" style="font-size: 20px;">mode_edit</span>
                                            </a>
                                        </h1><br>
                                        <table>
                                            <tr>
                                                <td>
                                                    <span class="material-icons col-grey" style="font-size: 20px;">map</span>
                                                </td>
                                                <td width="200">
                                                    <?php echo $akun->alamat_channel;?>
                                                </td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    <span class="material-icons col-grey" style="font-size: 20px;">mail_outline</span>
                                                </td>
                                                <td width="200">
                                                    <?php echo $akun->email_channel;?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="material-icons col-grey" style="font-size: 20px;">phone</span>
                                                </td>
                                                <td>
                                                    <?php echo $akun->telp_channel;?>
                                                </td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    <span class="material-icons col-grey" style="font-size: 20px;">language</span>
                                                </td>
                                                <td>
                                                    <?php echo $akun->website_channel;?>
                                                </td>
                                            </tr>
                                        </table>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>