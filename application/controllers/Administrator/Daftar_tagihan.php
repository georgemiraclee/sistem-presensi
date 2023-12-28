<?php defined('BASEPATH') OR exit('No direct script access allowed');
class daftar_tagihan extends CI_Controller
{ 
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('ceksession','global_lib'));
        $this->load->model('Db_datatable');
        $this->ceksession->login();
        $this->global_lib = new global_lib;
    }
    
    public function index()
    {
        $sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel'];
        
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
            redirect(base_url());exit();
        }

        $this->load->view('Administrator/header');
        $this->load->view('Administrator/daftar_tagihan');
        $this->load->view('Administrator/footer');
    }

    public function detail($id)
    {
        $getData =  $this->Db_select->query_all('select nama_user, nama_unit, id_jabatan, nama_jabatan, nominal FROM `tb_user` a join tb_unit b on a.id_unit = b.id_unit join tb_umk c on b.id_unit = c.id_unit join tb_jabatan d on a.jabatan = d.id_jabatan WHERE a.id_unit ='.$id.' order by a.jabatan desc');

        $data['list'] = "";
        foreach ($getData as $key => $value) {
            $no1 = $this->formula(11, $value->id_jabatan);
            $no2 = $this->formula(12, $value->id_jabatan);
            $no3 = $this->formula(13, $value->id_jabatan);
            $no4 = $this->formula(14, $value->id_jabatan);
            $no5 = $this->formula(15, $value->id_jabatan);
            $no6 = $this->formula(16, $value->id_jabatan);
            $no7 = $this->formula(17, $value->id_jabatan);
            $no8 = $this->formula(18, $value->id_jabatan);
            $no9 = $this->formula(19, $value->id_jabatan);

            $no = $key+1;
            $data['list'] .= "
                <tr>
                    <td>".$no."</td>
                    <td>".$value->nama_user."</td>
                    <td>".$value->nama_unit."</td>
                    <td>".$value->nama_jabatan."</td>
                    <td>".$this->rupiah($this->formula(11, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($this->formula(12, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($this->formula(13, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($this->formula(14, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($this->formula(15, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($this->formula(16, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($this->formula(17, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($this->formula(18, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($this->formula(19, $value->id_jabatan))."</td>
                    <td>".$this->rupiah($no1+$no2+$no3+$no4+$no5+$no6+$no7+$no8+$no9)."</td>
                </tr>";
        }
        $data['id_unit'] = $id;
      
        $sess = $this->session->userdata('user');
        $this->load->view('Administrator/header',$data);
        $this->load->view('Administrator/detail_daftar_tagihan');
        $this->load->view('Administrator/footer');
    }

    public function export($id){
        $getData =  $this->Db_select->query_all('select nama_user, nama_unit, id_jabatan, nama_jabatan, nominal FROM `tb_user` a join tb_unit b on a.id_unit = b.id_unit join tb_umk c on b.id_unit = c.id_unit join tb_jabatan d on a.jabatan = d.id_jabatan WHERE a.id_unit ='.$id);

        $getDataNew =  $this->Db_select->query('select nama_user, nama_unit, id_jabatan, nama_jabatan, nominal FROM `tb_user` a join tb_unit b on a.id_unit = b.id_unit join tb_umk c on b.id_unit = c.id_unit join tb_jabatan d on a.jabatan = d.id_jabatan WHERE a.id_unit ='.$id);

        $this->load->library('Excel');

        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('My Notes Code')
                     ->setLastModifiedBy('My Notes Code')
                     ->setTitle("Tagihan Gaji Pegawai ")
                     ->setSubject("PT. ARTDECO SEJAHTERA ABADI")
                     ->setDescription("BULAN AGUSTUS 2019")
                     ->setKeywords("Gaji Pegawai");

        $style_col = array(
          'font' => array('bold' => true), // Set font nya jadi bold
          'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $style_col2 = array(
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DAFTAR TAGIHAN GAJI PEGAWAI OUTSOURCING"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:N1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
        $excel->setActiveSheetIndex(0)->setCellValue('A2', $getDataNew->nama_unit); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A2:N2'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "PT. ARTDECO SEJAHTERA ABADI"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A3:N3'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
        $excel->setActiveSheetIndex(0)->setCellValue('A4', "BULAN AGUSTUS 2019"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A4:N4'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
        
        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A5', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B5', "Nama");
        $excel->setActiveSheetIndex(0)->setCellValue('C5', "Pengguna");
        $excel->setActiveSheetIndex(0)->setCellValue('D5', "Jabatan"); 
        $excel->setActiveSheetIndex(0)->setCellValue('E5', "UMR/UMK");
        $excel->setActiveSheetIndex(0)->setCellValue('F5', "Tunjangan Tetap");
        $excel->setActiveSheetIndex(0)->setCellValue('G5', "BPJS");
        $excel->setActiveSheetIndex(0)->setCellValue('H5', "BPJS");
        $excel->setActiveSheetIndex(0)->setCellValue('I5', "Lembur Melekat Di gaji");
        $excel->setActiveSheetIndex(0)->setCellValue('J5', "Hari Raya");
        $excel->setActiveSheetIndex(0)->setCellValue('K5', "PAKDIN");
        $excel->setActiveSheetIndex(0)->setCellValue('L5', "Fee");
        $excel->setActiveSheetIndex(0)->setCellValue('M5', "PPN");
        $excel->setActiveSheetIndex(0)->setCellValue('N5', "Jumlah Tagihan");

        $excel->getActiveSheet()->getStyle('A5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N5')->applyFromArray($style_col);

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 6; // Set baris pertama untuk isi tabel adalah baris ke 4

        foreach ($getData as $key => $value) {
            $no1 = $this->formula(11, $value->id_jabatan);
            $no2 = $this->formula(12, $value->id_jabatan);
            $no3 = $this->formula(13, $value->id_jabatan);
            $no4 = $this->formula(14, $value->id_jabatan);
            $no5 = $this->formula(15, $value->id_jabatan);
            $no6 = $this->formula(16, $value->id_jabatan);
            $no7 = $this->formula(17, $value->id_jabatan);
            $no8 = $this->formula(18, $value->id_jabatan);
            $no9 = $this->formula(19, $value->id_jabatan);

            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $key+1);
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $value->nama_user);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $value->nama_unit);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $value->nama_jabatan);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $this->formula(11, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $this->formula(12, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $this->formula(13, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $this->formula(14, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $this->formula(15, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $this->formula(16, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $this->formula(17, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $this->formula(18, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $this->formula(19, $value->id_jabatan));
            $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $no1+$no2+$no3+$no4+$no5+$no6+$no7+$no8+$no9);

            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_col2);
            $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_col2);

            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(20); // Set width kolom D

        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Laporan Tagihan Gaji");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        // header('Content-Type: application/vnd.ms-excel');
        // header('Content-Disposition: attachment;filename="DataTagihan.xls"');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DataTagihan.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write->save('php://output');
    }

    public function getData() {
        $sess = $this->session->userdata('user');
        $columns = array( 
            0 =>  'no', 
            1 =>  'nama_unit',
            2 =>  'aksi'
        );
        
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = $columns[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];
        $query = 'select *from tb_unit where id_channel = "'.$sess['id_channel'].'" and id_unit = 56';
        $totalData = $this->Db_global->allposts_count_all($query);
        $totalFiltered = $totalData;
        
        if(empty($this->input->post('search')['value'])){
            $posts = $this->Db_global->allposts_all($query." order by ".$order." ".$dir." limit ".$start.",".$limit."");
        }else{
            $search = $this->input->post('search')['value'];
            $posts = $this->Db_global->posts_search_all($query." and nama_unit like '%".$search."%' order by ".$order." ".$dir." limit ".$start.",".$limit."");
            $totalFiltered = $this->Db_global->posts_search_count_all($query." and nama_unit like '%".$search."%' or is_aktif like '%".$search."%'");
        }

        $data = array();
        if(!empty($posts)){
            foreach ($posts as $key => $post){
                $nestedData['no']  = $key+1;
                $nestedData['nama_unit']  = ucwords($post->nama_unit);
                $nestedData['aksi']  = '
                    <a href="'.base_url().'Administrator/daftar_tagihan/detail/'.$post->id_unit.'" style="color: grey"><button class="btn btn-info btn-xs"><i class="material-icons">open_in_new</i> <span class="icon-name">Invoice Unit Kerja</span></button></a>
                    ';
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

    public function formula($id_komponen, $id_jabatan) {
        $where['id_komponen_pendapatan'] = $id_komponen;
        $where['id_jabatan'] = $id_jabatan;
        $getIsiKomponen = $this->Db_select->select_where('tb_isi_komponen_pendapatan', $where);

        if ($getIsiKomponen) {
        if ($getIsiKomponen->is_formula == 1) {
            return $this->perhitungan($getIsiKomponen->formula);
        }else{
            return $getIsiKomponen->nominal;
        }
        }else{
        return 0;
        }
    }

    public function perhitungan($formula) {
        $data = explode(" ", $formula);

        for ($i=0; $i < count($data); $i++) { 
            /* detect id */
            $id = substr($data[$i], 0, 3);
            if ($id == "idk") {
                /* id Komponen */
                $idKomponen = substr($data[$i], 4);

                /* get data komponen */
                $getDataKomponen = $this->Db_select->select_where('tb_isi_komponen_pendapatan','id_komponen_pendapatan = '.$idKomponen);
                
                if ($getDataKomponen) {
                if ($getDataKomponen->is_formula == 1) {
                    $data[$i] = $this->perhitungan($getDataKomponen->formula);
                }else{
                    $data[$i] = $getDataKomponen->nominal;
                }
                }else{
                $data[$i] = 0;
                }
            } elseif ($id == "idu") {
                /* id Komponen */
                $idKomponen = substr($data[$i], 4);
                
                /* get data UMK */
                $getUMK = $this->Db_select->select_where('tb_umk', 'id_umk = '.$idKomponen);

                if ($getUMK) {
                $data[$i] = $getUMK->nominal;
                }else{
                $data[$i] = 0;
                }
            } elseif ($id == "idt") {
                /* id Komponen */
                $idKomponen = substr($data[$i], 4);

                /* get data tunjangan jabatan */
                $getTunjangan = $this->Db_select->select_where('tb_tunjangan_jabatan', 'id_tunjangan_jabatan = '.$idKomponen);

                if ($getTunjangan) {
                $data[$i] = $getTunjangan->nominal;
                }else{
                $data[$i] = 0;
                }

            }
            /* end */

            /* detect persen */
            $persen = substr($data[$i], -1);
            if ($persen == "%") {
                $data[$i] = str_replace("%", "/100", $data[$i]);
            }
            /* end */
        }

        $data = implode($data);

        eval( '$result = (' . $data. ');' );
        return $result;
    }
    
    public function rupiah($angka){
        $hasil_rupiah = number_format($angka,0,',','.');
        return $hasil_rupiah;
    }
}