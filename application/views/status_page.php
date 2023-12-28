<!DOCTYPE html>
<html>
<style>
body, html {
  height: 100%;
  margin: 0;
}

.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

.bgimg {
  background-image: url('https://www.w3schools.com/w3images/forestbridge.jpg');
  height: 100%;
  background-position: center;
  background-size: cover;
  position: relative;
  color: white;
  font-family: "Courier New", Courier, monospace;
  font-size: 25px;
}

.topleft {
  position: absolute;
  top: 0;
  left: 16px;
}

.bottomleft {
  position: absolute;
  bottom: 0;
  left: 16px;
}

.middle {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

hr {
  margin: auto;
  width: 40%;
}
</style>
<body>

<div class="bgimg">
  <div class="topleft">
    <img src="https://pressensi.com/assets/landingpage/images/logo2.png" style="max-width: 160px"> 
  </div>
  <div class="middle">
    <h1><?php echo $pesan;?></h1>
    <hr>
    <p><?php echo $message;?></p>
    <a href="<?php echo base_url();?>" class="button">Kembali</a>
  </div>
  <div class="bottomleft">
  </div>
</div>

</body>
</html>