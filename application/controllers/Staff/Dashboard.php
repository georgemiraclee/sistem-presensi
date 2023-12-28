<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '128M');
/**
* 
*/
class Dashboard extends CI_Controller
{ 
    private $file = 'appconfig/auto_respon.txt';
    function __construct()
    {
      parent::__construct();
      $this->load->library('Ceksession');
      $this->ceksession->login();
    }

    public function index()
    {   
      $sess = $this->session->userdata('user');
      if ($sess['akses'] !="staff") {
        redirect(base_url());exit();
      }
      $parrent=$sess['id_user'];

      $data_pegawai = $this->Db_select->query_all('select *from tb_user where user_id = '.$parrent.'');
      foreach ($data_pegawai as $key => $value) {
        if ($value->foto_user==""||$value->foto_user==null) {
          $foto="".base_url()."assets/images/member-photos/ava.png ";
        }else{
          $foto=" ".base_url()."assets/images/member-photos/$value->foto_user ";
        }

        $data_unit=$this->Db_select->query('select * from tb_unit where id_unit = '.$value->id_unit.'');
        $unit = $data_unit->nama_unit;
        $data_jabatan=$this->Db_select->query('select * from tb_jabatan where id_jabatan = '.$value->jabatan.'');
        $jabatan = $data_jabatan->nama_jabatan;
        $data_status=$this->Db_select->query('select * from tb_status_user where id_status_user ='.$value->status_user.' ');
        $status=$data_status->nama_status_user;
        $data['pegawai']='  <div class="col-sm-6 col-md-4">
              <div class="thumbnail" align="center">
                  <img src="'.$foto.'" class="img-thumbnail">
                  <div class="caption">
                      <h3>'.$value->nama_user.'</h3>
                        <p><small>Nip</small> :'.$value->nip.'<p>
                      <p><small>Unit</small> :'.$unit.'</p>
                      <p><small>Jabatan</small> :'.$jabatan.'</p>
                      <p><small>Status</small> :'.$status.'</p>
                  </div>
              </div>
        </div>';
      }

      $AreaChart = $this->AreaChart();
      $data['line_chart'] = $AreaChart;

      $select_kehadiran =$this->Db_select->query_all('select *from tb_absensi where user_id = '.$parrent.'');
      $data['stamp']="";

      foreach ($select_kehadiran as $key => $value) {
          $jadwal = json_decode(file_get_contents($this->file))->jam_kerja;
        $day = strtolower(date("l"));
        $jadwalNew = date_create($jadwal->$day->from);
        $jam_skrg = date_create(date("H:i", strtotime($value->waktu_datang)));
        $diff  = date_diff($jam_skrg, $jadwalNew);

        $keteranganTerlambat = "";
        if ($value->status_absensi == "Terlambat") {
            if ($diff->h != 0) {
                $keteranganTerlambat .= $diff->h." Jam ";
            }

            if ($diff->i != 0) {
                $keteranganTerlambat .= $diff->i." Menit ";
            }
        }

        if ($value->status_absensi == "Terlambat") {
            $warna='#f4bc42';
            $ket=$keteranganTerlambat;
        }
        if ($value->status_absensi == "Tidak Hadir") {
            $warna='#f44156';
              $ket="";
        }
        if ($value->status_absensi == "Tepat Waktu") {
            $warna='#41f48e';
              $ket="";
        } 


          $data['stamp'].=" { title :'".$value->status_absensi." ".$ket."',start: '".date('Y-m-d', strtotime($value->created_absensi))."',backgroundColor:'".$warna."',borderColor: '#fff' , height:'300' }, ";
          
      }

      $this->load->view('Staff/header', $data);
      $this->load->view('Staff/dashboard', $data);
      $this->load->view('Staff/footer');
    }

    public function AreaChart()
    {
        $sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];
        $query = 'select count(*) total from tb_absensi j join tb_user c on j.user_id = c.user_id where j.user_id = "'.$parrent.'" ';

        

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
}