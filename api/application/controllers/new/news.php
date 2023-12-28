<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class news extends CI_Controller {
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

    public function index()
    {
        $require = array('limit', 'page');
        $this->global_lib->input($require);

        $input = $this->input->post();

        /* get data news from kazee API */
        $news = $this->curlNews($input['page'], $input['limit']);

        if($news){
            if ($news->status) {
                $dataNews = array();
                foreach ($news->data as $key => $value) {
                    $putNews['judul'] = ucwords($value->title);
                    $putNews['deskripsi'] = $value->clean_content;
                    
                    if (count($value->image)) {
                        $putNews['link_gambar'] = $value->image[0];
                    } else {
                        $putNews['link_gambar'] = null;
                    }

                    $putNews['sumber'] = "News";
                    $putNews['terbit'] =  date('Y-m-d', $value->datetime_ms/1000);
                    $putNews['kategori'] = ucwords($value->_type);
                    $putNews['country'] = $value->meta->source->country;
                    $putNews['region'] = $value->meta->source->region;
                    $putNews['geolocation'] = $value->meta->source->geolocation;
                    $putNews['url'] = $value->url;

                    array_push($dataNews, $putNews);
                }
                $this->result = array(
                    'status' => true,
                    'message' => 'Data ditemukan',
                    'data' => array(
                        'news' => $dataNews,
                        'total' => $news->total
                    )
                );
            }else{
                $this->result = array(
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                    'data' => array()
                );
            }
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => array()
            );
        }

        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
    }

    public function curlNews($page, $limit)
    {
        $ch = curl_init();

        $url = "http://api.kazee.id/sorot/news-feed/sport/".$page."/".$limit;

        /* set url */
        curl_setopt($ch, CURLOPT_URL, $url);
        /* return the transfer as a string */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* ourput contains the output string */
        $output = curl_exec($ch);
        /* tutup curl */
        curl_close($ch);

        return json_decode($output);
    }
}