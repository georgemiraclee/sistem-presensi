<?php

class jadwal_kerja extends CI_Controller
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
    $id_channel = $user['id_channel'];
    $user = $this->session->userdata('user');
    if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama") {
      redirect(base_url());
    }

    /* cek apakah channel sudah mempunyai jadwal default */
    $where['id_channel'] = $id_channel;
    $getJadwal = $this->Db_select->select_all_where('tb_pola_kerja', $where, ['id_pola_kerja']);

    if (!$getJadwal) {
      $this->addDefault($id_channel);
    }

    $list = $this->Db_select->select_all_where('tb_pola_kerja', $where);
    $data['modalList'] = $this->modalList($list);
    $menu['main'] = 'pengaturan';
    $menu['child'] = 'pengaturan_pola';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/jadwal_kerja');
    $this->load->view('Administrator/footer');
  }

  public function addDefault($id_channel)
  {
    $namaFile = 'jadwal_default_' . $id_channel . '.txt';
    $file = 'appconfig/new/' . $namaFile;

    $insertDB['nama_pola_kerja'] = 'Pola Kerja Default';
    $insertDB['toleransi_keterlambatan'] = 0;

    $day = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    $libur = 2;
    $hari_kerja = 7;

    $insertDB['lama_pola_kerja'] = $hari_kerja;
    $insertDB['lama_hari_kerja'] = $hari_kerja - $libur;
    $insertDB['lama_hari_libur'] = $libur;
    $insertDB['id_channel'] = $id_channel;
    $insertDB['is_default'] = 1;

    $txt = array(
      "jam_kerja" => array(
        "monday" => array(
          "libur" => 0, "to" => '16:00', "from" => '08:00'
        ), "tuesday" => array(
          "libur" => 0, "to" => '16:00', "from" => '08:00'
        ), "wednesday" => array(
          "libur" => 0, "to" => '16:00', "from" => '08:00'
        ), "thursday" => array(
          "libur" => 0, "to" => '16:00', "from" => '08:00'
        ), "friday" => array(
          "libur" => 0, "to" => '16:00', "from" => '08:00'
        ), "saturday" => array(
          "libur" => 1, "to" => '16:00', "from" => '08:00'
        ), "sunday" => array(
          "libur" => 1, "to" => '16:00', "from" => '08:00'
        )
      )
    );
    write_file($file, json_encode($txt));

    $insertDB['file_pola_kerja'] = $namaFile;

    $this->Db_dml->insert('tb_pola_kerja', $insertDB);
  }

  public function modalList($data)
  {
    $modalList = "";

    if ($data) {
      foreach ($data as $key => $value) {
        $file = 'appconfig/new/' . $value->file_pola_kerja;
        if (file_exists($file)) {
          $jadwalKerja = json_decode(file_get_contents($file));

          $hariLibur = "Hari Libur";
          $hariKerja = "Hari Kerja";

          $idClass = 'classBorder';

          if ($jadwalKerja->jam_kerja->monday->libur == 1) {
            $hariSenin = $hariLibur;
            $idSenin = $idClass;
          } else {
            $idSenin = '';
            $hariSenin = $hariKerja;
          }

          if ($jadwalKerja->jam_kerja->tuesday->libur == 1) {
            $hariSelsasa = $hariLibur;
            $idSenin = $idClass;
          } else {
            $hariSelsasa = $hariKerja;
            $idSelasa = '';
          }

          if ($jadwalKerja->jam_kerja->wednesday->libur == 1) {
            $hariRabu = $hariLibur;
            $idRabu = $idClass;
          } else {
            $hariRabu = $hariKerja;
            $idRabu = '';
          }

          if ($jadwalKerja->jam_kerja->thursday->libur == 1) {
            $hariKamis = $hariLibur;
            $idKamis = $idClass;
          } else {
            $hariKamis = $hariKerja;
            $idKamis = '';
          }

          if ($jadwalKerja->jam_kerja->friday->libur == 1) {
            $hariJumat = $hariLibur;
            $idJumat = $idClass;
          } else {
            $hariJumat = $hariKerja;
            $idJumat = '';
          }

          if ($jadwalKerja->jam_kerja->saturday->libur == 1) {
            $hariSabtu = $hariLibur;
            $idSabtu = $idClass;
          } else {
            $hariSabtu = $hariKerja;
            $idSabtu = '';
          }

          if ($jadwalKerja->jam_kerja->sunday->libur == 1) {
            $hariMinggu = $hariLibur;
            $idMinggu = $idClass;
          } else {
            $hariMinggu = $hariKerja;
            $idMinggu = '';
          }

          if ($value->toleransi_keterlambatan == 1) {
            $toleransi = '<tr>
              <td width="200"></td>
              <td width="10"></td>
              <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
            </tr>';
          } else {
            $toleransi = '';
          }

          $modalList .= '
              <div class="modal fade" id="defaultModal' . $key . '" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title" id="defaultModalLabel">Detail Pola Jadwal Kerja</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <table>
                                  <tr>
                                      <td width="200">Nama Pola Kerja</td>
                                      <td width="10">:</td>
                                      <td>' . $value->nama_pola_kerja . '</td>
                                  </tr>
                                  <tr>
                                      <td width="200">Lama Pola</td>
                                      <td width="10">:</td>
                                      <td>' . $value->lama_pola_kerja . ' Hari</td>
                                  <tr>
                                      <td width="200">Toleransi Keterlambatan</td>
                                      <td width="10">:</td>
                                      <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                  </tr>
                               
                                  
                              </table>
                              <table class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th>Hari</th>
                                          <th>Status Kerja</th>
                                          <th>Jam Masuk</th>
                                          <th>Jam Keluar</th>
                                          <th>Toleransi<th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr class="' . $idSenin . '">
                                          <td>Senin</td>
                                          <td>' . $hariSenin . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->monday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->monday->to . '</td>
                                          <td>' . $value->waktu_toleransi_keterlambatan . ' Menit </td>
                                      </tr>
                                      <tr class="' . $idSelasa . '">
                                          <td>Selasa</td>
                                          <td>' . $hariSelsasa . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->tuesday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->tuesday->to . '</td>
                                          <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                      </tr>
                                      <tr class="' . $idRabu . '">
                                          <td>Rabu</td>
                                          <td>' . $hariRabu . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->wednesday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->wednesday->to . '</td>
                                          <td>' . $value->waktu_toleransi_keterlambatan . ' Menit </td>
                                      </tr>
                                      <tr class="' . $idKamis . '">
                                          <td>Kamis</td>
                                          <td>' . $hariKamis . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->thursday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->thursday->to . '</td>
                                          <td>' . $value->waktu_toleransi_keterlambatan . ' Menit </td>
                                      </tr>
                                      <tr class="' . $idJumat . '">
                                          <td>Jumat</td>
                                          <td>' . $hariJumat . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->friday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->friday->to . '</td>
                                          <td>' . $value->waktu_toleransi_keterlambatan . ' Menit </td>
                                      </tr>
                                      <tr class="' . $idSabtu . '">
                                          <td>Sabtu</td>
                                          <td>' . $hariSabtu . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->saturday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->saturday->to . '</td>
                                          <td>' .$value->waktu_toleransi_keterlambatan . ' Menit </td>
                                      </tr>
                                      <tr class="' . $idMinggu . '">
                                          <td>Minggu</td>
                                          <td>' . $hariMinggu . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->sunday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->sunday->to . '</td>
                                          <td>' . $value->waktu_toleransi_keterlambatan . ' Menit </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>';
        }
      }
    }

    return $modalList;
  }

  public function edit_pola($id)
  {
    $sess = $this->session->userdata('user');
    $sess = $this->session->userdata('user');
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama") {
      redirect(base_url());
    }

    $where['id_pola_kerja'] = $id;
    $data['data'] = $this->Db_select->select_where('tb_pola_kerja', $where);

    $file = 'appconfig/new/' . $data['data']->file_pola_kerja;
    if (!file_exists($file)) {
      $file = 'appconfig/new/jadwal_default_1.txt';
    }
    $data['listJadwal'] = json_decode(file_get_contents($file));
    $menu['main'] = 'pengaturan';
    $menu['child'] = 'pengaturan_pola';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/edit_pola');
    $this->load->view('Administrator/footer');
  }

  public function tambah_pola()
  {
    $sess = $this->session->userdata('user');
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama") {
      redirect(base_url());
    }
    $menu['main'] = 'pengaturan';
    $menu['child'] = 'pengaturan_pola';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/tambah_pola');
    $this->load->view('Administrator/footer');
  }

  public function insert_pola()
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];
    $namaFile = $id_channel . time() . '_auto_respon.txt';
    $file = 'appconfig/new/' . $namaFile;

    $insertDB['nama_pola_kerja'] = $this->input->post('nama_pola_kerja');

    if (isset($_POST['toleransi_keterlambatan'])) {
      $insertDB['toleransi_keterlambatan'] = 1;
      $insertDB['waktu_toleransi_keterlambatan'] = $this->input->post('waktu_toleransi_keterlambatan');
    } else {
      $insertDB['toleransi_keterlambatan'] = 0;
    }

    $day = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    $libur = 0;
    $hari_kerja = 7;

    for ($i = 0; $i < count($day); $i++) {
      if ($this->input->post($day[$i] . "A") == 0) {
        $libur = $libur + 1;
      }
    }

    $insertDB['lama_pola_kerja'] = $hari_kerja;
    $insertDB['lama_hari_kerja'] = $libur;
    $insertDB['lama_hari_libur'] = $hari_kerja - $libur;
    $insertDB['id_channel'] = $id_channel;

    $txt = array(
      "jam_kerja" => array(
        "monday" => array(
          "libur" => $this->input->post('mondayA'), "to" => $this->input->post('mondayC'), "from" => $this->input->post('mondayB')
        ), "tuesday" => array(
          "libur" => $this->input->post('tuesdayA'), "to" => $this->input->post('tuesdayC'), "from" => $this->input->post('tuesdayB')
        ), "wednesday" => array(
          "libur" => $this->input->post('wednesdayA'), "to" => $this->input->post('wednesdayC'), "from" => $this->input->post('wednesdayB')
        ), "thursday" => array(
          "libur" => $this->input->post('thursdayA'), "to" => $this->input->post('thursdayC'), "from" => $this->input->post('thursdayB')
        ), "friday" => array(
          "libur" => $this->input->post('fridayA'), "to" => $this->input->post('fridayC'), "from" => $this->input->post('fridayB')
        ), "saturday" => array(
          "libur" => $this->input->post('saturdayA'), "to" => $this->input->post('saturdayC'), "from" => $this->input->post('saturdayB')
        ), "sunday" => array(
          "libur" => $this->input->post('sundayA'), "to" => $this->input->post('sundayC'), "from" => $this->input->post('sundayB')
        )
      )
    );
    write_file($file, json_encode($txt));

    $insertDB['file_pola_kerja'] = $namaFile;

    $insert = $this->Db_dml->insert('tb_pola_kerja', $insertDB);

    if ($insert) {
      $result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
    } else {
      $result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
    }

    echo json_encode($result);
  }

  public function update_pola()
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];

    $where['id_pola_kerja'] = $this->input->post('id_pola_kerja');
    $getData = $this->Db_select->select_where('tb_pola_kerja', $where);
    if ($getData) {
      $file = 'appconfig/new/' . $getData->file_pola_kerja;

      $updateDB['nama_pola_kerja'] = $this->input->post('nama_pola_kerja');

      if (isset($_POST['toleransi_keterlambatan'])) {
        $updateDB['toleransi_keterlambatan'] = 1;
        $updateDB['waktu_toleransi_keterlambatan'] = $this->input->post('waktu_toleransi_keterlambatan');
      } else {
        $updateDB['toleransi_keterlambatan'] = 0;
      }

      $day = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
      $libur = 0;
      $hari_kerja = 7;

      for ($i = 0; $i < count($day); $i++) {
        if ($this->input->post($day[$i] . "A") == 1) {
          $libur = $libur + 1;
        }
      }

      $updateDB['lama_pola_kerja'] = $hari_kerja;
      $updateDB['lama_hari_kerja'] = $hari_kerja - $libur;
      $updateDB['lama_hari_libur'] = $libur;
      $updateDB['id_channel'] = $id_channel;

      $txt = array(
        "jam_kerja" => array(
          "monday" => array(
            "libur" => $this->input->post('mondayA'), "to" => $this->input->post('mondayC'), "from" => $this->input->post('mondayB')
          ), "tuesday" => array(
            "libur" => $this->input->post('tuesdayA'), "to" => $this->input->post('tuesdayC'), "from" => $this->input->post('tuesdayB')
          ), "wednesday" => array(
            "libur" => $this->input->post('wednesdayA'), "to" => $this->input->post('wednesdayC'), "from" => $this->input->post('wednesdayB')
          ), "thursday" => array(
            "libur" => $this->input->post('thursdayA'), "to" => $this->input->post('thursdayC'), "from" => $this->input->post('thursdayB')
          ), "friday" => array(
            "libur" => $this->input->post('fridayA'), "to" => $this->input->post('fridayC'), "from" => $this->input->post('fridayB')
          ), "saturday" => array(
            "libur" => $this->input->post('saturdayA'), "to" => $this->input->post('saturdayC'), "from" => $this->input->post('saturdayB')
          ), "sunday" => array(
            "libur" => $this->input->post('sundayA'), "to" => $this->input->post('sundayC'), "from" => $this->input->post('sundayB')
          )
        )
      );
      write_file($file, json_encode($txt));

      $this->Db_dml->update('tb_pola_kerja', $updateDB, $where);

      $result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
    } else {
      $result['status'] = false;
      $result['message'] = 'Data tidak ditemukan.';
      $result['data'] = array();
    }

    echo json_encode($result);
  }

  public function delete()
  {
    $where['id_pola_kerja'] = $this->input->post('id');
    $update['is_deleted'] = 1;

    $deleteData = $this->Db_dml->update('tb_pola_kerja', $update, $where);

    if ($deleteData) {
      $result['status'] = true;
      $result['message'] = 'Data berhasil dihapus.';
      $result['data'] = array();
    } else {
      $result['status'] = false;
      $result['message'] = 'Data gagal dihapus.';
      $result['data'] = array();
    }

    echo json_encode($result);
  }

  public function getData()
  {
    $sess = $this->session->userdata('user');
    $where = 'id_channel = "' . $sess['id_channel'] . '" and is_deleted = 0';
    $columns = array(
      0 =>  'no',
      1 =>  'nama_pola_kerja',
      2 =>  'lama_pola_kerja',
      3 =>  'lama_hari_kerja',
      4 =>  'lama_hari_libur',
      5 =>  'aksi'
    );
    $limit  = $this->input->post('length');
    $start  = $this->input->post('start');
    $order  = $columns[$this->input->post('order')[0]['column']];
    $dir    = $this->input->post('order')[0]['dir'];
    $totalData = $this->Db_global->allposts_count_all("select *from tb_pola_kerja where " . $where . "");
    $totalFiltered = $totalData;

    if (empty($this->input->post('search')['value'])) {
      $posts = $this->Db_global->allposts_all("select *from tb_pola_kerja where " . $where . " order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
    } else {
      $search = $this->input->post('search')['value'];
      $posts = $this->Db_global->posts_search_all("select *from tb_pola_kerja where " . $where . " and (nama_pola_kerja like '%" . $search . "%' or lama_pola_kerja like '%" . $search . "%' or lama_hari_kerja like '%" . $search . "%' or lama_hari_libur like '%" . $search . "%') order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
      $totalFiltered = $this->Db_global->posts_search_count_all("select *from tb_pola_kerja where " . $where . " and (nama_pola_kerja like '%" . $search . "%' or lama_pola_kerja like '%" . $search . "%' or lama_hari_kerja like '%" . $search . "%' or lama_hari_libur like '%" . $search . "%')");
    }

    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $key => $post) {
        $delete = "";
        if ($post->is_default == 0) {
          $delete = '<a href="#" onclick="hapus(' . $post->id_pola_kerja . ')" class="btn btn-sm btn-danger" data-type="ajax-loader"><span class="fa fa-trash"></span></a>';
        }
        $nestedData['no']  = $key + 1;
        $nestedData['nama_pola_kerja']  = $post->nama_pola_kerja;
        $nestedData['lama_pola_kerja']  = $post->lama_pola_kerja . " Hari";
        $nestedData['lama_hari_kerja']  = $post->lama_hari_kerja . " Hari";
        $nestedData['lama_hari_libur']  = $post->lama_hari_libur . " Hari";
        $nestedData['status']  = $post->is_default ? '<span class="badge badge-warning text-white">Default</span>' : '-';
        $nestedData['aksi']  = '
        <button class="btn btn-secondary btn-sm" data-type="ajax-loader" data-toggle="modal" data-target="#defaultModal' . $key . '"><span class="fa fa-search"></span></button>
        <a href="' . base_url('Administrator/jadwal_kerja/edit_pola/') . $post->id_pola_kerja . '" class="btn btn-info btn-sm"><span class="fa fa-pencil-alt"></span></a> ' . $delete;
        $data[] = $nestedData;
      }
    }
    $json_data = array(
      "draw"            => intval($this->input->post('draw')),
      "recordsTotal"    => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data"            => $data
    );

    echo json_encode($json_data);
  }
}
