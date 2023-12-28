<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
* 
*/
class polygon extends CI_Controller
{
	public function get_position($lat, $lng, $cordinate, $nama_polygon)
	{
        if (!file_exists(image_url()."polygon/".$nama_polygon)) {
            $real = realpath('../assets/polygon/'.$nama_polygon);
            if (($handle = fopen($real, "r")) !== FALSE) {
    			$ctr = 0;
                $index=0;
                $location = array();
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($ctr != 0) {
                        $num = count($data);
                        for ($c=0; $c < $num; $c++) {
                            if($c==0){
                                if (floatval($data[$c]) != 0) {
                                    $location[$index]['lat'] = floatval($data[$c]);
                                }
                            }else{
                                if (floatval($data[$c]) != 0) {
                                    $location[$index]['lng'] = floatval($data[$c]);
                                }
                            }
                        }
                        $index++;
                    }
                    $ctr++;
                }
                fclose($handle);
                $polygon = array();
                foreach ($location as $key => $value) {
                	$cek = $value['lat']." ".$value['lng'];
                	array_push($polygon, $cek);
                }
    		}
    		$points = "$lat $lng";
    		return $this->pointInPolygon($points, $polygon);
        }
	}
	public function pointInPolygon($point, $polygon, $pointOnVertex = true)
	{
		$this->pointOnVertex = $pointOnVertex;

        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }

        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return true;
        }

        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return true;
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return true;
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is odd, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return true;
        } else {
            return false;
        }
	}
	public function pointOnVertex($point, $vertices)
	{
		foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
	}
	public function pointStringToCoordinates($pointString)
	{
		$coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
	}
}