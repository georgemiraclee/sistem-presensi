<?php defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class kinerja_pegawai extends CI_Controller
{
  private $file = 'appconfig/auto_respon.txt';

  function __construct()
  {
    parent::__construct();
    $this->load->library('ceksession');
    $this->ceksession->login();
  }

  public function index()
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());
      exit();
    }

    $search = $this->input->get('search', true);
    $query = '';
    if ($this->input->get('departemen', true)) {
      $query .= " AND a.id_unit IN(" . $this->input->get('departemen', true) . ")";
    }
    if ($this->input->get('jabatan', true)) {
      $query .= " AND a.jabatan IN(" . $this->input->get('jabatan', true) . ")";
    }
    if ($this->input->get('tipe', true)) {
      $query .= " AND a.status_user IN(" . $this->input->get('tipe', true) . ")";
    }
    if ($this->input->get('jenkel', true)) {
      $jenkel = explode(',', $this->input->get('jenkel', true));
      $jenkelStr = "";
      foreach ($jenkel as $valueJenkel) {
        $jenkelStr .= ",'$valueJenkel'";
      }
      $jenkel = substr($jenkelStr, 1);
      $query .= " AND a.jenis_kelamin IN(" . $jenkel . ")";
    }

    if ($search) {
      $query .= ' AND (a.nama_user LIKE "%' . $search . '%" OR a.nip LIKE "%' . $search . '%" OR c.nama_jabatan LIKE "%' . $search . '%")';
    }

    $user = $this->Db_select->query_all('SELECT a.user_id, a.jabatan, a.nama_user, a.foto_user, a.jabatan, a.nip FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit JOIN tb_jabatan c ON a.jabatan = c.id_jabatan WHERE b.id_channel = ?' . $query . ' AND a.is_aktif = ? AND a.is_deleted = ?', [$id_channel, 1, 0]);

    $data['list'] = '';
    if ($user) {
      foreach ($user as $value) {
        if ($value->jabatan != null || $value->jabatan != '') {
          $ja = $this->Db_select->select_where('tb_jabatan', 'id_jabatan =' . $value->jabatan);
          $jb = $ja->nama_jabatan;

          if (strlen($jb) > 15) {
            $jb = substr($jb, 0, 15) . '...';
          }
        } else {
          $jb = "-";
        }

        if (strlen($value->nama_user) > 15) {
          $nama_user = substr($value->nama_user, 0, 15) . '...';
        } else {
          $nama_user = $value->nama_user;
        }

        if ($value->foto_user == "" || $value->foto_user == null) {
          $value->foto_user = "default_photo.jpg";
        }

        $filename = 'assets/images/member-photos/' . $value->foto_user;

        if (file_exists($filename)) {
          $value->foto_user = $value->foto_user;
        } else {
          $value->foto_user = "default_photo.jpg";
        }

        $data['list'] .= '
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-body" style=" padding: 17px 20px 1px 20px;">
                  <div class="media">
                    <div class="media-left">
                      <a href="javascript:void(0);">
                        <img class="media-object" src="' . base_url() . 'assets/images/member-photos/' . $value->foto_user . '" width="64" height="64" style="border-radius: 50%;">
                      </a>
                    </div>
                    <div class="media-body">
                      <a href="' . base_url() . 'Administrator/kinerja_pegawai/statistic/' . $value->user_id . '">
                        <h3 class="ml-2">' . $nama_user . '</h3>
                      </a>            
                      <span class="ml-2">' . $jb . '</span><br> 
                      <small class="ml-2">' . $value->nip . '</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          ';
      }
    } else {
      $data['list'] = '<div class="col-12 text-center"><h3 class="text-secondary">~ Empty ~</h3></div>';
    }

    $data['departemen'] = $this->Db_select->query_all("SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ?", [$id_channel, 1, 0]);
    $data['jabatan'] = $this->Db_select->query_all("SELECT id_jabatan, nama_jabatan FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC", [$id_channel, 1, 0]);
    $data['tipe'] = $this->Db_select->query_all("SELECT id_status_user, nama_status_user FROM tb_status_user WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ?", [$id_channel, 1, 0]);
    $menu['main'] = 'personalia';
    $menu['child'] = 'personalia_kinerja';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/kinerja_pegawai');
    $this->load->view('Administrator/footer');
  }

  public function statistic($id)
  {
    $sess = $this->session->userdata('user');
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());
      exit();
    }
    $file = 'appconfig/' . $sess['id_channel'] . '_auto_respon.txt';
    $parrent = $id;
    $data['id'] = $parrent;
    
    $user = $this->Db_select->select_where('tb_user', ['user_id' => $parrent], ['nama_user', 'foto_user', 'nip']);
    
    $data['nama_user'] = $user->nama_user;
    $data['foto_user'] = $user->foto_user;
    $data['nip'] = $user->nip;
    $select_kehadiran = $this->Db_select->query_all("SELECT waktu_datang, status_absensi, created_absensi FROM tb_absensi WHERE user_id = ?", [$parrent]);
    $data['stamp'] = "";

    foreach ($select_kehadiran as $key => $value) {
      $jadwal = json_decode(file_get_contents($file))->jam_kerja;
      $day = strtolower(date("l"));
      $jadwalNew = date_create($jadwal->$day->from);
      $jam_skrg = date_create(date("H:i", strtotime($value->waktu_datang)));
      $diff  = date_diff($jam_skrg, $jadwalNew);
      $keteranganTerlambat = "";
      if ($value->status_absensi == "Terlambat") {
        if ($diff->h != 0) {
          $keteranganTerlambat .= $diff->h . " Jam ";
        }
        if ($diff->i != 0) {
          $keteranganTerlambat .= $diff->i . " Menit ";
        }
      }
      if ($value->status_absensi == "Terlambat") {
        $warna = '#f4bc42';
        $ket = $keteranganTerlambat;
      }
      if ($value->status_absensi == "Tidak Hadir") {
        $warna = '#f44156';
        $ket = "";
      }
      if ($value->status_absensi == "Tepat Waktu") {
        $warna = '#41f48e';
        $ket = "";
      }
      $data['stamp'] .= " { title :'" . $value->status_absensi . " " . $ket . "',start: '" . date('Y-m-d', strtotime($value->created_absensi)) . "',backgroundColor:'" . $warna . "',borderColor: '#fff' , height:'300' }, ";
    }

    $filter = array();
    $query = "";
    $query2 = "";
    if ($this->input->get('dari', true)) {
      $filter['dari'] = $this->input->get('dari', true);
      $query .= " and date(created_absensi)  >= '" . $this->input->get('dari', true) . "'";
      $query2 .= " and date(created_at)  >= '" . $this->input->get('dari', true) . "'";
    } else {
      $query .= " AND created_absensi > '2018-08-04 13:16:29' ";
      $query2 .= " AND created_at > '2018-08-04 13:16:29' ";
    }

    if ($this->input->get('sampai', true)) {
      $filter['sampai'] = $this->input->get('sampai', true);
      $query .= " and date(created_absensi) <= '" . $this->input->get('sampai', true) . "' ";
      $query2 .= " and date(created_at) <= '" . $this->input->get('sampai', true) . "' ";
    }
    
    //statistic
    $select_kehadiran_2 = $this->Db_select->query_all("SELECT waktu_datang, waktu_pulang, created_absensi, status_absensi FROM tb_absensi WHERE user_id = ? $query", [$parrent]);
    $tw = $this->Db_select->count_all_where('tb_absensi', 'user_id = ' . $parrent . '' . $query . 'and status_absensi = "Tepat Waktu"');
    $tl = $this->Db_select->count_all_where('tb_absensi', 'user_id = ' . $parrent . '' . $query . 'and status_absensi = "Terlambat"');
    $al = $this->Db_select->count_all_where('tb_absensi', 'user_id = ' . $parrent . '' . $query . 'and status_absensi = "Tidak Hadir"');
    $ijin = $this->Db_select->count_all_where('tb_pengajuan', 'user_id = ' . $parrent . '' . $query2 . 'and status_approval = "1"');
    $th = $al - $ijin;
    $all = $tw + $tl + ($al - $ijin);
    $twp = @($tw / $all * 100);
    $tlp = @($tl / $all * 100);
    $thp = @($th / $all * 100);
    $ip = @($ijin / $all * 100);

    $data['tepat'] = $tw . "(" . number_format((float)$twp, 1, '.', '') . "%)";
    $data['telat'] = $tl . "(" . number_format((float)$tlp, 1, '.', '') . "%)";
    $data['alpa'] = $th . "(" . number_format((float)$thp, 1, '.', '') . "%)";
    $data['ijin'] = $ijin . "(" . number_format((float)$ip, 1, '.', '') . "%)";

    if ($twp > 70) {
      $data['sts'] = '<span class="badge bg-green">Memuaskan</span>';
    } else {
      $data['sts'] = '<span class="badge bg-red">Harus Ditingkatkan</span>';
    }

    $data['crt'] = "";

    $dt = array();
    foreach ($select_kehadiran_2 as $key => $value) {

      $date = date('Y-m-d', strtotime($value->created_absensi));

      if ($value->status_absensi == "Tidak Hadir") {
        $jaam  = 0;
      } else {
        if ($value->waktu_datang != "" || $value->waktu_datang != null && $value->waktu_pulang != "" || $value->waktu_pulang != null) {
          $first = date_create($value->waktu_datang);
          $second = date_create($value->waktu_pulang);
          // $diff = $first->date_diff( $second );
          $diff  = date_diff($first, $second);

          $jaam = $diff->format('%H.%I.%S');
        } else {
          $jaam  = 0;
        }
      }

      array_push($dt, $jaam);

      $data['crt'] .= "{

            period: '" . $date . "',

            Jam_kerja: '" . $jaam . "'

        },";
    }

    //pengajuan & cuti
    $data_pengajuan = $this->Db_select->query_all("SELECT status_approval, status_user, tanggal_awal_pengajuan, tanggal_akhir_pengajuan, keterangan_pengajuan, a.created_at FROM tb_pengajuan a JOIN tb_user b ON a.user_id = b.user_id JOIN tb_unit c ON b.id_unit = c.id_unit JOIN tb_status_pengajuan d ON a.status_pengajuan = d.id_status_pengajuan WHERE b.user_id = ?", [$parrent]);
    
    $data_cuti = $this->Db_select->select_where('tb_user', ['user_id' => $parrent], ['cuti']);
    $data['cuti'] = $data_cuti->cuti;
    $data['list'] = "";

    foreach ($data_pengajuan as $key => $value) {
      if ($value->status_approval == 1) {

        $value->status_approval = '<span class="badge bg-green">Approval</span>';
      } else if ($value->status_approval == 2) {

        $value->status_approval = '<span class="badge bg-red">Rejected</span>';
      } else {

        $value->status_approval = '<span class="badge badge-warning text-white">Pending</span>';
      }
      $data_status = $this->Db_select->query("SELECT nama_status_user FROM tb_status_user WHERE id_status_user = ?", [$value->status_user]);

      $status = $data_status->nama_status_user;

      $data['list'] .= "
            <tr>
                <td>" . date($value->created_at) . "</td>
                <td>" . $status . "</td>
                <td>" . date('d-m-Y', strtotime($value->tanggal_awal_pengajuan)) . " s/d " . date('d-m-Y', strtotime($value->tanggal_akhir_pengajuan)) . "</td>
                <td>" . $value->keterangan_pengajuan . "</td>
                <td>" . $value->status_approval . "</td>
            </tr>
        ";
    }

    $avg = @(array_sum($dt) / count($dt));
    $data['rata'] = number_format((float)$avg, 1, '.', '');
    $menu['main'] = 'personalia';
    $menu['child'] = 'personalia_kinerja';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/statistic_kinerja');
    $this->load->view('Administrator/footer');
  }
}
