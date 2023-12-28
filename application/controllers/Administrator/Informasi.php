<?php defined('BASEPATH') OR exit('No direct script access allowed');
class informasi extends CI_Controller
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
        $data['informasi'] = $this->Db_select->select_all_where('tb_informasi','id_channel='.$id_channel);
        $this->load->view('Administrator/header', $data);
        $this->load->view('Administrator/informasi');
        $this->load->view('Administrator/footer');
    }
    public function add()
    {
        $sess = $this->session->userdata('user');
        $this->load->view('Administrator/header');
        $this->load->view('Administrator/tambah_informasi');
        $this->load->view('Administrator/footer');
    }
    public function insert()
    {
        $sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel'];
        $data['judul_informasi'] = $this->input->post('judul_informasi');
        $data['type_informasi'] = $this->input->post('tipe');
        $data['penerima'] = $this->input->post('penerima');
        $data['deskripsi_informasi'] = $this->input->post('deskripsi_informasi');
        $data['id_channel']=$id_channel;
        
        if ($_FILES['userfile']['name'] != '') {
          $path = realpath('./assets/images/informasi');
          $time = '1_'.strtotime('now');
          $config = array(
            'allowed_types' => 'jpg|jpeg|gif|png',
            'upload_path' => $path,
            'encrypt_name' => false,
            'file_name' => $time
          );
          $this->upload->initialize($config);
          if ($this->upload->do_upload()) {
            $img_data = $this->upload->data();
            $new_imgname = $time.$img_data['file_ext'];
            $new_imgpath = $img_data['file_path'].$new_imgname;
            rename($img_data['full_path'], $new_imgpath);
            $data['url_file_informasi'] = $new_imgname;
          } else {
            $result['status'] = false;
            $result['message'] = $this->upload->display_errors();
            $result['data'] = array();
            echo json_encode($result); exit();
          }
        }

        $insertData = $this->Db_dml->insert('tb_informasi', $data);
        if ($insertData) {
          $result['status'] = true;
          $result['message'] = 'Data berhasil disimpan.';
          $result['data'] = array();
          $message = "informasi ".$this->input->post('judul_informasi');
          $this->global_lib->send_notification2('informasi', $id_channel, $message, $insertData);
          // FCM
          $this->global_lib->sendFCMAll2('informasi',$message, $id_channel);
        }else{
            $result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
        }
        echo json_encode($result);exit();
    }
    public function delete()
    {
        $where['id_informasi'] = $this->input->post('id_informasi');
        $delete = $this->Db_dml->delete('tb_informasi', $where);
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
    public function edit($id)
    {
        $sess = $this->session->userdata('user');
        // GET informasi
        $where['id_informasi'] = $id;
        $informasi = $this->Db_select->select_where('tb_informasi', $where);
        $data['judul_informasi'] = $informasi->judul_informasi;
        $data['deskripsi_informasi'] = $informasi->deskripsi_informasi;
        $data['id_informasi'] = $id;
        $this->load->view('Administrator/header', $data);
        $this->load->view('Administrator/edit_informasi');
        $this->load->view('Administrator/footer');
    }
    public function update()
    {
        $sess = $this->session->userdata('user');
        $where['id_informasi'] = $this->input->post('id_informasi');
        $data['judul_informasi'] = $this->input->post('judul_informasi');
        $data['deskripsi_informasi'] = $this->input->post('deskripsi_informasi');
        if ($_FILES['userfile']['name'] != '') {
            $path = realpath('./assets/images/informasi');
            $time = '1_'.strtotime('now');
            $config = array('allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => $path,
                            'encrypt_name' => false,
                            'file_name' => $time
                           );
            $this->upload->initialize($config);
            if ($this->upload->do_upload()) {
                $img_data = $this->upload->data();
                $new_imgname = $time.$img_data['file_ext'];
                $new_imgpath = $img_data['file_path'].$new_imgname;
                rename($img_data['full_path'], $new_imgpath);
                $data['url_file_informasi'] = $new_imgname;
            } else {
                $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>".$this->upload->display_errors()."</div>";
                $this->session->set_flashdata('pesan', $pesan);
                redirect(base_url()."Administrator/pegawai/edit/".$this->input->post('user_id'));exit();
            }
        }
        $update = $this->Db_dml->update('tb_informasi', $data, $where);
        if ($update == 1) {
            $result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
        }else{
            $result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
        }
        echo json_encode($result);exit();
    }
    public function update_status()
    {
        $sess = $this->session->userdata('user');
        $where['id_informasi'] = $this->input->post('id_informasi');
        $update['is_aktif'] = $this->input->post('is_aktif');
        $updateData = $this->Db_dml->update('tb_informasi', $update, $where);
        if ($updateData) {
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
    function get_data_user($value = null)
    {
        $sess = $this->session->userdata('user');
        $id_channel= $sess['id_channel'];
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
            redirect(base_url());exit();
        }
        $tb= 'tb_informasi';
        $wr= 'id_channel='.$id_channel ;
        $fld =  array(null,'judul_informasi','deskripsi_informasi','is_aktif','url_file_informasi');
        $src = array('judul_informasi');
        $ordr = array('created_informasi' => 'desc');;
        $list = $this->Db_datatable->get_datatables2($tb,$wr,$fld,$src,$ordr);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
           
             if ($field->is_aktif == 1) { 
                $varC = 'checked';
             }
             
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('d M Y', strtotime($field->created_informasi));
            $row[] = $field->judul_informasi;
            $row[] = '
                <div class="demo-switch">
                    <div class="switch">
                        <label>Tidak Aktif<input value="'.$field->id_informasi.'" onclick="is_aktif('.$field->id_informasi.')" type="checkbox" id="is_aktif'.$field->id_informasi.'" '.$varC.'><span class="lever"></span>Aktif</label>
                    </div>
                </div>
            ';
           
            $row[] = '<a href="<?php echo base_url();?>Administrator/informasi/edit/'.$field->id_informasi.'" style="color: grey"><span class="material-icons">mode_edit</span></a>
                     <a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus('.$field->id_informasi.')"><span class="material-icons">delete</span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Db_datatable->count_all2($tb,$wr,$fld,$src,$ordr),
            "recordsFiltered" => $this->Db_datatable->count_filtered2($tb,$wr,$fld,$src,$ordr),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
    public function list_ttp($id = null)
    {
        $sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel'];
        $dep = $this->Db_select->select_all_where('tb_unit','id_channel = '.$id_channel);
        $varB="";
        foreach ($dep as $key => $value) {
         $varB.="<option value='".$value->id_unit."'>".$value->nama_unit."</option>";
         }
                                                            
        $us = $this->Db_select->query_all('select * from tb_user a join tb_unit b on a.id_unit = b.id_unit where id_channel ='.$id_channel);
        $varA="";
        foreach ($us as $key => $values) {
         $varA.="<option value='".$values->user_id."'>".$values->nama_user."</option>";
         }
        $list = "";
        if ($id== 1) {
            $list .= '
            <input type="hidden" name="tipe" value="departement">
            <div class="form-group form-float">
                <label class="form-label"><i class="col-red">*</i> Pilih Unit Kerja</label>
                <div class="form-line">
                    <select class="form-control show-tick" name="penerima">
                        <option></option>
                        '.$varB.'                      
                    </select>
                </div>
            </div>';
        }
         if ($id== 2) {
            $list .= '
            <input type="hidden" name="tipe" value="pegawai">
            <div class="form-group form-float">
                <label class="form-label"><i class="col-red">*</i> Penerima</label>
                <div class="form-line">
                    <select class="form-control show-tick" name="penerima" multiple data-selected-text-format="count">
                        <option></option>
                        '.$varA.'
                    </select>
                </div>
            </div>';
        }
        if ($id == 3) {
            $list .= ' <input type="hidden" name="tipe" value="all">';
        }
        echo $list;
    }
}