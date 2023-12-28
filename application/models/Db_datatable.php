<?php
class Db_datatable extends CI_Model
{
	var $table = 'tb_absensi'; //nama tabel dari database
	var $column_order = array('nip', 'nama_user', 'created_absensi', 'waktu_datang', 'waktu_pulang', null); //field yang ada di table user
	var $column_search = array('nama_user', 'nip', 'status_absensi'); //field yang diizin untuk pencarian 
	var $order = array('created_absensi' => 'desc'); // default order 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	private function _get_datatables_query($st, $field = '*')
	{
		$this->db->select($field);
		$this->db->from($this->table);
		$this->db->join('tb_user', 'tb_user.user_id = tb_absensi.user_id');
		$this->db->join('tb_unit', 'tb_unit.id_unit = tb_user.id_unit');
		$this->db->where($st, NULL, FALSE);
		$i = 0;

		foreach ($this->column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	function get_datatables($st, $field = '*')
	{
		$this->_get_datatables_query($st, $field);
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function count_filtered($st, $field = '*')
	{
		$this->_get_datatables_query($st, $field);
		$query = $this->db->get();
		return $query->row();
	}
	public function count_all($st)
	{
		$this->db->from($this->table);
		$this->db->join('tb_user', 'tb_user.user_id = tb_absensi.user_id');
		$this->db->join('tb_unit', 'tb_unit.id_unit = tb_user.id_unit');
		$this->db->where($st, NULL, FALSE);
		return $this->db->count_all_results();
	}
	private function _get_datatables_query2($tb, $wr, $fld, $src, $ordr)
	{
		$this->db->from($tb);
		// $this->db->join('tb_user', 'tb_user.user_id = tb_absensi.user_id');
		// $this->db->join('tb_unit', 'tb_unit.id_unit = tb_user.id_unit');
		$this->db->where($wr, NULL, FALSE);
		// $this->db->Where()
		$i = 0;

		foreach ($src as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if (count($src) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($fld[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($ordr)) {
			$order = $ordr;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	function get_datatables2($tb, $wr, $fld, $src, $ordr)
	{
		$this->_get_datatables_query2($tb, $wr, $fld, $src, $ordr);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	function count_filtered2($tb, $wr, $fld, $src, $ordr)
	{
		$this->_get_datatables_query2($tb, $wr, $fld, $src, $ordr);
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function count_all2($tb, $wr, $fld, $src, $ordr)
	{
		$this->db->from($tb);
		// $this->db->join('tb_user', 'tb_user.user_id = tb_absensi.user_id');
		// $this->db->join('tb_unit', 'tb_unit.id_unit = tb_user.id_unit');
		$this->db->where($wr, NULL, FALSE);
		return $this->db->count_all_results();
	}
	private function _get_datatables_query3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr)
	{
		$this->db->from($tb);
		$this->db->join($j1, $jj1);
		$this->db->join($j2, $jj2);
		$this->db->where($wr, NULL, FALSE);
		$i = 0;

		foreach ($src as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if (count($src) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($fld[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($ordr)) {
			$order = $ordr;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	function get_datatables3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr)
	{
		$this->_get_datatables_query3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	function count_filtered3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr)
	{
		$this->_get_datatables_query3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr);
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function count_all3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr)
	{
		$this->db->from($tb);
		$this->db->join($j1, $jj1);
		$this->db->join($j2, $jj2);
		$this->db->where($wr, NULL, FALSE);
		return $this->db->count_all_results();
	}
}
