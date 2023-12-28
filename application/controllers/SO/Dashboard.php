<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Dashboard extends CI_Controller
{ 
    function __construct()
    {
        parent::__construct();
        $this->load->library('Ceksession');
        $this->ceksession->login();
    }
    public function index()
    {
        $sess = $this->session->userdata('user');
        if ($sess['akses'] =="admin_channel"||$sess['akses'] =="sekda"||$sess['akses'] =="staff") {
        redirect(base_url());exit();
        }
        $filter = array();
        $id_channel="1";
        $month_array = array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des');
        $status = array('Tepat Waktu', 'Terlambat', 'Tepat Waktu');
        $datar['user'] = $this->session->userdata('user');
        $tanggal = date("Y/m/d");
        $tanggal2 = date("m");
        $tanggal3 = date("d");
        // $data['select_absen'] = $this->DB_super_admin->select_absensi_today($tanggal);
        $jml = $this->DB_super_admin->count_absen($tanggal);
        $pgw = $this->DB_super_admin->count_pegawai();

        $data['skpd'] = $this->Db_select->query_all('select *from tb_unit order by nama_unit');
        $data['id_channel'] = $id_channel;
        $data['jabatan'] = $this->Db_select->query_all('select *from tb_jabatan where is_aktif = 1 order by nama_jabatan asc');

        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];

        $query = "select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id where date_format(created_absensi, '%m') = '".$tanggal2."' AND id_parent=".$parrent."";

        $pegawai = "select count(*) total from tb_user where is_admin='0' AND id_parent=".$parrent."";

        if ($this->input->get('skpd')) {
            $filter['skpd'] = $this->input->get('skpd');
            $query .= " and c.id_unit in(".$this->input->get('skpd').")";
            $pegawai .= " and id_unit in(".$this->input->get('skpd').")";
        }
        if ($this->input->get('jabatan')) {
            $filter['jabatan'] = $this->input->get('jabatan');
            $filter['jabatan'] = explode(',', $filter['jabatan']);
            $jabatan = "";
            foreach ($filter['jabatan'] as $key => $value) {
                $jabatan .= ",'$value'";
            }
            $jabatan = substr($jabatan, 1);
            $query .= " and c.jabatan in(".$jabatan.")";
            $pegawai .= " and jabatan in(".$this->input->get('jabatan').")";
        }
        if ($this->input->get('jenkel')) {
            $filter['jenkel'] = $this->input->get('jenkel');
            $filter['jenkel'] = explode(',', $filter['jenkel']);
            $jenkel = "";
            foreach ($filter['jenkel'] as $key => $value) {
                $jenkel .= ",'$value'";
            }
            $jenkel = substr($jenkel, 1);
            $query .= " and c.jenis_kelamin in(".$jenkel.")";
            $pegawai .= " and jenis_kelamin in(".$jenkel.")";
        }
        if ($this->input->get('dari')) {
            $filter['dari'] = $this->input->get('dari');
            $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
        }else{
            $query .= " and date_format(created_absensi, '%d') = day(now()) ";
        }
        if ($this->input->get('sampai')) {
            $filter['sampai'] = $this->input->get('sampai');
            $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
        }
        if ($filter) {
            $data['show'] = 'true';
        }else{
            $data['show'] = 'false';
        }
        $getDatatepat = $query.' and status_absensi = "Tepat Waktu"';
        $getDataTH = $query.' and status_absensi = "Tidak Hadir"';
        $getDataK = $query.' and status_absensi = "Terlambat"';

        $getcount = $this->Db_select->query($getDatatepat);
        $getcount2 = $this->Db_select->query($getDataTH);
        $getcount3 = $this->Db_select->query($getDataK);

        $tepat_pie=  $getcount->total;
        $tidakhadir_pie=$getcount2->total;
        $terlambat_pie=$getcount3->total;
        // $data['jumlah_hadir']=$tepat_pie;

        $data['tepat_waktu']=$tepat_pie;
        $data['terlambat']=$terlambat_pie;
        $data['tidakhadir']=$tidakhadir_pie;
        
        $terlambat = $this->Db_select->query_all($query);
        $pegawai = $this->Db_select->query($pegawai);
        $data['jml_pegawai'] = $pegawai->total;

        $map = $this->DataMaps();

        $data['performa'][0] = array('name' => 'Tepat waktu', 'y' => (int) $tepat_pie, 'color' => '#4CAF50');
        $data['performa'][1] = array('name' => 'Tidak Hadir', 'y' => (int) $tidakhadir_pie, 'color' => '#E91E63');
        $data['performa'][2] = array('name' => 'Terlambat', 'y' => (int) $terlambat_pie, 'color' => '#FF5722');

        $LineChart = $this->LineChart();
        $BarChart = $this->BarChart();
        $AreaChart = $this->AreaChart();
        $DonutChart = $this->DonutChart();
        // exit();

        $data['line_chart'] = "";
        $data['line_chart'] .= $LineChart.$BarChart.$AreaChart.$DonutChart;
        $data['map'] = $map;
        $this->load->view('SO/header',$data);
        $this->load->view('SO/dashboard',$data);
        $this->load->view('SO/footer');
    }
    public function LineChart()
    {
        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];
        $query = 'select * , count(created_absensi) total FROM tb_absensi j join tb_user c on j.user_id = c.user_id WHERE is_admin = "0" AND id_parent= "'.$parrent.'" ';
       $dari= $this->input->get('dari');
       $sampai= $this->input->get('sampai');
       $datas = array();
        if ($dari!= null and $dari != $sampai) {
            $filter['dari'] = $dari;
            $query .= ' and date(created_absensi)  >= "'.$dari.'"';    
        }
        if ($sampai != null and $sampai != $dari) {
            $filter['sampai'] = $sampai;
            $query .= ' and date(created_absensi) <= "'.$sampai.'"'; 
        }
        if ($dari==$sampai) {
            $getDataTW= $query.'and date(created_absensi) = date(now())  group by time(waktu_datang)';
            $getData1 = $this->Db_select->query_all($getDataTW);
            foreach ($getData1 as $key => $value) { 
            $dater = date('h:m:i', strtotime($value->waktu_datang));
            $datas[]= "".$dater."";           
            }
             // echo json_encode($getDataTW);exit();
        }else{
            $getDataTW = $query.'group by date(created_absensi)';
            $getData1 = $this->Db_select->query_all($getDataTW);
                 foreach ($getData1 as $key => $value) { 
                    $dater = date('d-m-Y', strtotime($value->created_absensi));
                    $datas[]= "".$dater."";
                    // $count['isi'].="".$value->total.","; 
                 } 
            // echo json_encode($getDataTW);exit();
        }

        
        
        
         $data['tepat']="";
         $data['tidak_hadir']="";
         $data['terlambat']="";

         for ($i=0; $i < count($datas) ; $i++) { 

            if ($this->input->get('dari')==$this->input->get('sampai')) {
            $query = 'select count(*) total FROM tb_absensi j join tb_user c on j.user_id = c.user_id WHERE date_format(waktu_datang, "%h:%m:%i")  ="'.$datas[$i].'"';
            }else{
                $query = 'select count(*) total FROM tb_absensi j join tb_user c on j.user_id = c.user_id WHERE date_format(created_absensi, "%d-%m-%Y")  ="'.$datas[$i].'"';
            }

            if ($this->input->get('skpd')) {
                $filter['skpd'] = $this->input->get('skpd');
                $query .= " and c.id_unit in(".$this->input->get('skpd').")";
            }
            if ($this->input->get('jabatan')) {
                $filter['jabatan'] = $this->input->get('jabatan');
                $filter['jabatan'] = explode(',', $filter['jabatan']);
                $jabatan = "";
                foreach ($filter['jabatan'] as $key => $value) {
                    $jabatan .= ",'$value'";
                }
                $jabatan = substr($jabatan, 1);
                $query .= " and c.jabatan in(".$jabatan.")";
            }
            if ($this->input->get('jenkel')) {
                $filter['jenkel'] = $this->input->get('jenkel');
                $filter['jenkel'] = explode(',', $filter['jenkel']);
                $jenkel = "";
                foreach ($filter['jenkel'] as $key => $value) {
                    $jenkel .= ",'$value'";
                }
                $jenkel = substr($jenkel, 1);
                $query .= " and c.jenis_kelamin in(".$jenkel.")";
            }  

                    // echo json_encode($);exit();

            $getDatatepat = $query.' and status_absensi = "Tepat Waktu"';
            $getDataTH = $query.' and status_absensi = "Tidak Hadir"';
            $getDataK = $query.' and status_absensi = "Terlambat"';
            $getcount = $this->Db_select->query($getDatatepat);
            $getcount2 = $this->Db_select->query($getDataTH);
            $getcount3 = $this->Db_select->query($getDataK);
            $data['tepat'].=" ".$getcount->total.", ";
            $data['tidak_hadir'].=" ".$getcount2->total.", ";
            $data['terlambat'].=" ".$getcount3->total.", ";
                  
                            
            // array_push($new_data, $getcount->total);
         }

        $chart = "<script type='text/javascript'>
                    $(function () {
                        $('#statistik').highcharts({
                            title: {
                                text: '',
                                x: -20 //center
                            },subtitle: {
                                text: '',
                                x: -20
                            },
                            yAxis: {
                                title: {
                                    text: 'Jumlah Pegawai'
                                }
                            },
                            xAxis: {
                                categories: ".json_encode($datas).",
                                type: 'category',
                                tickmarkPlacement: 'on',
                                title: {
                                    enabled: false
                                }
                            },
                            series: [{name: 'Tepat Waktu',data: [".$data['tepat']."],color: '#4CAF50'},
                            {name: 'Terlambat',data: [".$data['terlambat']."],color: 'orange'},
                            {name: 'Tidak Hadir',data: [".$data['tidak_hadir']."],color: 'pink'},
                                    
                            ]
                        });
                    });
                  </script>";
        return $chart;
    }
    public function BarChart()
    {
        $skpd = $this->Db_select->query_all('select a.id_unit, a.nama_unit, ifnull(cek.total, 0) total from tb_unit a left outer join(select tb_user.id_unit, count(*) total from tb_absensi join tb_user on tb_absensi.user_id = tb_user.user_id) cek on a.id_unit = cek.id_unit order by cek.total desc limit 10');
        $NewSkdp = '[';
        $NewTerlambat = array();
        $NewTepatWaktu = array();
        $NewTidakHadir = array();

        foreach ($skpd as $key => $value) {
            $NewSkdp .= "'$value->nama_unit'".",";
            $tanggal2 = date("m");
            $tanggal3 = date("d");
            $query = "select * from tb_absensi j join tb_user c on j.user_id = c.user_id where date_format(created_absensi, '%m') = '".$tanggal2."'";
            
            $pegawai = "select count(*) total from tb_user  where is_admin='0' and id_unit = ".$value->id_unit."";

            if ($this->input->get('skpd')) {
                $filter['skpd'] = $this->input->get('skpd');
                $query .= " and c.id_unit in(".$this->input->get('skpd').")";
                $pegawai .= " and id_unit in(".$this->input->get('skpd').")";
            }else{
                $query .= " and c.id_unit = ".$value->id_unit."";
            }
            if ($this->input->get('jabatan')) {
                $filter['jabatan'] = $this->input->get('jabatan');
                $filter['jabatan'] = explode(',', $filter['jabatan']);
                $jabatan = "";
                foreach ($filter['jabatan'] as $key => $value) {
                    $jabatan .= ",'$value'";
                }
                $jabatan = substr($jabatan, 1);
                $query .= " and c.jabatan in(".$jabatan.")";
                $pegawai .= " and jabatan in(".$this->input->get('jabatan').")";
            }
            if ($this->input->get('jenkel')) {
                $filter['jenkel'] = $this->input->get('jenkel');
                $filter['jenkel'] = explode(',', $filter['jenkel']);
                $jenkel = "";
                foreach ($filter['jenkel'] as $key => $value) {
                    $jenkel .= ",'$value'";
                }
                $jenkel = substr($jenkel, 1);
                $query .= " and c.jenis_kelamin in(".$jenkel.")";
                $pegawai .= " and jenis_kelamin in(".$jenkel.")";
            }
            if ($this->input->get('dari')) {
            $filter['dari'] = $this->input->get('dari');
            $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
        }else{
            $query .= " and date_format(created_absensi, '%d') = day(now()) ";
        }
        if ($this->input->get('sampai')) {
            $filter['sampai'] = $this->input->get('sampai');
            $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
        }
            
            $terlambat = $this->Db_select->query_all($query);
            $pgw = $this->Db_select->query($pegawai);

            $WaktuTerlambat = 0;
            $WaktuHadir = 0;
            $WaktuTidakHadir = 0;
            $averageDatang = 0;
            $averagePulang = 0;

            if (count($terlambat) > 0) {
                foreach ($terlambat as $key => $valuez) {
                    if (date("h:i", strtotime($valuez->created_absensi)) > "08:30") {
                        $WaktuTerlambat += 1;
                    }else{
                        $WaktuHadir += 1;
                    }
                }

            }
            $WaktuTidakHadir =$pgw->total - count($terlambat);
            if ($this->input->get('skpd')) {
                if ($value->id_unit != $this->input->get('skpd')) {
                    $WaktuTerlambat = 0;
                    $WaktuHadir = 0;
                    $WaktuTidakHadir = 0;     
                }
            }
            if ($pgw->total == 0) {
                $WaktuTerlambat = 0;
                $WaktuHadir = 0;
                $WaktuTidakHadir = 0;
            }

            array_push($NewTerlambat, $WaktuTerlambat);
            array_push($NewTepatWaktu, $WaktuHadir);
            array_push($NewTidakHadir, $WaktuTidakHadir);
        }
        $NewSkdp .= ']';

        $chart = "<script type='text/javascript'>
                    $(function() {
        Highcharts.chart('statistikSKPD', {
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: ".$NewSkdp."
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: [{
                name: 'Terlambat',
                data: ".json_encode($NewTerlambat).",
                color: '#FF5722'
            }, {
                name: 'Tidak Hadir',
                data: ".json_encode($NewTidakHadir).",
                color: '#E91E63'
            }, {
                name: 'Tepat Waktu',
                data: ".json_encode($NewTepatWaktu).",
                color: '#4CAF50'
            }]
        });
        }); 
        </script>";

        return $chart;
    }
    public function daftarHadir()
    {
        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];
        $pg=$this->DB_super_admin->select_absensi_pegawai2($parrent);
        // echo json_encode($pg);exit();
        $pegawai ='';
            foreach ($pg as $key => $value) {
                
                 if ($value->foto_user==null||$value->foto_user=='') {
                    $value->foto_user= "default_photo.jpg";
                }
                if ($value->waktu_datang==null||$value->waktu_datang=='') {
                    $datang = "-";
                    $istirahat = "-";
                    $kembali = "-";
                    $pulang = "-";
                    $class="offline";
                    $ket="<span class='label label-danger label-form float-right' style'color:red';>Belum Datang</span>";
                }else{
                    $bek="#32B098";
                    $class="online";
                    $datang = $value->waktu_datang;
                    if ($datang > "08:30") {
                        $ket="<span class='label label-warning float-right' style='background-color: #EA951D'>Kesiangan</span>";
                    }else {
                        $ket="<span class='label label-primary label-form float-right' style'color:red';>Tepat Waktu</span>";
                    }                    
                }
                 $pegawai.=' 
                 <br>
                 <div class="col-md-3 float-left">
                 <img src="'.base_url().'assets/images/member-photos/'.$value->foto_user.'" style="max-width:50px;" class=" float-left" >
                 </div>
                 <div class="col-md-9">
                    <h5>'.$value->nama_user.'</h5>
                    <p>'.$datang.' '.$ket.'</p>
                <hr>
                 </div>
                 
                ';
            }
        
        $tanggal = date("Y/m/d");
        $tanggal2 = date("m");
        $tanggal3 = date("d");
        $select_absen=$this->DB_super_admin->select_absensi_today($tanggal);
        $jml = $this->DB_super_admin->count_absen($tanggal);
        // $jumlah_hadir=$jml->total;
        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];

        $query = "select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id where date_format(created_absensi, '%m') = '".$tanggal2."' and id_parent=".$parrent."";
        $pgw = "select count(*) total from tb_absensi a join tb_user c on a.user_id = c.user_id = '0' and is_admin='0'  and id_parent=".$parrent." ";
        if ($this->input->get('skpd')) {
            $filter['skpd'] = $this->input->get('skpd');
            $query .= " and c.id_unit in(".$this->input->get('skpd').")";
            $pgw .= " and id_unit in(".$this->input->get('skpd').")";
        }
        if ($this->input->get('jabatan')) {
            $filter['jabatan'] = $this->input->get('jabatan');
            $filter['jabatan'] = explode(',', $filter['jabatan']);
            $jabatan = "";
            foreach ($filter['jabatan'] as $key => $value) {
                $jabatan .= ",'$value'";
            }
            $jabatan = substr($jabatan, 1);
            $query .= " and c.jabatan in(".$jabatan.")";
            $pgw .= " and jabatan in(".$this->input->get('jabatan').")";
        }
        if ($this->input->get('jenkel')) {
            $filter['jenkel'] = $this->input->get('jenkel');
            $filter['jenkel'] = explode(',', $filter['jenkel']);
            $jenkel = "";
            foreach ($filter['jenkel'] as $key => $value) {
                $jenkel .= ",'$value'";
            }
            $jenkel = substr($jenkel, 1);
            $query .= " and c.jenis_kelamin in(".$jenkel.")";
            $pgw .= " and a.jenis_kelamin in(".$jenkel.")";
        }
        if ($this->input->get('dari')) {
            $filter['dari'] = $this->input->get('dari');
            $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
            $pgw .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
        }else{
            $query .= " and date_format(created_absensi, '%d') = day(now()) ";
            $pgw .= " and date_format(created_absensi, '%d') = day(now()) ";
        }
        if ($this->input->get('sampai')) {
            $filter['sampai'] = $this->input->get('sampai');
            $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
            $pgw .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
        } 

        $getDatatepat = $query.' and status_absensi = "Tepat Waktu"';
        $getDataTH = $query.' and status_absensi = "Tidak Hadir"';
        $getDataK = $query.' and status_absensi = "Terlambat"';

        $getcount = $this->Db_select->query($getDatatepat);
        $getcount2 = $this->Db_select->query($getDataTH);
        $getcount3 = $this->Db_select->query($getDataK);

        $tepat_pie=  $getcount->total;
        $tidakhadir_pie=$getcount2->total;
        $terlambat_pie=$getcount3->total;

        $terlambat = $this->Db_select->query_all($query);
        $pgw = $this->Db_select->query($pgw);
        $tidak_hadir=$pgw->total - $jml->total;        

        $result['status'] = true;
        $result['message'] = 'Data ditemukan.';
        $result['data'] = $pegawai;
        $result['jumlah_hadir'] = $tepat_pie;
        $result['tidak_hadir'] = $tidakhadir_pie;
        $result['terlambat'] = $terlambat_pie;

        echo json_encode($result); exit();
    }
    public function DataMaps()
    {
        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];
        $query = 'select a.nama_user, a.foto_user, c.lat, c.lng, c.created_history_absensi from tb_user a join tb_absensi b on a.user_id = b.user_id join tb_history_absensi c on b.id_absensi = c.id_absensi where c.lat is not null and c.lng is not null  and id_parent='.$parrent.' ' ;
        if ($this->input->get('skpd')) {
            $filter['skpd'] = $this->input->get('skpd');
            $query .= " and a.id_unit in(".$this->input->get('skpd').")";
        }
        if ($this->input->get('jabatan')) {
            $filter['jabatan'] = $this->input->get('jabatan');
            $filter['jabatan'] = $this->input->get('jabatan');
            $filter['jabatan'] = explode(',', $filter['jabatan']);
            $jabatan = "";
            foreach ($filter['jabatan'] as $key => $value) {
                $jabatan .= ",'$value'";
            }
            $jabatan = substr($jabatan, 1);
            $query .= " and a.jabatan in(".$jabatan.")";
        }
        if ($this->input->get('jenkel')) {
            $filter['jenkel'] = $this->input->get('jenkel');
            $filter['jenkel'] = explode(',', $filter['jenkel']);
            $jenkel = "";
            foreach ($filter['jenkel'] as $key => $value) {
                $jenkel .= ",'$value'";
            }
            $jenkel = substr($jenkel, 1);
            $query .= " and a.jenis_kelamin in(".$jenkel.")";
        }
        if ($this->input->get('dari')) {
            $filter['dari'] = $this->input->get('dari');
            $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
        }else{
            $query .= " and date_format(created_absensi, '%d') = day(now()) ";
        }
        if ($this->input->get('sampai')) {
            $filter['sampai'] = $this->input->get('sampai');
            $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
        }
        $query .= ' group by a.nip order by c.created_history_absensi desc';

        $getData = $this->Db_select->query_all($query);

        foreach ($getData as $key => $value) {
            if ($value->foto_user == null || $value->foto_user == "") {
                $value->foto_user = "default_photo.jpg";
            }
            $day = date('d', strtotime($value->created_history_absensi));
            $years = date('Y', strtotime($value->created_history_absensi));
            $month = date('m', strtotime($value->created_history_absensi));
            $dayMonth = array(
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            );
            $value->created_history_absensi = $day.' '.$dayMonth[$month].' '.$years;
        }
        
        return json_encode($getData);
    }
    public function AreaChart()
    {
        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];
        $query = 'select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id where is_admin = "0" and id_parent = '.$parrent.'';

        if ($this->input->get('skpd')) {
            $filter['skpd'] = $this->input->get('skpd');
            $query .= " and c.id_unit in(".$this->input->get('skpd').")";
        }
        if ($this->input->get('jabatan')) {
            $filter['jabatan'] = $this->input->get('jabatan');
            $filter['jabatan'] = explode(',', $filter['jabatan']);
            $jabatan = "";
            foreach ($filter['jabatan'] as $key => $value) {
                $jabatan .= ",'$value'";
            }
            $jabatan = substr($jabatan, 1);
            $query .= " and c.jabatan in(".$jabatan.")";
        }
        if ($this->input->get('jenkel')) {
            $filter['jenkel'] = $this->input->get('jenkel');
            $filter['jenkel'] = explode(',', $filter['jenkel']);
            $jenkel = "";
            foreach ($filter['jenkel'] as $key => $value) {
                $jenkel .= ",'$value'";
            }
            $jenkel = substr($jenkel, 1);
            $query .= " and jenis_kelamin in(".$jenkel.")";
        }
        if ($this->input->get('dari')) {
            $filter['dari'] = $this->input->get('dari');
            $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
        }else{
            $query .= " and date_format(created_absensi, '%d') = day(now()) ";
        }
        if ($this->input->get('sampai')) {
            $filter['sampai'] = $this->input->get('sampai');
            $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
        }


         $tujuh = $query.'and time(waktu_datang) >= "07:00:00" AND time(waktu_datang) < "07:30:00"';
         $tujuh = $this->Db_select->query_all($tujuh);
         foreach ($tujuh as $key => $value) {
             $dtujuh=$value->total;
         }

         $tujuh30 = $query.'and time(waktu_datang) >= "07:30:00" AND time(waktu_datang) < "08:00:00"';
         $tujuh30 = $this->Db_select->query_all($tujuh30);
         foreach ($tujuh30 as $key => $value) {
             $dtujuh30=$value->total;
         }

         $delapan = $query.'and time(waktu_datang) >= "08:00:00" AND time(waktu_datang) < "08:30:00"';
         $delapan = $this->Db_select->query_all($delapan);
         foreach ($delapan as $key => $value) {
             $ddelapan=$value->total;
         }

         $delapan30 = $query.'and time(waktu_datang) >= "08:30:00" AND time(waktu_datang) < "09:00:00"';
         $delapan30 = $this->Db_select->query_all($delapan30);
         foreach ($delapan30 as $key => $value) {
             $ddelapan30=$value->total;
         }

         $sembilan = $query.'and time(waktu_datang) >= "09:00:00" AND time(waktu_datang) < "09:30:00"';
         $sembilan = $this->Db_select->query_all($sembilan);
         foreach ($sembilan as $key => $value) {
             $dsembilan=$value->total;
         }

         $sembilan30 = $query.'and time(waktu_datang) >= "09:30:00" AND time(waktu_datang) < "10:00:00"';
         $sembilan30 = $this->Db_select->query_all($sembilan30);
         foreach ($sembilan30 as $key => $value) {
             $dsembilan30=$value->total;
         }

         $sepuluh = $query.'and time(waktu_datang) > "10:00:00"';
         $sepuluh = $this->Db_select->query_all($sepuluh);
         foreach ($sepuluh as $key => $value) {
             $dsepuluh=$value->total;
         }

        $chart = "<script type='text/javascript'>
                    $(function () {
                        Highcharts.setOptions({
                         colors: ['#03A9F4', '#3F51B5']
                        });
                        Highcharts.chart('container2', {
                            chart: {
                                type: 'column',
                                options3d: {
                                    enabled: true,
                                    alpha: 10,
                                    beta: 25,
                                    depth: 70
                                },

                            },
                            title: {
                                text: 'Statistik jam Masuk Pegawai'
                            },
                             tooltip: {
                                pointFormat: '<b>{point.y:,.0f}</b> {series.name} datang pada jam tersebut'
                            },
                            plotOptions: {
                                column: {
                                    depth: 25
                                }
                            },
                            xAxis: {
                                categories: ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '< 10:00'],
                                title: {
                                    text: null
                                }
                        
                            },
                            yAxis: {
                                title: {
                                    text: null
                                }
                            },
                            series: [{
                                name: 'Pegawai',
                                data: [".$dtujuh.",".$dtujuh30.", ".$ddelapan.", ".$ddelapan30.", ".$dsembilan.", ".$dsembilan30.", ".$dsepuluh."]
                            }]
                        });
                    });
        </script>";

        return $chart;

    }
    public function DonutChart()
    {
        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];
        $query = 'select count(*) total FROM tb_user j join tb_unit c on j.id_unit = c.id_unit WHERE is_admin = 0 AND id_parent ='.$parrent.' ';
        if ($this->input->get('skpd')) {
            $filter['skpd'] = $this->input->get('skpd');
            $query .= " and c.id_unit in(".$this->input->get('skpd').")";
        }
        if ($this->input->get('jabatan')) {
            $filter['jabatan'] = $this->input->get('jabatan');
            $filter['jabatan'] = explode(',', $filter['jabatan']);
            $jabatan = "";
            foreach ($filter['jabatan'] as $key => $value) {
                $jabatan .= ",'$value'";
            }
            $jabatan = substr($jabatan, 1);
            $query .= " and jabatan in(".$jabatan.")";
        }
        if ($this->input->get('jenkel')) {
            $filter['jenkel'] = $this->input->get('jenkel');
            $filter['jenkel'] = explode(',', $filter['jenkel']);
            $jenkel = "";
            foreach ($filter['jenkel'] as $key => $value) {
                $jenkel .= ",'$value'";
            }
            $jenkel = substr($jenkel, 1);
            $query .= " and jenis_kelamin in(".$jenkel.")";
        }     
        $getDataL = $query.' and jenis_kelamin = "l"';
        $getDataP = $query.' and jenis_kelamin = "p"';

        $getDataL = $this->Db_select->query_all($getDataL);
        $getDataP = $this->Db_select->query_all($getDataP);
         foreach ($getDataL as $key => $value) {
             $pria=$value->total;
         }
         foreach ($getDataP as $key => $value) {
             $wanita=$value->total;
         }

        $chart = "<script type='text/javascript'>
                    $(function () {
                         Highcharts.setOptions({
                         colors: ['#2196F3', '#4CAF50']
                        });
                        Highcharts.chart('container3', {
                            chart: {
                                type: 'pie',
                                options3d: {
                                    enabled: true,
                                    alpha: 45
                                }
                            },
                             title: {
                                text: 'Statistik Gender Pegawai'
                            },
                            plotOptions: {
                                pie: {
                                    innerSize: 100,
                                    depth: 45
                                }
                            },
                            series: [{
                                name: 'Jumlah pegawai',
                                data: [
                                    ['Pria',".$pria."],
                                    ['Wanita', ".$wanita."]
                                ],
                                
                            }],
                            color:'#12bc89'
                        });
                    });
                            </script>";

                            return $chart;
                           
    }
    public function filter()
    {
         $filter = array();
        // echo json_encode($filter); exit();
        $id_channel="1";
        $month_array = array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des');
        $status = array('Tepat Waktu', 'Terlambat', 'Tepat Waktu');
        $datar['user'] = $this->session->userdata('user');
        $tanggal = date("Y/m/d");
        $tanggal2 = date("m");
        $tanggal3 = date("d");
        $data['select_absen']=$this->DB_super_admin->select_absensi_today($tanggal);
        $jml = $this->DB_super_admin->count_absen($tanggal);
        // $data['jumlah_hadir']=$jml->total;
        $pgw = $this->DB_super_admin->count_pegawai();

        $data['skpd'] = $this->Db_select->query_all('select *from tb_unit where id_channel = '.$id_channel.' order by nama_unit');
        $data['id_channel'] = $id_channel;
        $data['jabatan'] = $this->Db_select->query_all('select *from tb_jabatan where is_aktif = 1 order by nama_jabatan asc');

        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];
        $query = "select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id where date_format(created_absensi, '%m') = '".$tanggal2."' AND id_parent =".$parrent."  ";

        $pegawai = "select count(*) total from tb_user  where is_admin='0' AND id_parent =".$parrent."";

        if ($this->input->get('skpd')) {
            $filter['skpd'] = $this->input->get('skpd');
            $query .= " and c.id_unit in(".$this->input->get('skpd').")";
            $pegawai .= " and id_unit in(".$this->input->get('skpd').")";
        }
        if ($this->input->get('jabatan')) {
            $filter['jabatan'] = $this->input->get('jabatan');
            $filter['jabatan'] = explode(',', $filter['jabatan']);
            $jabatan = "";
            foreach ($filter['jabatan'] as $key => $value) {
                $jabatan .= ",'$value'";
            }
            $jabatan = substr($jabatan, 1);
            $query .= " and c.jabatan in(".$jabatan.")";
            $pegawai .= " and jabatan in(".$this->input->get('jabatan').")";
        }
        if ($this->input->get('jenkel')) {
            $filter['jenkel'] = $this->input->get('jenkel');
            $filter['jenkel'] = explode(',', $filter['jenkel']);
            $jenkel = "";
            foreach ($filter['jenkel'] as $key => $value) {
                $jenkel .= ",'$value'";
            }
            $jenkel = substr($jenkel, 1);
            $query .= " and c.jenis_kelamin in(".$jenkel.")";
            $pegawai .= " and jenis_kelamin in(".$jenkel.")";
        }
        if ($this->input->get('dari')) {
            $filter['dari'] = $this->input->get('dari');
            $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
        }else{
            $query .= " and date_format(created_absensi, '%d') = day(now()) ";
        }
        if ($this->input->get('sampai')) {
            $filter['sampai'] = $this->input->get('sampai');
            $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
        }
        if ($filter) {
            $data['show'] = 'true';
        }else{
            $data['show'] = 'false';
        }

            $getDatatepat = $query.' and status_absensi = "Tepat Waktu"';
            $getDataTH = $query.' and status_absensi = "Tidak Hadir"';
            $getDataK = $query.' and status_absensi = "Terlambat"';

            $getcount = $this->Db_select->query($getDatatepat);
            $getcount2 = $this->Db_select->query($getDataTH);
            $getcount3 = $this->Db_select->query($getDataK);

            $tepat_pie=  $getcount->total;
            $tidakhadir_pie=$getcount2->total;
            $terlambat_pie=$getcount3->total;
        

        $terlambat = $this->Db_select->query_all($query);

        $pegawai = $this->Db_select->query($pegawai);
        // echo json_encode($pegawai);exit();
        $data['jml_pegawai'] = $pegawai->total;
        // $data['tidak_hadir'] = $pegawai->total - $jml->total;

        // $WaktuTerlambat = 0;
        // $WaktuHadir = 0;
        // $WaktuTidakHadir = 0;
        // $averageDatang = 0;
        // $averagePulang = 0;

        $map = $this->DataMaps();

        // if (count($terlambat) > 0) {
        //     foreach ($terlambat as $key => $value) {
        //         if (date("h:i", strtotime($value->created_absensi)) > "08:30") {
        //             $WaktuTerlambat += 1;
        //         }else{
        //             $WaktuHadir += 1;
        //         }

        //         if ($value->waktu_datang != null || $value->waktu_datang != '') {
        //             $averageDatang += strtotime($value->waktu_datang);
        //         }

        //         if ($value->waktu_pulang != null || $value->waktu_pulang != '') {
        //             $averagePulang += strtotime($value->waktu_pulang);
        //         }
        //     }

        // }
        // $WaktuTidakHadir = ($tanggal3*$pegawai->total) - ($WaktuTerlambat+$WaktuHadir);
        // $coba= ($tanggal3*$pegawai->total);

        $data['performa'][0] = array('name' => 'Tepat waktu', 'y' => (int) $tepat_pie, 'color' => '#4CAF50');
        $data['performa'][1] = array('name' => 'Tidak Hadir', 'y' => (int) $tidakhadir_pie, 'color' => '#E91E63');
        $data['performa'][2] = array('name' => 'Terlambat', 'y' => (int) $terlambat_pie, 'color' => '#FF5722');

        // echo json_encode($data['performa']);exit();

        $LineChart = $this->LineChart();
        $BarChart = $this->BarChart();
        $AreaChart = $this->AreaChart();
        $DonutChart = $this->DonutChart();

        $data['line_chart'] = "";
        $data['line_chart'] .= $LineChart.$BarChart.$AreaChart.$DonutChart;
        $data['map'] = $map;
        $this->load->view('SO/header',$data);
        $this->load->view('SO/dashboard',$data);
        $this->load->view('SO/footer'); 
    }
}