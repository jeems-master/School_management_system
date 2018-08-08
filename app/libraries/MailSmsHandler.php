<?php
class MailSmsHandler {

	var $settings ;
	var $title;

	public function mail($to,$title,$message,$fullName=""){
		$panelInit = new \DashboardInit();
		$settings = $panelInit->settingsArray;
		$this->title = $settings['siteTitle'];

		$this->settings = json_decode($settings['mailProvider'],true);
		if($this->settings['mailProvider'] == "" || !isset($this->settings['mailProvider'])){
			exit;
		}

		if($this->settings['mailProvider'] == "mail"){
			$header = "From: ".$settings['systemEmail']."\r\n";
			$header.= "MIME-Version: 1.0\r\n";
			$header.= "Content-Type: text/html; charset=utf-8\r\n";
			$header.= "X-Priority: 1\r\n";

			mail($to, $title, $message, $header);
		}elseif($this->settings['mailProvider'] == "phpmailer"){
			$mail = new PHPMailer(true);
			try {
				$mail->From = $settings['systemEmail'];
				$mail->FromName = $settings['siteTitle'];
				$mail->addAddress($to, $fullName);
				$mail->Subject = $title;
				$mail->Body    = $message;
				$mail->IsHTML(true);
				$mail->send();
			} catch (phpmailerException $e) {
				Log::info($e->errorMessage());
			} catch (Exception $e) {
				Log::info($e->errorMessage());
			}
		}elseif($this->settings['mailProvider'] == "smtp"){
			$mail             = new PHPMailer(true);
			try {
				$mail->IsSMTP();
				$mail->SMTPAuth   = true;
				$mail->Host       = $this->settings['smtpHost'];
				$mail->Port       = $this->settings['smtpPort'];
				$mail->Username   = $this->settings['smtpUserName'];
				$mail->Password   = $this->settings['smtpPassWord'];

				if(isset($this->settings['smtpTLS']) && $this->settings['smtpTLS'] != ""){
					if($this->settings['smtpTLS'] == "TLS"){
						$mail->SMTPSecure = 'tls';
					}elseif($this->settings['smtpTLS'] == "SSL"){
						$mail->SMTPSecure = 'ssl';
					}
				}

				$mail->SetFrom($settings['systemEmail'], $settings['siteTitle']);
				$mail->Subject = $title;
				$mail->Body    = $message;
				$mail->addAddress($to, $fullName);
				$mail->IsHTML(true);
				$mail->Send();
			} catch (phpmailerException $e) {
				Log::info($e->errorMessage());
			} catch (Exception $e) {
				Log::info($e->errorMessage());
			}
		}elseif($this->settings['mailProvider'] == "ses"){
			$amazonSES = new SimpleEmailService($this->settings['AmazonSESAccessKey'],$this->settings['AmazonSESSecretKey']);
			$m = new SimpleEmailServiceMessage();
			$m->addTo($to);
			$m->setFrom($this->settings['AmazonSESVerifiedSender']);

			$m->setSubject($title);
			$m->setMessageFromString(strip_tags($message),$message);
			return $amazonSES->sendEmail($m);
		}
	}

	public function sms($to,$message){
		$panelInit = new \DashboardInit();
		$settings = $panelInit->settingsArray;
		$this->title = $settings['siteTitle'];

		$this->settings = json_decode($settings['smsProvider'],true);

		if($this->settings['smsProvider'] == "" || !isset($this->settings['smsProvider'])){
			exit;
		}

		$to = $this->prepareNumber($to);

		if($this->settings['smsProvider'] == "nexmo"){
			$this->nexmo($to,$message);
		}elseif($this->settings['smsProvider'] == "twilio"){
			$this->twilio($to,$message);
		}elseif($this->settings['smsProvider'] == "hoiio"){
			$this->hoiio($to,$message);
		}elseif($this->settings['smsProvider'] == "clickatell"){
			$this->clickatell($to,$message);
		}elseif($this->settings['smsProvider'] == "intellisms"){
			$this->intellisms($to,$message);
		}elseif($this->settings['smsProvider'] == "bulksms"){
			$this->bulksms($to,$message);
		}elseif($this->settings['smsProvider'] == "concepto"){
			$this->concepto($to,$message);
		}elseif($this->settings['smsProvider'] == "msg91"){
			$this->msg91($to,$message);
		}elseif($this->settings['smsProvider'] == "custom"){
			$this->custom($to,$message);
		}
	}

	public function nexmo($to,$message){
		if($this->settings['nexmoApiKey'] == "" || $this->settings['nexmoApiSecret'] == "") return;
		$nexmo_sms = new NexmoMessage($this->settings['nexmoApiKey'], $this->settings['nexmoApiSecret']);
		$to = $this->prepareNumberFormat($to,"c");
		$info = $nexmo_sms->sendText( $to, $this->title,$message );
		if(Config::get('app.debug') == true){
			Log::info($info);
		}
	}

	public function twilio($to,$message){
		if($this->settings['twilioSID'] == "" || $this->settings['twilioToken'] == "" || $this->settings['twilioFN'] == "") return;
		$client = new Services_Twilio($this->settings['twilioSID'], $this->settings['twilioToken']);
		$to = $this->prepareNumberFormat($to,"c");
		$message = $client->account->messages->create(array("From" => $this->settings['twilioFN'],"To" => $to,"Body" => $message));
		if(Config::get('app.debug') == true){
			Log::info($message);
		}
	}

	public function hoiio($to,$message){
		if($this->settings['hoiioAppId'] == "" || $this->settings['hoiioAccessToken'] == "") return;
		$client = new HoiioService($this->settings['hoiioAppId'], $this->settings['hoiioAccessToken']);
		$data = $client->sms(str_replace(' ', '', $to),$message);
		if(Config::get('app.debug') == true){
			Log::info($data);
		}
	}

	public function clickatell($to,$message){
		if($this->settings['clickatellUserName'] == "" || $this->settings['clickatellPassword'] == "" || $this->settings['clickatellApiKey'] == "") return;
	    $baseurl ="http://api.clickatell.com";
	    $url = "$baseurl/http/auth?user=".$this->settings['clickatellUserName']."&password=".$this->settings['clickatellPassword']."&api_id=".$this->settings['clickatellApiKey'];
	    $ret = file($url);
	    $sess = explode(":",$ret[0]);
	    if ($sess[0] == "OK") {
	 		$sess_id = trim($sess[1]);
			$to = $this->prepareNumberFormat($to,"00");
	        $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=".$to."&text=".urlencode($message);

	        $ret = file($url);
	        $send = explode(":",$ret[0]);
	    }
		if(Config::get('app.debug') == true){
			Log::info($sess);
			if(isset($ret)){
				Log::info($ret);
			}
		}
	}

	public function intellisms($to,$message){
		if($this->settings['intellismsUserName'] == "" || $this->settings['intellismsPassword'] == "" || $this->settings['intellismsSenderNumber'] == "") return;
		$objIntelliSMS = new IntelliSMS();
		$objIntelliSMS->Username = $this->settings['intellismsUserName'];
		$objIntelliSMS->Password = $this->settings['intellismsPassword'];
		$to = $this->prepareNumberFormat($to,"c");
		$sess = $objIntelliSMS->SendMessage ( $to,$message, $this->settings['intellismsSenderNumber'] );
		if(Config::get('app.debug') == true){
			Log::info($sess);
		}
	}

	public function bulksms($to,$message){
		if($this->settings['bulksmsUserName'] == "" || $this->settings['bulksmsPassword'] == "" ) return;
		$to = $this->prepareNumberFormat($to,"00");
		$url = 'http://bulksms.vsms.net/eapi/submission/send_sms/2/2.0';
		$data = 'username='.$this->settings['bulksmsUserName'].'&password='.$this->settings['bulksmsPassword'].'&message='.urlencode($message).'&msisdn='.urlencode( $to );

		$sess = $this->bulksms_post_request($url, $data);
		if(Config::get('app.debug') == true){
			Log::info($sess);
		}
	}

	public function concepto($to,$message){
		if($this->settings['conceptoSubdomain'] == "" || $this->settings['conceptoUserName'] == "" || $this->settings['conceptoSenderId'] == "") return;
		$to = $this->prepareNumberFormat($to,"00");
		$sess = file_get_contents("http://".$this->settings['conceptoSubdomain'].".liveair.in/httpapi/httpapi?token=".$this->settings['conceptoUserName']."&sender=".$this->settings['conceptoSenderId']."&number=".$to."&route=2&type=1&sms=".urlencode($message));
		if(Config::get('app.debug') == true){
			Log::info($sess);
		}
	}

	public function msg91($to,$message){
		if($this->settings['msg91Authkey'] == "" || $this->settings['msg91SenderId'] == "") return;
		$to = $this->prepareNumberFormat($to,"c");
		$sess = file_get_contents("http://api.msg91.com/api/sendhttp.php?authkey=".$this->settings['msg91Authkey']."&mobiles=".$to."&message=".urlencode($message)."&sender=".$this->settings['msg91SenderId']."&route=4");
		if(Config::get('app.debug') == true){
			Log::info($sess);
		}
	}

	public function custom($to,$message){
		if(!isset($this->settings['customHTTPURL']) || !isset($this->settings['customHTTPParams']) || !isset($this->settings['customHTTPType']) || !isset($this->settings['customHTTPToFormat']) ) return;
		if($this->settings['customHTTPURL'] == "" || $this->settings['customHTTPParams'] == "" || $this->settings['customHTTPType'] == "" || $this->settings['customHTTPToFormat'] == "") return;

		if($this->settings['customHTTPToFormat'] == "00"){
			$to = $this->prepareNumberFormat($to,"00");
		}elseif($this->settings['customHTTPToFormat'] == "+"){
			$to = $to;
		}else{
			$to = $this->prepareNumberFormat($to,"c");
		}

		$this->settings['customHTTPParams'] = str_replace("{to}",trim($to),$this->settings['customHTTPParams']);
		$this->settings['customHTTPParams'] = str_replace("{message}",urlencode(trim($message)),$this->settings['customHTTPParams']);

		if($this->settings['customHTTPType'] == "get"){
			$smsrequest = $this->settings['customHTTPURL']."?".$this->settings['customHTTPParams'];

			$server_output = file_get_contents($smsrequest);
			if(Config::get('app.debug') == true){
				Log::info($server_output);
			}

		}else{

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,$this->settings['customHTTPURL']);
			if($this->settings['customHTTPType'] == "post"){
				curl_setopt($ch, CURLOPT_POST, 1);
			}

			curl_setopt($ch, CURLOPT_POSTFIELDS,$this->settings['customHTTPParams']);

			// in real life you should use something like:
			// curl_setopt($ch, CURLOPT_POSTFIELDS,
			//          http_build_query(array('postvar1' => 'value1')));

			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);
			if(Config::get('app.debug') == true){
				Log::info($server_output);
			}

			curl_close ($ch);
		}
	}

	public function bulksms_post_request($url, $data, $optional_headers = 'Content-type:application/x-www-form-urlencoded') {
		$params = array('http'      => array(
			'method'       => 'POST',
			'content'      => $data,
			));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}

		$ctx = stream_context_create($params);


		$response = @file_get_contents($url, false, $ctx);
		if ($response === false) {
			print "Problem reading data from $url, No status returned\n";
		}

		return $response;
	}

	public function prepareNumber($to){
		$to = str_replace(" ","",$to);
		$to = str_replace("-","",$to);
		return $to;
	}

	public function prepareNumberFormat($to,$plusStyle="00"){
		if($plusStyle == "00"){
			return str_replace("+","00",$to);
		}elseif($plusStyle == "c"){
			return str_replace("+","",$to);
		}
	}

}
?>
