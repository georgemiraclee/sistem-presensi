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
    if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
      redirect(base_url());exit();
    }
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
            <label for="email_address">Nama Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="email_address" class="form-control" value="'.$getChannel->nama_channel.'" disabled>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_address">Telepon Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="email_address" class="form-control" value="'.$getChannel->telp_channel.'" disabled>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_address">Deskripsi Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                  <textarea class="form-control" disabled readonly cols="30" rows="3">'.$getChannel->deskripsi_channel.'</textarea>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_address">Alamat Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                  <textarea class="form-control" disabled readonly cols="30" rows="3">'.$getChannel->alamat_channel.'</textarea>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_address">Email Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="email_address" class="form-control" value="'.$getChannel->email_channel.'" disabled>
                </div>
            </div>
          </div>
        </form> 
    '; 
    $data['head'] = '
        <h2>Akun Data 
            <a href="'.base_url().'Administrator/Setting_akun/edit" class="btn btn-warning btn-sm float-right text-white">
                <span class="fa fa-pencil-alt"></span>
                <span>Edit Data</span>
            </a>
        </h2>
    ';

    $menu['main'] = '';
    $menu['child'] = '';
    $data['menu'] = $menu;
    $this->load->view('Administrator/header',$data);
    $this->load->view('Administrator/setting_akun');
    $this->load->view('Administrator/footer');
  }

  public function edit()
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];
    if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
    redirect(base_url());exit();
    }
    $getChannel = $this->Db_select->select_where('tb_channel', 'id_channel = '.$id_channel);
    $getUser = $this->Db_select->select_where('tb_user', 'user_id = '.$sess['id_user']);
    // echo json_encode($sess);exit();
    $data['head'] = '<h2>Edit Akun Data </h2>';
    $data['list'] = '
        <form id="add-form" class="row" action="javascript:void(0);" method="post">
            <input type="hidden" name="user_id" value="'.$sess['id_user'].'" >
            <input type="hidden" name="id_channel" value="'.$sess['id_channel'].'" >
          
          <div class="col-md-6">
            <label for="nama_user"><span class="text-danger">*</span> Nama</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="nama_user" name="nama_user" value="'.$getUser->nama_user.'" required>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_user"><span class="text-danger">*</span> Username</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="email_user" class="form-control" value="'.$getUser->email_user.'"  name="email_user" required>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="foto_user"><span class="text-danger">*</span> Foto</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="file" id="foto_user" name="foto_user">
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="password"><span class="text-danger">*</span> Password <span class="text-danger text-xs">*Kosongkan apaila tidak ingin mengubah password</span></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="password" id="password" class="form-control" name="password">
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="nama_channel"><span class="text-danger">*</span> Nama Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="nama_channel" class="form-control" value="'.$getChannel->nama_channel.'"  name="nama_channel" required>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="deskripsi_channel">Deskripsi Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                  <textarea class="form-control" cols="30" rows="3" name="deskripsi_channel" id="deskripsi_channel">'.$getChannel->deskripsi_channel.'</textarea>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="alamat_channel">Alamat Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                  <textarea class="form-control" cols="30" rows="3" name="alamat_channel" id="alamat_channel">'.$getChannel->alamat_channel.'</textarea>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="telp_channel"><span class="text-danger">*</span> Telepon Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="telp_channel" class="form-control" value="'.$getChannel->telp_channel.'" name="telp_channel" required>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="email_channel"><span class="text-danger">*</span> Email Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" id="email_channel" class="form-control" value="'.$getChannel->email_channel.'" name="email_channel" required>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <label for="logo_channel">Logo Perusahaan</small></label>
            <div class="form-group">
                <div class="form-line">
                    <input type="file" id="logo_channel" name="logo_channel">
                </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="float-right">
              <a href="'.base_url("Administrator/setting_akun").'" class="btn btn-danger"><span class="fa fa-ban"></span> Batal</a>
              <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
            </div>
          </div>
        </form> 
    ';

    $menu['main'] = '';
    $menu['child'] = '';
    $data['menu'] = $menu;
    $this->load->view('Administrator/header',$data);
    $this->load->view('Administrator/setting_akun');
    $this->load->view('Administrator/footer');
  }
   
    public function update()
    {
        $sess = $this->session->userdata('user');
        $where['user_id'] = $this->input->post('user_id');
        $data['nama_user'] = $this->input->post('nama_user');
        $data['email_user'] = $this->input->post('email_user');
        if ($this->input->post('password')) {
            $data['password_user'] = md5($this->input->post('password'));
        }
        if($_FILES['foto_user']['name']){

            $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time

            $config['upload_path'] = './assets/images/member-photos'; //path folder

            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan

            $config['max_size'] = '2048'; //maksimum besar file 2M

            $config['file_name'] = $nmfile; //nama yang terupload nantinya

            $time = $this->input->post('user_id').'_'.strtotime('now');

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
         $where2['id_channel'] = $this->input->post('id_channel');
            $data2['nama_channel'] = $this->input->post('nama_channel');
            $data2['deskripsi_channel'] = $this->input->post('deskripsi_channel');
            $data2['alamat_channel'] = $this->input->post('alamat_channel');
            $data2['telp_channel'] = $this->input->post('telp_channel');
            $data2['email_channel'] = $this->input->post('email_channel');
            if($_FILES['logo_channel']['name']){
                $this->load->library('upload');
                $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
                $config['upload_path'] = './assets/images/unit'; //path folder
                $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
                $config['max_size'] = '2048'; //maksimum besar file 2M
                $config['file_name'] = $nmfile; //nama yang terupload nantinya
                $this->upload->initialize($config);
                if ($this->upload->do_upload('logo_channel')){
                    $gbr = $this->upload->data();
                    $name3 = $gbr['file_name'];
                    $data2['logo_channel'] = $name3;
                }else{
                    $result['status'] = false;

                    $result['message'] = $this->upload->display_errors();

                    $result['data'] = array();

                    echo json_encode($result); exit();
                }
            }
            $update2 = $this->Db_dml->update('tb_channel', $data2, $where2);
        if ($update == 1|| $update2 ==1) {
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