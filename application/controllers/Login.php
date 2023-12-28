<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index()
	{
		$this->load->view('login');
	}

	public function auth()
	{
		$password = md5($this->input->post('password'));

		$user = $this->db_select->query('select *from tb_user where nip like "%'.$this->input->post('username').'%" or email_user like "%'.$this->input->post('username').'%"');
    
		if ($user) {
			if ($user->password_user == $password) {
				if ($user->is_aktif == 1) {
					if ($user->is_superadmin == 1) {
						$sess['nip'] = $user->nip;
            $sess['nama'] = $user->nama_user;
            $sess['foto'] = $user->foto_user;
            $sess['akses'] = 'superadmin';
            $this->session->set_userdata('user',$sess);

            $result['status'] = true;
            $result['message'] = 'Login berhasil.';
            $result['data'] = '';
					}elseif ($user->is_admin_utama == 1) {
						$sess['email_user'] = $user->email_user;
            $sess['nama'] = $user->nama_user;
            $sess['foto'] = $user->foto_user;
            $sess['akses'] = 'admin_utama';
            $this->session->set_userdata('user',$sess);

            $result['status'] = true;
            $result['message'] = 'Login berhasil.';
            $result['data'] = '';
					}elseif ($user->id_unit != null && $user->is_admin == 1) {
						$sess['email_user'] = $user->email_user;
            $sess['nama'] = $user->nama_user;
            $sess['foto'] = $user->foto_user;
            $sess['akses'] = 'admin_unit';
            $this->session->set_userdata('user',$sess);

            $result['status'] = true;
            $result['message'] = 'Login berhasil.';
            $result['data'] = '';
					}else{
						$result['status'] = false;
            $result['message'] = 'Anda tidak memiliki akses.';
            $result['data'] = '';
					}
				}else{
					$result['status'] = true;
          $result['message'] = 'Akun Anda telah di non aktifkan karena suatu alasan';
          $result['data'] = '';
				}
			}else{
				$result['status'] = false;
        $result['message'] = 'Username dan password tidak cocok.';
        $result['data'] = '';
			}
		}else{
			$result['status'] = false;
      $result['message'] = 'Username dan password tidak cocok.';
      $result['data'] = '';
		}

		echo json_encode($result);
	}

	public function logout() {
    $this->session->unset_userdata('user');
    redirect(base_url());
  }
 public function rememberme(){
  if(!empty($_POST["remember_me"])) {
    //buat cookie
    setcookie ("user_id",$_POST["username"],time()+ (3600 * 365 * 24 * 60 * 60));
    setcookie ("password",$_POST["password"],time()+ (3600 * 365 * 24 * 60 * 60));
  } else {
    if(isset($_COOKIE["login"])) {
     setcookie ("login","");
     }
     if(isset($_COOKIE["userpassword"])) {
     setcookie ("userpassword","");
     }
   }

 }
  public function forgotPassword()
  {
    if ($_POST) {
      $username = $this->input->post('username');
      /* get data user */
      $getData = $this->Db_select->query('select *from tb_user where email_user = "'.$username.'"');

      if ($getData) {
        /* aksi kirim email */
        $respon = json_decode($this->sendMail($getData->user_id));
        if ($respon->status) {
          $result['status'] = true;
          $result['message'] = "Proses perubahan password telah kami kirim melalui email.";
        }else{
          $result['status'] = false;
          $result['message'] = "Perubahan password gagal dikirim.";
        }
      } else {
        $result['status'] = false;
        $result['message'] = "Akun anda belum terdaftar di dalam aplikasi.";
      }

      echo json_encode($result); exit();
    }else{
      $this->load->view('forgot');
    }
  }

  public function sendMail($user_id)
  {
    $data = array(
      'user_id' => $user_id
    );

    // Prepare new cURL resource
    $ch = curl_init('https://pressensi.com/api/new/user/sendEmailNew');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Set HTTP Header for POST request 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Submit the POST request
    $result = curl_exec($ch);
    
    // Close cURL session handle
    curl_close($ch);

    return $result;
  }
}
