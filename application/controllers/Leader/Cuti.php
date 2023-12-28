<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class cuti extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('ceksession','global_lib'));
		$this->ceksession->login();

		$this->global_lib = new global_lib;
	}

	public function index()
	{
		$sess = $this->session->userdata('user');
    if (!$sess['akses']) {
      redirect(base_url());exit();
    }
    $parrent=$sess['id_user'];

		$data['data_cuti'] = $this->Db_select->query_all('select *from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where id_parent = '.$parrent.'');

		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/cuti');
		$this->load->view('SEKDA/footer');
	}

	public function insert()
	{
		$sess = $this->session->userdata('user');
		$insert = array();

		$insert['id_channel'] = $sess['id_channel'];
		$insert['nama_unit'] = $this->input->post('nama_unit');
		$insert['alamat_unit'] = $this->input->post('alamat_unit');

    if($_FILES['userfile']['name']) {
      $this->load->library('upload');
      $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
      $config['upload_path'] = './assets/images/unit'; //path folder
      $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
      $config['max_size'] = '2048'; //maksimum besar file 2M
      $config['file_name'] = $nmfile; //nama yang terupload nantinya

      $this->upload->initialize($config);

      if ($this->upload->do_upload('userfile')){
        $gbr = $this->upload->data();
        $name3 = $gbr['file_name'];
        $insert['icon_unit'] = $name3;
      }
    }

		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_unit', $insert);

			if ($insertData) {
				$result['status'] = true;
        $result['message'] = 'Data berhasil disimpan.';
        $result['data'] = array();
			}else{
				$result['status'] = false;
        $result['message'] = 'Data gagal disimpan.';
        $result['data'] = array();
			}
		}else{
			$result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
		}

		echo json_encode($result);
	}

	public function delete()
	{
		$where['id_unit'] = $this->input->post('id_unit');

		$delete = $this->Db_dml->delete('tb_unit', $where);

		if ($delete == 1) {
			$result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
		}else{
			$result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
		}

		echo json_encode($result);
	}

	public function update()
	{
		$where['id_unit'] = $this->input->post('id_unit');
		$update = array();

		if ($this->input->post('nama_unit')) {
			$update['nama_unit'] = $this->input->post('nama_unit');
		}

		if ($this->input->post('alamat_unit')) {
			$update['alamat_unit'] = $this->input->post('alamat_unit');
		}

		if($_FILES['userfile']['name']){
      $this->load->library('upload');
      $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
      $config['upload_path'] = './assets/images/unit'; //path folder
      $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
      $config['max_size'] = '2048'; //maksimum besar file 2M
      $config['file_name'] = $nmfile; //nama yang terupload nantinya

      $this->upload->initialize($config);

      if ($this->upload->do_upload('userfile')){
				$gbr = $this->upload->data();
        $name3 = $gbr['file_name'];
        $update['icon_unit'] = $name3;
			}
    }

		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_unit', $update, $where);

			if ($updateData) {
				$result['status'] = true;
        $result['message'] = 'Data berhasil disimpan.';
        $result['data'] = array();
			}else{
				$result['status'] = false;
        $result['message'] = 'Data gagal disimpan.';
        $result['data'] = array();
			}
		}else{
			$result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
		}

		echo json_encode($result);
	}

  public function getRoleAccess($id_pengajuan, $data)
  {
    if ($data->id_parent) {
      $sess = $this->session->userdata('user');
      if ($sess['id_user'] == $data->id_parent) {
        return true;
      } else {
        $cekApproval = $this->Db_select->select_where('tb_history_approval_pengajuan', ['id_pengajuan' => $id_pengajuan, 'user_id' => $data->id_parent]);
        if ($cekApproval) {
          return true;
        } else {
          return false;
        }
      }
    } else {
      return true;
    }
  }

  function getWorkingDays($startDate, $endDate){
		$begin=strtotime($startDate);
		$end=strtotime($endDate);

    return ceil(abs($end - $begin) / 86400);
	}

	public function approve_pengajuan() {
		$sess = $this->session->userdata('user');
		$update['status_approval'] = 1;
		$where['id_pengajuan'] = $this->input->post('id');
		$getUser = $this->Db_select->query('select *from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where a.id_pengajuan = "'.$this->input->post('id').'"');
		$dataUser = $this->Db_select->select_where('tb_user','user_id = "'.$getUser->user_id.'"');
        
    if ($dataUser) {
      /* cek type pengajuan */
      if ($getUser->nama_status_pengajuan == "cuti" || $getUser->nama_status_pengajuan == "Cuti" || $getUser->nama_status_pengajuan == "Cuti Tahunan") {
        $getCuti = $this->Db_select->select_where('tb_pengaturan_cuti','id_channel = '.$sess['id_channel']);

        if (!$getCuti) {
          $input['jumlah_cuti_tahunan'] = 14;
          $input['jatah_cuti_bulanan'] = 3;
          $input['batasan_cuti'] = 0;
          $input['id_channel'] = $sess['id_channel'];

          $this->Db_dml->insert('tb_pengaturan_cuti', $input);
        }

        $getCuti = $this->Db_select->select_where('tb_pengaturan_cuti','id_channel = '.$sess['id_channel']);
        
        $beginday = date("Y-m-d", strtotime($getUser->tanggal_awal_pengajuan));
        $lastday = date("Y-m-d", strtotime($getUser->tanggal_akhir_pengajuan));
        
        $nr_work_days = $this->getWorkingDays($beginday,$lastday);
        
        $dataCuti = $dataUser->cuti;
        
        if ($dataCuti < $nr_work_days) {
          $result['status'] = false;
          $result['message'] = 'Cuti tahunan user telah habis.';
          $result['data'] = array();
        }else{
          if ($getCuti->batasan_cuti == 1) {
            $getHistory = $this->Db_select->query('select count(*) total from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where user_id = "'.$getUser->user_id.'" and nama_status_pengajuan in("Cuti", "Cuti Tahunan) and status_approval = 1 and date_format(now(), "%Y-%m") = date_format(tanggal_awal_pengajuan, "%Y-%m")');

            if ($getHistory->total > $getCuti->jatah_cuti_bulanan) {
              $this->result = array(
                'status' => false,
                'message' => 'Cuti bulanan anda sudah habis',
                'data' => null
              );

              $this->loghistory->createLog($this->result);
              echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
            }
          }

          /* set history */
          $insert['id_pengajuan'] = $this->input->post('id');
          $insert['user_id'] = $sess['id_user'];
          $insert['status'] = 1;

          if ($this->Db_dml->insert('tb_history_approval_pengajuan', $insert)) {
            /*update sisa cuti*/
            $whereCuti['user_id'] = $getUser->user_id;
            $updateCuti['cuti'] = $dataCuti;
            $this->Db_dml->update('tb_user',$updateCuti, $whereCuti);

            $getData = $this->Db_select->query('select b.nama_status_pengajuan from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where a.id_pengajuan = "'.$this->input->post('id').'"');

            $result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();

            $message = "Pengajuan  ".$getData->nama_status_pengajuan." layer 1 Anda Telah Disetujui";
            $this->global_lib->send_notification_user($getUser->user_id, 'acc_pengajuan', $message, $this->input->post('id'));
            // FCM
            $this->global_lib->NEWsendFCM('Approval Pengajuan '.$getData->nama_status_pengajuan, $message, $getUser->user_id,'','pengajuan',$getData->nama_status_pengajuan);
          } else {
            $result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
          }
        }
      }else{
        /* set history */
        $insert['id_pengajuan'] = $this->input->post('id');
        $insert['user_id'] = $getUser->user_id;
        $insert['status'] = 1;

        if ($this->Db_dml->insert('tb_history_approval_pengajuan', $insert)) {
          $message = "Pengajuan  ".$getUser->nama_status_pengajuan." layer 1 Anda Telah Disetujui";
          $this->global_lib->send_notification_user($getUser->user_id, 'acc_pengajuan', $message, $this->input->post('id'));
          // FCM
          $this->global_lib->NEWsendFCM('Approval Pengajuan '.$getUser->nama_status_pengajuan, $message, $getUser->user_id,'','pengajuan',$getUser->nama_status_pengajuan);

          $result['status'] = true;
          $result['message'] = 'Approval pengajuan berhasil.';
          $result['data'] = null;
        } else {
          $result['status'] = false;
          $result['message'] = 'Approval pengajuan gagal.';
          $result['data'] = null;
        }
      }
    }else{
      $result['status'] = false;
      $result['message'] = 'Data tidak ditemukan.';
      $result['data'] = array();
    }    

		echo json_encode($result);
	}

	public function reject_pengajuan() {
		$sess = $this->session->userdata('user');
		$where['id_pengajuan'] = $this->input->post('id');
		$update['status_approval'] = 2;

    /* history Approval */
    $historyApproval['id_pengajuan'] = $this->input->post('id');
    $historyApproval['user_id'] = $sess['id_user'];
    $historyApproval['status'] = 2;
    $updateData = $this->Db_dml->insert('tb_history_approval_pengajuan', $historyApproval);

		// $updateData = $this->Db_dml->update('tb_pengajuan', $update, $where);
		$getData = $this->Db_select->query('select b.nama_status_pengajuan from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where a.id_pengajuan = "'.$this->input->post('id').'"');

		if ($updateData) {
			$result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();

      $getUser = $this->Db_select->select_where('tb_pengajuan', $where)->user_id;
			$this->global_lib->send_notification_user($getUser, 'reject_pengajuan');

			// FCM
			$message = "Pengajuan  ".$getData->nama_status_pengajuan." layer 1 Anda Tidak Disetujui";
			$this->global_lib->NEWsendFCM('Approval Pengajuan '.$getData->nama_status_pengajuan, $message, $getUser,'','pengajuan',$getData->nama_status_pengajuan);
		}else{
			$result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
		}

		echo json_encode($result);
	}

	public function detail_cuti($id)
	{
		$data_cuti = $this->Db_select->query('select *from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where id_pengajuan = '.$id.'');
    
    $data_jabatan=$this->Db_select->query('select * from tb_jabatan where id_jabatan = '.$data_cuti->jabatan.'');
    $jabatan = $data_jabatan->nama_jabatan;
    $data_status=$this->Db_select->query('select * from tb_status_user where id_status_user ='.$data_cuti->status_user.' ');
    $status=$data_status->nama_status_user;

    $getHistory = $this->Db_select->select_where('tb_history_approval_pengajuan', ['id_pengajuan' => $id]);

    $data['tombol'] = '';
    if ($data_cuti->status_approval == 1) {
      $status_approval = '<span class="badge bg-green">Approval</span>';
    } else if ($data_cuti->status_approval == 2) {
      $status_approval = '<span class="badge badge-danger">Rejected</span>';
    } else {
      $status_approval = '<span class="badge badge-warning text-white">Butuh Konfirmasi</span>';
    }

    if (!$getHistory) {
      if ($data_cuti->status_approval == 1) {
        $status_approval = '<span class="badge badge-success">Approval</span>';
      } else if ($data_cuti->status_approval == 2) {
        $status_approval = '<span class="badge badge-danger">Rejected</span>';
      } else {
        $status_approval = '<span class="badge badge-warning text-white">Butuh Konfirmasi</span>';
        $data['tombol'] = '
          <button id="acc'.$data_cuti->id_pengajuan.'" onclick="acc('.$data_cuti->id_pengajuan.')" class="btn btn-primary btn-block btn-sm">ACC</button>
          <button id="reject'.$data_cuti->id_pengajuan.'" onclick="reject('.$data_cuti->id_pengajuan.')" class="btn btn-warning text-white btn-block btn-sm">Reject</button>';
      } 
    }

    if ($data_cuti->foto_user==""||$data_cuti->foto_user==null) {
      $data['foto'] = "https://sman93jkt.sch.id/wp-content/uploads/2018/01/765-default-avatar.png";
    }else{
      $filename = './assets/images/member-photos/'.$data_cuti->foto_user;
      if (!file_exists($filename)) {
        $data['foto'] = "https://sman93jkt.sch.id/wp-content/uploads/2018/01/765-default-avatar.png";
      } else {
        $data['foto'] = " ".base_url()."assets/images/member-photos/".$data_cuti->foto_user;
      }
    }

    $data['item'] = $data_cuti;
    $data['history'] = $this->Db_select->query_all('select *from tb_history_approval_pengajuan a join tb_user b on a.user_id = b.user_id where a.id_pengajuan = '.$id);
    $data['list']="
      <h5>".$data_cuti->nip."</h5>
      <h5>".$data_cuti->nama_user."</h5>
      <h5>".$data_cuti->nama_unit."</h5>
      <h5>".$jabatan."</h5>
      <h5>".$status."</h5>
      <h5>".$data_cuti->email_user."</h5>
      <h5>".$data_cuti->telp_user."</h5>
      <h5>".$data_cuti->alamat_user."</h5>";
    $data['cuti']="<h5>Keterangan Pengajuan Cuti :".$data_cuti->keterangan_pengajuan."</h5>
     <h5>Waktu Pengajuan Cuti : ".date('d-m-Y', strtotime($data_cuti->tanggal_awal_pengajuan))." sampai ".date('d-m-Y', strtotime($data_cuti->tanggal_akhir_pengajuan))." </h5>
     <h5>Kategori Pengajuan Cuti : ".$data_cuti->nama_status_pengajuan." </h5>
     <h5>Status Pengajuan Cuti : ".$status_approval." </h5>";
		
		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/detail_cuti');
		$this->load->view('SEKDA/footer');
	}
}