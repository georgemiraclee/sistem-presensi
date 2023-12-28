<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class auth extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('global_lib', 'loghistory'));
        $this->loghistory = new loghistory();
        $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );
    }
    public function registration_by()
    {
        $require = array('nama', 'email');
        $this->global_lib->input($require);
        $where['email'] = $this->input->post('email');
        $user = $this->db_select->select_where('tb_trial', $where);
        if (!$user) {               
            $insert['nama_user'] = $this->input->post('nama');
            $insert['email'] = $this->input->post('email');
            $insert['password'] = md5($this->random_code());
            $insert['perusahaan'] = "";
            $insert['jumlah_pegawai'] = 0;
            $insert['telepon'] = $this->input->post('telepon');
            $insert['alamat'] = "";
            $insert['is_aktif'] = 1;
            $registration = $this->db_dml->normal_insert('tb_trial', $insert);
            if ($registration == 1) {
                $getUser = $this->db_select->select_where('tb_trial',$where);
                $data['id_trial'] = $getUser->id_trial;
                $data['nama_user'] = $getUser->nama_user;
                $data['email'] = $getUser->email;
                $this->result = array(
                    'status' => true,
                    'message' => 'Registrasi berhasil, kami akan verifikasi akun anda.',
                    'data' => $data
                );
            }else{
                $this->result = array(
                    'status' => false,
                    'message' => 'Registrasi gagal, silahkan coba lagi',
                    'data' => null
                );
            }
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Email telah digunakan',
                'data' => null
            );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
    }
    public function informasi_pengguna()
    {
        $require = array('id_trial','nama_user', 'email' , 'telepon', 'alamat','perusahaan','telepon_perusahaan','jumlah_pegawai','alamat_perusahaan','id_provinsi','id_kota','id_kecamatan','id_kelurahan');
        $this->global_lib->input($require);
        $date = strtotime("+7 day");

        $insert['nama_channel'] = $this->input->post('perusahaan');
        $insert['telp_channel'] = $this->input->post('telepon_perusahaan');
        $insert['alamat_channel'] = $this->input->post('alamat_perusahaan');
        $insert['limit_user'] = 5;
        $insert['status'] = 1;
        $insert['is_trial'] = 1;
        $insert['id_provinsi'] = $this->input->post('id_provinsi');
        $insert['id_kota'] = $this->input->post('id_kota');
        $insert['id_kecamatan'] = $this->input->post('id_kecamatan');
        $insert['id_kelurahan'] = $this->input->post('id_kelurahan');
        $insert['expired_date'] = date('Y-d-d h:i', $date);
        $insert_data = $this->db_dml->normal_insert('tb_channel', $insert);

        if ($insert_data) {
            $update['id_channel'] = $insert_data;
            $update['nama_user'] = $this->input->post('nama_user');
            $update['email'] = $this->input->post('email');
            $update['telepon'] = $this->input->post('telepon');
            $update['alamat'] = $this->input->post('alamat');
            $update['jumlah_pegawai'] = $this->input->post('jumlah_pegawai');
            $where['id_trial'] = $this->input->post('id_trial');
            $update_data = $this->db_dml->update('tb_trial', $update, $where);
            if ($update_data == 1) {
                $data['id_trial'] = $this->input->post('id_trial');
                $data['id_channel'] = $insert_data;
                $data['expired_date'] = date('Y-d-d h:i', $date);
                $this->result = array(
                    'status' => true,
                    'message' => 'Data Personal & Perusahaan Berhasil disimpan.',
                    'data' => $data
                );
            }else{
                $this->result = array(
                    'status' => false,
                    'message' => 'Data gagal disimpan, silahkan coba lagi',
                    'data' => null
                );
            }
           
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Data Perusahaan Gagal Disimpan, silahkan coba lagi',
                'data' => null
            );
        }

        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
    }
    public function data_provinsi() {

        $getProvinsi = $this->db_select->select_all('data_provinsi'); 
        if ($getProvinsi == null || $getProvinsi == []) {
            $this->result = array(
                    'status' => false,
                    'message' => 'Data Provinsi Kosong',
                    'data' => null
                );
        }else{
            $this->result = array(
                    'status' => true,
                    'message' => 'Data Provinsi Berhasil Diambil',
                    'data' => $getProvinsi
                );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
    }
    public function data_kota($id) {

        $getKota = $this->db_select->select_all_where('data_kota', 'id_provinsi = '.$id ); 
        if ($getKota == null || $getKota == []) {
            $this->result = array(
                    'status' => false,
                    'message' => 'Data Kota Kosong',
                    'data' => null
                );
        }else{
            $this->result = array(
                    'status' => true,
                    'message' => 'Data Kota Berhasil Diambil',
                    'data' => $getKota
                );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
    }
    public function data_kecamatan($id) {

        $getKecamatan = $this->db_select->select_all_where('data_kecamatan', 'id_kota = '.$id ); 
        if ($getKecamatan == null || $getKecamatan == []) {
            $this->result = array(
                    'status' => false,
                    'message' => 'Data Kecamatan Kosong',
                    'data' => null
                );
        }else{
            $this->result = array(
                    'status' => true,
                    'message' => 'Data Kecamatan Berhasil Diambil',
                    'data' => $getKecamatan
                );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
    }
    public function data_kelurahan($id) {

        $getKelurahan = $this->db_select->select_all_where('data_kelurahan', 'id_kecamatan = '.$id ); 
        if ($getKelurahan == null || $getKelurahan == []) {
            $this->result = array(
                    'status' => false,
                    'message' => 'Data Kelurahan Kosong',
                    'data' => null
                );
        }else{
            $this->result = array(
                'status' => true,
                'message' => 'Data Kelurahan Berhasil Diambil',
                'data' => $getKelurahan
            );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
    }
    public function registration_subscriber()
    {
        $require = array('nama_user', 'email', 'telepon', 'password','perusahaan','alamat_perusahaan','deskripsi_perusahaan','jumlah_pegawai','harga','jenis_subscribe');
        $this->global_lib->input($require);
        $date = strtotime("+31 day");

        $insert['nama_channel'] = $this->input->post('perusahaan');
        $insert['alamat_channel'] = $this->input->post('alamat_perusahaan');
        $insert['deskripsi_channel'] = $this->input->post('deskripsi_perusahaan');
        $insert['limit_user'] = $this->input->post('jumlah_pegawai');
        $insert['status'] = 1;
        $insert['is_trial'] = 0;
        $insert['expired_date'] = date('Y-d-d h:i', $date);
        $insert_data = $this->db_dml->normal_insert('tb_channel', $insert);

        if ($insert_data) {
            $where['email_user'] = $this->input->post('email');
            $where2['email'] = $this->input->post('email');
            $is_user = $this->db_select->select_where('tb_user', $where);
            $is_subscriber = $this->db_select->select_where('tb_subscriber', $where2);
            if (!$is_user && !$is_subscriber) {
                $where_telp['telp_user'] = $this->input->post('telepon');
                $where_telp2['telepon'] = $this->input->post('telepon');
                $is_user_telp = $this->db_select->select_where('tb_user', $where_telp);
                $is_subscriber_telp = $this->db_select->select_where('tb_subscriber', $where_telp2);
                if (!$is_user_telp && !$is_subscriber_telp) {
                    $insert_user['nama_user']=$this->input->post('nama_user');
                    $insert_user['email_user']=$this->input->post('email_user');
                    $insert_user['telp_user']=$this->input->post('telepon');
                    $insert_user['is_admin']=1;
                    $insert_user['password_user']=$this->input->post('password');
                    $insert_data_user = $this->db_dml->normal_insert('tb_user', $insert_user);
                    if ($insert_data_user) {
                        $insert_subscriber['nama_user']=$this->input->post('nama_user');
                        $insert_subscriber['email_user']=$this->input->post('email_user');
                        $insert_subscriber['telp_user']=$this->input->post('telepon');
                        $insert_subscriber['password_user']=$this->input->post('password');
                        $insert_subscriber['perusahaan'] = $this->input->post('perusahaan');
                        $insert_subscriber['alamat_perusahaan'] = $this->input->post('alamat_perusahaan');
                        $insert_subscriber['deskripsi_perusahaan'] = $this->input->post('deskripsi_perusahaan');
                        $insert_subscriber['jenis_subscribe'] = $this->input->post('jenis_subscribe');
                        $insert_subscriber['harga'] = $this->input->post('harga');
                        $insert_data_subscriber = $this->db_dml->normal_insert('tb_subscriber', $insert_subscriber);
                        if ($insert_data_subscriber) {
                            $data['id_channel'] = $insert_data;
                            $data['id_user'] = $insert_data_user;
                            $data['limit_user'] = $this->input->post('jumlah_pegawai');
                            $data['expired_date'] = date('Y-d-d h:i', $date);

                             $this->result = array(
                            'status' => true,
                            'message' => 'Registrasi Berhasil, Silahkan lanjutkan pembayaran',
                            'data' => null
                            );
                        }else{
                            $this->result = array(
                            'status' => false,
                            'message' => 'Data Subsriber Gagal Disimpan',
                            'data' => null
                            );
                        }

                    }else{
                        $this->result = array(
                        'status' => false,
                        'message' => 'Data User Gagal Disimpan',
                        'data' => null
                        );
                    }
                }else{
                    $this->result = array(
                    'status' => false,
                    'message' => 'Nomor Telepon telah digunakan',
                    'data' => null
                    );
                }

            }else{
                $this->result = array(
                'status' => false,
                'message' => 'Email telah digunakan',
                'data' => null
                );
            }
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Data Perusahaan Gagal Disimpan, silahkan coba lagi',
                'data' => null
            );
        }

        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
    }
    public function random_code() {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < 6; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}