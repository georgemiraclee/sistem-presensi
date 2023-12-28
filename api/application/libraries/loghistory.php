<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class loghistory extends CI_Controller
{
	public function createLog($output)
	{
		$input = "";
		$current = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $datestring = "%Y-%m-%d %H:%i:%s";
    $datelog = "%Y-%m-%d";
    $time = time();
    $tgl = mdate($datestring, $time);
    $filelog = mdate($datelog, $time);
    $file = '../loghistory/'.$filelog.'.txt';

    if ($this->agent->is_browser()) {
      $agent = $this->agent->browser().' '.$this->agent->version();
    } else if ($this->agent->is_robot()) {
      $agent = $this->agent->robot();
    } else if ($this->agent->is_mobile()) {
      $agent = $this->agent->mobile();
    } else {
      $agent = 'Unidentified User Agent';
    }

    if ($_POST) {
      $input .= "INPUT POST  : \r\n";

      $p = implode("<br>", array_keys($_POST));
      $variable = explode("<br>", $p);

      foreach ($variable as $key => $value) {
        if (is_array($_POST[$value])) {
          $val = json_encode($_POST[$value]);
        } else {
          $val = $_POST[$value];
        }

        $input .= $value." = ".$val."\r\n";
      }
    }

    if ($_GET) {
      $input .= "INPUT GET  : \r\n";

      $p = implode("<br>", array_keys($_GET));
      $variable = explode("<br>", $p);

      foreach ($variable as $key => $value) {
        $input .= $value." = ".$_GET[$value]."\r\n";
      }
    }

    $data =  "WAKTU : ".$tgl
      ."\r\n".
      "BROWSER : ".$agent." on ".$this->agent->platform()
      ."\r\n".
      "URL : ".$current
      ."\r\n".
      "Prev. URL : ".$this->agent->referrer()
      ."\r\n".
      "IP : ".$this->input->ip_address()
      ."\r\n".
      $input.
      "Output : ". json_encode($output)."\r\n"
      ."==============================================================\r\n";

    // if (read_file($file)) {
    //   file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
    // } else {
    //   write_file($file, $data);
    // }
	}
}