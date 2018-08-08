<?php

class promotionController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();
		if($this->data['users']->role != "admin") exit;

		if(!$this->data['users']->hasThePerm('Promotion')){
			exit;
		}
	}

	public function listStudents(){
		$toReturn = array('students'=>array(),"classes"=>array());

		if(Input::get('selectType') == "selStudents"){
			$studentIds = array();
			$studentList = Input::get('studentInfo');
			while (list($key, $value) = each($studentList)) {
				$studentIds[] = $value['id'];
			}

			$students = User::whereIn('id',$studentIds)->get();
			foreach ($students as $value) {
				$toReturn['students'][$value->id] = array("id"=>$value->id,"fullName"=>$value->fullName,"class"=>$value->studentClass,"acYear"=>$value->studentAcademicYear);
			}
		}else{
			$students = User::where('studentAcademicYear',Input::get('acYear'))->where('studentClass',Input::get('classId'))->where('role','student')->where('activated',1)->get();
			foreach ($students as $value) {
				$toReturn['students'][$value->id] = array("id"=>$value->id,"fullName"=>$value->fullName,"class"=>$value->studentClass,"acYear"=>$value->studentAcademicYear);
			}
		}

		$DashboardController = new DashboardController();
		$toReturn['classes'] = $DashboardController->classesList(Input::get('acYear'));

		return $toReturn;
	}

	public function promoteNow(){
		$returnResponse = array();

		$promote = Input::get('promote');
		if(count($promote) > 0){
			$studentIdList = array();
			$studentDetailsList = array();
			while (list(, $value) = each($promote)) {
				$studentIdList[] = $value['id'];
			}

			$users = User::whereIn('id',$studentIdList)->get();
			foreach ($users as $value) {
				if(student_academic_years::where('studentId',$value->id)->where('academicYearId',$promote[$value->id]['acYear'])->count() > 0 AND \Input::get('promoType') != "graduate"){
					$returnResponse[] = array("id"=>$value->id,"fullName"=>$value->fullName,"status"=>"User already been in that academic year before");
				}else{
					if(\Input::get('promoType') != "graduate"){
						$studentAcademicYears = new student_academic_years();
						$studentAcademicYears->studentId = $value->id;
						$studentAcademicYears->academicYearId = $promote[$value->id]['acYear'];
						$studentAcademicYears->classId = $promote[$value->id]['class'];
						if($this->panelInit->settingsArray['enableSections'] == true){
							$studentAcademicYears->sectionId = $promote[$value->id]['section'];
						}
						$studentAcademicYears->save();

						if($this->data['panelInit']->settingsArray['invoiceGenStudentPromotion'] == 1){
							$User = User::where('id',$value->id)->first();
							PaymentsController::generateInvoice($User,'promoted');
						}

						$updateArray = array('studentClass'=>$promote[$value->id]['class'],'studentAcademicYear'=>$promote[$value->id]['acYear']);
						if($this->panelInit->settingsArray['enableSections'] == true){
							$updateArray['studentSection'] = $promote[$value->id]['section'];
						}
					}else{
						$User = User::where('id',$value->id)->first();
						PaymentsController::generateInvoice($User,'graduated');

						$updateArray = array(
											'studentClass'=>-1,
											'studentSection'=>0
										);
					}
					User::where('id',$value->id)->update($updateArray);

					$returnResponse[] = array("id"=>$value->id,"fullName"=>$value->fullName,"status"=>"User promoted successfully");
				}
			}

			return $returnResponse;
		}

	}

	public function searchStudents($student){
		$students = User::where('role','student')->where('fullName','like','%'.$student.'%')->orWhere('username','like','%'.$student.'%')->orWhere('email','like','%'.$student.'%')->get();
		$retArray = array();
		foreach ($students as $student) {
			$retArray[$student->id] = array("id"=>$student->id,"name"=>$student->fullName,"email"=>$student->email);
		}
		return json_encode($retArray);
	}

}
