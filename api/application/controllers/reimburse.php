<?php

/**
 * 
 */
class reimburse extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
        $this->load->library(array('global_lib','loghistory'));
        $this->global_lib->authentication();
        $this->loghistory = new loghistory;
        $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );
	}

	public function index()
	{
        $this->result = array(
            'status' => false,
            'message' => 'Akses ditolak',
            'data' => array()
        );

		$this->loghistory->createLog($this->result);
        echo json_encode($this->result);
	}

	public function listReimburse()
	{
		$require = array('page');
    $this->global_lib->input($require);

    $page = $this->input->post('page');
    $limit = 15;

    if (is_numeric($page) && $page > 0) {
      $start = ($page - 1) * $limit;
      /* get variable */
      $nip = $this->input->post('nip');

      /* get user */
      $getUser = $this->db_select->select_where('tb_user','nip = "'.$nip.'"');
      
      if ($getUser) {
        /* get data reimburse pegawai */
        $getData = $this->db_select->query_all('select a.id_reimburse, a.`created_at` tanggal, sum(b.nominal) total, ifnull(acc.total_acc, 0) acc, count(b.id_reimburse) jumlah from tb_reimburse a join tb_history_reimburse b on a.`id_reimburse` = b.`id_reimburse` left outer join (select id_history_reimburse, count(*) total_acc from tb_aksi_reimburse where status_reimburse = 1 group by id_history_reimburse) as acc on acc.id_history_reimburse = b.id_history_reimburse where a.`user_id` = "'.$getUser->user_id.'" group by a.`id_reimburse` order by a.`id_reimburse` desc limit '.$start.','.$limit);
        $totalData = $this->db_select->query('select count(*) total from tb_reimburse where user_id = "'.$getUser->user_id.'"');

        if ($getData) {
          $this->result = array(
            'status' => true,
            'message' => 'Data ditemukan',
            'total' => $totalData->total,
            'data' => $getData
          );
        }else{
          $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => array()
          );
        }
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data pengguna tidak ditemukan',
          'data' => array()
        );
      }
    }else{
      $this->result = array(
          'status' => false,
          'message' => 'Data tidak ditemukan',
          'data' => array()
      );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
	}

    public function detailReimburse()
    {
        $require = array('id_reimburse');
        $this->global_lib->input($require);

        /* set data */
        $id_reimburse = $this->input->post('id_reimburse');

        /* get data */
        $getData = $this->db_select->select_all_where('tb_history_reimburse','id_reimburse = "'.$id_reimburse.'"');

        if($getData){
            foreach ($getData as $key => $value) {
                if ($value->file_reimburse != null || $value->file_reimburse != "") {
                    $path = realpath('../assets/images/reimburse/' . $value->file_reimburse);

                    if (file_exists($path)) {
                        $value->file_reimburse = image_url()."images/reimburse/".$value->file_reimburse;
                    }else{
                        $value->file_reimburse = image_url().'images/reimburse/default.jpg';
                    }
                }

                /* cek aksi reimburse */
                $whereAksi['id_history_reimburse'] = $value->id_history_reimburse;
                $getAksi = $this->db_select->select_where('tb_aksi_reimburse', $whereAksi);

                /* set disetujui */
                $value->disetujui = 0;
                if ($getAksi) {
                    $value->disetujui = $getAksi->dibayarkan;
                }
            }

            $this->result = array(
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $getData
            );
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => array()
            );
        }

        $this->loghistory->createLog($this->result);
        echo json_encode($this->result);
    }

    public function claim()
    {
        $this->loghistory->createLog($this->result);
        // $data = $this->imageGet();
        $require = array('data');
        $this->global_lib->input($require);
        
        /* set variable */
        $nip = $this->input->post('nip');
        $data = json_decode($this->input->post('data'));

        $getUser = $this->db_select->select_where('tb_user', 'nip = "'.$nip.'"');
        if ($getUser){
            if(count($data) > 0){
                /* save reimburse */
                $reimburse['user_id'] = $getUser->user_id;
                
                /* id reimburse */
                $idReimburse = $this->db_dml->insert('tb_reimburse', $reimburse);
                foreach ($data as $key => $value) {
                    $hstryReimburse['id_reimburse'] = $idReimburse;
                    $hstryReimburse['id_tipe_reimburse'] = $value->tipe;
                    $hstryReimburse['keterangan_reimburse'] = $value->keterangan;
                    $hstryReimburse['nominal'] = $value->nominal;
                    $hstryReimburse['tanggal_reimburse'] = $value->tanggal;
                    
                    $image = $this->uploadFile($value->bukti, $getUser->user_id);
                    $hstryReimburse['file_reimburse'] = $image;

                    $this->db_dml->insert('tb_history_reimburse', $hstryReimburse);
                }

                $this->result = array(
                    'status' => true,
                    'message' => 'Data berhasil dikirim',
                    'data' => array()
                );
            }else{
                $this->result = array(
                    'status' => false,
                    'message' => 'Data gagal dikirim',
                    'data' => array()
                );
            }
        }else{
            echo 'hae'; exit();
            $this->result = array(
                'status' => false,
                'message' => 'Data pengguna tidak ditemukan',
                'data' => array()
            );
        }

        echo json_encode($this->result);
    }

    public function tipeReimburse()
    {
        /* set variable */
        $nip = $this->input->post('nip');

        /* get data user */
        $getUser = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$nip.'"');

        if($getUser){
            /* get data */
            $data = $this->db_select->select_all_where('tb_tipe_reimburse','id_channel = "'.$getUser->id_channel.'"');

            if($data){
                $this->result = array(
                    'status' => true,
                    'message' => 'Data ditemukan',
                    'data' => $data
                );
            }else{
                $this->result = array(
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                    'data' => array()
                );
            }
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Data pengguna tidak ditemukan',
                'data' => array()
            );
        }

        $this->loghistory->createLog($this->result);
        echo json_encode($this->result);
    }

    public function uploadFile($base64_string, $user_id) {
        $data = explode( ',', $base64_string ); // convert nama base64

        if (count($data) == 1) {
            $encoded_string = $data[0]; // mengambil hasil dari convert
        }else{
            $encoded_string = $data[1]; // mengambil hasil dari convert
        }
        
        $target_dir = '../assets/images/reimburse/'; // add the specific path to save the file
        $decoded_file = base64_decode($encoded_string); // decode the file
        $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
        $extension = $this->mime2ext($mime_type); // extract extension from mime type
        $namefile = $user_id.uniqid().'.'.$extension; // rename file as a unique name
        $file_dir = $target_dir.$namefile;

        try {
            file_put_contents($file_dir, $decoded_file); // save
        } catch (Exception $e) {
        }

        return $namefile;
    }

    public function mime2ext($mime){
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
        "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
        "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
        "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
        "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
        "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
        "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
        "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
        "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
        "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
        "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
        "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
        "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
        "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
        "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
        "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
        "pdf":["application\/pdf","application\/octet-stream"],
        "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
        "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
        "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
        "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
        "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
        "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
        "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
        "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
        "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
        "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
        "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
        "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
        "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
        "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
        "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
        "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
        "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
        "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
        "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
        "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
        "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
        "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
        "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
        "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
        "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
        "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
        "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes,true);
        foreach ($all_mimes as $key => $value) {
            if(array_search($mime,$value) !== false) return $key;
        }
        return false;
    }
}