<?php
require_once("mysqlminang.php");
$p=new Mysqlminang("jadwal","localhost","root","");
$sql="Select * from jadwalacara";
$data=array();
foreach($p->GetAllRows($sql) as $row)
{
	$data[]=array(
				'title'=>$row->judul,
				'start'=>$row->tgl1,
				'end'=>$row->tgl2,
				);
}
	echo json_encode($data);
?>