<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Snap extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	public function __construct()
    {
        parent::__construct();
        $params = array('server_key' => 'SB-Mid-server-8SCEoI_RXLy4lhF20mixzOUP', 'production' => false);
		$this->load->library('Midtrans/midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
    }

    public function index()
    {
    	$this->load->view('midtrans/checkout_snap');
    }

    public function token()
    {
		$sess = $this->session->userdata('subscriber');
    	$parts = explode(' ', $sess['nama']); // $meta->post_title
		$name_first = array_shift($parts);
		$name_last = array_pop($parts);
		$name_middle = trim(implode(' ', $parts));
		$harga = str_replace(".","",$sess['harga']);

		// cek email
		if (!filter_var($sess['email'], FILTER_VALIDATE_EMAIL)) {
			$sess['email'] = $sess['email']."@pressensi.com"; 
		}
		
		// Required
		$transaction_details = array(
		  'order_id' => rand(),
		  'gross_amount' => $harga, // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
		  'id' => 'a1',
		  'price' => $sess['hargaitem'],
		  'quantity' => $sess['pegawai'],
		  'name' => $sess['paket']
		);

		// Optional
		$item_details = array ($item1_details);

		// Optional
		$billing_address = array(
		  'first_name'    => $name_first,
		  'last_name'     => $name_middle,
		  'address'       => $sess['alamat'],
		  // 'city'          => "Jakarta",
		  // 'postal_code'   => "16602",
		  'phone'         => $sess['telepon'],
		  'country_code'  => 'IDN'
		);

		// Optional
		$customer_details = array(
		  'first_name'    => $name_first,
		  'last_name'     => $name_middle,
		  'email'         => $sess['email'],
		  'phone'         => $sess['telepon'],
		  'billing_address'  => $billing_address
		);

		// Data yang akan dikirim untuk request redirect_url.
        $credit_card['secure'] = true;
        //ser save_card true to enable oneclick or 2click
        //$credit_card['save_card'] = true;

        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O",$time),
            'unit' => 'minute', 
            'duration'  => 2
        );
        
        $transaction_data = array(
            'transaction_details'=> $transaction_details,
            'item_details'       => $item_details,
            'customer_details'   => $customer_details,
            'credit_card'        => $credit_card,
            'expiry'             => $custom_expiry
        );

		error_log(json_encode($transaction_data));
		$snapToken = $this->midtrans->getSnapToken($transaction_data);
		error_log($snapToken);
		echo $snapToken;
    }

    public function finish()
    {
		$sess = $this->session->userdata('subscriber');
		$result = json_decode($this->input->post('result_data'));
		
		/* save database */
		$transaction['user_id'] = $sess['id_user'];
		$transaction['transaction_id'] = $result->transaction_id;
		$transaction['order_id'] = $result->order_id;
		$transaction['gross_amount'] = $result->gross_amount;
		$transaction['payment_type'] = $result->payment_type;
		$transaction['transaction_time'] = $result->transaction_time;
		$transaction['transaction_status'] = $result->transaction_status;
		$transaction['bank'] = $result->bank;
		$transaction['card_type'] = $result->card_type;
		
		$this->Db_dml->insert('tb_pembayaran', $transaction);

		if ($result->status_code == 200) {
			$data['pesan'] = "Success !";
		}else{
			$data['pesan'] = "Pembayaran Diproses!";
		}
		$data['message'] = $result->status_message;
    	$this->load->view('status_page', $data);

    }
}
