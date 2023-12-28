<?php defined('BASEPATH') OR exit('No direct script access allowed');
class setting_akun extends CI_Controller
{ 
  function __construct()
  {
    parent::__construct();
    $this->load->library(array('ceksession','global_lib'));
    $this->load->model('Db_datatable');
    $this->ceksession->login();
    $this->global_lib = new global_lib;
  }

  public function index()
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];
    $getChannel = $this->Db_select->select_where('tb_channel', 'id_channel = '.$id_channel);
    $getUser = $this->Db_select->select_where('tb_user', 'user_id = '.$sess['id_user']);

    $data['list'] = '
        <form id="add-form" class="row">
          <div class="col-md-6">
            <label for="email_address">Nama Admin</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" value="'.$getUser->nama_user.'" disabled>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_address">Email/Username Admin</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="email_address" class="form-control" value="'.$getUser->email_user.'" disabled>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_address">Nomor Telepon</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="email_address" class="form-control" value="'.$getUser->telp_user.'" disabled>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_address">Alamat</small></label>
            <div class="form-group">
                <div class="form-line">
                  <textarea class="form-control" disabled readonly cols="30" rows="3">'.$getUser->alamat_user.'</textarea>
                </div>
            </div>
          </div>
        </form> 
    '; 
    $data['head'] = '
        <h2>Akun Data 
            <a href="'.base_url().'Leader/setting_akun/edit" class="btn btn-warning btn-sm float-right text-white">
                <span class="fa fa-pencil-alt"></span>
                <span>Edit Data</span>
            </a>
        </h2>
    ';
    $this->load->view('SEKDA/header',$data);
    $this->load->view('SEKDA/setting_akun');
    $this->load->view('SEKDA/footer');
  }

  public function edit()
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];
    $getChannel = $this->Db_select->select_where('tb_channel', 'id_channel = '.$id_channel);
    $getUser = $this->Db_select->select_where('tb_user', 'user_id = '.$sess['id_user']);
    // echo json_encode($sess);exit();
    $data['head'] = '<h2>Edit Akun Data </h2>';
    $data['list'] = '
        <form id="add-form" class="row" action="javascript:void(0);" method="post">
            <input type="hidden" name="user_id" value="'.$sess['id_user'].'" >
            <input type="hidden" name="id_channel" value="'.$sess['id_channel'].'" >
          
          <div class="col-md-6">
            <label for="nik">NIK</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" disabled readonly class="form-control" value="'.$getUser->nip.'" required>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="nama_user"><span class="text-danger">*</span> Nama</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="nama_user" name="nama_user" value="'.$getUser->nama_user.'" required>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_user"><span class="text-danger">*</span> Email</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="email_user" class="form-control" value="'.$getUser->email_user.'"  name="email_user" required>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="alamat_user"><span class="text-danger">*</span> Alamat</small></label>
            <div class="form-group">
                <div class="form-line">
                  <textarea class="form-control" name="alamat_user" id="alamat_user" required>'.$getUser->alamat_user.'</textarea>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="telp_user"><span class="text-danger">*</span> Nomor Telepon</label>
            <div class="form-group">
                <div class="form-line">
                    <input type="number" id="telp_user" class="form-control" name="telp_user" value="'.$getUser->telp_user.'">
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="password"><span class="text-danger">*</span> Password <span class="text-danger text-xs">*Kosongkan apaila tidak ingin meubah password</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="password" class="form-control" name="password">
                </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="float-right">
              <a href="'.base_url("Leader/setting_akun").'" class="btn btn-secondary"><span class="fa fa-ban"></span> Batal</a>
              <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
            </div>
          </div>
        </form> 
    '; 
    $this->load->view('SEKDA/header',$data);
    $this->load->view('SEKDA/setting_akun');
    $this->load->view('SEKDA/footer');
  }
   
  public function update()
  {
      $where['user_id'] = $this->input->post('user_id');
      $data['nama_user'] = $this->input->post('nama_user');
      $data['email_user'] = $this->input->post('email_user');
      $data['alamat_user'] = $this->input->post('alamat_user');
      $data['telp_user'] = $this->input->post('telp_user');
      if ($this->input->post('password')) {
        $data['password_user'] = md5($this->input->post('password'));
      }
      if($_FILES['foto_user']['name']){
        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
        $config['upload_path'] = './assets/images/member-photos'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = '2048'; //maksimum besar file 2M
        $config['file_name'] = $nmfile; //nama yang terupload nantinya
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('foto_user')){
          $gbr = $this->upload->data();
          $name3 = $gbr['file_name'];
          $data['foto_user'] = $name3;
        }else{
          $result['status'] = false;
          $result['message'] = $this->upload->display_errors();
          $result['data'] = array();
          echo json_encode($result); exit();
        }
      }
      $update = $this->Db_dml->update('tb_user', $data, $where);      
      if ($update == 1) {
        $result['status'] = true;
        $result['message'] = 'Data berhasil disimpan.';
        $result['data'] = array();
      }else{
        $result['status'] = false;
        $result['message'] = 'Tidak ada perubahan data.';
        $result['data'] = array();
      }
      echo json_encode($result);exit();
  }
    

}