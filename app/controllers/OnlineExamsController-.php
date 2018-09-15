<?php

class OnlineExamsController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();

		if(!$this->data['users']->hasThePerm('onlineExams')){
			exit;
		}
	}

	public function listAll()
	{
		$toReturn = array();
		$toReturn['classes'] = classes::where('classAcademicYear',$this->panelInit->selectAcYear)->get()->toArray();
		$classesArray = array();
		while (list(, $class) = each($toReturn['classes'])) {
			$classesArray[$class['id']] = array("classTitle"=>$class['className'],"subjects"=>json_decode($class['classSubjects']));
		}

		if($this->data['users']->role == "teacher"){
			$subjects = subject::where('teacherId','LIKE','%"'.$this->data['users']->id.'"%')->get()->toArray();
		}else{
			$subjects = subject::get()->toArray();
		}
		$subjectArray = array();
		while (list(, $subject) = each($subjects)) {
			$subjectArray[$subject['id']] = $subject['subjectTitle'];
		}

		$toReturn['onlineExams'] = array();
		$onlineExams = new online_exams();

		if($this->data['users']->role == "teacher"){
			$onlineExams = $onlineExams->where('examTeacher',$this->data['users']->id);
		}

		if($this->data['users']->role == "student"){
			$onlineExams = $onlineExams->where('examClass','LIKE','%"'.$this->data['users']->studentClass.'"%');
			if($this->panelInit->settingsArray['enableSections'] == true){
				$onlineExams = $onlineExams->where('sectionId','LIKE','%"'.$this->data['users']->studentSection.'"%');
			}
		}

		$onlineExams = $onlineExams->where('exAcYear',$this->panelInit->selectAcYear);
		$onlineExams = $onlineExams->get();
		foreach ($onlineExams as $key => $onlineExam) {
			$classId = json_decode($onlineExam->examClass);
			if($this->data['users']->role == "student" AND !in_array($this->data['users']->studentClass, $classId)){
				continue;
			}
			$toReturn['onlineExams'][$key]['id'] = $onlineExam->id;
			$toReturn['onlineExams'][$key]['examTitle'] = $onlineExam->examTitle;
			$toReturn['onlineExams'][$key]['examDescription'] = $onlineExam->examDescription;
			if(isset($subjectArray[$onlineExam->examSubject])){
				$toReturn['onlineExams'][$key]['examSubject'] = $subjectArray[$onlineExam->examSubject];
			}
			$toReturn['onlineExams'][$key]['ExamEndDate'] = $onlineExam->ExamEndDate;
			$toReturn['onlineExams'][$key]['ExamShowGrade'] = $onlineExam->ExamShowGrade;
			$toReturn['onlineExams'][$key]['classes'] = "";

			while (list(, $value) = each($classId)) {
				if(isset($classesArray[$value])){
					$toReturn['onlineExams'][$key]['classes'] .= $classesArray[$value]['classTitle'].", ";
				}
			}
		}
		$toReturn['userRole'] = $this->data['users']->role;
		return $toReturn;
	}

	public function delete($id){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;
		if ( $postDelete = online_exams::where('id', $id)->first() )
        {
            $postDelete->delete();
            return $this->panelInit->apiOutput(true,$this->panelInit->language['delExam'],$this->panelInit->language['exDeleted']);
        }else{
            return $this->panelInit->apiOutput(false,$this->panelInit->language['delExam'],$this->panelInit->language['exNotExist']);
        }
	}

	public function create(){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;
		$onlineExams = new online_exams();
		$onlineExams->examTitle = Input::get('examTitle');
		$onlineExams->examDescription = Input::get('examDescription');
		$onlineExams->examClass = json_encode(Input::get('examClass'));
		if($this->panelInit->settingsArray['enableSections'] == true){
			$onlineExams->sectionId = json_encode(Input::get('sectionId'));
		}
		$onlineExams->examTeacher = $this->data['users']->id;
		$onlineExams->examSubject = Input::get('examSubject');
		$onlineExams->examDate = $this->panelInit->dateToUnix(Input::get('examDate'));
		$onlineExams->exAcYear = $this->panelInit->selectAcYear;
		$onlineExams->ExamEndDate = $this->panelInit->dateToUnix(Input::get('ExamEndDate'));
		if(Input::has('ExamShowGrade')){
			$onlineExams->ExamShowGrade = Input::get('ExamShowGrade');
		}
		$onlineExams->examTimeMinutes = Input::get('examTimeMinutes');
		$onlineExams->examDegreeSuccess = Input::get('examDegreeSuccess');
		$onlineExams->examQuestion = json_encode(Input::get('examQuestion'));
		$onlineExams->save();

		$examClass = Input::get('examClass');
		while (list(, $value) = each($examClass)) {
			$this->panelInit->mobNotifyUser('class',$value,$this->panelInit->language['newOnlineExamAdded']." ".Input::get('examTitle'));
		}

		return $this->panelInit->apiOutput(true,$this->panelInit->language['addExam'],$this->panelInit->language['examCreated'],$onlineExams->toArray() );
	}

	function fetch($id){
		$istook = online_exams_grades::where('examId',$id)->where('studentId',$this->data['users']->id)->count();

		$onlineExams = online_exams::where('id',$id)->first()->toArray();
		$onlineExams['examClass'] = json_decode($onlineExams['examClass']);
		$onlineExams['examQuestion'] = json_decode($onlineExams['examQuestion']);
		if(time() > $onlineExams['ExamEndDate'] || time() < $onlineExams['examDate']){
			$onlineExams['finished'] = true;
		}
		if($istook > 0){
			$onlineExams['taken'] = true;
		}

		$DashboardController = new DashboardController();
		$onlineExams['subject'] = $DashboardController->subjectList($onlineExams['examClass']);
		$onlineExams['sections'] = $DashboardController->sectionsList($onlineExams['examClass']);
		return $onlineExams;
	}

	function marks($id){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;
		$return = array();

		$exam = online_exams::where('id',$id)->first();
		$return['examDegreeSuccess'] = $exam->examDegreeSuccess;

		$return['grade'] = \DB::table('online_exams_grades')
					->where('examId',$id)
					->leftJoin('users', 'users.id', '=', 'online_exams_grades.studentId')
					->select('online_exams_grades.id as id',
					'online_exams_grades.examGrade as examGrade',
					'online_exams_grades.examDate as examDate',
					'online_exams_grades.examQuestionsAnswers as examQuestionsAnswers',
					'users.fullName as fullName',
					'users.id as studentId')
					->get();

		return json_encode($return);
	}

	function edit($id){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;
		$onlineExams = online_exams::find($id);
		$onlineExams->examTitle = Input::get('examTitle');
		$onlineExams->examDescription = Input::get('examDescription');
		$onlineExams->examClass = json_encode(Input::get('examClass'));
		if($this->panelInit->settingsArray['enableSections'] == true){
			$onlineExams->sectionId = json_encode(Input::get('sectionId'));
		}
		$onlineExams->examTeacher = $this->data['users']->id;
		$onlineExams->examSubject = Input::get('examSubject');
		$onlineExams->examDate = $this->panelInit->dateToUnix(Input::get('examDate'));
		$onlineExams->ExamEndDate = $this->panelInit->dateToUnix(Input::get('ExamEndDate'));
		if(Input::has('ExamShowGrade')){
			$onlineExams->ExamShowGrade = Input::get('ExamShowGrade');
		}
		$onlineExams->examTimeMinutes = Input::get('examTimeMinutes');
		$onlineExams->examDegreeSuccess = Input::get('examDegreeSuccess');
		$onlineExams->examQuestion = json_encode(Input::get('examQuestion'));
		$onlineExams->save();

		return $this->panelInit->apiOutput(true,$this->panelInit->language['editExam'],$this->panelInit->language['examModified'],$onlineExams->toArray() );
	}

	function uploadImage(){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;

		if(Input::has('oldImage') AND Input::get('oldImage') != ""){
			@unlink('uploads/onlineExams/'.Input::get('oldImage'));
		}

		$fileInstance = Input::file('questionImage');
		$newFileName = uniqid().".".$fileInstance->getClientOriginalExtension();
		$fileInstance->move('uploads/onlineExams/',$newFileName);

		return $newFileName;
	}

	function take($id){
		$istook = online_exams_grades::where('examId',$id)->where('studentId',$this->data['users']->id);
		$istookFinish = $istook->first();
		$istook = $istook->count();

		if($istook == 0){
			$onlineExamsGrades = new online_exams_grades();
			$onlineExamsGrades->examId = $id;
			$onlineExamsGrades->studentId = $this->data['users']->id;
			$onlineExamsGrades->examDate = time() ;
			$onlineExamsGrades->save();
		}

		$onlineExams = online_exams::where('id',$id)->first()->toArray();
		$onlineExams['examClass'] = json_decode($onlineExams['examClass']);
		$onlineExams['examQuestion'] = json_decode($onlineExams['examQuestion'],true);
		while (list($key, $value) = each($onlineExams['examQuestion'])) {
			if(isset($onlineExams['examQuestion'][$key]['Tans'])){
				unset($onlineExams['examQuestion'][$key]['Tans']);
			}
			if(isset($onlineExams['examQuestion'][$key]['Tans1'])){
				unset($onlineExams['examQuestion'][$key]['Tans1']);
			}
			if(isset($onlineExams['examQuestion'][$key]['Tans2'])){
				unset($onlineExams['examQuestion'][$key]['Tans2']);
			}
			if(isset($onlineExams['examQuestion'][$key]['Tans3'])){
				unset($onlineExams['examQuestion'][$key]['Tans3']);
			}
			if(isset($onlineExams['examQuestion'][$key]['Tans4'])){
				unset($onlineExams['examQuestion'][$key]['Tans4']);
			}
		}
		if(time() > $onlineExams['ExamEndDate'] || time() < $onlineExams['examDate']){
			$onlineExams['finished'] = true;
		}

		if($istook > 0 AND $istookFinish['examQuestionsAnswers'] != null){
			return $this->panelInit->apiOutput(false,$this->panelInit->language['takeExam'],$this->panelInit->language['exAlreadyTook']);
		}

		if($onlineExams['examTimeMinutes'] != 0 AND $istook > 0){
			if( (time() - $istookFinish['examDate']) > $onlineExams['examTimeMinutes']*60){
				return $this->panelInit->apiOutput(false,$this->panelInit->language['takeExam'],$this->panelInit->language['examTimedOut']);
			}
		}

		if($onlineExams['examTimeMinutes'] == 0){
			$onlineExams['timeLeft'] = 0;
		}else{
			if($istook == 0){
				$onlineExams['timeLeft'] = $onlineExams['examTimeMinutes'] * 60;
			}
			if($istook > 0){
				$onlineExams['timeLeft'] = $onlineExams['examTimeMinutes']*60 - (time() - $istookFinish['examDate']);
			}
		}

		$onlineExams['examDate'] = $this->panelInit->unixToDate($onlineExams['examDate']);
		$onlineExams['ExamEndDate'] =$this->panelInit->unixToDate($onlineExams['ExamEndDate']);
		return $onlineExams;
	}

	function took($id){
		$onlineExams = online_exams::where('id',$id)->first()->toArray();
		$onlineExams['examQuestion'] = json_decode($onlineExams['examQuestion'],true);

		$toReturn = array();
		$answers = Input::get('examQuestion');
		$score = 0;
		while (list($key, $value) = each($answers)) {
			$answers[$key]['state'] = 0;
			if( !isset($onlineExams['examQuestion'][$key]['type']) || (isset($onlineExams['examQuestion'][$key]['type']) AND $onlineExams['examQuestion'][$key]['type'] == "radio")){
				$answers[$key]['userAnswer'] = $onlineExams['examQuestion'][$key]['ans'.$value['answer']];
				if($value['answer'] == $onlineExams['examQuestion'][$key]['Tans']){
					if(isset($onlineExams['examQuestion'][$key]['questionMark'])){
						$score += $onlineExams['examQuestion'][$key]['questionMark'];
					}else{
						$score++;
					}
					$answers[$key]['state'] = 1;
				}
			}
			if(isset($onlineExams['examQuestion'][$key]['type']) AND $onlineExams['examQuestion'][$key]['type'] == "check"){
				$pass = true;
				$answers[$key]['userAnswer'] = array();

				if(isset($value['answer1'])){
					$answers[$key]['userAnswer'][] = $onlineExams['examQuestion'][$key]['ans1'];
				}
				if(isset($onlineExams['examQuestion'][$key]['Tans1']) AND !isset($value['answer1'])){
					$pass = false;
				}

				if(isset($value['answer2'])){
					$answers[$key]['userAnswer'][] = $onlineExams['examQuestion'][$key]['ans2'];
				}
				if(isset($onlineExams['examQuestion'][$key]['Tans2']) AND !isset($value['answer2'])){
					$pass = false;
				}

				if(isset($value['answer3'])){
					$answers[$key]['userAnswer'][] = $onlineExams['examQuestion'][$key]['ans3'];
				}
				if(isset($onlineExams['examQuestion'][$key]['Tans3']) AND !isset($value['answer3'])){
					$pass = false;
				}

				if(isset($value['answer4'])){
					$answers[$key]['userAnswer'][] = $onlineExams['examQuestion'][$key]['ans4'];
				}
				if(isset($onlineExams['examQuestion'][$key]['Tans4']) AND !isset($value['answer4'])){
					$pass = false;
				}

				$answers[$key]['userAnswer'] = implode(",",$answers[$key]['userAnswer']);
				if($pass == true){
					if(isset($onlineExams['examQuestion'][$key]['questionMark'])){
						$score += $onlineExams['examQuestion'][$key]['questionMark'];
					}else{
						$score++;
					}
					$answers[$key]['state'] = 1;
				}
				unset($pass);
			}
			if(isset($onlineExams['examQuestion'][$key]['type']) AND $onlineExams['examQuestion'][$key]['type'] == "text"){
				$answers[$key]['userAnswer'] = $value['answer'];
				$onlineExams['examQuestion'][$key]['ans1'] = explode(",",$onlineExams['examQuestion'][$key]['ans1']);
				if(isset($value['answer']) AND in_array($value['answer'],$onlineExams['examQuestion'][$key]['ans1'])){
					if(isset($onlineExams['examQuestion'][$key]['questionMark'])){
						$score += $onlineExams['examQuestion'][$key]['questionMark'];
					}else{
						$score++;
					}
					$answers[$key]['state'] = 1;
				}
			}
		}

		$onlineExamsGrades = online_exams_grades::where('examId',$id)->where('studentId',$this->data['users']->id)->first();
		$onlineExamsGrades->examId = Input::get('id') ;
		$onlineExamsGrades->studentId = $this->data['users']->id ;
		$onlineExamsGrades->examQuestionsAnswers = json_encode($answers) ;
		$onlineExamsGrades->examGrade = $score ;
		$onlineExamsGrades->examDate = time() ;
		$onlineExamsGrades->save();

		if($onlineExams['ExamShowGrade'] == 1){
			if($onlineExams['examDegreeSuccess'] != "0"){
				if($onlineExams['examDegreeSuccess'] <= $score){
					$score .= " - Succeeded";
				}else{
					$score .= " - Failed";
				}
			}
			$toReturn['grade'] = $score;
		}
		$toReturn['finish'] = true;
		return json_encode($toReturn);
	}

	function export($id,$type){
		if($this->data['users']->role != "admin") exit;
		if($type == "excel"){
			$classArray = array();
			$classes = classes::get();
			foreach ($classes as $class) {
				$classArray[$class->id] = $class->className;
			}

			$data = array(1 => array ('Matricula','Nome completo','Data','Resultado'));
			$grades = \DB::table('online_exams_grades')
					->where('examId',$id)
					->leftJoin('users', 'users.id', '=', 'online_exams_grades.studentId')
					->select('online_exams_grades.id as id',
					'online_exams_grades.examGrade as examGrade',
					'online_exams_grades.examDate as examDate',
					'users.fullName as fullName',
					'users.id as studentId',
					'users.studentRollId as studentRollId')
					->get();
			foreach ($grades as $value) {
				$data[] = array ($value->studentRollId,$value->fullName,$this->panelInit->unixToDate($value->examDate) , $value->examGrade );
			}

			$xls = new Excel_XML('UTF-8', false, 'Exam grades Sheet');
			$xls->addArray($data);
			$xls->generateXML('Avaliacao Online');
		}elseif ($type == "pdf") {
			$classArray = array();
			$classes = classes::get();
			foreach ($classes as $class) {
				$classArray[$class->id] = $class->className;
			}

			$header = array ('Matricula','Nome completo','Data','Resultado');
			$data = array();
			$grades = \DB::table('online_exams_grades')
					->where('examId',$id)
					->leftJoin('users', 'users.id', '=', 'online_exams_grades.studentId')
					->select('online_exams_grades.id as id',
					'online_exams_grades.examGrade as examGrade',
					'online_exams_grades.examDate as examDate',
					'users.fullName as fullName',
					'users.id as studentId',
					'users.studentRollId as studentRollId')
					->get();
			foreach ($grades as $value) {
				$data[] = array ($value->studentRollId,$value->fullName,$this->panelInit->unixToDate($value->examDate), $value->examGrade );
			}

			$pdf = new FPDF();
			$pdf->SetFont('Arial','',10);
			$pdf->AddPage();
			// Header
			foreach($header as $col)
				$pdf->Cell(60,7,$col,1);
			$pdf->Ln();
			// Data
			foreach($data as $row)
			{
				foreach($row as $col)
					$pdf->Cell(60,6,$col,1);
				$pdf->Ln();
			}
			$pdf->Output();
		}
		exit;
	}
}
