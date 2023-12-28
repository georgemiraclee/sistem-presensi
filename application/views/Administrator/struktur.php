<link href="<?php echo base_url();?>assets/orgChart/new/jquery.orgchart.css" media="all" rel="stylesheet" type="text/css" />
<style type="text/css">
    #orgChart{
        width: auto;
        height: auto;
    }
    .node{
        min-width: 200px;
        min-height: 70px;
    }
    .org-add-button{
        text-align: center;
        margin-left: 30%;    
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Struktur Organisasi</h1>
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
                            <div id="orgChartContainer">
                                <div id="orgChart"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right">
                                <a href="<?php echo base_url();?>Administrator/struktur" class="btn btn-danger m-t-15"><span class="fa fa-ban"></span> Batal</a>
                                <button type="button" onclick="getData()" class="btn btn-primary m-t-15"><span class="fa fa-save"></span> Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/orgChart/new/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/orgChart/new/jquery.orgchart.js"></script>
<script type="text/javascript">
    var url = '<?php echo base_url();?>Administrator/struktur/insert';
    var testData = <?php echo $dataStruktur->struktur_data;?>;
    $(function(){
        org_chart = $('#orgChart').orgChart({
            data: testData,
            showControls: true,
            allowEdit: true,
            dragAndDrop: true,
            onAddNode: function(node){ 
                org_chart.newNode(node.data.id); 
            },
            onDeleteNode: function(node){
                org_chart.deleteNode(node.data.id); 
            },
            onClickNode: function(node){
            }
        });
    });
    function getData() {
        var data = org_chart.getData(); 
        $.ajax({
            method  : 'POST',
            url     : url,
            async   : true,
            data    : {
                key: data
            },
            success: function(data, status, xhr) {
                try {
                    var result = JSON.parse(xhr.responseText);
                    if (result.status == true) {
                        swal(result.message, {
                            icon: "success",
                        }).then((acc) => {
                            window.location='<?php echo base_url();?>Administrator/struktur';
                        });
                    } else {
                      swal("Warning!", result.message, "warning");
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
</script>