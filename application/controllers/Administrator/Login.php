<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class login extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('Cookie', 'String'));
    $this->encryption->initialize(
      array(
        'cipher' => 'aes-256',
        'mode' => 'ctr',
        'key' => 'super-secret-key'
      )
    );
  }

  public function auth()
  {
    $password = md5($this->input->post('password'));
    $user = $this->Db_select->query('select a.*, b.id_channel new_id_channel from tb_user a left outer join tb_unit b on a.id_unit = b.id_unit where nip like "' . $this->input->post('username') . '" or email_user like "' . $this->input->post('username') . '"');
    $remember = $this->input->post('remember_me');

    if ($user) {
      if ($user->password_user == $password) {
        if ($user->is_aktif == 1) {
          if ($remember) {
            $cookie_remember['username'] = $this->input->post('username');
            $cookie_remember['password'] = $this->input->post('password');
            $cookie_remember['remember_me'] = true;
            $key = $this->encryption->encrypt(json_encode($cookie_remember));
            set_cookie('pressensiapps', $key, 3600 * 24 * 30);
            $update_key = array(
              'cookie' => $key
            );
            $this->Db_dml->update('tb_user', $update_key, ['user_id' => $user->user_id]);
          }

          if ($user->id_struktur != null || $user->id_struktur != '') {
            if ($user->id_struktur == 1) {
              if ($user->is_admin == 1 && $user->is_trial == 1) {
                $sess['email_user'] = $user->email_user;
                $sess['nama'] = $user->nama_user;
                $sess['foto'] = $user->foto_user;
                $sess['id_user'] = $user->user_id;
                $sess['id_unit'] = $user->id_unit;
                $sess['id_channel'] = $user->admin_channel;
                $sess['is_new'] = $user->is_new;
                $sess['akses'] = 'admin_utama';

                $this->session->set_userdata('user', $sess);
                $result['status'] = true;
                $result['message'] = 'Login berhasil.';
                $result['data'] = '';
              } else {
                $sess['email_user'] = $user->email_user;
                $sess['nama'] = $user->nama_user;
                $sess['foto'] = $user->foto_user;
                $sess['id_user'] = $user->user_id;
                $sess['id_unit'] = $user->id_unit;
                $sess['akses'] = 'sekda';
                $sess['id_channel'] = $user->new_id_channel;

                $this->session->set_userdata('user', $sess);
                $result['status'] = true;
                $result['message'] = 'Login berhasil.';
                $result['data'] = '';
              }
            } else {
              $sess['email_user'] = $user->email_user;
              $sess['nama'] = $user->nama_user;
              $sess['foto'] = $user->foto_user;
              $sess['id_user'] = $user->user_id;
              $sess['id_unit'] = $user->id_unit;
              $sess['role_access'] = $user->role;
              $sess['akses'] = 'staff';
              $sess['id_channel'] = $user->new_id_channel;

              $this->session->set_userdata('user', $sess);
              $result['status'] = true;
              $result['message'] = 'Login berhasil.';
              $result['data'] = '';
            }
          } else {
            if ($user->is_admin == 1 && $user->admin_channel != null) {
              $sess['email_user'] = $user->email_user;
              $sess['nama'] = $user->nama_user;
              $sess['foto'] = $user->foto_user;
              $sess['id_user'] = $user->user_id;
              $sess['id_unit'] = $user->id_unit;
              $sess['id_channel'] = $user->admin_channel;
              $sess['is_new'] = $user->is_new;
              $sess['akses'] = 'admin_utama';

              $this->session->set_userdata('user', $sess);
              $result['status'] = true;
              $result['message'] = 'Login berhasil.';
              $result['data'] = '';
            } elseif ($user->id_unit != null && $user->is_admin == null) {
              $sess['id_user'] = $user->user_id;
              $sess['email_user'] = $user->email_user;
              $sess['nama'] = $user->nama_user;
              $sess['foto'] = $user->foto_user;
              $sess['akses'] = 'admin_unit';
              $sess['id_channel'] = $user->new_id_channel;

              $this->session->set_userdata('user', $sess);
              $result['status'] = true;
              $result['message'] = 'Login berhasil.';
              $result['data'] = '';
            } else if ($user->is_superadmin == 1) {
              $sess['email_user'] = $user->email_user;
              $sess['nama'] = $user->nama_user;
              $sess['foto'] = $user->foto_user;
              $sess['id_user'] = $user->user_id;
              $sess['akses'] = 'superadmin';
              $sess['id_channel'] = $user->new_id_channel;

              $this->session->set_userdata('user', $sess);
              $result['status'] = true;
              $result['message'] = 'Login berhasil.';
              $result['data'] = '';
            } else {
              $result['status'] = false;
              $result['message'] = 'Anda tidak memiliki akses.';
              $result['data'] = '';
            }
          }
        } else {
          $result['status'] = false;
          $result['message'] = 'Akun Anda telah di non aktifkan karena suatu alasan';
          $result['data'] = '';
        }
      } else {
        $result['status'] = false;
        $result['message'] = 'Username dan password tidak cocok.';
        $result['data'] = '';
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Username dan password tidak cocok';
      $result['data'] = '';
    }

    echo json_encode($result);
  }

  public function requestResetPassword()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|regex_match[/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix]', [
      'required' => '%s tidak boleh kosong',
      'regex_match' => '%s tidak mempunyai format yang valid'
    ]);
    $isValid = $this->form_validation->run();
    if (!$isValid) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(422)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Harap perhatikan masukan yang di inputkan',
          'errors' => $this->form_validation->error_array(),
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $user = $this->Db_select->select_where('tb_user', ['email_user' => $this->input->post('email', true)]);

    if (is_null($user)) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(404)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Email tidak ditemukan',
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $date_now = strtotime(date('Y-m-d H:i:s') . '+1day');
    $date_now = date('Y-m-d H:i:s', $date_now);
    $reset_password_token = md5(now() . '.' . $this->randomCode());
    $is_updated = $this->Db_dml->update('tb_user', [
      'reset_password_token' => $reset_password_token,
      'reset_password_token_valid_until' => $date_now
    ], ['user_id' => $user->user_id]);

    if ($is_updated == 0) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(500)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Terjadi kesalahan pada server!',
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $message = $this->load->view('email/request_reset_password_email', [
      'link_reset_password' => base_url('login/reset-password?token=' . $reset_password_token)
    ], TRUE);
    $this->email->clear();
    $this->email->from('hello@pressensi.com', 'Admin Pressensi');
    $this->email->to($user->email_user);
    $this->email->subject('Reset password akun admin pressensi');
    $this->email->message($message);
    $is_deliver = $this->email->send();
    if (!$is_deliver) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(500)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Gagal mengirim email reset pasword',
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode([
        'status' => true,
        'message' => 'Berhasil mengirim email permintaan reset password!',
        'data' => []
      ], JSON_PRETTY_PRINT));
  }

  public function resetPasswordPage()
  {
    $token = $this->input->get('token', true);
    if (is_null($token)) {
      redirect(base_url('login'));
    }

    $user = $this->Db_select->select_where('tb_user', ['reset_password_token' => $token]);
    if (is_null($user)) {
      show_404();
    }

    if (now() > strtotime($user->reset_password_token_valid_until)) {
      $this->load->view('expired_token');
    } else {
      $this->load->view('reset_password');
    }
  }

  public function changePassword()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('token', 'Token', 'trim|required', [
      'required' => "%s tidak boleh kosong"
    ]);
    $this->form_validation->set_rules('new_password', 'Password baru', 'trim|required', [
      'required' => "%s tidak boleh kosong"
    ]);
    $this->form_validation->set_rules('confirm_password', 'Konfirmasi password', 'trim|required|matches[new_password]', [
      'required' => "%s tidak boleh kosong",
      'matches' => "%s tidak sama dengan password"
    ]);
    $isValid = $this->form_validation->run();
    if (!$isValid) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(422)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Harap perhatikan masukan yang di inputkan',
          'errors' => $this->form_validation->error_array(),
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $user = $this->Db_select->select_where('tb_user', ['reset_password_token' => $this->input->post('token', true)]);
    if (is_null($user)) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(404)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Token tidak ditemukan',
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }
    if (now() > strtotime($user->reset_password_token_valid_until)) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(403)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Token tidak sudah tidak valid',
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $is_updated = $this->Db_dml->update('tb_user', [
      'password_user' => md5($this->input->post('new_password', true)),
      'reset_password_token' => null,
      'reset_password_token_valid_until' => null
    ], ['user_id' => $user->user_id]);

    if ($is_updated == 0) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(500)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Password gagal diubah!',
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode([
        'status' => false,
        'message' => 'Password berhasil diubah!',
        'data' => []
      ], JSON_PRETTY_PRINT));
  }

  public function logout()
  {
    delete_cookie('pressensiapps');
    $this->session->unset_userdata('user');
    redirect(base_url('login'));
  }

  private function randomCode()
  {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $randomString = "";
    for ($i = 0; $i < 6; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
