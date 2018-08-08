<?php

class reportsController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();

		if(!$this->data['users']->hasThePerm('Reports')){
			exit;
		}
	}

	public function report(){
		if($this->data['users']->role != "admin") exit;
        if(Input::get('stats') == 'usersStats'){
            return $this->usersStats();
        }
        if(Input::get('stats') == 'stdAttendance'){
            return $this->stdAttendance(Input::get('data'));
        }
        if(Input::get('stats') == 'stfAttendance'){
            return $this->stfAttendance(Input::get('data'));
        }
		if(Input::get('stats') == 'stdVacation'){
            return $this->stdVacation(Input::get('data'));
        }
		if(Input::get('stats') == 'stfVacation'){
            return $this->stfVacation(Input::get('data'));
        }
		if(Input::get('stats') == 'payments'){
            return $this->reports(Input::get('data'));
        }
		if(Input::get('stats') == 'marksheetGenerationPrepare'){
            return $this->marksheetGenerationPrepare();
        }

	}

    public function usersStats(){
        $toReturn = array();
        $toReturn['admins'] = array();
        $toReturn['admins']['activated'] = User::where('role','admin')->where('activated','1')->count();
        $toReturn['admins']['inactivated'] = User::where('role','admin')->where('activated','0')->count();
        $toReturn['admins']['total'] = $toReturn['admins']['activated'] + $toReturn['admins']['inactivated'];

        $toReturn['teachers'] = array();
        $toReturn['teachers']['activated'] = User::where('role','teacher')->where('activated','1')->count();
        $toReturn['teachers']['inactivated'] = User::where('role','teacher')->where('activated','0')->count();
        $toReturn['teachers']['total'] = $toReturn['teachers']['activated'] + $toReturn['teachers']['inactivated'];

        $toReturn['students'] = array();
        $toReturn['students']['activated'] = User::where('role','student')->where('activated','1')->count();
        $toReturn['students']['inactivated'] = User::where('role','student')->where('activated','0')->count();
        $toReturn['students']['total'] = $toReturn['students']['activated'] + $toReturn['students']['inactivated'];

        $toReturn['parents'] = array();
        $toReturn['parents']['activated'] = User::where('role','parent')->where('activated','1')->count();
        $toReturn['parents']['inactivated'] = User::where('role','parent')->where('activated','0')->count();
        $toReturn['parents']['total'] = $toReturn['parents']['activated'] + $toReturn['parents']['inactivated'];

        return $toReturn;
    }

    public function preAttendaceStats(){
        $toReturn = array();
		$classes = classes::where('classAcademicYear',$this->panelInit->selectAcYear)->get();
		$toReturn['classes'] = array();
		$subjList = array();
		foreach ($classes as $class) {
			$class['classSubjects'] = json_decode($class['classSubjects'],true);
			if(is_array($class['classSubjects'])){
				foreach ($class['classSubjects'] as $subject) {
					$subjList[] = $subject;
				}
			}
			$toReturn['classes'][$class->id] = $class->className ;
		}

		$subjList = array_unique($subjList);
		if($this->data['panelInit']->settingsArray['attendanceModel'] == "subject"){
			$toReturn['subjects'] = array();
			if(count($subjList) > 0){
				$subjects = subject::whereIN('id',$subjList)->get();
				foreach ($subjects as $subject) {
					$toReturn['subjects'][$subject->id] = $subject->subjectTitle ;
				}
			}
		}

		$toReturn['role'] = $this->data['users']->role;
		$toReturn['attendanceModel'] = $this->data['panelInit']->settingsArray['attendanceModel'];

        return $toReturn;
    }

    public function stdAttendance($data){
        $sql = "select * from attendance where ";
		$sqlArray = array();
		$toReturn = array();

		$students = array();
		$studentArray = User::where('role','student');
		if(isset($data['classId']) AND $data['classId'] != "" ){
			$studentArray = $studentArray->where('studentClass',$data['classId']);
		}
		if(isset($data['sectionId']) AND $data['sectionId'] != "" ){
			$studentArray = $studentArray->where('studentSection',$data['sectionId']);
		}
		$studentArray = $studentArray->get();
		foreach ($studentArray as $stOne) {
			$students[$stOne->id] = array('name'=>$stOne->fullName,'studentRollId'=>$stOne->studentRollId,'attendance'=>'');
		}

		$subjectsArray = subject::get();
		$subjects = array();
		foreach ($subjectsArray as $subject) {
			$subjects[$subject->id] = $subject->subjectTitle ;
		}

		if(isset($data['classId']) AND $data['classId'] != "" ){
			$sqlArray[] = "classId='".$data['classId']."'";
		}
		if($this->data['panelInit']->settingsArray['attendanceModel'] == "subject" AND isset($data['subjectId']) AND $data['subjectId'] != ""){
			$sqlArray[] = "subjectId='".$data['subjectId']."'";
		}
		if(isset($data['status']) AND $data['status'] != "All"){
			$sqlArray[] = "status='".$data['status']."'";
		}

		if(isset($data['attendanceDayFrom']) AND $data['attendanceDayFrom'] != "" AND isset($data['attendanceDayTo']) AND $data['attendanceDayTo'] != ""){
			$days = $this->panelInit->rangeDates($data['attendanceDayFrom'],$data['attendanceDayTo']);
			$sqlArray[] = "date > (".$days['start'].") AND date < (".$days['end'].") ";
		}

		$sql = $sql . implode(" AND ", $sqlArray);
		$attendanceArray = DB::select( DB::raw($sql) );

		foreach ($attendanceArray as $stAttendance) {
			if(isset($students[$stAttendance->studentId])){
				$toReturn[$stAttendance->id] = $stAttendance;
				$toReturn[$stAttendance->id]->studentName = $students[$stAttendance->studentId]['name'];
				if($stAttendance->subjectId != ""){
					$toReturn[$stAttendance->id]->studentSubject = $subjects[$stAttendance->subjectId];
				}
				$toReturn[$stAttendance->id]->studentRollId = $students[$stAttendance->studentId]['studentRollId'];
			}
		}

		if(isset($data['exportType']) AND $data['exportType'] == "excel"){
			$data = array(1 => array ('Data','Matricula', 'Nome completo','Disciplina','Status'));

			foreach ($toReturn as $value) {
				if($value->status == 0){
					$value->status = $this->panelInit->language['Absent'];
				}elseif ($value->status == 1) {
					$value->status = $this->panelInit->language['Present'];
				}elseif ($value->status == 2) {
					$value->status = $this->panelInit->language['Late'];
				}elseif ($value->status == 3) {
					$value->status = $this->panelInit->language['LateExecuse'];
				}
				$data[] = array ($this->panelInit->unixToDate($value->date), (isset($value->studentRollId)?$value->studentRollId:""),(isset($value->studentName)?$value->studentName:""),(isset($value->studentSubject)?$value->studentSubject:""),$value->status);
			}

			$xls = new Excel_XML('UTF-8', false, 'Students Atendance Report');
			$xls->addArray($data);
			$xls->generateXML('Frequencia');
			exit;
		}

		if(isset($data['exportType']) AND $data['exportType'] == "pdf"){
			$header = array ('Data','Matricula', 'Nome completo','Disciplina','Status');
			$data = array();
			foreach ($toReturn as $value) {
				if($value->status == 0){
					$value->status = $this->panelInit->language['Absent'];
				}elseif ($value->status == 1) {
					$value->status = $this->panelInit->language['Present'];
				}elseif ($value->status == 2) {
					$value->status = $this->panelInit->language['Late'];
				}elseif ($value->status == 3) {
					$value->status = $this->panelInit->language['LateExecuse'];
				}
				$data[] = array ($this->panelInit->unixToDate($value->date), (isset($value->studentRollId)?$value->studentRollId:""),(isset($value->studentName)?$value->studentName:""),(isset($value->studentSubject)?$value->studentSubject:""),$value->status);
			}

			$pdf = new FPDF();
			$pdf->SetFont('Arial','',10);
			$pdf->AddPage();
			// Header
			foreach($header as $col){
				$pdf->Cell(40,7,$col,1);
			}
			$pdf->Ln();
			// Data
			foreach($data as $row)
			{
				foreach($row as $col){
					$pdf->Cell(40,6,$col,1);
				}
				$pdf->Ln();
			}
			$pdf->Output();
			exit;
		}

		return $toReturn;
    }

    public function stfAttendance($data){
        $sql = "select * from attendance where ";
		$sqlArray = array();
		$toReturn = array();

		$teachers = array();
		$studentArray = User::where('role','teacher')->get();
		foreach ($studentArray as $stOne) {
			$teachers[$stOne->id] = array('name'=>$stOne->fullName,'attendance'=>'');
		}

		if(isset($data['status']) AND $data['status'] != "All"){
			$sqlArray[] = "status='".$data['status']."'";
		}

		if(isset($data['attendanceDayFrom']) AND $data['attendanceDayFrom'] != "" AND isset($data['attendanceDayTo']) AND $data['attendanceDayTo'] != ""){
			$days = $this->panelInit->rangeDates($data['attendanceDayFrom'],$data['attendanceDayTo']);
			$sqlArray[] = "date > (".$days['start'].") AND date < (".$days['end'].") ";
		}

        $sqlArray[] = "classId = '0'";

		$sql = $sql . implode(" AND ", $sqlArray);
		$attendanceArray = DB::select( DB::raw($sql) );

		foreach ($attendanceArray as $stAttendance) {
			$toReturn[$stAttendance->id] = $stAttendance;
			if(isset($teachers[$stAttendance->studentId])){
				$toReturn[$stAttendance->id]->studentName = $teachers[$stAttendance->studentId]['name'];
			}
		}

		if(isset($data['exportType']) AND $data['exportType'] == "excel"){
			$data = array(1 => array ('Data', 'Nome completo','Status'));
			foreach ($toReturn as $value) {
				if($value->status == 0){
					$value->status = $this->panelInit->language['Absent'];
				}elseif ($value->status == 1) {
					$value->status = $this->panelInit->language['Present'];
				}elseif ($value->status == 2) {
					$value->status = $this->panelInit->language['Late'];
				}elseif ($value->status == 3) {
					$value->status = $this->panelInit->language['LateExecuse'];
				}
				$data[] = array ($this->panelInit->unixToDate($value->date), $value->studentName,$value->status);
			}

			$xls = new Excel_XML('UTF-8', false, 'Staff Atendance Report');
			$xls->addArray($data);
			$xls->generateXML('Frequencia');
			exit;
		}

		if(isset($data['exportType']) AND $data['exportType'] == "pdf"){
			$header = array ('Data', 'Nome completo','Status');
			$data = array();
			foreach ($toReturn as $value) {
				if($value->status == 0){
					$value->status = $this->panelInit->language['Absent'];
				}elseif ($value->status == 1) {
					$value->status = $this->panelInit->language['Present'];
				}elseif ($value->status == 2) {
					$value->status = $this->panelInit->language['Late'];
				}elseif ($value->status == 3) {
					$value->status = $this->panelInit->language['LateExecuse'];
				}
				$data[] = array ($this->panelInit->unixToDate($value->date), $value->studentName,$value->status);
			}

			$pdf = new FPDF();
			$pdf->SetFont('Arial','',10);
			$pdf->AddPage();
			// Header
			foreach($header as $col){
				$pdf->Cell(40,7,$col,1);
			}
			$pdf->Ln();
			// Data
			foreach($data as $row)
			{
				foreach($row as $col){
					$pdf->Cell(40,6,$col,1);
				}
				$pdf->Ln();
			}
			$pdf->Output();
			exit;
		}

		return $toReturn;
    }

	public function stdVacation($data){
		$datesList = $this->panelInit->rangeDates($data['fromDate'],$data['toDate']);

		if(count($datesList) > 0){
			$vacationList = \DB::table('vacation')
						->leftJoin('users', 'users.id', '=', 'vacation.userid')
						->select('vacation.id as id',
						'vacation.userid as userid',
						'vacation.vacDate as vacDate',
						'vacation.acceptedVacation as acceptedVacation',
						'users.fullName as fullName')
						->where('vacation.acYear',$this->panelInit->selectAcYear)
						->where('vacation.role','student')
						->where('vacation.vacDate','>',$datesList['start'])
						->where('vacation.vacDate','<',$datesList['end'])
						->get();

			return $vacationList;
		}

		return array();
	}

	public function stfVacation($data){
		$datesList = $this->panelInit->rangeDates($data['fromDate'],$data['toDate']);

		if(count($datesList) > 0){
			$vacationList = \DB::table('vacation')
						->leftJoin('users', 'users.id', '=', 'vacation.userid')
						->select('vacation.id as id',
						'vacation.userid as userid',
						'vacation.vacDate as vacDate',
						'vacation.acceptedVacation as acceptedVacation',
						'users.fullName as fullName')
						->where('vacation.acYear',$this->panelInit->selectAcYear)
						->where('vacation.role','teacher')
						->where('vacation.vacDate','>',$datesList['start'])
						->where('vacation.vacDate','<',$datesList['end'])
						->get();

			return $vacationList;
		}

		return array();
	}

	public function reports($data){
		$datesList = $this->panelInit->rangeDates($data['fromDate'],$data['toDate']);

		$payments = \DB::table('payments')
					->leftJoin('users', 'users.id', '=', 'payments.paymentStudent')
					->where('payments.paymentDate','>',$datesList['start'])
					->where('payments.paymentDate','<',$datesList['end'])
					->select('payments.id as id',
					'payments.paymentTitle as paymentTitle',
					'payments.paymentDescription as paymentDescription',
					'payments.paymentAmount as paymentAmount',
					'payments.paymentStatus as paymentStatus',
					'payments.paymentDate as paymentDate',
					'payments.paymentStudent as studentId',
					'users.fullName as fullName');

		if($data['status'] != "All"){
			$payments = $payments->where('paymentStatus',$data['status']);
		}
		$payments = $payments->where('paymentDate','>',$datesList['start'])->where('paymentDate','<',$datesList['end'])->orderBy('id','DESC')->get();

		return $payments;
	}

	public function marksheetGenerationPrepare(){
		$toReturn = array();
		$toReturn['classes'] = classes::where('classAcademicYear',$this->panelInit->selectAcYear)->get()->toArray();
		$toReturn['exams'] = exams_list::where('examAcYear',$this->panelInit->selectAcYear)->get()->toArray();
		return $toReturn;
	}

}
