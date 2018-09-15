<?php
class DashboardInit {

	public $panelItems;
	public $settingsArray = array();
	public $language;
	public $version = "2.6";
	public $nversion = "260";
	public $lowAndVersion = "1.1";
	public $nLowAndVersion = "110";
	public $teacherClasses = array();
	public $isRTL;
	public $languageUniversal;
	public $selectAcYear;
	public $defTheme;
	public $baseURL;

	public function __construct(){
		$this->panelItems = array(
									"dashboard"=>array("title"=>"dashboard","icon"=>"fa fa-dashboard","url"=> URL::to('#/'),"permissions"=>array('admin','teacher','student','parent') ),
									"staticContent"=>array("title"=>"staticPages","icon"=>"fa fa-file-text","activated"=>"staticpagesAct","cusPerm"=>"staticPages","permissions"=>array('admin','teacher','student','parent'),
													"children"=>array(
															"controlStatic"=>array("title"=>"controlPages","url"=>URL::to('#/static'),"icon"=>"fa fa-cog","permissions"=>array('admin') )
														)
									),

									"messages"=>array("title"=>"Messages","url"=>URL::to('#/messages'),"icon"=>"fa fa-envelope","activated"=>"messagesAct","permissions"=>array('admin','parent') ),
									"calender"=>array("title"=>"Calender","url"=>URL::to('#/calender'),"icon"=>"fa fa-calendar","activated"=>"calendarAct","permissions"=>array('admin','teacher','student','parent') ),
									"classSchedule"=>array("title"=>"classSch","url"=>URL::to('#/classschedule'),"icon"=>"fa fa-list","activated"=>"classSchAct","cusPerm"=>"classSch","permissions"=>array('admin','teacher','student','parent') ),
									"attendance"=>array("title"=>"Attendance","icon"=>"fa fa-bar-chart","activated"=>"attendanceAct","cusPerm"=>"Attendance","permissions"=>array('admin','teacher'),
														"children"=>array(
															"controlAttendance"=>array("title"=>"Attendance","url"=>URL::to('#/attendance'),"icon"=>"fa fa-check","permissions"=>array('admin','teacher') ),
															"statsAttendance"=>array("title"=>"attendanceStats","url"=>URL::to('#/attendanceStats'),"icon"=>"fa fa-bar-chart","permissions"=>array('admin','teacher') ),
														)
									),
									"vacation"=>array("title"=>"Vacation","url"=>URL::to('#/vacation'),"icon"=>"fa fa-coffee","activated"=>"vacationAct","permissions"=>array('teacher','student') ),
									"myAttendance"=>array("title"=>"Attendance","url"=>URL::to('#/attendanceStats'),"icon"=>"fa fa-bar-chart","activated"=>"attendanceAct","permissions"=>array('student','parent') ),
									"staffAttendance"=>array("title"=>"staffAttendance","url"=>URL::to('#/staffAttendance'),"icon"=>"fa fa-check","activated"=>"staffAttendanceAct","cusPerm"=>"staffAttendance","permissions"=>array('admin') ),

									"hostel"=>array("title"=>"HostelManage","icon"=>"fa fa-bar-chart","activated"=>"hostelAct","cusPerm"=>"HostelManage","permissions"=>array('admin'),
														"children"=>array(
															"controlHostel"=>array("title"=>"Hostel","url"=>URL::to('#/hostel'),"icon"=>"fa fa-check","permissions"=>array('admin') ),
															"hostelCat"=>array("title"=>"HostelCat","url"=>URL::to('#/hostelCat'),"icon"=>"fa fa-bar-chart","permissions"=>array('admin') ),
														)
									),

									"library"=>array("title"=>"Library","url"=>URL::to('#/library'),"icon"=>"fa fa-folder-open","activated"=>"bookslibraryAct","cusPerm"=>"Library","permissions"=>array('admin','teacher','student','parent') ),
									"media"=>array("title"=>"mediaCenter","url"=>URL::to('#/media'),"icon"=>"fa fa-video-camera","activated"=>"mediaAct","cusPerm"=>"mediaCenter","permissions"=>array('admin','teacher','student','parent') ),

									"teachers"=>array("title"=>"teachers","url"=>URL::to('#/teachers'),"icon"=>"fa fa-suitcase","cusPerm"=>"teachers","permissions"=>array('admin') ),
									"students"=>array("title"=>"students","url"=>URL::to('#/students'),"icon"=>"fa fa-users","cusPerm"=>"students","permissions"=>array('admin','teacher','parent') ),
									"parents"=>array("title"=>"parents","url"=>URL::to('#/parents'),"icon"=>"fa fa-user","cusPerm"=>"parents","permissions"=>array('admin') ),

									"studentsMarksheet"=>array("title"=>"Marksheet","url"=>URL::to('#/students/marksheet'),"icon"=>"fa fa-table","permissions"=>array('student') ),

									"gradelevels"=>array("title"=>"gradeLevels","url"=>URL::to('#/gradeLevels'),"icon"=>"fa fa-check-square-o","cusPerm"=>"gradeLevels","permissions"=>array('admin') ),
									"materials"=>array("title"=>"studyMaterial","url"=>URL::to('#/materials'),"icon"=>"fa fa-book","activated"=>"materialsAct","cusPerm"=>"studyMaterial","permissions"=>array('admin','teacher','student') ),
									"assignments"=>array("title"=>"Assignments","url"=>URL::to('#/assignments'),"icon"=>"fa fa-file-pdf-o","activated"=>"assignmentsAct","cusPerm"=>"Assignments","permissions"=>array('admin','teacher','student','parent') ),
									"examslist"=>array("title"=>"examsList","url"=>URL::to('#/examsList'),"icon"=>"fa fa-graduation-cap","cusPerm"=>"examsList","permissions"=>array('admin','teacher','student','parent') ),
									"onlineexams"=>array("title"=>"onlineExams","url"=>URL::to('#/onlineExams'),"icon"=>"fa fa-graduation-cap","activated"=>"onlineexamsAct","cusPerm"=>"onlineExams","permissions"=>array('admin','teacher','student') ),

									"newsboard"=>array("title"=>"newsboard","url"=>URL::to('#/newsboard'),"icon"=>"fa fa-bullhorn","activated"=>"newsboardAct","cusPerm"=>"newsboard","permissions"=>array('admin','teacher','student','parent') ),
									"events"=>array("title"=>"events","url"=>URL::to('#/events'),"icon"=>"fa fa-clock-o","activated"=>"eventsAct","cusPerm"=>"events","permissions"=>array('admin','teacher','student','parent') ),

									"accounting"=>array("title"=>"accounting","icon"=>"fa fa-bar-chart","activated"=>"paymentsAct","cusPerm"=>"accounting","permissions"=>array('admin','student','parent'),
														"children"=>array(
															"controlFeeTypes"=>array("title"=>"FeeTypes","url"=>URL::to('#/feeType'),"icon"=>"fa fa-money","activated"=>"paymentsAct","permissions"=>array('admin') ),
															"controlFeeAllocation"=>array("title"=>"FeeAllocation","url"=>URL::to('#/feeAllocation'),"icon"=>"fa fa-money","activated"=>"paymentsAct","permissions"=>array('admin') ),
															"controlPayments"=>array("title"=>"Payments","url"=>URL::to('#/payments'),"icon"=>"fa fa-money","activated"=>"paymentsAct","permissions"=>array('admin','student','parent') ),
															"expenses"=>array("title"=>"Expenses","url"=>URL::to('#/expenses'),"icon"=>"fa fa-bar-chart","permissions"=>array('admin') ),
														)
									),

									"transportations"=>array("title"=>"Transportation","url"=>URL::to('#/transports'),"icon"=>"fa fa-bus","activated"=>"transportAct","cusPerm"=>"Transportation","permissions"=>array('admin','teacher','student','parent') ),

									"classes"=>array("title"=>"classes","url"=>URL::to('#/classes'),"icon"=>"fa fa-sitemap","activated"=>"eventsAct","cusPerm"=>"classes","permissions"=>array('admin') ),

									//"classes"=>array("title"=>"classes","icon"=>"fa fa-sitemap","cusPerm"=>"classes","permissions"=>array('admin'),
									//					"children"=>array(
									//						"classes"=>array("title"=>"classes","url"=>URL::to('#/classes'),"icon"=>"fa fa-sitemap","permissions"=>array('admin') ),
									//						"sections"=>array("title"=>"sections","url"=>URL::to('#/sections'),"icon"=>"fa fa-sitemap","permissions"=>array('admin') ),
									//					)
									//),

									"subjects"=>array("title"=>"Subjects","url"=>URL::to('#/subjects'),"icon"=>"fa fa-book","cusPerm"=>"Subjects","permissions"=>array('admin') ),
									"reports"=>array("title"=>"Reports","url"=>URL::to('#/reports'),"icon"=>"fa fa-pie-chart","activated"=>"reportsAct","cusPerm"=>"Reports","permissions"=>array('admin') ),

									"mailsms"=>array("title"=>"mailsms","url"=>URL::to('#/mailsms'),"icon"=>"fa fa-send","cusPerm"=>"mailsms","permissions"=>array('admin','teacher') ),
									"adminTasks"=>array("title"=>"adminTasks","icon"=>"fa fa-cog","permissions"=>array('admin'),
																				"children"=>array(
																						"academicyear"=>array("title"=>"academicyears","url"=>URL::to('#/academicYear'),"icon"=>"fa fa-calendar-check-o","cusPerm"=>"academicyears","permissions"=>array('admin') ),
																						"promotion"=>array("title"=>"Promotion","url"=>URL::to('#/promotion'),"icon"=>"fa fa-arrow-up","cusPerm"=>"Promotion","permissions"=>array('admin') ),
																						//"mobNotif"=>array("title"=>"Mobile Notifications","url"=>URL::to('#/mobileNotif'),"icon"=>"fa fa-send","cusPerm"=>"mobileNotif","permissions"=>array('admin') ),
																						//"mailsmsTemplates"=>array("title"=>"mailsmsTemplates","url"=>URL::to('#/mailsmsTemplates'),"icon"=>"fa fa-envelope-o","cusPerm"=>"mailsmsTemplates","permissions"=>array('admin') ),
																						"polls"=>array("title"=>"Polls","url"=>URL::to('#/polls'),"icon"=>"fa fa-tasks","activated"=>"pollsAct","cusPerm"=>"Polls","permissions"=>array('admin') ),
																						//"dormitories"=>array("title"=>"Dormitories","url"=>URL::to('#/dormitories'),"icon"=>"fa fa-building-o","cusPerm"=>"Dormitories","permissions"=>array('admin') ),
																						//"siteSettings" => array("title"=>"generalSettings","url"=>URL::to('#/settings'),"icon"=>"fa fa-cog","cusPerm"=>"generalSettings","permissions"=>array('admin') ),
																						//"languages" => array("title"=>"Languages","url"=>URL::to('#/languages'),"icon"=>"fa fa-font","cusPerm"=>"Languages","permissions"=>array('admin') ),
																						//"admins"=>array("title"=>"Administrators","url"=>URL::to('#/admins'),"icon"=>"fa fa-gears","cusPerm"=>"Administrators","permissions"=>array('admin') ),
																						"terms"=>array("title"=>"schoolTerms","url"=>URL::to('#/terms'),"icon"=>"fa fa-file-text-o","cusPerm"=>"generalSettings","permissions"=>array('admin') ),
																					)
																				)
					);

		$settings = settings::get();
        $items = array();
        foreach($settings as $setting){
            $items[$setting->fieldName] = $setting->fieldValue;
        }
		$this->settingsArray = $items;

		$staticPages = static_pages::where('pageActive','1')->get();
		foreach ($staticPages as $pages) {
			$this->panelItems['staticContent']['children'][md5(uniqid())] = array("title"=>$pages->pageTitle,"url"=>URL::to('#/static')."/".$pages->id,"icon"=>"fa fa-file-text","permissions"=>array('admin','teacher','student','parent') );
		}

		if($this->settingsArray['allowTeachersMailSMS'] == "none" AND !Auth::guest() AND \Auth::user()->role == "teacher"){
			unset($this->panelItems['mailsms']);
		}

		//Languages
		$defLang = $defLang_ = $this->settingsArray['languageDef'];
		if(isset($this->settingsArray['languageAllow']) AND $this->settingsArray['languageAllow'] == "1" AND !Auth::guest() AND \Auth::user()->defLang != 0){
			$defLang = \Auth::user()->defLang;
		}

		//Theme
		$this->defTheme = $this->settingsArray['layoutColor'];
		if(isset($this->settingsArray['layoutColorUserChange']) AND $this->settingsArray['layoutColorUserChange'] == "1" AND !Auth::guest() AND \Auth::user()->defTheme != ""){
			$this->defTheme = \Auth::user()->defTheme;
		}

		$language = languages::whereIn('id',array($defLang,1))->get();
		if(count($language) == 0){
			$language = languages::whereIn('id',array($defLang_,1))->get();
		}

		foreach ($language as $value) {
			if($value->id == 1){
				$this->language = json_decode($value->languagePhrases,true);
				$this->languageUniversal = "en";
			}else{
				$this->languageUniversal = $value->languageUniversal;
				$this->isRTL = $value->isRTL;
				$phrases = json_decode($value->languagePhrases,true);
				while (list($key, $value) = each($phrases)) {
					$this->language[$key] = $value;
				}
			}
		}

		//Selected academicYear
		if (Session::has('selectAcYear')){
			$this->selectAcYear = Session::get('selectAcYear');
		}else{
			$currentAcademicYear = academic_year::where('isDefault','1')->first();
			$this->selectAcYear = $currentAcademicYear->id;
			Session::put('selectAcYear', $this->selectAcYear);
		}

		//Process Scheduled Payments
		$fee_allocation = fee_allocation::where('isProcessed','0')->whereIn('allocationWhen',array('reccuring','date'))->where('allocationDate','<=',time())->where('allocationDate','!=','0');
		if( $fee_allocation->count() > 0 ){
			$fee_allocation = $fee_allocation->get()->toArray();
			$feeTypesArray = array();
			$feeTypes = fee_type::get();
			foreach($feeTypes as $type){
				$feeTypesArray[$type->id] = $type->feeTitle;
			}

			while (list(, $value) = each($fee_allocation)) {
				if($value['allocationType'] == "student"){
					$user = User::where('id',$value['allocationId'])->first();

					$paymentDescription = array();
					$paymentAmount = 0;
					$allocationValues = json_decode($value['allocationValues'],true);
					while (list($key_, $value_) = each($allocationValues)) {
						if(isset($feeTypesArray[$key_])){
							$paymentDescription[] = $feeTypesArray[$key_];
							$paymentAmount += $value_;
						}
					}

					$payments = new payments();
					$payments->paymentTitle = $value['allocationTitle'];
					$payments->paymentDescription = implode(", ",$paymentDescription);
					$payments->paymentStudent = $user->id;
					$payments->paymentAmount = $paymentAmount;
					$payments->paymentStatus = "0";
					$payments->paymentDate = time();
					$payments->paymentUniqid = uniqid();
					$payments->save();

				}
				if($value['allocationType'] == "class"){
					$users = User::where('studentClass',$value['allocationId'])->where('activated','1')->get();
					foreach ($users as $user) {

						$paymentDescription = array();
						$paymentAmount = 0;
						$allocationValues = json_decode($value['allocationValues'],true);
						while (list($key_, $value_) = each($allocationValues)) {
							if(isset($feeTypesArray[$key_])){
								$paymentDescription[] = $feeTypesArray[$key_];
								$paymentAmount += $value_;
							}
						}

						$payments = new payments();
						$payments->paymentTitle = $value['allocationTitle'];
						$payments->paymentDescription = implode(", ",$paymentDescription);
						$payments->paymentStudent = $user->id;
						$payments->paymentAmount = $paymentAmount;
						$payments->paymentStatus = "0";
						$payments->paymentDate = time();
						$payments->paymentUniqid = uniqid();
						$payments->save();

					}
				}
				if($value['allocationWhen'] == "reccuring"){
					$nextallocationDate = 0;
					if($value['allocationRec'] != ""){
						$allocationRec = explode(".",$value['allocationRec']);
						$nextallocationDate = intval($value['allocationDate']);
						if($allocationRec[1] == "d"){
							$nextallocationDate =  strtotime('+'.$allocationRec[0].' day',$nextallocationDate);
						}
						if($allocationRec[1] == "m"){
							$nextallocationDate = strtotime('+'.$allocationRec[0].' month',$nextallocationDate);
						}
						if($allocationRec[1] == "y"){
							$nextallocationDate = strtotime('+'.$allocationRec[0].' year',$nextallocationDate);
						}
					}
					fee_allocation::where('id',$value['id'])->update( array('allocationDate'=>$nextallocationDate) );
				}else{
					fee_allocation::where('id',$value['id'])->update( array('isProcessed'=>'1') );
				}
			}
		}

		$this->baseURL = URL::to('/');
	}

	public function mobNotifyUser($userType,$userIds,$notifData){
		$mobNotifications = new \mob_notifications();

		if($userType == "users"){
			$mobNotifications->notifTo = "users";
			if(!is_array($userIds)){
				$userIds = array($userIds);
			}
			$userIdsList = array();
			while (list(, $value) = each($userIds)) {
				$userIdsList[] = array('id'=>$value);
			}
			$mobNotifications->notifToIds = json_encode($userIdsList);
		}elseif($userType == "class"){
			$mobNotifications->notifTo = "students";
			$mobNotifications->notifToIds = $userIds;
		}elseif($userType == "role"){
			$mobNotifications->notifTo = $userIds;
			$mobNotifications->notifToIds = "";
		}

		$mobNotifications->notifData = htmlspecialchars($notifData,ENT_QUOTES);
		$mobNotifications->notifDate = time();
		$mobNotifications->notifSender = "Automated";
		$mobNotifications->save();
	}

	public static function globalXssClean()
	{
	  $sanitized = static::arrayStripTags(Input::get());
	  Input::merge($sanitized);
	}

	public static function arrayStripTags($array)
	{
	    $result = array();

	    foreach ($array as $key => $value) {
	        // Don't allow tags on key either, maybe useful for dynamic forms.
	        $key = strip_tags($key);

	        // If the value is an array, we will just recurse back into the
	        // function to keep stripping the tags out of the array,
	        // otherwise we will set the stripped value.
	        if (is_array($value)) {
	            $result[$key] = static::arrayStripTags($value);
	        } else {
	            // I am using strip_tags(), you may use htmlentities(),
	            // also I am doing trim() here, you may remove it, if you wish.
	            $result[$key] = trim(strip_tags($value));
	        }
	    }

	    return $result;
	}

	public function viewop($layout,$view,&$data,$div=""){
		if(Request::ajax()){
			if($div != ""){
				echo "DBArea('".htmlspecialchars($this->sanitize_output( View::make($view, $data) ),ENT_QUOTES)."','".$div."');";
			}else{
				echo "DBArea('".htmlspecialchars($this->sanitize_output( View::make($view, $data) ),ENT_QUOTES)."');";
			}
			exit;
		}else{
			$data['content'] = View::make($view, $data);
			$layout->with($data);
		}
	}

	function sanitize_output($buffer) {
		$search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','/\s\s+/');
		$replace = array('>','<',' ',' ');
		$buffer = preg_replace($search, $replace, $buffer);

		return $buffer;
	}

	public static function breadcrumb($breadcrumb){
		echo "<ol class='breadcrumb'>
					<li><a class='aj' href='".URL::to('/dashboard')."'><i class='fa fa-dashboard'></i> Home</a></li>";
		$i = 0;
		while (list($key, $value) = each($breadcrumb)) {
			$i++;
			if($i == count($breadcrumb)){
				echo "<li class='active'>".$key."</li>";
			}else{
				echo "<li class='bcItem'><a class='aj' href='$value' title='$key'>$key</a></li>";
			}
		}
		echo "</ol>";
	}

	public function truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = false) {
		if ($considerHtml) {
			// if the plain text is shorter than the maximum length, return the whole text
			if (strlen ( preg_replace ( '/<.*?>/', '', $text ) ) <= $length) {
				return $text;
			}
			// splits all html-tags to scanable lines
			preg_match_all ( '/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER );
			$total_length = strlen ( $ending );
			$open_tags = array ( );
			$truncate = '';
			foreach ( $lines as $line_matchings ) {
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if (! empty ( $line_matchings [1] )) {
					// if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
					if (preg_match ( '/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings [1] )) {
						// do nothing
					// if tag is a closing tag (f.e. </b>)
					} else if (preg_match ( '/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings [1], $tag_matchings )) {
						// delete tag from $open_tags list
						$pos = array_search ( $tag_matchings [1], $open_tags );
						if ($pos !== false) {
							unset ( $open_tags [$pos] );
						}
						// if tag is an opening tag (f.e. <b>)
					} else if (preg_match ( '/^<\s*([^\s>!]+).*?>$/s', $line_matchings [1], $tag_matchings )) {
						// add tag to the beginning of $open_tags list
						array_unshift ( $open_tags, strtolower ( $tag_matchings [1] ) );
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings [1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen ( preg_replace ( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings [2] ) );
				if ($total_length + $content_length > $length) {
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if (preg_match_all ( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings [2], $entities, PREG_OFFSET_CAPTURE )) {
						// calculate the real length of all entities in the legal range
						foreach ( $entities [0] as $entity ) {
							if ($entity [1] + 1 - $entities_length <= $left) {
								$left --;
								$entities_length += strlen ( $entity [0] );
							} else {
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr ( $line_matchings [2], 0, $left + $entities_length );
					// maximum lenght is reached, so get off the loop
					break;
				} else {
					$truncate .= $line_matchings [2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if ($total_length >= $length) {
					break;
				}
			}
		} else {
			if (strlen ( $text ) <= $length) {
				return $text;
			} else {
				$truncate = substr ( $text, 0, $length - strlen ( $ending ) );
			}
		}
		// if the words shouldn't be cut in the middle...
		if (! $exact) {
			// ...search the last occurance of a space...
			$spacepos = strrpos ( $truncate, ' ' );
			if (isset ( $spacepos )) {
				// ...and cut the text in this position
				$truncate = substr ( $truncate, 0, $spacepos );
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		if ($considerHtml) {
			// close all unclosed html-tags
			foreach ( $open_tags as $tag ) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}

	//Work with Date & Time
	public function ttime($time,$format='d-m-Y H:i a',$timeZone = "") {
		if($timeZone == ""){
			$timeZone = \Auth::user()->timezone;
		}
		$dd = DateTime::createFromFormat($format, $time, new DateTimeZone($timeZone));
		$dd->setTimeZone(new DateTimeZone('Europe/London'));
		return $dd->getTimestamp();
	}

	public function tdate($format,$timestamp = "",$timeZone = ""){
		if($timestamp == ""){
			$timestamp = time();
		}
		if($timeZone == ""){
			$timeZone = \Auth::user()->timezone;
		}
		$date = new DateTime("@".$timestamp);
		$date->setTimezone(new DateTimeZone($timeZone));
		return $date->format($format);
	}

	public function apiOutput($success,$title=null,$messages = null,$data=null){
		$returnArray = array("status"=>"");

		if($title != null){
			$returnArray['title'] = $title;
		}

		if($messages != null){
			$returnArray['message'] = $messages;
		}

		if($data != null){
			$returnArray['data'] = $data;
		}

		if($success){
			$returnArray['status'] = 'success';
			return $returnArray;
		}else{
			$returnArray['status'] = 'failed';
			return $returnArray;
		}
	}

	function rangeDates($StartDate, $EndDate,$format = ""){
		if($format == ""){
			$format = $this->settingsArray['dateformat'];
		}
		$toReturn = array();
		$toReturn['start'] = $this->dateToUnix($StartDate,$format)-100;
		$toReturn['end'] = $this->dateToUnix($EndDate,$format)+100;
		return $toReturn;
	}

	function dateToUnix($date,$format=""){
		if($format == ""){
			$format = $this->settingsArray['dateformat'];
		}
		if($format == ""){
			$format = "d/m/Y";
		}
		$d = DateTime::createFromFormat($format, $date);
		if(!is_bool($d)){
			$d->setTime(0,0,0);
			return abs($d->getTimestamp());
		}
	}

	function unixToDate($timestamp,$format=""){
		if($format == ""){
			$format = $this->settingsArray['dateformat'];
		}
		if($format == ""){
			$format = "d/m/Y";
		}
		$currentTime = DateTime::createFromFormat( 'U', $timestamp);
		return $currentTime->format( $format );
	}
}
