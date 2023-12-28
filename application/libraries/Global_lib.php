<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class global_lib
{
	protected $CI;
    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    public function __construct()
    {
            // Assign the CodeIgniter super-object
            $this->CI =& get_instance();
            $this->CI->load->library('session');
    }
    public function send_notification_user($id, $jenis, $message, $id2)
    {
    	$insert['user_id'] = $id;
    	$insert['jenis_notifikasi'] = $jenis;
      $insert['keterangan'] = $message;
      $insert['id'] = $id2;
    	$this->CI->Db_dml->normal_insert('tb_notifikasi', $insert);
    }

    public function getPolaUser($user_id)
    {
        /* get channel */
        $id_channel = $this->CI->Db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where user_id ='.$user_id)->id_channel;

        $tglNow = date('Y-m-d');
        $cekPolaUser = $this->CI->Db_select->query('select *from tb_pola_user where user_id = "'.$user_id.'" and "'.$tglNow.'" between start_pola_kerja and end_pola_kerja');

        if ($cekPolaUser) {
            $getPola = $this->CI->Db_select->select_where('tb_pola_kerja','id_pola_kerja = "'.$cekPolaUser->id_pola_kerja.'"');
            $file = 'appconfig/new/'.$getPola->file_pola_kerja;
        }else{
            $wherea['id_channel'] = $id_channel;
            $wherea['is_default'] = 1;
            $getPola = $this->CI->Db_select->select_where('tb_pola_kerja', $wherea);
            $file = 'appconfig/new/'.$getPola->file_pola_kerja;
        }

        $data['getPola'] = $getPola;
        $data['file'] = $file;
        return $data;
    }

    public function getDispensasi($user_id)
    {
        $tanggalSkrg = mdate("%Y-%m-%d", time());
        $cekDispensasi = $this->CI->Db_select->select_where('tb_dispensasi', 'tanggal_dispensasi = "'.$tanggalSkrg.'" and user_id = "'.$user_id.'"');

        if ($cekDispensasi) {
            return true;
        }else{
            return false;
        }
    }

    public function getStatusAbsensi($getPola, $jadwal)
    {
        if ($getPola->toleransi_keterlambatan == 1) {
            $dispensasiKeterlambatan = $getPola->waktu_toleransi_keterlambatan;
            if ($dispensasiKeterlambatan == null || $dispensasiKeterlambatan == "") {
                $dispensasiKeterlambatan = 0;
            }
        }else{
            $dispensasiKeterlambatan = 0;
        }

        $day = strtolower(date("l"));
        $jadwal = $jadwal->$day;
        $newJamMasuk = $jadwal->from;
        $time = strtotime($newJamMasuk);
        $endTime = date("H:i", strtotime('+'.$dispensasiKeterlambatan.' minutes', $time));
        if (date('H:i', strtotime(mdate("%Y-%m-%d %H:%i:%s", time()))) > $endTime) {
            return 'Terlambat';
        }else{
            return 'Tepat Waktu';
        }
    }

    public function konversi_detik($waktu)
    {
        if(($waktu>0) and ($waktu<60)){
            $lama=number_format($waktu,2)." detik";
            return $lama;
        }
        if(($waktu>60) and ($waktu<3600)){
            $detik=fmod($waktu,60);
            $menit=$waktu-$detik;
            $menit=$menit/60;
            $lama=$menit." Menit";
            return $lama;
        }
        elseif($waktu >3600){
            $detik=fmod($waktu,60);
            $tempmenit=($waktu-$detik)/60;
            $menit=fmod($tempmenit,60);
            $jam=($tempmenit-$menit)/60;    
            $lama=$jam." Jam ".$menit." Menit";
            return $lama;
        }
    }
    public function send_notification($jenis)
    {
        $insert_batch = array();
        $insert['jenis_notifikasi'] = $jenis;
        $getUser = $this->CI->Db_select->select_all_where('tb_user','is_admin = 0');
        foreach ($getUser as $key => $value) {
            $insert['user_id'] = $value->user_id;
            array_push($insert_batch, $insert);
        }
        $this->CI->Db_dml->insert_batch('tb_notifikasi', $insert_batch);
    }
    
  public function send_notification2($jenis, $id_channel, $message, $id)
  {
    $insert_batch = array();
    $getUser = $this->CI->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and b.id_channel = "'.$id_channel.'"');
    
    foreach ($getUser as $value) {
      $insert['user_id'] = $value->user_id;
      $insert['jenis_notifikasi'] = $jenis;
      $insert['id'] = $id;
      $insert['keterangan'] = $message;
      $insert['fcm_sent'] = 0;
      
      array_push($insert_batch, $insert);
    }
    $this->CI->Db_dml->insert_batch('tb_notifikasi', $insert_batch);
  }

    public function sendNotifikasiUser($jenis, $id_user, $message, $id)
    {

        $insert['keterangan'] = $message;
        $insert['id'] = $id;
        $insert['jenis_notifikasi'] = $jenis;
        $insert['user_id'] = $id_user;

        $this->CI->Db_dml->insert('tb_notifikasi', $insert);
    }

    public function sendNotifikasiDepartemen($jenis, $id_unit, $message, $id)
    {
      $getUnit = $this->CI->Db_select->query_all('select *from tb_user where id_unit = "'.$id_unit.'"');
      
      foreach ($getUnit as $value) {
        $insert['jenis_notifikasi'] = $jenis;
        $insert['user_id'] = $value->user_id;
        $insert['keterangan'] = $message;
        $insert['id'] = $id;
  
        $this->CI->Db_dml->insert('tb_notifikasi', $insert);
      }
    }

    public function rupiah($angka){
        $hasil= "Rp". number_format($angka, '2', ',', '.');
        return $hasil;
    }

    public function sendFCMAll($title, $message)
    {
        $getUser = $this->CI->Db_select->select_all_where('tb_user','is_admin = 0');
        if ($getUser) {
            foreach ($getUser as $key => $value) {
                if (!is_null($value->reg_id) && !empty($value->reg_id)) {
                    $fcmMsg = array(
                        'body' => $message,
                        'title' => $title,
                        'sound' => "default",
                        'color' => "#203E78" 
                    );
                    $fcmFields = array(
                        'to' => $value->reg_id,
                        'priority' => 'high',
                        'notification' => $fcmMsg
                    );
                    $headers = array(
                        'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
                    $result = curl_exec($ch );
                    curl_close( $ch );
                }
            }
        }
    }
    
    public function sendFCMAll2($title, $message, $id_channel, $image = "")
    {
        $getUser = $this->CI->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and b.id_channel = "'.$id_channel.'"');
        if ($getUser) {
            foreach ($getUser as $key => $value) {
                if (!is_null($value->reg_id) && !empty($value->reg_id)) {
                    $timestamp = date("Y-m-d H:i:s");
                    $fcmMsg = array(
                        "image" => $image,
                        "timestamp" => $timestamp,
                        "title" => $title,
                        "message" => $message,
                        "is_background" => true
                    );

                    $fcmFields = array(
                        'to' => $value->reg_id,
                        'priority' => 'high',
                        'notification' => $fcmMsg,
                        'data' => $fcmMsg
                    );
                    $headers = array(
                        'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
                    $result = curl_exec($ch );
                    curl_close( $ch );
                }
            }
        }
    }

    public function newFCM($title, $message, $id_channel, $image = "", $kategori, $tipe)
    {
        $getUser = $this->CI->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and b.id_channel = "'.$id_channel.'"');
        if ($getUser) {
            foreach ($getUser as $key => $value) {
                if (!is_null($value->reg_id) && !empty($value->reg_id)) {
                    $timestamp = date("Y-m-d H:i:s");
                    $fcmMsg = array(
                        "image" => $image,
                        "timestamp" => $timestamp,
                        "title" => $title,
                        "message" => $message,
                        "is_background" => true,
                        "kategori" => $kategori,
                        "tipe" => $tipe
                    );

                    $fcmFields = array(
                        'to' => $value->reg_id,
                        'priority' => 'high',
                        'notification' => $fcmMsg,
                        'data' => $fcmMsg
                    );
                    $headers = array(
                        'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
                    $result = curl_exec($ch );
                    curl_close( $ch );
                }
            }
        }
    }

    public function FCMuser($title, $message, $user_id, $image = "", $kategori, $tipe)
    {
        $getUser = $this->CI->Db_select->select_all_where('tb_user','is_admin = 0 and user_id = "'.$user_id.'"');
        if ($getUser) {
            foreach ($getUser as $key => $value) {
                if (!is_null($value->reg_id) && !empty($value->reg_id)) {
                    $timestamp = date("Y-m-d H:i:s");
                    $fcmMsg = array(
                        "image" => $image,
                        "timestamp" => $timestamp,
                        "title" => $title,
                        "message" => $message,
                        "is_background" => true,
                        "kategori" => $kategori,
                        "tipe" => $tipe
                    );

                    $fcmFields = array(
                        'to' => $value->reg_id,
                        'priority' => 'high',
                        'notification' => $fcmMsg,
                        'data' => $fcmMsg
                    );
                    $headers = array(
                        'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
                    $result = curl_exec($ch );
                    curl_close( $ch );
                }
            }
        }
    }

    public function FCMdepartemen($title, $message, $id_unit, $image = "", $kategori, $tipe)
    {
        $getUser = $this->CI->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and b.id_unit = "'.$id_unit.'"');
        if ($getUser) {
            foreach ($getUser as $value) {
                if (!is_null($value->reg_id) && !empty($value->reg_id)) {
                    $timestamp = date("Y-m-d H:i:s");
                    $fcmMsg = array(
                        "image" => $image,
                        "timestamp" => $timestamp,
                        "title" => $title,
                        "message" => $message,
                        "is_background" => true,
                        "kategori" => $kategori,
                        "tipe" => $tipe
                    );

                    $fcmFields = array(
                        'to' => $value->reg_id,
                        'priority' => 'high',
                        'notification' => $fcmMsg,
                        'data' => $fcmMsg
                    );
                    $headers = array(
                        'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
                    $result = curl_exec($ch );
                    curl_close( $ch );
                }
            }
        }
    }
    
    public function sendFCM($title, $message, $user_id, $image = "")
    {
        $getUser = $this->CI->Db_select->select_where('tb_user','is_admin = 0 and user_id = "'.$user_id.'"');
        if ($getUser) {
            if (!is_null($getUser->reg_id) && !empty($getUser->reg_id)) {
                $timestamp = date("Y-m-d H:i:s");

                $fcmMsg = array(
                    "image" => $image,
                    "timestamp" => $timestamp,
                    "title" => $title,
                    "message" => $message,
                    "is_background" => true
                );

                $fcmFields = array(
                    'to' => $getUser->reg_id,
                    'priority' => 'high',
                    'notification' => $fcmMsg,
                    'data' => $fcmMsg
                );
                $headers = array(
                    'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
                $result = curl_exec($ch );
                curl_close( $ch );
            }
        }
    }

    public function NEWsendFCM($title, $message, $user_id, $image = "", $kategori, $tipe)
    {
        $getUser = $this->CI->Db_select->select_all_where('tb_user','is_admin = 0 and user_id = "'.$user_id.'"');
        if ($getUser) {
            foreach ($getUser as $key => $value) {
                if (!is_null($value->reg_id) && !empty($value->reg_id)) {
                    $timestamp = date("Y-m-d H:i:s");

                    $fcmMsg = array(
                        "image" => $image,
                        "timestamp" => $timestamp,
                        "title" => $title,
                        "message" => $message,
                        "is_background" => true,
                        "kategori" => $kategori,
                        "tipe" => $tipe
                    );

                    $fcmFields = array(
                        'to' => $value->reg_id,
                        'priority' => 'high',
                        'notification' => $fcmMsg,
                        'data' => $fcmMsg
                    );
                    $headers = array(
                        'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
                    $result = curl_exec($ch );
                    curl_close( $ch );
                }
            }
        }
    }

    public static function convert($center, $radius, $numberOfSegments = 32)
    {
        $n = $numberOfSegments;
        $flatCoordinates = [];
        $coordinates = [];
        for ($i = 0; $i < $n; $i++) {
            $flatCoordinates = array_merge($flatCoordinates, static::offset($center, $radius, 2 * pi() * $i / $n));
        }
        $flatCoordinates[] = $flatCoordinates[0];
        $flatCoordinates[] = $flatCoordinates[1];
        for ($i = 0, $j = 0; $j < count($flatCoordinates); $j += 2) {
            $coordinates[$i++] = array_slice($flatCoordinates, $j, 2);
        }
        return [
            'type' => 'Polygon',
            'coordinates' => [array_reverse($coordinates)]
        ];
    }
    public static function toRadians($angleInDegrees = null)
    {
        return $angleInDegrees * pi() / 180;
    }
    public static function toDegrees($angleInRadians = null)
    {
        return $angleInRadians * 180 / pi();
    }
    public static function offset($c1, $distance, $bearing)
    {
        $lat1 = static::toRadians($c1[1]);
        $lon1 = static::toRadians($c1[0]);
        $dByR = $distance / 6378137; // distance divided by 6378137 (radius of the earth) wgs84
        $lat = asin(
        sin($lat1) * cos($dByR) +
        cos($lat1) * sin($dByR) * cos($bearing)
        );
        $lon = $lon1 + atan2(
        sin($bearing) * sin($dByR) * cos($lat1),
        cos($dByR) - sin($lat1) * sin($lat)
        );
        return [static::toDegrees($lon), static::toDegrees($lat)];
    }
}