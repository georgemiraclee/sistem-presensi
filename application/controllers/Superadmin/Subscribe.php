<?php

/**
 * 
 */
class Subscribe extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('Superadmin/header');
		$this->load->view('Superadmin/subscribe');
		$this->load->view('Superadmin/footer');
	}

	public function getData()
	{
		$sess = $this->session->userdata('user');
    	$columns = array( 
	      0 =>  'id', 
	      1 =>  'email',
	      2 =>  'company',
	      3 =>  'total_employe',
	      4 =>  'created_at'
	    );
	    $limit  = $this->input->post('length');
	    $start  = $this->input->post('start');
	    $order  = $columns[$this->input->post('order')[0]['column']];
	    $dir    = $this->input->post('order')[0]['dir'];
	    $totalData = $this->Db_global->allposts_count_all("select *from tb_subscribe");
	    $totalFiltered = $totalData;
	    
	    if(empty($this->input->post('search')['value'])){
          $posts = $this->Db_global->allposts_all("select *from tb_subscribe order by ".$order." ".$dir." limit ".$start.",".$limit."");
        }else{
          $search = $this->input->post('search')['value'];
          $posts = $this->Db_global->posts_search_all("select *from tb_subscribe where (email like '%".$search."%' or company like '%".$search."%' or total_employe like '%".$search."%' or id like '%".$search."%' or created_at like '%".$search."%') order by ".$order." ".$dir." limit ".$start.",".$limit."");
          $totalFiltered = $this->Db_global->posts_search_count_all("select *from tb_subscribe where (email like '%".$search."%' or company like '%".$search."%' or total_employe like '%".$search."%' or id like '%".$search."%' or created_at like '%".$search."%')");
        }

        $data = array();
	    if(!empty($posts)){
    		foreach ($posts as $post){
    			$nestedData['no']  = $post->id;
    			$nestedData['email']  = $post->email;
    			$nestedData['company']  = $post->company;
    			$nestedData['employee']  = $post->total_employe;
    			$nestedData['created_at']  = date('d F Y', strtotime($post->created_at));
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
}