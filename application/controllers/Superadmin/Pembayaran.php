<?php

/**
 * 
 */
class Pembayaran extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('Superadmin/header');
		$this->load->view('Superadmin/pembayaran');
		$this->load->view('Superadmin/footer');
	}

	public function getData()
	{
		$sess = $this->session->userdata('user');
    	$columns = array( 
	      0 =>  'id_pembayaran', 
	      1 =>  'nama_user',
	      2 =>  'transaction_time',
	      3 =>  'aksi'
	    );
	    $limit  = $this->input->post('length');
	    $start  = $this->input->post('start');
	    $order  = $columns[$this->input->post('order')[0]['column']];
	    $dir    = $this->input->post('order')[0]['dir'];
	    $totalData = $this->Db_global->allposts_count_all("select * from tb_pembayaran a join tb_user b on a.user_id = b.user_id");
	    $totalFiltered = $totalData;
	    
	    if(empty($this->input->post('search')['value'])){
          $posts = $this->Db_global->allposts_all("select * from tb_pembayaran a join tb_user b on a.user_id = b.user_id order by ".$order." ".$dir." limit ".$start.",".$limit."");
        }else{
          $search = $this->input->post('search')['value'];
          $posts = $this->Db_global->posts_search_all("select * from tb_pembayaran a join tb_user b on a.user_id = b.user_id where (email_user like '%".$search."%' or transaction_time like '%".$search."%') order by ".$order." ".$dir." limit ".$start.",".$limit."");
          $totalFiltered = $this->Db_global->posts_search_count_all("select * from tb_pembayaran a join tb_user b on a.user_id = b.user_id where (email_user like '%".$search."%' or transaction_time like '%".$search."%')");
        }

        $data = array();
	    if(!empty($posts)){
    		foreach ($posts as $key => $post){
    			$nestedData['no']  = $key+1;
    			$nestedData['nama_user']  = $post->nama_user;
    			$nestedData['transaction_time']  = $post->transaction_time;
    			$nestedData['aksi']  = "<a href='".base_url()."Superadmin/pembayaran/detail/".$post->id_pembayaran."'><button class='btn btn-info'>Detail</button></a>";
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
	public function detail($id)
	{
		$query = $this->Db_select->query("select * from tb_pembayaran a join tb_user b on a.user_id = b.user_id where id_pembayaran =".$id);

		$data['nama_user'] = $query->nama_user;
		$data['email_user'] = $query->email_user;
		$data['telp_user'] = $query->telp_user;
		$data['id_transaksi'] = $query->transaction_id;
		$data['order_id'] = $query->order_id;
		$data['payment_type'] = $query->payment_type;
		$data['bank'] = $query->bank;
		$data['transaction_status'] = $query->transaction_status;
		$data['transaction_time'] = $query->transaction_time;

		$this->load->view('Superadmin/header');
		$this->load->view('Superadmin/detail_pembayaran',$data);
		$this->load->view('Superadmin/footer');
	}
}