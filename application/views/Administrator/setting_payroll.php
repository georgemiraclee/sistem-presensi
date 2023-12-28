    <section class="content">
            <div class="container-fluid">
                <!-- Advanced Form Example With Validation -->
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>Pengaturan Potongan BPJS & PPH 21 <?php echo $judul;?></h2>
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
                                <form id="wizard_with_validation2" action="javascript:void(0);" method="POST">
                                    <h3>Upah minimum & Pengali</h3>
                                    <fieldset>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" min="1" onKeyPress="return goodchars(event,'0123456789',this)" class="form-control" name="upah_minimum" value="<?php echo $upah_minimum;?>" id="upah_minimum" required>
                                                <label class="form-label">Upah minimum *(UMR/UMP/UMK)</label>
                                            </div>
                                            <div class="help-info">Masukan nominal tanpa menggunakan tanda baca (Rp/,/./.00)</div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" min="1" onKeyPress="return goodchars(event,'0123456789',this)" class="form-control" name="nilai_pengali" value="<?php echo $nilai_pengali;?>" id="nilai_pengali" required>
                                                <label class="form-label">Nilai maksimum pengali</label>
                                            </div>
                                            <div class="help-info">Masukan nominal tanpa menggunakan tanda baca (Rp/,/./.00)</div>
                                        </div>
                                    </fieldset>
                                    <h3>BPJS Ketenagakerjaan</h3>
                                    <fieldset>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <label class=" form-label">Jaminan Kecelakaan Kerja(JKK) </label>
                                                <br>
                                                <br>
                                                <div class="demo-radio-button form-control">
                                                    <input  type="radio" id="jkk" name="jkk" value="0.24" <?php echo $jkk;?>>
                                                    <label for="jkk">Kelompok I (0,24%)</label>
                                                    <input  type="radio" name="jkk" id="jkk2" value="0.54" <?php echo $jkk2;?>>
                                                    <label for="jkk2">Kelompok II (0,54%)</label>
                                                    <input  type="radio" name="jkk" id="jkk3" value="0.89" <?php echo $jkk3;?>>
                                                    <label for="jkk3">Kelompok III (0,89%)</label>
                                                    <input  type="radio" id="jkk4" name="jkk" value="1.27" <?php echo $jkk4;?>>
                                                    <label for="jkk4">Kelompok IV (1,27%)</label>
                                                    <input  type="radio" id="jkk5" name="jkk" value="1.74" <?php echo $jkk5;?>>
                                                    <label for="jkk5">Kelompok V (1,74%)</label>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onkeypress="return onlyNumbersWithDot(event);" name="jkm" id="jkm" value="<?php echo $jkm;?>" class="form-control" required>
                                                <label class="form-label">Jaminan Kematian(JKM)</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onkeypress="return onlyNumbersWithDot(event);" name="jht" id="jht" value="<?php echo $jht;?>" class="form-control" required>
                                                <label class="form-label">Jaminan Hari Tua(JHT)</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onkeypress="return onlyNumbersWithDot(event);" name="jht_perusahaan" value="<?php echo $jht_perusahaan;?>" id="jht_perusahaan" class="form-control" required>
                                                <label class="form-label">Jaminan Hari Tua(JHT) <small style="color: pink">*Di tanggung Perusahaan</small></label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onkeypress="return onlyNumbersWithDot(event);" name="jp" id="jp" value="<?php echo $jp;?>" class="form-control" required>
                                                <label class="form-label">Jaminan Pensiun(JP)</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onkeypress="return onlyNumbersWithDot(event);" name="jp_perusahaan" value="<?php echo $jp_perusahaan;?>" id="jp_perusahaan" class="form-control" required>
                                                <label class="form-label">Jaminan Pensiun(JP) <small style="color:pink">*Di tanggung perusahaan</small></label>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <h3>BPJS Kesehatan </h3>
                                    <fieldset>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onkeypress="return onlyNumbersWithDot(event);" name="jk" id="jk" value="<?php echo $jk;?>" class="form-control" required>
                                                <label class="form-label">Jaminan Kesehatan(JK)</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onkeypress="return onlyNumbersWithDot(event);" name="jk_perusahaan" value="<?php echo $jk_perusahaan;?>" id="jk_perusahaan" class="form-control" required>
                                                <label class="form-label">Jaminan kesehatan(JK) <small style="color:pink">*Di tanggung perusahaan</small></label>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <h3>PPH 21 </h3>
                                    <fieldset>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <label class=" form-label">Metode Perhitungan </label>
                                                <br>
                                                <br>
                                                <div class="demo-radio-button form-control">
                                                    <input name="group1" type="radio" id="metode_pph" name="metode_pph" <?php echo $metode_pph;?> value="Gross Up Method" >
                                                    <label for="metode_pph">Gross Up Method</label>
                                                    <input name="group1" type="radio" id="metode_pph2" name="metode_pph" <?php echo $metode_pph2;?> value="Gross Method">
                                                    <label for="metode_pph2">Gross Method</label>
                                                    <input name="group1" type="radio" class="with-gap" id="metode_pph3" <?php echo $metode_pph3;?> name="metode_pph" value="Nett Method">
                                                    <label for="metode_pph3">Nett Method</label>                                               

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onKeyPress="return goodchars(event,'0123456789',this)" name="ptkp_pribadi" id="ptkp_pribadi" value="<?php echo $ptkp_pribadi;?>" class="form-control" required>
                                                <label class="form-label">PTKP Diri <small style="color:pink">*Wajib Pajak Orang Pribadi</small></label>
                                            </div>
                                        </div>
                                         <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" onKeyPress="return goodchars(event,'0123456789',this)" name="ptkp_tanggungan" id="ptkp_tanggungan" value="<?php echo $ptkp_tanggungan;?>" class="form-control" required>
                                                <label class="form-label">PTKP Istri <small style="color:pink">*Masing-masing Tanggungan</small></label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Advanced Form Example With Validation -->
            </div>
        </section>
        <!-- Jquery Core Js -->
        <script src="<?php echo base_url();?>assets/admin/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap Core Js -->
        <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap/js/bootstrap.js"></script>
        <!-- Select Plugin Js -->
        <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-select/js/bootstrap-select.js"></script>
        <!-- Slimscroll Plugin Js -->
        <script src="<?php echo base_url();?>assets/admin/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
        <!-- Jquery Validation Plugin Css -->
        <script src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/jquery.validate.js"></script>
        <!-- JQuery Steps Plugin Js -->
        <script src="<?php echo base_url();?>assets/admin/plugins/jquery-steps/jquery.steps.js"></script>
        <!-- Sweet Alert Plugin Js -->
        <script src="<?php echo base_url();?>assets/admin/plugins/sweetalert/sweetalert.min.js"></script>
        <!-- Waves Effect Plugin Js -->
        <script src="<?php echo base_url();?>assets/admin/plugins/node-waves/waves.js"></script>
        <!-- Custom Js -->
        <script src="<?php echo base_url();?>assets/admin/js/admin.js"></script>
        <script src="<?php echo base_url();?>assets/admin/js/pages/forms/form-wizard.js"></script>

        <script type="text/javascript">
            function onlyNumbersWithDot(e) {           
                var charCode;
                if (e.keyCode > 0) {
                    charCode = e.which || e.keyCode;
                }
                else if (typeof (e.charCode) != "undefined") {
                    charCode = e.which || e.keyCode;
                }
                if (charCode == 46)
                    return true
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }
        </script>

        <script language="javascript">
            function getkey(e)
            {
                if (window.event)
                   return window.event.keyCode;
                else if (e)
                   return e.which;
                else
                   return null;
            }
            function goodchars(e, goods, field)
            {
                var key, keychar;
                key = getkey(e);
                if (key == null) return true;
                 
                keychar = String.fromCharCode(key);
                keychar = keychar.toLowerCase();
                goods = goods.toLowerCase();
                 
                // check goodkeys
                if (goods.indexOf(keychar) != -1)
                    return true;
                // control keys
                if ( key==null || key==0 || key==8 || key==9 || key==27 )
                   return true;
                    
                if (key == 13) {
                    var i;
                    for (i = 0; i < field.form.elements.length; i++)
                        if (field == field.form.elements[i])
                            break;
                    i = (i + 1) % field.form.elements.length;
                    field.form.elements[i].focus();
                    return false;
                    };
                // else return false
                return false;
            }
        </script>

        <script type="text/javascript">
            //Advanced form with validation
        var urlInsert = "<?php echo base_url('Administrator/setting_payroll/insert'); ?>";


        var form = $('#wizard_with_validation2').show();
        form.steps({
            headerTag: 'h3',
            bodyTag: 'fieldset',
            transitionEffect: 'slideLeft',
            onInit: function (event, currentIndex) {
                $.AdminBSB.input.activate();
                //Set tab width
                var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
                var tabCount = $tab.length;
                $tab.css('width', (100 / tabCount) + '%');
                //set button waves effect
                setButtonWavesEffect(event);
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                if (currentIndex > newIndex) { return true; }
                if (currentIndex < newIndex) {
                    form.find('.body:eq(' + newIndex + ') label.error').remove();
                    form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                }
                form.validate().settings.ignore = ':disabled,:hidden';
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                setButtonWavesEffect(event);
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ':disabled';
                return form.valid();
            },
            onFinished: function (event, currentIndex) {    
                var upah_minimum = $('#upah_minimum').val();
                var nilai_pengali = $('#nilai_pengali').val();
                var jkm = $('#jkm').val();
                var jht = $('#jht').val();
                var jht_perusahaan = $('#jht_perusahaan').val();
                var jp = $('#jp').val();
                var jp_perusahaan = $('#jp_perusahaan').val();
                var jk = $('#jk').val();
                var jk_perusahaan = $('#jk_perusahaan').val();
                var metode_pph = $('#metode_pph3').val();
                var ptkp_pribadi = $('#ptkp_pribadi').val();
                var ptkp_tanggungan =$('#ptkp_tanggungan').val();
                //cek jkk
                    var jkk = $('#jkk').val();
                    if(document.getElementById('jkk').checked){
                         jkk = $('#jkk').val();
                    }
                    if(document.getElementById('jkk2').checked){
                         jkk = $('#jkk2').val();
                    }
                    if(document.getElementById('jkk3').checked){
                         jkk = $('#jkk3').val();
                    }
                    if(document.getElementById('jkk4').checked){
                         jkk = $('#jkk4').val();
                    }
                    if(document.getElementById('jkk5').checked){
                         jkk = $('#jkk5').val();
                    }
                //cek jkk end
                //cek methode
                    var metode_pph = $('#metode_pph').val();
                    if(document.getElementById('metode_pph').checked){
                         metode_pph = $('#metode_pph').val();
                    }
                    if(document.getElementById('metode_pph2').checked){
                         metode_pph = $('#metode_pph2').val();
                    }
                    if(document.getElementById('metode_pph3').checked){
                         metode_pph = $('#metode_pph3').val();
                    }
                //cek methode end
                $.ajax({
                    method  : 'POST',
                    url     : urlInsert,
                    async   : true,
                    data    : {
                        upah_minimum: upah_minimum,
                        nilai_pengali: nilai_pengali,
                        jkk: jkk,
                        jkm: jkm,
                        jht: jht,
                        jht_perusahaan: jht_perusahaan,
                        jp: jp,
                        jp_perusahaan: jp_perusahaan,
                        jk: jk,
                        jk_perusahaan: jk_perusahaan,
                        metode_pph: metode_pph,
                        ptkp_pribadi: ptkp_pribadi,
                        ptkp_tanggungan: ptkp_tanggungan
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
                      swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                    }
                });
            }
        });
        </script>
        <!-- Demo Js -->
        <script src="<?php echo base_url();?>assets/admin/js/demo.js"></script>
    </body>
    </html>