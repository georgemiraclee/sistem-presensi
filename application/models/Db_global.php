<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class db_global extends CI_Model
{
	public function allposts_count_all($query){
		$query = $this->db->query($query);
        return $query->num_rows();
	}

	public function allposts_all($query){
		$query = $this->db->query($query);

		if($query->num_rows()>0){
			return $query->result(); 
		}else{
			return null;
		} 
    }

    public function posts_search_all($query){
        $query = $this->db->query($query);
       
        if($query->num_rows()>0){
            return $query->result();  
        }else{
            return null;
        }
    }

    public function posts_search_count_all($query){
        $query = $this->db->query($query);
        return $query->num_rows();
    }
}