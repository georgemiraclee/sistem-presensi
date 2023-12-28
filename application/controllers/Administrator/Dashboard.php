<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */
class Dashboard extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->library('Ceksession');
    $this->ceksession->login();
  }

  public function index()
  {
    $user = $this->session->userdata('user');

    if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama" && $user['role_access'] != '1') {
      redirect(base_url());
      exit();
    }

    $date_now = date("Y-m-d");
    $filter = array();
    $data['id_channel'] = $user['id_channel'];

    $data['skpd'] = $this->Db_select->query_all("SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_deleted = ? ORDER BY nama_unit ASC", [$user['id_channel'], 0]);

    $data['jabatan'] = $this->Db_select->query_all("SELECT id_jabatan, nama_jabatan FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC", [$user['id_channel'], 1, 0]);

    $query = "SELECT COUNT(*) total FROM tb_absensi j JOIN tb_user c ON j.user_id = c.user_id JOIN tb_unit x ON c.id_unit = x.id_unit WHERE x.id_channel = ? AND c.is_admin = '0' AND c.is_superadmin = '0'";

    $pegawai = "SELECT COUNT(*) total FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE b.id_channel = ? AND a.is_admin = ? AND a.is_superadmin = ? AND a.is_aktif = ? AND a.is_deleted = ?";

    $getPengajuan = "SELECT COUNT(*) total FROM tb_pengajuan a JOIN tb_status_pengajuan b ON a.status_pengajuan = b.id_status_pengajuan JOIN tb_user c ON a.user_id = c.user_id WHERE id_channel = ?";

    $getListPengajuan = "SELECT COUNT(*) total FROM tb_pengajuan a JOIN tb_user b ON a.user_id = b.user_id JOIN tb_unit c ON b.id_unit = c.id_unit WHERE b.is_aktif = 1 AND c.id_channel = ?";

    $getListLembur = "SELECT COUNT(*) total FROM tb_lembur a JOIN tb_user b ON a.user_id = b.user_id JOIN tb_unit c ON b.id_unit = c.id_unit WHERE c.id_channel = ? AND a.status = ? AND b.is_aktif = ?";

    if ($this->input->get('skpd')) {
      $filter['skpd'] = $this->input->get('skpd');
      $query .= " AND c.id_unit IN(" . $this->input->get('skpd') . ")";
      $pegawai .= " AND b.id_unit IN(" . $this->input->get('skpd') . ")";
      $getPengajuan .= " AND c.id_unit IN(" . $this->input->get('skpd') . ")";
      $getListPengajuan .= " AND b.id_unit IN(" . $this->input->get('skpd') . ")";
      $getListLembur .= " AND b.id_unit IN(" . $this->input->get('skpd') . ")";
    }

    if ($this->input->get('jabatan')) {
      $filter['jabatan'] = $this->input->get('jabatan');
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";

      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }

      $jabatan = substr($jabatan, 1);
      $query .= " AND c.jabatan IN(" . $jabatan . ")";
      $pegawai .= " AND a.jabatan IN(" . $this->input->get('jabatan') . ")";
      $getPengajuan .= " AND c.jabatan IN(" . $this->input->get('jabatan') . ")";
      $getListPengajuan .= " AND b.jabatan IN(" . $this->input->get('jabatan') . ")";
      $getListLembur .= " AND b.jabatan IN(" . $this->input->get('jabatan') . ")";
    }

    if ($this->input->get('jenkel')) {
      $filter['jenkel'] = $this->input->get('jenkel');
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " AND c.jenis_kelamin IN(" . $jenkel . ")";
      $pegawai .= " AND a.jenis_kelamin IN(" . $jenkel . ")";
      $getPengajuan .= " AND c.jenis_kelamin IN(" . $jenkel . ")";
      $getListPengajuan .= " AND b.jenis_kelamin IN(" . $jenkel . ")";
      $getListLembur .= " AND b.jenis_kelamin IN(" . $jenkel . ")";
    }

    if ($this->input->get('dari')) {
      $filter['dari'] = $this->input->get('dari', true);
      $filter['sampai'] = $this->input->get('sampai', true);
      $query .= " AND j.created_absensi BETWEEN '" . $this->input->get('dari') . " 00:00:00' AND '" . $this->input->get('sampai') . " 23:59:59'";
      $getPengajuan .= " AND a.tanggal_akhir_pengajuan BETWEEN '" . $this->input->get('dari') . " 00:00:00' AND '" . $this->input->get('sampai') . " 23:59:59'";
    } else {
      $query .= " AND j.created_absensi BETWEEN '$date_now 00:00:00' AND '$date_now 23:59:59' ";
      $getPengajuan .= " AND a.tanggal_akhir_pengajuan = '$date_now' ";
    }

    $data['show'] = $filter != [];
    $getDatatepat = $query . ' AND j.status_absensi = "Tepat Waktu"';
    $getDataTH = $query . ' AND j.status_absensi = "Tidak Hadir"';
    $getDataK = $query . ' AND j.status_absensi = "Terlambat"';

    $getcount = $this->Db_select->query($getDatatepat, [$user['id_channel']]);
    $getcount2 = $this->Db_select->query($getDataTH, [$user['id_channel']]);
    $getcount3 = $this->Db_select->query($getDataK, [$user['id_channel']]);

    $tepat_pie = $getcount->total;
    $terlambat_pie = $getcount3->total;
    $cnt = $tepat_pie + $terlambat_pie;
    /*jumlah pegawai*/

    $data['tepat_waktu'] = $tepat_pie;
    $data['terlambat'] = $terlambat_pie;

    $pegawai = $this->Db_select->query($pegawai, [$user['id_channel'], 0, 0, 1, 0]);

    if ($this->input->get('dari') || $this->input->get('sampai')) {
      $tidakhadir_pie = $getcount2->total;
    } else {
      $tidakhadir_pie = $pegawai->total - $cnt;
    }
    $data['tidakhadir'] = $tidakhadir_pie;

    //data pengajuan
    $cuti = $getPengajuan . ' AND nama_status_pengajuan IN("Cuti", "Cuti Tahunan")';
    $data['count_cuti'] = $this->Db_select->query($cuti, [$user['id_channel']])->total;

    $izin = $getPengajuan . ' AND nama_status_pengajuan = "Izin"';
    $data['count_izin'] = $this->Db_select->query($izin, [$user['id_channel']])->total;

    $sakit = $getPengajuan . ' AND nama_status_pengajuan = "Sakit"';
    $data['count_sakit'] = $this->Db_select->query($sakit, [$user['id_channel']])->total;

    $map = $this->DataMaps($user['id_channel']);
    $LineChart = $this->LineChart($user['id_channel']);
    $BarChart = $this->BarChart($user['id_channel']);
    $AreaChart = $this->AreaChart($user['id_channel']);

    $data['line_chart'] = "";
    $data['line_chart'] .= $LineChart.$BarChart.$AreaChart;
    $data['map'] = $map;
    
    $filter['unit'] = $this->input->get('skpd');
    $filter['jabatan'] = $this->input->get('jabatan');

    $id_channel = $user['id_channel'];
    $count_user_aktif_query = "SELECT COUNT(*) total FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE b.id_channel = $id_channel AND a.is_aktif = 1 AND a.is_deleted = 0";
    $count_user_non_aktif_query = "SELECT COUNT(*) total FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE b.id_channel = $id_channel AND a.is_aktif = 0";

    if ($filter['jabatan']) {
      $jabatan = implode(',', explode(',', $filter['jabatan']));
      $count_user_aktif_query .= " AND a.jabatan IN(" . $jabatan . ")";
      $count_user_non_aktif_query .= " AND a.jabatan IN(" . $jabatan . ")";
    }
    if ($filter['unit']) {
      $unit = implode(',', explode(',', $filter['unit']));
      $count_user_aktif_query .= " AND a.id_unit IN($unit)";
      $count_user_non_aktif_query .= " AND a.id_unit IN($unit)";
    }

    $data['aktifUser'] = $this->Db_select->query($count_user_aktif_query)->total;
    $data['nonaktifUser'] = $this->Db_select->query($count_user_non_aktif_query)->total;

    /* get data status persetujuan */
    $getStatus = $this->Db_select->query_all("SELECT id_status_pengajuan, nama_status_pengajuan FROM tb_status_pengajuan WHERE nama_status_pengajuan NOT LIKE '%lembur%' AND id_channel = ? AND is_deleted = ? ORDER BY nama_status_pengajuan ASC", [$user['id_channel'], 0]);
    /* PERSETUJUAN */

    $data['persetujuan'] = '';
    $tmpPengajuan = [];
    foreach ($getStatus as $key => $value) {
      /* get total pengajuan yang belum acc */
      $listPengajuan = $getListPengajuan . " AND a.status_approval = ? AND a.status_pengajuan = ?";
      $getTotal = $this->Db_select->query($listPengajuan, [$user['id_channel'], 0, $value->id_status_pengajuan])->total;

      $tmpPengajuan[$value->nama_status_pengajuan . '.' . $value->id_status_pengajuan] = intval($getTotal);
    }
    $getLembur = $this->Db_select->query($getListLembur, [$user['id_channel'], 0, 1])->total;
    $tmpPengajuan['Lembur' . '.901'] = intval($getLembur);

    arsort($tmpPengajuan);
    foreach ($tmpPengajuan as $key => $value) {
      $persetujuan = explode('.', $key);
      $data['persetujuan'] .= '<li style="list-style-type: none; margin-bottom: 10px; cursor:pointer;" onclick="dataPengajuan(' . $persetujuan[1] . ')" data-toggle="modal" data-target="#myModal">' . $persetujuan[0] . ' <span class="badge bg-blue float-right">' . $value . '</span></li>';
    }

    $menu['main'] = 'beranda';
    $menu['child'] = '';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/dashboard');
    $this->load->view('Administrator/footer');
  }

  public function listAbsensi()
  {
    $status = $this->input->post('status');
    $sess = $this->session->userdata('user');

    $id_channel = $sess['id_channel'];
    $tanggal2 = date("m");
    $query_list = "select c.nama_user, c.nip, j.id_absensi, x.nama_unit from tb_absensi j join tb_user c on j.user_id = c.user_id join tb_unit x on c.id_unit = x.id_unit where date_format(j.created_absensi, '%m') = '" . $tanggal2 . "' and x.id_channel = '" . $id_channel . "' and c.is_admin = '0' and c.is_superadmin = '0' and c.is_deleted = 0";
    $query_tidak = "select a.nama_user, a.nip, b.nama_unit from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = '" . $id_channel . "' and a.is_aktif = 1 and a.is_deleted = 0";

    if ($this->input->get('skpd')) {
      $query_list .= " and c.id_unit in(" . $this->input->get('skpd') . ")";
      $query_tidak .= " and a.id_unit in(" . $this->input->get('skpd') . ")";
    }
    if ($this->input->get('jabatan')) {
      $query_list .= " and c.jabatan in(" . $this->input->get('jabatan') . ")";
      $query_tidak .= " and a.jabatan in(" . $this->input->get('jabatan') . ")";
    }
    if ($this->input->get('jenkel')) {
      $jenkelList = $this->input->get('jenkel');
      $jenkelList = explode(',', $jenkelList);
      $jenkel = "";
      foreach ($jenkelList as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query_list .= " and c.jenis_kelamin in(" . $jenkel . ")";
      $query_tidak .= " and a.jenis_kelamin in(" . $jenkel . ")";
    }
    if ($this->input->get('dari')) {
      $query_list .= " and date(created_absensi)  >= '" . $this->input->get('dari') . "'";
      $query_tidak .= " and a.user_id not in (select user_id from tb_absensi where date(created_absensi) >= '" . $this->input->get('dari') . " and date(created_absensi) <= '" . $this->input->get('sampai') . "')";
    } else {
      $query_list .= " and date_format(created_absensi, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') ";
      $query_tidak .= " and a.user_id not in (select user_id from tb_absensi where date_format(created_absensi, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d'))";
    }
    if ($this->input->get('sampai')) {
      $query_list .= " and date(created_absensi) <= '" . $this->input->get('sampai') . "' ";
    }

    if ($status == "Tidak Hadir") {
      $tp = $this->Db_select->query_all($query_tidak);
    } elseif ($status == "Hadir") {
      $listDatatepat = $query_list . ' and status_absensi in("Tepat Waktu", "Terlambat")';
      $tp = $this->Db_select->query_all($listDatatepat);
    } else {
      $listDatatepat = $query_list . ' and status_absensi = "' . $status . '"';
      $tp = $this->Db_select->query_all($listDatatepat);
    }

    echo json_encode($tp);
  }

  private function listPengajuan()
  {
    $status = $this->input->post('status');
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];

    $getPengajuan = "select a.id_pengajuan id_absensi, c.nip nip, c.nama_user nama_user, d.nama_unit from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan join tb_user c on a.user_id = c.user_id join tb_unit d on c.id_unit = d.id_unit where d.id_channel = '" . $id_channel . "' ";

    if ($this->input->get('skpd')) {
      $getPengajuan .= " and id_unit in(" . $this->input->get('skpd') . ")";
    }
    if ($this->input->get('jabatan')) {
      $getPengajuan .= " and jabatan in(" . $this->input->get('jabatan') . ")";
    }
    if ($this->input->get('jenkel')) {
      $jenkelList = $this->input->get('jenkel');
      $jenkelList = explode(',', $jenkelList);
      $jenkel = "";
      foreach ($jenkelList as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $getPengajuan .= " and jenis_kelamin in(" . $jenkel . ")";
    }
    if ($this->input->get('dari')) {
      $getPengajuan .= " and date(tanggal_akhir_pengajuan )  >= '" . $this->input->get('dari') . "' and date(tanggal_akhir_pengajuan) <= '" . $this->input->get('sampai') . "'";
    } else {
      $getPengajuan .= " and date_format(tanggal_akhir_pengajuan, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') ";
    }

    $sakit = $getPengajuan . ' and nama_status_pengajuan = "' . $status . '"';
    if ($status == 'cuti') {
      $sakit = $getPengajuan . ' and nama_status_pengajuan like "%cuti%"';
    }

    $get_sakit = $this->Db_select->query_all($sakit);

    echo json_encode($get_sakit);
  }

  public function pengajuanIzin()
  {
    $status = $this->input->post('status');
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];

    if ($status == 901) {
      $getListPengajuan = "select b.nama_user, b.nip, a.id_lembur as id_absensi, c.nama_unit from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where b.is_aktif = 1 and a.status = 0 and c.id_channel = " . $id_channel;
    } else {
      $getListPengajuan = "select b.nama_user, b.nip, a.id_pengajuan as id_absensi, c.nama_unit from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where a.status_approval = 0 and b.is_aktif = 1 and c.id_channel = " . $id_channel;
    }

    if ($this->input->get('skpd')) {
      $getListPengajuan .= " and b.id_unit in(" . $this->input->get('skpd') . ")";
    }
    if ($this->input->get('jabatan')) {
      $getListPengajuan .= " and b.jabatan in(" . $this->input->get('jabatan') . ")";
    }
    if ($this->input->get('jenkel')) {
      $jenkelList = $this->input->get('jenkel');
      $jenkelList = explode(',', $jenkelList);
      $jenkel = "";
      foreach ($jenkelList as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $getListPengajuan .= " and b.jenis_kelamin in(" . $jenkel . ")";
    }

    if ($status != 901) {
      $query = $getListPengajuan . " and a.status_pengajuan = " . $status;
    } else {
      $query = $getListPengajuan;
    }

    $result = $this->Db_select->query_all($query);

    echo json_encode($result);
    exit();
  }

  public function LineChart($id_channel)
  {
    $query = "SELECT j.created_absensi, COUNT(*) total FROM tb_absensi j JOIN tb_user c ON j.user_id = c.user_id JOIN tb_unit x ON c.id_unit = x.id_unit WHERE x.id_channel = ? AND c.is_admin = ? AND c.is_superadmin = ?";
    $dari = $this->input->get('dari');
    $sampai = $this->input->get('sampai');
    $datas = [];

    if (!is_null($dari) && ($dari != $sampai)) {
      $filter['dari'] = $dari;
      $query .= ' AND date(created_absensi)  >= "' . $dari . '"';
    }

    if (!is_null($sampai) && ($sampai != $dari)) {
      $filter['sampai'] = $sampai;
      $query .= ' AND date(created_absensi) <= "' . $sampai . '" ';
    }

    $date_now = date("Y-m-d");
    if ($dari == $sampai) {
      $getDataTW = $query . " AND created_absensi BETWEEN '$date_now 00:00:00' AND '$date_now 23:59:59' - INTERVAL 7 DAY GROUP BY DATE(created_absensi)";
      $getData1 = $this->Db_select->query_all($getDataTW, [$id_channel, 0, 0]);
      foreach ($getData1 as $key => $value) {
        $dater = date('Y-m-d', strtotime($value->created_absensi));
        $datas[] = "" . $dater . "";
      }
    } else {
      $getDataTW = $query . ' GROUP BY date(created_absensi)';
      $getData1 = $this->Db_select->query_all($getDataTW, [$id_channel, 0, 0]);
      foreach ($getData1 as $key => $value) {
        $dater = date('Y-m-d', strtotime($value->created_absensi));
        $datas[] = "" . $dater . "";
      }
    }

    $data['tepat'] = "";
    $data['tidak_hadir'] = "";
    $data['terlambat'] = "";
    for ($i = 0; $i < count($datas); $i++) {
      if ($this->input->get('dari') == $this->input->get('sampai')) {
        $query = 'SELECT COUNT(*) total FROM tb_absensi j JOIN tb_user c ON j.user_id = c.user_id WHERE date_format(waktu_datang, "%h:%m:%i") = "' . $datas[$i] . '"';
      } else {
        $query = "SELECT COUNT(*) total FROM tb_absensi j JOIN tb_user c ON j.user_id = c.user_id WHERE created_absensi BETWEEN '$datas[$i] 00:00:00' AND '$datas[$i] 23:59:59' ";
      }
      if ($this->input->get('skpd')) {
        $filter['skpd'] = $this->input->get('skpd');
        $query .= " AND c.id_unit in(" . $this->input->get('skpd') . ")";
      }
      if ($this->input->get('jabatan')) {
        $filter['jabatan'] = $this->input->get('jabatan');
        $filter['jabatan'] = explode(',', $filter['jabatan']);
        $jabatan = "";
        foreach ($filter['jabatan'] as $key => $value) {
          $jabatan .= ",'$value'";
        }
        $jabatan = substr($jabatan, 1);
        $query .= " AND c.jabatan in(" . $jabatan . ")";
      }
      if ($this->input->get('jenkel')) {
        $filter['jenkel'] = $this->input->get('jenkel');
        $filter['jenkel'] = explode(',', $filter['jenkel']);
        $jenkel = "";
        foreach ($filter['jenkel'] as $key => $value) {
          $jenkel .= ",'$value'";
        }
        $jenkel = substr($jenkel, 1);
        $query .= " AND c.jenis_kelamin in(" . $jenkel . ")";
      }
      if ($this->input->get('status')) {
        $filter['status'] = $this->input->get('status');
        $filter['status'] = explode(',', $filter['status']);
        $statusAbsensi = "";
        foreach ($filter['status'] as $key => $value) {
          $statusAbsensi .= ",'$value'";
        }
        $statusAbsensi = substr($statusAbsensi, 1);
        $query .= " AND status_absensi in(" . $statusAbsensi . ")";
      }

      $getDatatepat = $query . ' AND status_absensi = "Tepat Waktu"';
      $getDataTH = $query . ' AND status_absensi = "Tidak Hadir"';
      $getDataK = $query . ' AND status_absensi = "Terlambat"';

      $getcount = $this->Db_select->query($getDatatepat);
      $getcount2 = $this->Db_select->query($getDataTH);
      $getcount3 = $this->Db_select->query($getDataK);
      $data['tepat'] .= " " . $getcount->total . ", ";
      $data['tidak_hadir'] .= " " . $getcount2->total . ", ";
      $data['terlambat'] .= " " . $getcount3->total . ", ";
    }

    $chart = "<script type='text/javascript'>
                $(function () {
                    $('#statistik').highcharts({
                        title: {
                            text: '',
                            x: -20 //center
                        },subtitle: {
                            text: '',
                            x: -20
                        },
                        yAxis: {
                            title: {
                                text: 'Jumlah Pegawai'
                            }
                        },
                        xAxis: {
                            categories: " . json_encode($datas) . ",
                            type: 'category',
                            tickmarkPlacement: 'on',
                            title: {
                                enabled: false
                            }
                        },
                        series: [
                            {name: 'Tepat Waktu',data: [" . $data['tepat'] . "],color: '#4CAF50'},
                            {name: 'Terlambat',data: [" . $data['terlambat'] . "],color: 'orange'},
                            {name: 'Tidak Hadir',data: [" . $data['tidak_hadir'] . "],color: 'pink'},
                        ]
                    });
                });</script>";
    return $chart;
  }

  public function BarChart($id_channel)
  {
    $skpd = $this->Db_select->query_all("SELECT a.id_unit, a.nama_unit, IFNULL(cek.total, 0) total FROM tb_unit a LEFT OUTER JOIN(SELECT tb_user.id_unit, COUNT(*) total FROM tb_absensi JOIN tb_user ON tb_absensi.user_id = tb_user.user_id) cek ON a.id_unit = cek.id_unit WHERE a.id_channel = ? ORDER BY cek.total DESC LIMIT 10", [$id_channel]);
    $NewSkdp = '[';
    $NewTerlambat = array();
    $NewTepatWaktu = array();
    $NewTidakHadir = array();
    $NewHadir = array();
    foreach ($skpd as $value) {
      $NewSkdp .= "'$value->nama_unit'" . ",";
      $query = "SELECT j.created_absensi FROM tb_absensi j JOIN tb_user c ON j.user_id = c.user_id JOIN tb_unit x ON c.id_unit = x.id_unit WHERE c.is_deleted = ? AND x.id_channel = ?";
      $pegawai = "SELECT COUNT(*) total FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE a.is_admin = ? AND a.is_superadmin = ? AND a.is_deleted = ? AND a.is_aktif = ? AND a.id_unit = ? AND b.id_channel = ?";

      if ($this->input->get('skpd')) {
        $filter['skpd'] = $this->input->get('skpd');
        $query .= " AND c.id_unit IN(" . $this->input->get('skpd') . ")";
        $pegawai .= " AND b.id_unit IN(" . $this->input->get('skpd') . ")";
      } else {
        $query .= " AND c.id_unit = " . $value->id_unit . "";
      }

      if ($this->input->get('jabatan')) {
        $filter['jabatan'] = $this->input->get('jabatan');
        $filter['jabatan'] = explode(',', $filter['jabatan']);
        $jabatan = "";
        foreach ($filter['jabatan'] as $key => $valuex) {
          $jabatan .= ",'$valuex'";
        }
        $jabatan = substr($jabatan, 1);
        $query .= " AND c.jabatan IN(" . $jabatan . ")";
        $pegawai .= " AND jabatan IN(" . $this->input->get('jabatan') . ")";
      }

      if ($this->input->get('jenkel')) {
        $filter['jenkel'] = $this->input->get('jenkel');
        $filter['jenkel'] = explode(',', $filter['jenkel']);
        $jenkel = "";
        foreach ($filter['jenkel'] as $key => $valuees) {
          $jenkel .= ",'$valuees'";
        }
        $jenkel = substr($jenkel, 1);
        $query .= " AND c.jenis_kelamin in(" . $jenkel . ")";
        $pegawai .= " AND jenis_kelamin in(" . $jenkel . ")";
      }

      if ($this->input->get('dari') && $this->input->get('sampai')) {
        $filter['dari'] = $this->input->get('dari');
        $filter['sampai'] = $this->input->get('sampai');

        $query .= " AND created_absensi BETWEEN '" . $filter['dari'] . " 00:00:00' AND '" . $filter['sampai'] . " 23:59:59' ";
      } else if ($this->input->get('dari')) {
        $filter['dari'] = $this->input->get('dari');
        $query .= " AND date(created_absensi)  >= '" . $this->input->get('dari') . "'";
      } else if ($this->input->get('sampai')) {
        $filter['sampai'] = $this->input->get('sampai');
        $query .= " AND date(created_absensi) <= '" . $this->input->get('sampai') . "' ";
      } else {
        $date_now = date('Y-m-d');
        $query .= " AND created_absensi BETWEEN '$date_now 00:00:00' AND '$date_now 23:59:59' ";
      }

      $terlambat = $this->Db_select->query_all($query, [0, $id_channel]);
      $pgw = $this->Db_select->query($pegawai, [0, 0, 0, 1, $value->id_unit, $id_channel]);
      $WaktuTerlambat = 0;
      $WaktuHadir = 0;
      $WaktuTidakHadir = 0;
      if (count($terlambat) > 0) {
        foreach ($terlambat as $valuez) {
          if (date("h:i", strtotime($valuez->created_absensi)) > "08:30") {
            $WaktuTerlambat += 1;
          } else {
            $WaktuHadir += 1;
          }
        }
      }
      $WaktuTidakHadir = $pgw->total - count($terlambat);
      if ($this->input->get('skpd')) {
        if ($value->id_unit != $this->input->get('skpd')) {
          $WaktuTerlambat = 0;
          $WaktuHadir = 0;
          $WaktuTidakHadir = 0;
        }
      }
      if ($pgw->total == 0) {
        $WaktuTerlambat = 0;
        $WaktuHadir = 0;
        $WaktuTidakHadir = 0;
      }

      $hadir = $WaktuTerlambat + $WaktuHadir;

      array_push($NewTerlambat, $WaktuTerlambat);
      array_push($NewTepatWaktu, $WaktuHadir);
      array_push($NewTidakHadir, $WaktuTidakHadir);
      array_push($NewHadir, $hadir);
    }
    $NewSkdp .= ']';

    if ($id_channel == 14) {
      $chart = "<script type='text/javascript'>
        $(function() {
          Highcharts.chart('statistikSKPD', {
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: " . $NewSkdp . "
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: [{
                name: 'Tidak Hadir',
                data: " . json_encode($NewTidakHadir) . ",
                color: '#E91E63'
            }, {
                name: 'Hadir',
                data: " . json_encode($NewHadir) . ",
                color: '#4CAF50'
            }]
          });
        }); 
      </script>";
    } else {
      $chart = "<script type='text/javascript'>
        $(function() {
          Highcharts.chart('statistikSKPD', {
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: " . $NewSkdp . "
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: [{
                name: 'Terlambat',
                data: " . json_encode($NewTerlambat) . ",
                color: '#FF5722'
            }, {
                name: 'Tidak Hadir',
                data: " . json_encode($NewTidakHadir) . ",
                color: '#E91E63'
            }, {
                name: 'Tepat Waktu',
                data: " . json_encode($NewTepatWaktu) . ",
                color: '#4CAF50'
            }]
          });
        }); 
      </script>";
    }
    return $chart;
  }
  
  public function daftarHadir()
  {
    $sess = $this->session->userdata('user');
    $pg = $this->DB_super_admin->select_absensi_pegawai($sess['id_channel']);
    $pegawai = '';
    foreach ($pg as $key => $value) {

      if ($value->url_file_absensi == null || $value->url_file_absensi == '') {
        $value->url_file_absensi = "ava.png";
      }
      if ($value->waktu_datang == null || $value->waktu_datang == '') {
        $datang = "-";
        $istirahat = "-";
        $kembali = "-";
        $pulang = "-";
        $class = "offline";
        $ket = "<span class='label label-danger label-form float-right' style'color:red';>Belum Datang</span>";
      } else {
        $bek = "#32B098";
        $class = "online";
        $datang = $value->waktu_datang;
        if ($datang > "08:30") {
          $ket = "<span class='label label-warning float-right' style='background-color: #EA951D'>Kesiangan</span>";
        } else {
          $ket = "<span class='label label-primary label-form float-right' style'color:red';>Tepat Waktu</span>";
        }
      }
      $nama = $value->nama_user;
      if (strlen($nama) > 15)
        $nama = substr($nama, 0, 10) . '...';
      $pegawai .= '<br>
              <div class="col-md-3 float-left">
                  <img src="' . base_url() . 'assets/images/absensi/' . $value->url_file_absensi . '" style="max-width:50px;" class=" float-left" >
              </div>
              <div class="col-md-9">
                  <h5>' . $nama . '</h5>
                  <p>' . $datang . ' ' . $ket . '</p><hr>
              </div>';
    }
    $tanggal2 = date("m");
    $query = "select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id join tb_unit x on c.id_unit = x.id_unit where date_format(created_absensi, '%m') = '" . $tanggal2 . "' and x.id_channel = '" . $sess['id_channel'] . "'";
    $pgw = "select count(*) total from tb_absensi a join tb_user c on a.user_id = c.user_id join tb_unit x on c.id_unit = x.id_unit where is_admin='0' and x.id_channel = '" . $sess['id_channel'] . "'";
    if ($this->input->get('skpd')) {
      $filter['skpd'] = $this->input->get('skpd');
      $query .= " and c.id_unit in(" . $this->input->get('skpd') . ")";
      $pgw .= " and c.id_unit in(" . $this->input->get('skpd') . ")";
    }
    if ($this->input->get('jabatan')) {
      $filter['jabatan'] = $this->input->get('jabatan');
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";
      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " and c.jabatan in(" . $jabatan . ")";
      $pgw .= " and c.jabatan in(" . $this->input->get('jabatan') . ")";
    }
    if ($this->input->get('jenkel')) {
      $filter['jenkel'] = $this->input->get('jenkel');
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " and c.jenis_kelamin in(" . $jenkel . ")";
      $pgw .= " and c.jenis_kelamin in(" . $jenkel . ")";
    }
    if ($this->input->get('dari')) {
      $filter['dari'] = $this->input->get('dari');
      $query .= " and date(created_absensi)  >= '" . $this->input->get('dari') . "'";
      $pgw .= " and date(a.created_absensi)  >= '" . $this->input->get('dari') . "'";
    } else {
      $query .= " and date_format(j.created_absensi, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') ";
      $pgw .= " and date_format(a.created_absensi, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') ";
    }
    if ($this->input->get('sampai')) {
      $filter['sampai'] = $this->input->get('sampai');
      $query .= " and date(j.created_absensi) <= '" . $this->input->get('sampai') . "' ";
      $pgw .= " and date(a.created_absensi) <= '" . $this->input->get('sampai') . "' ";
    }

    // echo json_encode($query);exit();
    $getDatatepat = $query . ' and status_absensi = "Tepat Waktu"';
    $getDataTH = $query . ' and status_absensi = "Tidak Hadir"';
    $getDataK = $query . ' and status_absensi = "Terlambat"';
    // echo json_encode($getDataTH);exit();
    $getcount = $this->Db_select->query($getDatatepat);
    $getcount2 = $this->Db_select->query($getDataTH);
    $getcount3 = $this->Db_select->query($getDataK);
    $tepat_pie =  $getcount->total;
    // $tidakhadir_pie=$getcount2->total;
    $terlambat_pie = $getcount3->total;
    $terlambat = $this->Db_select->query_all($query);
    $pgw = $this->Db_select->query($pgw);
    $cnt = $tepat_pie + $terlambat_pie;
    /*jumlah pegawai*/
    $jumlah_pgw = $this->Db_select->query('select count(*) total from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = "' . $sess['id_channel'] . '" and a.is_aktif = "1"');
    $tidakhadir_pie = $jumlah_pgw->total - $cnt;
    $result['status'] = true;
    $result['message'] = 'Data ditemukan.';
    // $result['data'] = $pegawai;  
    $result['jumlah_hadir'] = $tepat_pie;
    $result['tidak_hadir'] = $tidakhadir_pie;
    $result['terlambat'] = $terlambat_pie;
    echo json_encode($result);
    exit();
  }

  public function DataMaps($id_channel)
  {
    $query = "SELECT a.user_id id, a.nip, a.nama_user, a.foto_user, b.waktu_datang, d.lat, d.lng, d.created_history_absensi, j.nama_unit FROM tb_user a JOIN tb_absensi b ON a.user_id = b.user_id JOIN tb_history_absensi d on b.id_absensi = d.id_absensi join tb_unit j on a.id_unit = j.id_unit where a.is_admin = 0 AND a.is_superadmin = 0 AND j.id_channel = ? AND b.id_absensi = (
      SELECT MAX(c.id_absensi) FROM tb_absensi c WHERE c.user_id = a.user_id) AND d.id_history_absensi = (SELECT MAX(e.id_history_absensi) FROM tb_history_absensi e WHERE b.id_absensi = e.id_absensi
    )";

    if ($this->input->get('skpd')) {
      $filter['skpd'] = $this->input->get('skpd');
      $query .= " AND a.id_unit in(" . $this->input->get('skpd') . ")";
    }
    if ($this->input->get('jabatan')) {
      $filter['jabatan'] = $this->input->get('jabatan');
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";
      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " AND a.jabatan in(" . $jabatan . ")";
    }
    if ($this->input->get('jenkel')) {
      $filter['jenkel'] = $this->input->get('jenkel');
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " AND a.jenis_kelamin in(" . $jenkel . ")";
    }
    if ($this->input->get('dari')) {
      $filter['dari'] = $this->input->get('dari');
      $query .= " AND date(created_absensi)  >= '" . $this->input->get('dari') . "'";
    } else {
      $query .= " AND date_format(created_absensi, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') ";
    }
    if ($this->input->get('sampai')) {
      $filter['sampai'] = $this->input->get('sampai');
      $query .= " AND date(created_absensi) <= '" . $this->input->get('sampai') . "' ";
    }
    $query .= " ORDER BY b.created_absensi DESC";
    $getData = $this->Db_select->query_all($query, [$id_channel]);
    foreach ($getData as $key => $value) {
      if ($value->foto_user == null || $value->foto_user == "") {
        $value->foto_user = "default_photo.jpg";
      }
      $day = date('d', strtotime($value->created_history_absensi));
      $years = date('Y', strtotime($value->created_history_absensi));
      $month = date('m', strtotime($value->created_history_absensi));
      $dayMonth = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
      );
      $value->created_history_absensi = $day . ' ' . $dayMonth[$month] . ' ' . $years;
    }

    return json_encode($getData);
  }

  public function AreaChart($id_channel)
  {
    $query = "SELECT COUNT(*) total FROM tb_absensi j JOIN tb_user c ON j.user_id = c.user_id JOIN tb_unit x ON c.id_unit = x.id_unit WHERE x.id_channel = ? AND c.is_admin = ? AND c.is_superadmin = ?";

    if ($this->input->get('skpd')) {
      $filter['skpd'] = $this->input->get('skpd');
      $query .= " AND c.id_unit in(" . $this->input->get('skpd') . ")";
    }

    if ($this->input->get('jabatan')) {
      $filter['jabatan'] = $this->input->get('jabatan');
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";
      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " AND c.jabatan in(" . $jabatan . ")";
    }

    if ($this->input->get('jenkel')) {
      $filter['jenkel'] = $this->input->get('jenkel');
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " AND jenis_kelamin in(" . $jenkel . ")";
    }

    if ($this->input->get('dari') && $this->input->get('sampai')) {
      $filter['dari'] = $this->input->get('dari');
      $filter['sampai'] = $this->input->get('sampai');

      $query .= " AND created_absensi BETWEEN '" . $filter['dari'] . " 00:00:00' AND '" . $filter['sampai'] . " 23:59:59' ";
    } else if ($this->input->get('dari')) {
      $filter['dari'] = $this->input->get('dari');
      $query .= " AND date(created_absensi)  >= '" . $this->input->get('dari') . "'";
    } else if ($this->input->get('sampai')) {
      $filter['sampai'] = $this->input->get('sampai');
      $query .= " AND date(created_absensi) <= '" . $this->input->get('sampai') . "' ";
    } else {
      $date_now = date('Y-m-d');
      $query .= " AND created_absensi BETWEEN '$date_now 00:00:00' AND '$date_now 23:59:59' ";
    }

    $tujuh = $query . ' AND time(waktu_datang) BETWEEN "07:00:00" AND "07:30:00"';
    $tujuh = $this->Db_select->query_all($tujuh, [$id_channel, 0, 0]);
    foreach ($tujuh as $key => $value) {
      $dtujuh = $value->total;
    }
    $tujuh30 = $query . ' AND time(waktu_datang) BETWEEN "07:30:00" AND "08:00:00"';
    $tujuh30 = $this->Db_select->query_all($tujuh30, [$id_channel, 0, 0]);
    foreach ($tujuh30 as $key => $value) {
      $dtujuh30 = $value->total;
    }
    $delapan = $query . ' AND time(waktu_datang) BETWEEN "08:00:00" AND "08:30:00"';
    $delapan = $this->Db_select->query_all($delapan, [$id_channel, 0, 0]);
    foreach ($delapan as $key => $value) {
      $ddelapan = $value->total;
    }
    $delapan30 = $query . ' AND time(waktu_datang) BETWEEN "08:30:00" AND "09:00:00"';
    $delapan30 = $this->Db_select->query_all($delapan30, [$id_channel, 0, 0]);
    foreach ($delapan30 as $key => $value) {
      $ddelapan30 = $value->total;
    }
    $sembilan = $query . ' AND time(waktu_datang) BETWEEN "09:00:00" AND "09:30:00"';
    $sembilan = $this->Db_select->query_all($sembilan, [$id_channel, 0, 0]);
    foreach ($sembilan as $key => $value) {
      $dsembilan = $value->total;
    }
    $sembilan30 = $query . ' AND time(waktu_datang) BETWEEN "09:30:00" AND "10:00:00"';
    $sembilan30 = $this->Db_select->query_all($sembilan30, [$id_channel, 0, 0]);
    foreach ($sembilan30 as $key => $value) {
      $dsembilan30 = $value->total;
    }
    $sepuluh = $query . ' AND time(waktu_datang) > "10:00:00"';
    $sepuluh = $this->Db_select->query_all($sepuluh, [$id_channel, 0, 0]);
    foreach ($sepuluh as $key => $value) {
      $dsepuluh = $value->total;
    }
    $dateNow = date('d F Y');
    $chart = "<script type='text/javascript'>
                $(function () {
                    Highcharts.setOptions({
                      colors: ['#03A9F4', '#3F51B5']
                    });
                    Highcharts.chart('container2', {
                        chart: {
                            type: 'column',
                            options3d: {
                                enabled: true,
                                alpha: 10,
                                beta: 25,
                                depth: 70
                            },
                        },
                        title: {
                            text: 'Statistik Jam Masuk Pegawai'
                        },
                        subtitle: {
                        text: '$dateNow'
                        },
                          tooltip: {
                            pointFormat: '<b>{point.y:,.0f}</b> {series.name} datang pada jam tersebut'
                        },
                        plotOptions: {
                            column: {
                                depth: 25
                            }
                        },
                        xAxis: {
                            categories: ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '< 10:00'],
                            title: {
                                text: null
                            }
                    
                        },
                        yAxis: {
                            title: {
                                text: null
                            }
                        },
                        series: [{
                            name: 'Pegawai',
                            data: [" . $dtujuh . "," . $dtujuh30 . ", " . $ddelapan . ", " . $ddelapan30 . ", " . $dsembilan . ", " . $dsembilan30 . ", " . $dsepuluh . "]
                        }]
                    });
                });
    </script>";
    return $chart;
  }

  private function DonutChart($id_channel)
  {
    $query = 'select count(*) total FROM tb_user j join tb_unit c on j.id_unit = c.id_unit join tb_unit x on j.id_unit = x.id_unit WHERE j.is_admin = 0 and j.is_superadmin = "0" and x.id_channel = "' . $id_channel . '"';
    if ($this->input->get('skpd')) {
      $filter['skpd'] = $this->input->get('skpd');
      $query .= " and c.id_unit in(" . $this->input->get('skpd') . ")";
    }
    if ($this->input->get('jabatan')) {
      $filter['jabatan'] = $this->input->get('jabatan');
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";
      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " and jabatan in(" . $jabatan . ")";
    }
    if ($this->input->get('jenkel')) {
      $filter['jenkel'] = $this->input->get('jenkel');
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " and jenis_kelamin in(" . $jenkel . ")";
    }
    $getDataL = $query . ' and jenis_kelamin = "l"';
    $getDataP = $query . ' and jenis_kelamin = "p"';
    $getDataL = $this->Db_select->query_all($getDataL);
    $getDataP = $this->Db_select->query_all($getDataP);
    foreach ($getDataL as $key => $value) {
      $pria = $value->total;
    }
    foreach ($getDataP as $key => $value) {
      $wanita = $value->total;
    }
    $chart = "<script type='text/javascript'>
                  $(function () {
                        Highcharts.setOptions({
                        colors: ['#2196F3', '#4CAF50']
                      });
                      Highcharts.chart('container3', {
                          chart: {
                              type: 'pie',
                              options3d: {
                                  enabled: true,
                                  alpha: 45
                              }
                          },
                            title: {
                              text: 'Statistik Gender Pegawai'
                          },
                          plotOptions: {
                              pie: {
                                  innerSize: 100,
                                  depth: 45
                              }
                          },
                          series: [{
                              name: 'Jumlah pegawai',
                              data: [
                                  ['Pria'," . $pria . "],
                                  ['Wanita', " . $wanita . "]
                              ],
                              
                          }],
                          color:'#12bc89'
                      });
                  });
                          </script>";
    return $chart;
  }
}
