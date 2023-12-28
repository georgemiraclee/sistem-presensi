<?php
	class DB_super_admin extends CI_Model{
		 var $tabel = 'tb_user';
		 var $tabel2 = 'tb_unit';
		 var $tabel3 = 'tb_jaringan	';
		public function select_admin_unit(){
			$sql = "select * from tb_user where is_admin = '1'";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function update_admin_unit($nip,$nama,$pass){
		$data=array(
			'nama_user'=>$this->input->post('nama_user'),
			'email_user'=>$this->input->post('email_user'),
			'telp_user'=>$this->input->post('telp_user'),
			'alamat_user'=>$this->input->post('alamat_user'),
			'id_unit'=>$this->input->post('id_unit'),
			'jabatan'=>$this->input->post('jabatan'),
			'password_user'=>$pass,
			'foto_user'=>$nama			
			);
		$this->db->where('nip', $nip);
		$this->db->update('tb_user', $data);
		}
		public function update_admin_unit2($nip,$pass){
		$data=array(
			'nama_user'=>$this->input->post('nama_user'),
			'email_user'=>$this->input->post('email_user'),
			'telp_user'=>$this->input->post('telp_user'),
			'alamat_user'=>$this->input->post('alamat_user'),
			'id_unit'=>$this->input->post('id_unit'),
			'password_user'=>$pass,
			'jabatan'=>$this->input->post('jabatan')			
			);
		$this->db->where('nip', $nip);
		$this->db->update('tb_user', $data);
		}
		public function insert_admin_unit($data){
			$this->db->insert($this->tabel,$data);
			return TRUE;
		}
		public function delete_admin_unit($id){
			$sql = "delete from tb_user where nip = '".$id."'";
			$this->db->query($sql);
			if ($this->db->affected_rows()) {
				return true;
			} else {
				return false;
			}
		}
		//data Unit
		public function select_data_unit(){
			$sql = "select * from  tb_unit";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function update_data_unit($id_unit,$name){
		$data=array(
			'nama_unit'=>$this->input->post('nama_unit'),
			'icon_unit'=>$name			
			);
		$this->db->where('id_unit', $id_unit);
		$this->db->update('tb_unit', $data);
		}
		public function update_data_unit2($id_unit){
		$data=array(
			'nama_unit'=>$this->input->post('nama_unit'),		
			);
		$this->db->where('id_unit', $id_unit);
		$this->db->update('tb_unit', $data);
		}
		public function insert_data_unit($data){
			$this->db->insert($this->tabel2,$data);
			return TRUE;
		}
		public function delete_data_unit($id){
			$sql = "delete from tb_unit where id_unit = '".$id."'";
			$this->db->query($sql);
			if ($this->db->affected_rows()) {
				return true;
			} else {
				return false;
			}
		}
		//data Jaringan
		public function select_data_jaringan(){
			$sql = "select * from  tb_jaringan";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function update_data_jaringan($id_jaringan){
		$data=array(
			'ssid_jaringan'=>$this->input->post('ssid_jaringan'),
			'lokasi_jaringan'=>$this->input->post('lokasi_jaringan'),
			);
		$this->db->where('id_jaringan', $id_jaringan);
		$this->db->update('tb_jaringan', $data);
		}
		public function insert_data_jaringan($data){
			$this->db->insert($this->tabel3,$data);
			return TRUE;
		}
		public function delete_data_jaringan($id){
			$sql = "delete from tb_jaringan where id_jaringan = '".$id."'";
			$this->db->query($sql);
			if ($this->db->affected_rows()) {
				return true;
			} else {
				return false;
			}
		}
		public function select_pegawai_rank(){
			$sql = "select * , count(j.user_id) total FROM tb_absensi j join tb_user c on j.user_id = c.user_id WHERE is_admin = '0' And status_absensi ='Tepat Waktu' group by j.user_id order by total desc";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_skpd_rank(){
			$sql = "select b.nama_unit, b.icon_unit, coalesce(tepat.tepat_waktu, 0) tepat_waktu, coalesce(terlambat.terlambat, 0) terlambat, coalesce(tidak.tidak_hadir, 0) tidak_hadir from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join (select tb_unit.id_unit, count(*) tepat_waktu from tb_user join tb_unit on tb_user.id_unit = tb_unit.id_unit join tb_absensi on tb_user.user_id = tb_absensi.user_id where tb_absensi.status_absensi = 'Tepat Waktu') as tepat on b.id_unit = tepat.id_unit left outer join (select tb_unit.id_unit, count(*) terlambat from tb_user join tb_unit on tb_user.id_unit = tb_unit.id_unit join tb_absensi on tb_user.user_id = tb_absensi.user_id where tb_absensi.status_absensi = 'Terlambat') as terlambat on b.id_unit = terlambat.id_unit left outer join (select tb_unit.id_unit, count(*) tidak_hadir from tb_user join tb_unit on tb_user.id_unit = tb_unit.id_unit join tb_absensi on tb_user.user_id = tb_absensi.user_id where tb_absensi.status_absensi = 'Tidak Hadir') as tidak on b.id_unit = tidak.id_unit order by tepat.tepat_waktu desc";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		//pegawai
		public function select_pegawai(){
			$sql = "select * from tb_user where is_admin = '0'";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_detail_pegawai($id){
			$sql = "select * from tb_user where user_id =".$id."";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_detail_unit($ut){
			$sql = "select * from  tb_unit where id_unit = '".$ut."'";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_detail_absen($nip){
			$sql = "select * from  tb_absensi where user_id = '".$nip."'";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_absensi_today($nip){
			$sql = "select  date_format(waktu_datang, '%H:%i') as datang, date_format(waktu_istirahat, '%H:%i') as istirahat, date_format(waktu_kembali, '%H:%i') as kembali, date_format(waktu_pulang, '%H:%i') as pulang  from tb_absensi a where user_id = '".$nip."' and date_format(created_absensi, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d')";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_absensi_todayNew(){
			$sql = "select  date_format(waktu_datang, '%H:%i') as datang, date_format(waktu_istirahat, '%H:%i') as istirahat, date_format(waktu_kembali, '%H:%i') as kembali, date_format(waktu_pulang, '%H:%i') as pulang  from `tb_absensi` where date_format(created_absensi, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d')";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function count_absen($tanggal, $id_channel = null) {
			$sql = "select count(*) total from tb_absensi a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where a.created_absensi >= '".$tanggal."' and b.is_admin = '0' and b.is_superadmin = '0' and c.id_channel = '".$id_channel."'";
			$query = $this->db->query($sql);
			$result = $query->row();
	      	return $result;
		}
		public function new_count_absen($tanggal) {
			$sql = "select count(*) total from tb_absensi a join tb_user b on a.user_id = b.user_id where a.created_absensi >= '".$tanggal."' and b.is_admin = '0' and b.is_superadmin = '0'";
			$query = $this->db->query($sql);
			$result = $query->row();
	      	return $result;
		}
		public function count_absen_telat($tanggal) {
		$sql = "select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id where created_absensi >= '".$tanggal."' AND status_absensi = 'Terlambat' ";
		$query = $this->db->query($sql);
		$result = $query->row();
      	return $result;
		}
		public function count_absen_telat2($tanggal, $id_channel) {
		$sql = "select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id join tb_unit d on c.id_unit = d.id_unit where created_absensi >= '".$tanggal."' AND status_absensi = 'Terlambat' and d.id_channel = '".$id_channel."'";
		$query = $this->db->query($sql);
		$result = $query->row();
      	return $result;
		}
		public function count_absen_telat3($tanggal, $id_channel) {
		$sql = "select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id join tb_unit d on c.id_unit = d.id_unit where created_absensi >= '".$tanggal."' AND status_absensi = 'Tepat Waktu' and d.id_channel = '".$id_channel."'";
		$query = $this->db->query($sql);
		$result = $query->row();
			return $result;
		}
		public function count_pegawai($id_channel = null) {
			$sql = "select count(*) total from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_aktif = 1 and a.is_admin='0' and a.is_superadmin = '0' and b.id_channel = '".$id_channel."'";
			$query = $this->db->query($sql);
			$result = $query->row();
	      	return $result;
		}
		public function new_count_pegawai() {
			$sql = "select count(*) total from tb_user a where a.is_admin='0' and a.is_superadmin = '0'";
			$query = $this->db->query($sql);
			$result = $query->row();
	      	return $result;
		}
		public function select_lokasi($id_ab){
			$sql = "select * from  tb_history_absensi where id_absensi = '".$id_ab."'";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function count_kehadiran($nip) {
		$sql = "select count(*) total from tb_absensi where user_id >= '".$nip."' and date_format(waktu_datang, '%H:%i')<= '09:00' and date_format(waktu_datang, '%m') = month(now()) " ;
		$query = $this->db->query($sql);
		$result = $query->row();
      	return $result;
		}
		public function count_kesiangan($nip) {
		$sql = "select count(*) total from tb_absensi where user_id >= '".$nip."' and date_format(waktu_datang, '%H:%i')>= '09:00' ";
		$query = $this->db->query($sql);
		$result = $query->row();
      	return $result;
		}
		public function select_password($nip){
			$sql = "select * from tb_user where user_id = '".$nip."'";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_absensi_pegawai($id_channel = null){
			$sql="select user_id, nip, nama_user, foto_user,tanggal_lahir, jabatan,date_format(b.waktu_datang, '%H:%i') waktu_datang, date_format(b.waktu_istirahat, '%H:%i') waktu_istirahat, date_format(b.waktu_kembali, '%H:%i') waktu_kembali, date_format(b.waktu_pulang, '%H:%i') waktu_pulang, b.url_file_absensi, b.status_absensi from tb_user a left outer join (select user_id aa, waktu_datang, waktu_istirahat, waktu_kembali, waktu_pulang, url_file_absensi, status_absensi from tb_absensi where date_format(waktu_datang, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') group by user_id) b on a.user_id = b.aa join tb_unit c on a.id_unit = c.id_unit where a.is_aktif = 1 and a.is_admin = '0' and a.is_superadmin = '0' and c.id_channel = '".$id_channel."' order by (b.waktu_datang is null), b.waktu_datang asc";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function new_select_absensi_pegawai(){
			$sql="select user_id, nip, nama_user, foto_user,tanggal_lahir, jabatan,date_format(b.waktu_datang, '%H:%i') waktu_datang, date_format(b.waktu_istirahat, '%H:%i') waktu_istirahat, date_format(b.waktu_kembali, '%H:%i') waktu_kembali, date_format(b.waktu_pulang, '%H:%i') waktu_pulang, b.url_file_absensi, b.status_absensi from tb_user a left outer join (select user_id aa, waktu_datang, waktu_istirahat, waktu_kembali, waktu_pulang, url_file_absensi, status_absensi from tb_absensi where date_format(waktu_datang, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') group by user_id) b on a.user_id = b.aa where a.is_admin = '0' and a.is_superadmin = '0' order by (b.waktu_datang is null), b.waktu_datang asc";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_absensi_pegawai2($parrent){
			$sql="select user_id, nama_user, foto_user, jabatan,date_format(b.waktu_datang, '%H:%i') waktu_datang, date_format(b.waktu_istirahat, '%H:%i') waktu_istirahat, date_format(b.waktu_kembali, '%H:%i') waktu_kembali, date_format(b.waktu_pulang, '%H:%i') waktu_pulang, b.url_file_absensi from tb_user a left outer join (select user_id aa, waktu_datang, waktu_istirahat, waktu_kembali, waktu_pulang, url_file_absensi from tb_absensi where date_format(waktu_datang, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') group by user_id) b on a.user_id = b.aa where a.id_parent = '".$parrent."' order by (b.waktu_datang is null), b.waktu_datang  asc";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_absensi_pegawai3($unit){
			$sql="select user_id, nip, nama_user, foto_user, jabatan,id_unit,date_format(b.waktu_datang, '%H:%i') waktu_datang, date_format(b.waktu_istirahat, '%H:%i') waktu_istirahat, date_format(b.waktu_kembali, '%H:%i') waktu_kembali, date_format(b.waktu_pulang, '%H:%i') waktu_pulang, b.url_file_absensi from tb_user a left outer join (select user_id aa, waktu_datang, waktu_istirahat, waktu_kembali, waktu_pulang, url_file_absensi from tb_absensi where date_format(waktu_datang, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') group by user_id) b on a.user_id = b.aa where a.id_unit = '".$unit."' AND b.waktu_datang is not null order by (b.waktu_datang is null), b.waktu_datang asc";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_user_login(){
			$sql = "select * from tb_user j join tb_absensi c on j.user_id = c.user_id where date_format(created_absensi, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d')";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		public function select_lokasi_terakhir($id_absen){
			$sql = "select * FROM tb_history_absensi WHERE id_absensi ='".$id_absen."' ORDER BY `created_history_absensi` DESC limit 1";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result;
		}
		// Fetch data according to per_page limit.
    public function fetch_data($limit, $id) {
        $this->db->limit($limit);
        $this->db->where('user_id', $id);
        $query = $this->db->get("tb_user");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
	}
	?>