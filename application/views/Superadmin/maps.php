<div class="page-content-wrap">
    <div id="map" style="width:100%; height: 607px;"></div>         
</div>

<script>
	var data = <?php echo $map;?>;
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-rB6cp32C9kALtCmg_EFiFpLgKwNjEZs&callback=initMap" async defer></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/maps/usercluster.js"></script>