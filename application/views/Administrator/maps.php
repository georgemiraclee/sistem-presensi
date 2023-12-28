<style type="text/css">
  div.absolute {
    position: absolute;
    right: 30px;
    top: 110px;
  } 
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Maps</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div id="map" style="height: 520px;"></div>
      <div class="row">
        <div class="absolute">
          <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#demo">Map Menu</button>
          <div class="row collapse" id="demo" style="right: 15px; position: absolute; width: 200px; height: 100%; background-color: white; padding: 20px; margin-top: 10px">
            <div class="col-md-12">
              <h6>STATUS</h6>
              <div style="overflow: hidden;">
                <div class="demo-checkbox">
                  <input type="checkbox" id="kategori1" onchange="filterKategori(1)" name="status[]"  value="Tepat Waktu"/>
                  <label for="kategori1">Tepat Waktu</label>
                </div>
                <div class="demo-checkbox">
                  <input type="checkbox" id="kategori2" onchange="filterKategori(2)" name="status[]"  value="Terlambat"/>
                  <label for="kategori2">Terlambat</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="<?php echo base_url(); ?>assets/js/maps/usercluster.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&callback=initMap"></script>

<script type="text/javascript">
  
  var data = <?php echo $map;?>;
  var domain = '<?php echo base_url(); ?>';
</script>