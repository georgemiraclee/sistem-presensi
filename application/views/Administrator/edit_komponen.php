
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Pengaturan Pengurangan Saldo
                        </h2>
                    </div>
                    <div class="body">
                        <?php foreach ($data_komponen as $key => $value) { $no = $key+1;
                            $cekData = $this->Db_select->select_where('tb_isi_komponen_pendapatan','id_jabatan ='.$jabatan.' and id_komponen_pendapatan ='.$value->id_komponen_pendapatan);
                            if ($cekData != "" && $cekData != null ) {
                                if ($cekData->is_formula == 1) {
                                    $cek = 'checked';
                                    $formula = $cekData->formula;
                                    $nominal = "";
                                    $cssf="";
                                    $cssn='style= "display: none"';
                                    $is_formula = 1;
                                }else{
                                    $cek = '';
                                    $formula = '';
                                    $nominal = $cekData->nominal;
                                    $cssf='style= "display: none"';
                                    $cssn='';
                                    $is_formula = 0;
                                }
                            }else{
                                $cek = '';
                                $formula = '';
                                $nominal = '';
                                $cssf='style= "display: none"';
                                $cssn='';
                                $is_formula = '';
                            }
                            ?>
                            <div class="komponen">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?php echo $no;?>.Konfigurasi komponen Potongan <i><?php echo $value->nama_komponen_pendapatan;?></i><h5>
                                    </div>
                                </div>
                                <div class="body"  style="border: solid 1px ; margin-top: -15px">
                                    <form class="form-horizontal" name="form<?php echo $value->id_komponen_pendapatan;?>" id="add-form<?php echo $value->id_komponen_pendapatan;?>" action="javascript:void(0);" method="post">
                                        <input type="hidden" name="id_jabatan" value="<?php echo $jabatan;?>">
                                        <input type="hidden" name="id_unit" value="<?php echo $id_unit;?>">
                                        <input type="hidden" name="is_formula" value="<?php echo $is_formula;?>" id="is_formula<?php echo $value->id_komponen_pendapatan;?>">
                                        <input type="hidden" name="id_komponen_pendapatan" value="<?php echo $value->id_komponen_pendapatan;?>">
                                        <div class="row clearfix">
                                            <div class="col-lg-2 col-md-1 col-sm-3 col-xs-4 form-control-label">
                                                <label for="email_address_2">Formula :</label>
                                            </div>
                                            <div class="col-lg-10 col-md-11 col-sm-9 col-xs-8">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <div class="switch">
                                                            <label>OFF<input type="checkbox" onclick="myFunction<?php echo $value->id_komponen_pendapatan;?>()" id="cek<?php echo $value->id_komponen_pendapatan;?>" <?php echo $cek;?> ><span class="lever"></span>ON</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix" <?php echo $cssn;?> id="nominal<?php echo $value->id_komponen_pendapatan;?>">
                                            <div class="col-lg-2 col-md-1 col-sm-3 col-xs-4 form-control-label">
                                                <label for="email_address_2">Nominal :</label>
                                            </div>
                                            <div class="col-lg-10 col-md-11 col-sm-9 col-xs-8">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="numer" name="nominal" placeholder="" class="form-control" value="<?php echo $nominal;?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix" id="formula<?php echo $value->id_komponen_pendapatan;?>" <?php echo $cssf;?> >
                                            <div class="col-lg-2 col-md-1 col-sm-3 col-xs-4 form-control-label">
                                                <label for="email_address_2">Isi Formula :</label>
                                            </div>
                                            <div class="col-lg-10 col-md-11 col-sm-9 col-xs-8">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <!-- <textarea class="form-control" name="displayResult"></textarea> -->
                                                        <input type="text" name="displayResult<?php echo $value->id_komponen_pendapatan;?>" value="<?php echo $formula;?>" class="form-control" onkeypress='validate(event)' />
                                                    </div>
                                                </div>
                                                <br>
                                               
                                                           
                                                <div class="col-md-7 icon-button-demo">
                                                     <select class="col-md-7" name="bk<?php echo $value->id_komponen_pendapatan;?>">
                                                                <?php echo $list_kom;?>
                                                            </select>
                                                    <button type="button" class="btn btn-info col-md-4 float-right" onClick="calcNumbers<?php echo $value->id_komponen_pendapatan;?>(bk<?php echo $value->id_komponen_pendapatan;?>.value)" style="margin-top: 10px">Add item</button>
                                                        
                                                </div>
                                                <div class="col-md-5 icon-button-demo" style="margin-top: 8px">
                                                    <input type="button" class="btn btn-info" name="bplus<?php echo $value->id_komponen_pendapatan;?>" value=" + " onClick="calcNumbers<?php echo $value->id_komponen_pendapatan;?>(bplus<?php echo $value->id_komponen_pendapatan;?>.value)">
                                                    <input type="button" class="btn btn-info" name="bmin<?php echo $value->id_komponen_pendapatan;?>" value=" - " onClick="calcNumbers<?php echo $value->id_komponen_pendapatan;?>(bmin<?php echo $value->id_komponen_pendapatan;?>.value)">
                                                    <input type="button" class="btn btn-info" name="beq<?php echo $value->id_komponen_pendapatan;?>" value=" * " onClick="calcNumbers<?php echo $value->id_komponen_pendapatan;?>(beq<?php echo $value->id_komponen_pendapatan;?>.value)">
                                                    <input type="button" class="btn btn-info" name="bper<?php echo $value->id_komponen_pendapatan;?>" value=" / " onClick="calcNumbers<?php echo $value->id_komponen_pendapatan;?>(bper<?php echo $value->id_komponen_pendapatan;?>.value)">
                                                    

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: -10px">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-info float-right">Simpan Data Komponen</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    var urlInsert = "<?php echo base_url('Administrator/pendapatan_departemen/insert_pendapatan'); ?>";
     <?php foreach ($data_komponen as $key => $value) { ?>
        function myFunction<?php echo $value->id_komponen_pendapatan;?>() {
          var checkBox = document.getElementById("cek<?php echo $value->id_komponen_pendapatan;?>");
          var nominal = document.getElementById("nominal<?php echo $value->id_komponen_pendapatan;?>");
          var formula = document.getElementById("formula<?php echo $value->id_komponen_pendapatan;?>");
          var is_formula = document.getElementById("is_formula<?php echo $value->id_komponen_pendapatan;?>");

          if (checkBox.checked == true){
            nominal.style.display = "none";
            formula.style.display = "block";
            is_formula.value = 1;

          } else {
            nominal.style.display = "block";
            formula.style.display = "none";
            is_formula.value = 0;

          }
        }
        function calcNumbers<?php echo $value->id_komponen_pendapatan;?>(result){
            form<?php echo $value->id_komponen_pendapatan;?>.displayResult<?php echo $value->id_komponen_pendapatan;?>.value=form<?php echo $value->id_komponen_pendapatan;?>.displayResult<?php echo $value->id_komponen_pendapatan;?>.value+result;
        }
         $('#add-form<?php echo $value->id_komponen_pendapatan;?>').on('submit',(function(e) {
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
    <?php }?>

       function validate(evt) {
          var theEvent = evt || window.event;

          // Handle paste
          if (theEvent.type === 'paste') {
              key = event.clipboardData.getData('text/plain');
          } else {
          // Handle key press
              var key = theEvent.keyCode || theEvent.which;
              key = String.fromCharCode(key);
          }
          var regex = /[0-9]|\%|\)|\(|\./;
          if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
          }
        } 
    
</script>

