<?php

class upgradeController extends \BaseController {

	var $data = array();
	var $panelInit ;

	public function __construct(){

	}

	public function index($method = "main")
	{
		try{
			if(!Schema::hasTable('settings')){
				return Redirect::to('/install');
			}

			if(Schema::hasTable('settings')){
				$testInstalled = settings::where('fieldName','thisVersion')->get();
				if($testInstalled->count() > 0){
					$testInstalled = $testInstalled->first();
					if($testInstalled->fieldValue >= 2.6){
						echo "Already upgraded or at higher version";
						exit;
					}
				}
			}
		}catch(Exception $e){  }

		$this->data['currStep'] = "welcome";
		return View::make('upgrade', $this->data);
	}

	public function proceed()
	{
		if(Input::get('nextStep') == "1"){
			if (filter_var(Input::get('email'), FILTER_VALIDATE_EMAIL)) {
				if (!Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password'),'activated'=>1,'role'=>'admin')))
				{
					$loginError = false;
					$this->data['loginError'] = "loginError";
				}
			}else{
				if (!Auth::attempt(array('username' => Input::get('email'), 'password' => Input::get('password'),'activated'=>1,'role'=>'admin')))
				{
					$loginError = false;
					$this->data['loginError'] = "loginError";
				}
			}
			if(!isset($loginError)) {
				file_put_contents('app/storage/meta/lc',Input::get('cpc'));
				if($this->sbApi() == "err"){
					@unlink('app/storage/meta/lc');
					$this->data['installErrors'][] = "Código de ativação inválido";
					$loginError = false;
					$this->data['loginError'] = "loginError";
				}
			}
			$this->data['currStep'] = "welcome";
			if(!isset($loginError)) {
				$this->data['currStep'] = "1";
				$this->data['nextStep'] = "2";

				$testData = uniqid();

				@file_put_contents("uploads/assignments/test", $testData);
				@file_put_contents("uploads/books/test", $testData);
				@file_put_contents("uploads/cache/test", $testData);
				@file_put_contents("uploads/media/test", $testData);
				@file_put_contents("uploads/profile/test", $testData);
				@file_put_contents("uploads/studyMaterial/test", $testData);
				@file_put_contents("uploads/assignmentsAnswers/test", $testData);
				@file_put_contents("uploads/onlineExams/test", $testData);
				@file_put_contents("uploads/expenses/test", $testData);

				@file_put_contents("app/storage/cache/test", $testData);
				@file_put_contents("app/storage/logs/test", $testData);
				@file_put_contents("app/storage/meta/test", $testData);
				@file_put_contents("app/storage/sessions/test", $testData);
				@file_put_contents("app/storage/views/test", $testData);

				if(@file_get_contents("uploads/assignments/test") != $testData){
					$this->data['perrors'][] = "uploads/assignments";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/assignments";
				}

				if(@file_get_contents("uploads/books/test") != $testData){
					$this->data['perrors'][] = "uploads/books";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/books";
				}

				if(@file_get_contents("uploads/cache/test") != $testData){
					$this->data['perrors'][] = "uploads/cache";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/cache";
				}

				if(@file_get_contents("uploads/media/test") != $testData){
					$this->data['perrors'][] = "uploads/media";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/media";
				}

				if(@file_get_contents("uploads/profile/test") != $testData){
					$this->data['perrors'][] = "uploads/profile";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/profile";
				}

				if(@file_get_contents("uploads/studyMaterial/test") != $testData){
					$this->data['perrors'][] = "uploads/studyMaterial";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/studyMaterial";
				}

				if(@file_get_contents("uploads/assignmentsAnswers/test") != $testData){
					$this->data['perrors'][] = "uploads/assignmentsAnswers";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/assignmentsAnswers";
				}

				if(@file_get_contents("uploads/expenses/test") != $testData){
					$this->data['perrors'][] = "uploads/expenses";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/expenses";
				}

				if(@file_get_contents("uploads/onlineExams/test") != $testData){
					$this->data['perrors'][] = "uploads/onlineExams";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "uploads/onlineExams";
				}

				if(@file_get_contents("app/storage/cache/test") != $testData){
					$this->data['perrors'][] = "app/storage/cache";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "app/storage/cache";
				}

				if(@file_get_contents("app/storage/logs/test") != $testData){
					$this->data['perrors'][] = "app/storage/logs";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "app/storage/logs";
				}

				if(@file_get_contents("app/storage/meta/test") != $testData){
					$this->data['perrors'][] = "app/storage/meta";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "app/storage/meta";
				}

				if(@file_get_contents("app/storage/sessions/test") != $testData){
					$this->data['perrors'][] = "app/storage/sessions";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "app/storage/sessions";
				}

				if(@file_get_contents("app/storage/views/test") != $testData){
					$this->data['perrors'][] = "app/storage/views";
					$this->data['nextStep'] = "1";
				}else{
					$this->data['success'][] = "app/storage/views";
				}
			}
		}

		if(Input::get('nextStep') == "2"){
			$this->data['currStep'] = "2";
			$this->data['nextStep'] = "3";

			$testInstalled = settings::where('fieldName','thisVersion')->first();
			$fix14Bug = settings::where('fieldName','siteLogo')->count();
			$fix24Bug = settings::where('fieldName','emailIsMandatory')->count();

			if($fix24Bug > 0){
				$testInstalled->fieldValue = "2.4";
			}

			if($testInstalled->fieldValue == "1.2" || $testInstalled->fieldValue == "1.3"){
				//Upgrade from first version to 1.4
				DB::unprepared(file_get_contents('app/storage/dbsqlUp14'));
				$settings = settings::where('fieldName','thisVersion')->first();
				$settings->fieldValue = '1.4';
				$settings->save();

				$testInstalled->fieldValue = "1.4";
			}
			if($testInstalled->fieldValue == "1.4" AND $fix14Bug == 0){
				//Upgrade from first version to 1.4
				DB::unprepared(file_get_contents('app/storage/dbsqlUp20'));

				//Classes and relation with subjects
				$classes = classes::get();
				foreach ($classes as $class) {
					$classesUpdateArray = array();
					$subjects = subject::where('classId',$class->id)->get();
					foreach ($subjects as $subject) {
						$classesUpdateArray[] = $subject->id;
					}
					$classesUpdateArray = json_encode($classesUpdateArray);

					classes::where('id', $class->id)->update(array('classSubjects' => $classesUpdateArray));
					unset($classesUpdateArray);
				}

				//create academic years
				$users = User::where('role','student')->get();
				foreach ($users as $user) {
					$studentAcademicYears = new student_academic_years();
					$studentAcademicYears->studentId = $user->id;
					$studentAcademicYears->academicYearId = 1;
					$studentAcademicYears->classId = $user->studentClass;
					$studentAcademicYears->save();
				}

				DB::unprepared('ALTER TABLE `subject` CHANGE `teacherId` `teacherId` int(250)   NOT NULL after `subjectTitle` ,DROP COLUMN `classId` ;');
				$settings = settings::where('fieldName','thisVersion')->first();
				$settings->fieldValue = '2.0';
				$settings->save();

				$testInstalled->fieldValue = "2.0";
			}
			if($testInstalled->fieldValue == "2.0" OR ($testInstalled->fieldValue == "1.4" AND $fix14Bug > 0) ){
				//Upgrade from first version to 2.0
				DB::unprepared(file_get_contents('app/storage/dbsqlUp21'));

				$settings = settings::where('fieldName','thisVersion')->first();
				$settings->fieldValue = '2.1';
				$settings->save();

				$testInstalled->fieldValue = "2.1";
			}
			if($testInstalled->fieldValue == "2.1" ){
				//Upgrade from first version to 2.2

				$settings = settings::where('fieldName','thisVersion')->first();
				$settings->fieldValue = '2.2';
				$settings->save();

				$testInstalled->fieldValue = "2.2";
			}
			if($testInstalled->fieldValue == "2.2" ){
				//Upgrade from first version to 2.3
				DB::unprepared(file_get_contents('app/storage/dbsqlUp23'));
				$assignments = assignments::select('id','AssignDeadLine')->get();
				foreach ($assignments as $value) {
					if (substr_count($value->AssignDeadLine, '/') == 2) {
						assignments::where('id', $value->id)->update(array('AssignDeadLine' => $this->dateToUnix($value->AssignDeadLine,"m/d/Y") ));
					}
				}
				$attendance = attendance::select('id','date')->get();
				foreach ($attendance as $value) {
					if (substr_count($value->date, '/') == 2) {
						attendance::where('id', $value->id)->update(array('date' => $this->dateToUnix($value->date,"m/d/Y") ));
					}
				}
				$events = events::select('id','eventDate')->get();
				foreach ($events as $value) {
					if (substr_count($value->eventDate, '/') == 2) {
						events::where('id', $value->id)->update(array('eventDate' => $this->dateToUnix($value->eventDate,"m/d/Y") ));
					}
				}
				$examsList = DB::table('examsList')->select('id','examDate')->get();
				foreach ($examsList as $value) {
					if (substr_count($value->examDate, '/') == 2) {
						 DB::table('examsList')->where('id', $value->id)->update(array('examDate' => $this->dateToUnix($value->examDate,"m/d/Y") ));
					}
				}
				$payments = payments::select('id','paymentDate')->get();
				foreach ($payments as $value) {
					if (substr_count($value->paymentDate, '/') == 2) {
						payments::where('id', $value->id)->update(array('paymentDate' => $this->dateToUnix($value->paymentDate,"m/d/Y") ));
					}
				}
				$vacation = vacation::select('id','vacDate')->get();
				foreach ($vacation as $value) {
					if (substr_count($value->vacDate, '/') == 2) {
						vacation::where('id', $value->id)->update(array('vacDate' => $this->dateToUnix($value->vacDate,"m/d/Y") ));
					}
				}
				$subject = subject::select('id','teacherId')->get();
				foreach ($subject as $value) {
					if (substr_count($value->teacherId, '[') == 0) {
						subject::where('id', $value->id)->update(array('teacherId' => json_encode(array($value->teacherId)) ));
					}
				}

				$settings = settings::where('fieldName','officialVacationDay')->first();
				$officialVac = json_decode($settings->fieldValue,true);
				if(is_array($officialVac) AND count($officialVac) > 0){
					while (list($key, $value) = each($officialVac)) {
						$officialVac[$key] = $this->dateToUnix($value,"m/d/Y");
					}
					$settings->fieldValue = json_encode($officialVac);
				}
				$settings->save();

				$settings = settings::where('fieldName','thisVersion')->first();
				$settings->fieldValue = '2.3';
				$settings->save();

				$testInstalled->fieldValue = "2.3";
			}

			if($testInstalled->fieldValue == "2.3" ){
				//Upgrade from first version to 2.3
				DB::unprepared(file_get_contents('app/storage/dbsqlUp24'));

				$settings = settings::where('fieldName','thisVersion')->first();
				$settings->fieldValue = '2.4';
				$settings->save();

				$testInstalled->fieldValue = "2.4";
			}

			if($testInstalled->fieldValue == "2.4"){
				//Upgrade from first version to 2.4
				DB::unprepared(file_get_contents('app/storage/dbsqlUp25'));

				$settings = settings::where('fieldName','thisVersion')->first();
				$settings->fieldValue = '2.5';
				$settings->save();

				$testInstalled->fieldValue = "2.5";
			}

			if($testInstalled->fieldValue == "2.5"){
				//Upgrade from first version to 2.4
				DB::unprepared(file_get_contents('app/storage/dbsqlUp26'));

				$settings = settings::where('fieldName','thisVersion')->first();
				$settings->fieldValue = '2.6';
				$settings->save();

				//Change the exam list structure
				$classesIds = array();
				$examClasses = classes::select('id')->get();
				foreach ($examClasses as $value) {
					$classesIds[] = ''.$value->id.'';
				}
				$examMarksheetColumns = array(array("id"=>1,"title"=>"Attendance Marks"));
				DB::table('exams_list')->update( array('examClasses'=> json_encode($classesIds) ,'examMarksheetColumns'=> json_encode($examMarksheetColumns) ) );

				//Change the exam marks structure
				$updateArray = array();
				$examMarks = exam_marks::where('totalMarks','');
				if($examMarks->count() > 0){
					$examMarks = $examMarks->get();
					$updateSql = "";

					foreach ($examMarks as $mark) {
						$updateSql .= "UPDATE exam_marks SET examMark='".json_encode( array('1'=>$mark->attendanceMark) )."',totalMarks='".$mark->examMark."' where id='".$mark->id."'; ";
					}

					if($updateSql != ""){
						DB::unprepared($updateSql);
					}
				}

				//Change the Fee Allocation
				DB::table('fee_allocation')->update( array('allocationWhen'=> 'registered' ) );

				if (!File::exists('uploads/onlineExams')){
					File::makeDirectory('uploads/onlineExams');
				}

				if (!File::exists('uploads/expenses')){
					File::makeDirectory('uploads/expenses');
				}

				$testInstalled->fieldValue = "2.6";
			}

			DB::unprepared(file_get_contents('app/storage/dbsqlUpLang'));
		}

		if(Input::get('nextStep') == "3"){
			$this->data['currStep'] = "3";
		}

		return View::make('upgrade', $this->data);
	}

	function dateToUnix($date,$format=""){
		$d = DateTime::createFromFormat($format, $date);
		$d->setTime(0,0,0);
		return $d->getTimestamp();
	}

	public function sbApi(){
		$url = "http://solutionsbricks.com/license";
		$pco = @file_get_contents('app/storage/meta/lc');
		if($pco == false){
			return "err";
		}
		$data = array("p"=>1,"n"=>$pco,"u"=>Request::url());
		if(function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$output = curl_exec($ch);
			curl_close($ch);
		}elseif(function_exists('file_get_contents')){
			$postdata = http_build_query($data);

			$opts = array('http' =>
			    array(
			        'method'  => 'POST',
			        'header'  => 'Content-type: application/x-www-form-urlencoded',
			        'content' => $postdata
			    )
			);

			$context  = stream_context_create($opts);

			$output = file_get_contents($url, false, $context);
		}else{
			$stream = fopen($url, 'r', false, stream_context_create(array(
		          'http' => array(
		              'method' => 'POST',
		              'header' => 'Content-type: application/x-www-form-urlencoded',
		              'content' => http_build_query($data)
		          )
		      )));

		      $output = stream_get_contents($stream);
		      fclose($stream);
		}
		if($output == "err"){
			@unlink('app/storage/meta/lc');
		}
		return $output;
	}

}
