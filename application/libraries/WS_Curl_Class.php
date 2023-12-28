<?php
/***********************************************************************************
* CRM Erweiterung Webservices
* Version: 1.0
* Copyright (C) crm-now
* All Rights Reserved
* www.crm-now.de
************************************************************************************/

class WS_Curl_Class {
	var $endpointUrl;
	var $userName;
	var $userKey;
	var $token;
	var $curl_handler;
	var $errorMsg = '';
	
	var $defaults = array(
			CURLOPT_HEADER => 0,
			CURLOPT_HTTPHEADER => array('Expect:'),
			// CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0
		);
	
	//constructor saves the values
	function __construct() {
		$this->endpointUrl="http://crm.sorot.id/webservice.php";
		$this->userName='webservice';
		$this->userKey='HIG9MxGE8Io9RRBB';
		$this->token=0;
	}

	private function getChallenge() {
		$curl_handler = curl_init();
		$params = array("operation" => "getchallenge", "username" => $this->userName);
		$options = array(CURLOPT_URL => $this->endpointUrl."?".http_build_query($params));
		curl_setopt_array($curl_handler, ($this->defaults + $options));
		
		$result = curl_exec($curl_handler);
		if (!$result) {
			$this->errorMsg = curl_error($curl_handler);
			return false;
		}
		$jsonResponse = json_decode($result, true);
		
		if($jsonResponse["success"]==false) {
			$this->errorMsg = "getChallenge failed: ".$jsonResponse["error"]["message"]."<br>";
			return false;
		}

		$challengeToken = $jsonResponse["result"]["token"];

		return $challengeToken;
	}

	function login() {
		$curl_handler = curl_init();
		$token = $this->getChallenge();
		//create md5 string containing user accesskey from my preference page
		//and the challenge token obtained from get challenge result
		$generatedKey = md5($token.$this->userKey);
		
		$params = array("operation" => "login", "username" => $this->userName, "accessKey" => $generatedKey);
		$options = array(CURLOPT_URL => $this->endpointUrl, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => http_build_query($params));
		curl_setopt_array($curl_handler, ($this->defaults + $options));
		$result = curl_exec($curl_handler);
		if (!$result) {
			$this->errorMsg = curl_error($curl_handler);
			return false;
		}
		$jsonResponse = json_decode($result, true);
		if($jsonResponse["success"]==false) {
			$this->errorMsg = "Login failed: ".$jsonResponse["error"]["message"]."<br>";
			return false;
		}
		
		$sessionId = $jsonResponse["result"]["sessionName"];
		//save session id
		$this->token=$sessionId;
		
		return true;
	}
	
	function query($query) {
		$curl_handler = curl_init();
		$params = array("operation" => "query", "sessionName" => $this->token, "query" => $query);
		$options = array(CURLOPT_URL => $this->endpointUrl."?".http_build_query($params));
		curl_setopt_array($curl_handler, ($this->defaults + $options));
		
		$result = curl_exec($curl_handler);
		
		return $this->handleReturn($result, __FUNCTION__);
	}
	
	function describe($type) {
		$curl_handler = curl_init();
		$params = array("operation" => "describe", "sessionName" => $this->token, "elementType" => $type);
		$options = array(CURLOPT_URL => $this->endpointUrl."?".http_build_query($params));
		curl_setopt_array($curl_handler, ($this->defaults + $options));
		
		$result = curl_exec($curl_handler);
		
		return $this->handleReturn($result, __FUNCTION__);
	}
	
	function create($type, $element, $filepath = '') {
		$curl_handler = curl_init();
		$params = array("operation" => "create", "format" => "json", "sessionName" => $this->token, "elementType" => $type, "element" => json_encode($element));
		$options = array(CURLOPT_URL => $this->endpointUrl, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => http_build_query($params));
		if ($filepath != '') {
			$filename = pathinfo($filepath, PATHINFO_BASENAME);
			$size = filesize($filepath);
			$add_options = array(CURLOPT_HTTPHEADER => array("Content-Type: multipart/form-data"), CURLOPT_INFILESIZE => $size);
			if (!function_exists('curl_file_create')) {
				$add_params = array("filedata" => "@$filepath", "filename" => $filename);
			} else {
				$cfile = curl_file_create($filepath, '', $filename);
				$add_params = array('tmp_file' => $cfile);
			}
			
			$options += $add_options;
			// $this->defaults[CURLOPT_HEADER] = 1;
			$options[CURLOPT_POSTFIELDS] = $params + $add_params;
		}
		
		curl_setopt_array($curl_handler, ($this->defaults + $options));
		// $this->defaults[CURLOPT_HEADER] = 0;
		$result = curl_exec($curl_handler);
		
		return $this->handleReturn($result, __FUNCTION__);
	}
	
	function update($element) {
		$curl_handler = curl_init();
		$params = array("operation" => "update", "format" => "json", "sessionName" => $this->token, "element" => json_encode($element));
		$options = array(CURLOPT_URL => $this->endpointUrl, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => http_build_query($params));
		curl_setopt_array($curl_handler, ($this->defaults + $options));
		$result = curl_exec($curl_handler);
		
		return $this->handleReturn($result, __FUNCTION__);
	}
	
	function updateDocRel($docid, $relids, $preserve = true) {
		$curl_handler = curl_init();
		$params = array("operation" => "update_document_relations", "docid" => $docid, "sessionName" => $this->token, "relids" => $relids, "preserve" => var_export($preserve, true));
		$options = array(CURLOPT_URL => $this->endpointUrl, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => http_build_query($params));
		curl_setopt_array($curl_handler, ($this->defaults + $options));
		$result = curl_exec($curl_handler);
		
		return $this->handleReturn($result, __FUNCTION__);
	}
	
	function retrieve($id) {
		$curl_handler = curl_init();
		$params = array("operation" => "retrieve", "id" => $id, "sessionName" => $this->token);
		$options = array(CURLOPT_URL => $this->endpointUrl."?".http_build_query($params));
		curl_setopt_array($curl_handler, ($this->defaults + $options));
		$result = curl_exec($curl_handler);
		
		return $this->handleReturn($result, __FUNCTION__);
	}
	
	private function handleReturn($result, $name) {
		if (!$result) {
			$this->errorMsg = curl_error($curl_handler);
			return false;
		}
		$jsonResponse = json_decode($result, true);
		
		if (!$jsonResponse) {
			$this->errorMsg = "$name failed: ".$result."<br>";
			return false;
		}
		if($jsonResponse["success"]==false) {
			$this->errorMsg = "$name failed: ".$jsonResponse["error"]["message"]."<br>";
			return false;
		}
		return $jsonResponse["result"];
	}
}
?>