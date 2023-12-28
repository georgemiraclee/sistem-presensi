<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class DashboardChannel extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function getDashboard($id_channel,$skpd = null)
    {
        $filter = array();
        if ($this->input->get('skpd')) {
            $filter['skpd'] = $this->input->get('skpd');
        }
        if ($this->input->get('jabatan')) {
            $filter['jabatan'] = $this->input->get('jabatan');
        }
        if ($this->input->get('jenkel')) {
            $filter['jenkel'] = $this->input->get('jenkel');
        }

        if ($this->input->get('status')) {
            $filter['status'] = $this->input->get('status');
        }

        if ($filter) {
            $data['show'] = 'true';
        }else{
            $data['show'] = 'false';
        }

        // echo json_encode($filter); exit();
        $month_array = array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des');
        $status = array('Tepat Waktu', 'Terlambat', 'Tepat Waktu');
        $datar['user'] = $this->session->userdata('user');
        $tanggal = date("Y/m/d");
        $tanggal2 = date("m");
        $tanggal3 = date("d");
        $data['select_absen']=$this->DB_super_admin->select_absensi_today($tanggal);
        $jml = $this->DB_super_admin->count_absen($tanggal);
        $data['jumlah_hadir']=$jml->total;
        $pgw = $this->DB_super_admin->count_pegawai();
        $data['tidak_hadir']=$pgw->total - $jml->total;
        $data['skpd'] = $this->db_select->query_all('select *from tb_unit where id_channel = '.$id_channel.' order by nama_unit');
        $data['id_channel'] = $id_channel;
        $data['jabatan'] = $this->db_select->query_all('select jabatan from tb_user where jabatan is not null group by jabatan');

        $terlambat = $this->db_select->query_all("select * from tb_absensi j join tb_user c on j.nip = c.nip where date_format(created_absensi, '%m') = '".$tanggal2."'");
        $WaktuTerlambat = 0;
        $WaktuHadir = 0;
        $WaktuTidakHadir = 0;
        $averageDatang = 0;
        $averagePulang = 0;

        $map = $this->DataMaps();

        if (count($terlambat) > 0) {
            foreach ($terlambat as $key => $value) {
                if (date("h:i", strtotime($value->created_absensi)) > "08:30") {
                    $WaktuTerlambat += 1;
                }else{
                    $WaktuHadir += 1;
                }

                if ($value->waktu_datang != null || $value->waktu_datang != '') {
                    $averageDatang += strtotime($value->waktu_datang);
                }

                if ($value->waktu_pulang != null || $value->waktu_pulang != '') {
                    $averagePulang += strtotime($value->waktu_pulang);
                }
            }

        }
        $WaktuTidakHadir = $tanggal3*$pgw->total - count($terlambat);

        $data['performa'][0] = array('name' => 'Hadir', 'y' => (int) $WaktuHadir, 'color' => '#12bc89');
        $data['performa'][1] = array('name' => 'Tidak Hadir', 'y' => (int) $WaktuTidakHadir, 'color' => '#3e4042');
        $data['performa'][2] = array('name' => 'Terlambat', 'y' => (int) $WaktuTerlambat, 'color' => '#EA951D');

        // echo json_encode($data['performa']); exit();

        $LineChart = $this->LineChart();
        $BarChart = $this->BarChart();
        $AreaChart = $this->AreaChart();
        $DonutChart = $this->DonutChart();

        $data['line_chart'] = "";
        $data['line_chart'] .= $LineChart.$BarChart.$AreaChart.$DonutChart;
        $data['map'] = $map;
           
        $this->load->view('Super_admin/header', $data);
        $this->load->view('Super_admin/dashboard_channel',$data);
    }

    public function LineChart()
    {
        $status = array('Tepat Waktu', 'Terlambat', 'Tepat Waktu');
        $kategori = "";
        $xy = "";
        $time = time();

        $i = 1;
        $xy .= "{name: 'Tepat Waktu', data: [";

        while ($i <= 12) {
            if ($i == 1) {
                $kategori .= "'Januari ".date("Y",$time)."',";
                $bulan = '01-'.date("Y",$time);
            } else if ($i == 2) {
                $kategori .= "'Februari ".date("Y",$time)."',";
                $bulan = '02-'.date("Y",$time);
            } else if ($i == 3) {
                $kategori .= "'Maret ".date("Y",$time)."',";
                $bulan = '03-'.date("Y",$time);
            } else if ($i == 4) {
                $kategori .= "'April ".date("Y",$time)."',";
                $bulan = '04-'.date("Y",$time);
            } else if ($i == 5) {
                $kategori .= "'Mei ".date("Y",$time)."',";
                $bulan = '05-'.date("Y",$time);
            } else if ($i == 6) {
                $kategori .= "'Juni ".date("Y",$time)."',";
                $bulan = '06-'.date("Y",$time);
            } else if ($i == 7) {
                $kategori .= "'Juli ".date("Y",$time)."',";
                $bulan = '07-'.date("Y",$time);
            } else if ($i == 8) {
                $kategori .= "'Agustus ".date("Y",$time)."',";
                $bulan = '08-'.date("Y",$time);
            } else if ($i == 9) {
                $kategori .= "'September ".date("Y",$time)."',";
                $bulan = '09-'.date("Y",$time);
            } else if ($i == 10) {
                $kategori .= "'Oktober ".date("Y",$time)."',";
                $bulan = '10-'.date("Y",$time);
            } else if ($i == 11) {
                $kategori .= "'November ".date("Y",$time)."',";
                $bulan = '11-'.date("Y",$time);
            } else {
                $kategori .= "'Desember ".date("Y",$time)."',";
                $bulan = '12-'.date("Y",$time);
            }

            $data_statistik = $this->db_select->query_all('select *from tb_absensi where date_format(created_absensi,"%m-%Y") = "'.$bulan.'" and date_format(created_absensi,"%h:%i") < "08:30"');

            if (count($data_statistik) == 0) {
                $jmh = 0;
            } else {
                $jmh = count($data_statistik);
            }
            
            $xy .= $jmh.",";

            $i++;
        }

        $xy .= "], color: '#12bc89'},";

        $i = 1;
        $xy .= "{name: 'Terlambat', data: [";

        while ($i <= 12) {
            if ($i == 1) {
                $kategori .= "'Januari ".date("Y",$time)."',";
                $bulan = '01-'.date("Y",$time);
            } else if ($i == 2) {
                $kategori .= "'Februari ".date("Y",$time)."',";
                $bulan = '02-'.date("Y",$time);
            } else if ($i == 3) {
                $kategori .= "'Maret ".date("Y",$time)."',";
                $bulan = '03-'.date("Y",$time);
            } else if ($i == 4) {
                $kategori .= "'April ".date("Y",$time)."',";
                $bulan = '04-'.date("Y",$time);
            } else if ($i == 5) {
                $kategori .= "'Mei ".date("Y",$time)."',";
                $bulan = '05-'.date("Y",$time);
            } else if ($i == 6) {
                $kategori .= "'Juni ".date("Y",$time)."',";
                $bulan = '06-'.date("Y",$time);
            } else if ($i == 7) {
                $kategori .= "'Juli ".date("Y",$time)."',";
                $bulan = '07-'.date("Y",$time);
            } else if ($i == 8) {
                $kategori .= "'Agustus ".date("Y",$time)."',";
                $bulan = '08-'.date("Y",$time);
            } else if ($i == 9) {
                $kategori .= "'September ".date("Y",$time)."',";
                $bulan = '09-'.date("Y",$time);
            } else if ($i == 10) {
                $kategori .= "'Oktober ".date("Y",$time)."',";
                $bulan = '10-'.date("Y",$time);
            } else if ($i == 11) {
                $kategori .= "'November ".date("Y",$time)."',";
                $bulan = '11-'.date("Y",$time);
            } else {
                $kategori .= "'Desember ".date("Y",$time)."',";
                $bulan = '12-'.date("Y",$time);
            }

            $data_statistik = $this->db_select->query_all('select *from tb_absensi where date_format(created_absensi,"%m-%Y") = "'.$bulan.'" and date_format(created_absensi,"%h:%i") > "08:30"');

            if ($data_statistik == null) {
                $jmh = 0;
            } else {
                $jmh = count($data_statistik);
            }
            
            $xy .= $jmh.",";

            $i++;
        }

        $xy .= "], color: '#EA951D'},";

        $i = 1;
        $xy .= "{name: 'Tidak Hadir', data: [";

        while ($i <= 12) {
            if ($i == 1) {
                $kategori .= "'Januari ".date("Y",$time)."',";
                $bulan = '01-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '1';
            } else if ($i == 2) {
                $kategori .= "'Februari ".date("Y",$time)."',";
                $bulan = '02-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '2';
            } else if ($i == 3) {
                $kategori .= "'Maret ".date("Y",$time)."',";
                $bulan = '03-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '3';
            } else if ($i == 4) {
                $kategori .= "'April ".date("Y",$time)."',";
                $bulan = '04-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '4';
            } else if ($i == 5) {
                $kategori .= "'Mei ".date("Y",$time)."',";
                $bulan = '05-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '5';
            } else if ($i == 6) {
                $kategori .= "'Juni ".date("Y",$time)."',";
                $bulan = '06-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '6';
            } else if ($i == 7) {
                $kategori .= "'Juli ".date("Y",$time)."',";
                $bulan = '07-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '7';
            } else if ($i == 8) {
                $kategori .= "'Agustus ".date("Y",$time)."',";
                $bulan = '08-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '8';
            } else if ($i == 9) {
                $kategori .= "'September ".date("Y",$time)."',";
                $bulan = '09-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '9';
            } else if ($i == 10) {
                $kategori .= "'Oktober ".date("Y",$time)."',";
                $bulan = '10-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '10';
            } else if ($i == 11) {
                $kategori .= "'November ".date("Y",$time)."',";
                $bulan = '11-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '11';
            } else {
                $kategori .= "'Desember ".date("Y",$time)."',";
                $bulan = '12-'.date("Y",$time);
                $tanggal = date("Y",$time);
                $newBulan = '12';
            }

            $data_statistik = $this->db_select->query_all('select *from tb_absensi where date_format(created_absensi,"%m-%Y") = "'.$bulan.'"');

            $pgw = $this->DB_super_admin->count_pegawai();
            $totalHari = cal_days_in_month(CAL_GREGORIAN,$newBulan,$tanggal);

            if (count($data_statistik) == 0) {
                $tanggal = date('n');
                $tanggal2 = date('j');
                if ($i == $tanggal) {
                    $jmh = $tanggal2*$pgw->total - count($data_statistik);
                }else{
                    $jmh = 0;
                }
            } else {
                $jmh = $totalHari*$pgw->total - count($data_statistik);
            }

            $xy .= $jmh.",";

            $i++;
        }

        $xy .= "], color: '#3e4042'},";

        // echo json_encode($xy); exit();

        $chart = "<script type='text/javascript'>
                    $(function () {
                        $('#statistik').highcharts({
                            title: {
                                text: 'Statistik Laporan',
                                x: -20 //center
                            },subtitle: {
                                text: '',
                                x: -20
                            },
                            yAxis: {
                                title: {
                                    text: 'Jumlah Laporan'
                                }
                            },
                            xAxis: {
                                categories: [".$kategori."],
                                type: 'category',
                                tickmarkPlacement: 'on',
                                title: {
                                    enabled: false
                                }
                            },
                            series: [".$xy."]
                        });
                    });
                  </script>";

        return $chart;
    }

    public function BarChart()
    {
        $skpd = $this->db_select->query_all('select a.id_unit, a.nama_unit, ifnull(cek.total, 0) total from tb_unit a left outer join(select tb_user.id_unit, count(*) total from tb_absensi join tb_user on tb_absensi.nip = tb_user.nip) cek on a.id_unit = cek.id_unit order by cek.total desc limit 10');
        $NewSkdp = '[';
        $NewTerlambat = array();
        $NewTepatWaktu = array();
        $NewTidakHadir = array();

        foreach ($skpd as $key => $value) {
            $NewSkdp .= "'$value->nama_unit'".",";
            $tanggal2 = date("m");
            $tanggal3 = date("d");
            $terlambat = $this->db_select->query_all("select * from tb_absensi j join tb_user c on j.nip = c.nip where date_format(created_absensi, '%m') = '".$tanggal2."' and c.id_unit = ".$value->id_unit."");
            $pgw = $this->DB_super_admin->count_pegawai();

            $WaktuTerlambat = 0;
            $WaktuHadir = 0;
            $WaktuTidakHadir = 0;
            $averageDatang = 0;
            $averagePulang = 0;

            if (count($terlambat) > 0) {
                foreach ($terlambat as $key => $value) {
                    if (date("h:i", strtotime($value->created_absensi)) > "08:30") {
                        $WaktuTerlambat += 1;
                    }else{
                        $WaktuHadir += 1;
                    }
                }

            }
            $WaktuTidakHadir = $tanggal3*$pgw->total - count($terlambat);

            array_push($NewTerlambat, $WaktuTerlambat);
            array_push($NewTepatWaktu, $WaktuHadir);
            array_push($NewTidakHadir, $WaktuTidakHadir);
        }
        $NewSkdp .= ']';

        // echo json_encode($NewTerlambat); exit();
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
                color: '#EA951D'
            }, {
                name: 'Tidak Hadir',
                data: ".json_encode($NewTidakHadir).",
                color: '#3e4042'
            }, {
                name: 'Tepat Waktu',
                data: ".json_encode($NewTepatWaktu).",
                color: '#12bc89'
            }]
        });
        }); 
        </script>";

        return $chart;
    }

    public function daftarHadir()
    {
        $pg=$this->DB_super_admin->select_absensi_pegawai();
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
                    $ket="<span class='label label-primary label-form float-right' style'color:red';>Belum Datang</span>";
                }else{
                    $bek="#32B098";
                    $class="online";
                    $datang = $value->waktu_datang;
                    if ($datang > "08:30") {
                        $ket="<span class='label label-form float-right' style='background-color: #EA951D'>Kesiangan</span>";
                    }else {
                        $ket="<span class='label label-info label-form float-right' style'color:red';>Tepat Waktu</span>";
                    }                    
                }
                // $pegawai .='<tr>
       //                          <td><strong>'.$value->nip.'</strong></td>
       //                          <td><strong>'.$value->nama_user.'</strong></td>
       //                          <td>'.$datang.'</td>
       //                          <td>'.$ket.'</td>
       //                      </tr>';
                 $pegawai.=' <a href="#" class="list-group-item">                                 
                                        <div class="list-group-status status-'.$class.'"></div>
                                            <img src="'.base_url().'assets/images/member-photos/'.$value->foto_user.'" class="float-left">
                                                <span class="contacts-title">'.$value->nama_user.'</span>
                                                <p>'.$datang.' '.$ket.'</p>
                                            </a> ';
            }
        

        $tanggal = date("Y/m/d");
        $tanggal2 = date("m");
        $tanggal3 = date("d");
        $select_absen=$this->DB_super_admin->select_absensi_today($tanggal);
        $jml = $this->DB_super_admin->count_absen($tanggal);
        $jumlah_hadir=$jml->total;
        $pgw = $this->DB_super_admin->count_pegawai();
        $tidak_hadir=$pgw->total - $jml->total;

        // $query = "select * from tb_absensi j join tb_user c on j.nip = c.nip where date_format(created_absensi, '%m') = '".$tanggal."'";
        // if ($this->input->get('skpd')) {
        //     $filter = 
        // }

        $terlambat = $this->db_select->query_all("select * from tb_absensi j join tb_user c on j.nip = c.nip where date_format(created_absensi, '%m') = '".$tanggal2."'");
        $WaktuTerlambat = 0;
        $WaktuHadir = 0;
        $WaktuTidakHadir = 0;
        $averageDatang = 0;
        $averagePulang = 0;

        if (count($terlambat) > 0) {
            foreach ($terlambat as $key => $value) {
                if (date("h:i", strtotime($value->created_absensi)) > "08:30") {
                    $WaktuTerlambat += 1;
                }else{
                    $WaktuHadir += 1;
                }

                if ($value->waktu_datang != null || $value->waktu_datang != '') {
                    $averageDatang += strtotime($value->waktu_datang);
                }

                if ($value->waktu_pulang != null || $value->waktu_pulang != '') {
                    $averagePulang += strtotime($value->waktu_pulang);
                }
            }

        }
        
        $WaktuTidakHadir = $pgw->total - count($terlambat);

        if ($averageDatang != 0) {
            $averageDatang = date("h:i", $averageDatang / count($terlambat));
        }else{
            $averageDatang = '-';
        }

        if ($averagePulang != 0) {
            $averagePulang = date("h:i", $averagePulang / count($terlambat));
        }else{
            $averagePulang = '-';
        }

        $result['status'] = true;
        $result['message'] = 'Data ditemukan.';
        $result['data'] = $pegawai;
        $result['jumlah_hadir'] = $WaktuHadir;
        $result['tidak_hadir'] = $WaktuTidakHadir;
        $result['terlambat'] = $WaktuTerlambat;
        $result['waktu_datang'] = $averageDatang;
        $result['waktu_pulang'] = $averagePulang;

        echo json_encode($result); exit();
    }

    public function DataMaps()
    {
        $getData = $this->db_select->query_all('select a.nama_user, a.foto_user, c.lat, c.lng, c.created_history_absensi from tb_user a join tb_absensi b on a.nip = b.nip join tb_history_absensi c on b.id_absensi = c.id_absensi where c.lat is not null and c.lng is not null group by a.nip order by c.created_history_absensi desc');

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
         $tujuh = $this->db_select->query_all('select count(*) total FROM tb_absensi j join tb_user c on j.nip = c.nip WHERE date_format(waktu_datang, "%h:i:s") > "07:00:00" AND date_format(waktu_datang, "%h:i:s") < "07:30:00" AND is_superadmin ="0"');
         foreach ($tujuh as $key => $value) {
             $dtujuh=$value->total;
         }
          $tujuh30 = $this->db_select->query_all('select count(*) total FROM tb_absensi j join tb_user c on j.nip = c.nip WHERE date_format(waktu_datang, "%h:i:s") > "07:30:00" AND date_format(waktu_datang, "%h:i:s") < "08:00:00" AND is_superadmin ="0"');
         foreach ($tujuh30 as $key => $value) {
             $dtujuh30=$value->total;
         }
          $delapan= $this->db_select->query_all('select count(*) total FROM tb_absensi j join tb_user c on j.nip = c.nip WHERE date_format(waktu_datang, "%h:i:s") > "08:00:00" AND date_format(waktu_datang, "%h:i:s") < "08:30:00" AND is_superadmin ="0"');
         foreach ($delapan as $key => $value) {
             $ddelapan=$value->total;
         }
         $delapan30= $this->db_select->query_all('select count(*) total FROM tb_absensi j join tb_user c on j.nip = c.nip WHERE date_format(waktu_datang, "%h:i:s") > "08:30:00" AND date_format(waktu_datang, "%h:i:s") < "09:00:00" AND is_superadmin ="0"');
         foreach ($delapan30 as $key => $value) {
             $ddelapan30=$value->total;
         }
         $sembilan= $this->db_select->query_all('select count(*) total FROM tb_absensi j join tb_user c on j.nip = c.nip WHERE date_format(waktu_datang, "%h:i:s") > "09:00:00" AND date_format(waktu_datang, "%h:i:s") < "09:30:00" AND is_superadmin ="0"');
         foreach ($sembilan as $key => $value) {
             $dsembilan=$value->total;
         }
         $sembilan30= $this->db_select->query_all('select count(*) total FROM tb_absensi j join tb_user c on j.nip = c.nip WHERE date_format(waktu_datang, "%h:i:s") > "09:30:00" AND date_format(waktu_datang, "%h:i:s") < "10:00:00" AND is_superadmin ="0"');
         foreach ($sembilan30 as $key => $value) {
             $dsembilan30=$value->total;
         }
         $sepuluh= $this->db_select->query_all('select count(*) total FROM tb_absensi j join tb_user c on j.nip = c.nip WHERE date_format(waktu_datang, "%h:i:s") > "10:00:00" AND is_superadmin ="0"');
         foreach ($sepuluh as $key => $value) {
             $dsepuluh=$value->total;
         }

        $chart = "<script type='text/javascript'>
$(function () {
    Highcharts.setOptions({
     colors: ['#12bc89', '#3e4042']
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
         $getDataL = $this->db_select->query_all('select count(*) total FROM `tb_user` WHERE jenis_kelamin ="l" AND is_superadmin = 0');
         $getDataP = $this->db_select->query_all('select count(*) total FROM `tb_user` WHERE jenis_kelamin ="p" AND is_superadmin = 0');
         foreach ($getDataL as $key => $value) {
             $pria=$value->total;
         }
         foreach ($getDataP as $key => $value) {
             $wanita=$value->total;
         }

        $chart = "<script type='text/javascript'>
$(function () {
     Highcharts.setOptions({
     colors: ['#12bc89', '#3e4042']
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
}