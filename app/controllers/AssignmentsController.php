<?php

class AssignmentsController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();

		if(!$this->data['users']->hasThePerm('Assignments')){
			exit;
		}
	}

	public function listAll()
	{
		$toReturn = array();
		$toReturn['classes'] = classes::where('classAcademicYear',$this->panelInit->selectAcYear)->get()->toArray();
		$classesArray = array();
		while (list(, $class) = each($toReturn['classes'])) {
			$classesArray[$class['id']] = $class['className'];
		}

		$toReturn['assignments'] = array();
		if(count($classesArray) > 0){
			$assignments = new assignments();
			if($this->data['users']->role == "teacher"){
				$assignments = $assignments->where('teacherId',$this->data['users']->id);
			}

			if($this->data['users']->role == "student"){
				$assignments = $assignments->where('classId','LIKE','%"'.$this->data['users']->studentClass.'"%');
				if($this->panelInit->settingsArray['enableSections'] == true){
					$assignments = $assignments->where('sectionId','LIKE','%"'.$this->data['users']->studentSection.'"%');
				}
				$assignments = $assignments->get();
			}elseif ($this->data['users']->role == "parent") {
				$parentOf = json_decode($this->data['users']->parentOf,true);
				if(!is_array($parentOf)){
					$parentOf = array();
				}
				$ids = array();
				while (list(, $value) = each($parentOf)) {
					$ids[] = $value['id'];
				}

				if(count($ids) == 0){
					unset($assignments);
				}

				if(count($ids) > 0){
					$classArray = array();
					$sectionArray = array();
					$studentArray = User::where('role','student')->whereIn('id',$ids)->select('id','fullName','studentClass','studentSection')->get();
					foreach ($studentArray as $stOne) {
						$students[$stOne->id] = array('id'=>$stOne->id,'fullName'=>$stOne->fullName,'studentClass'=>$stOne->studentClass,'studentSection'=>$stOne->studentSection);
						$classArray[] = $stOne->studentClass;
						if($this->panelInit->settingsArray['enableSections'] == true){
							$sectionArray[] = $stOne->studentSection;
						}
					}

					if(count($classArray) > 0){
						$assignments = $assignments->where(function($query) use ($classArray){
											while (list(, $value) = each($classArray)) {
												$query = $query->orWhere('classId','LIKE','%"'.$value.'"%');
											}
								        });
					}

					if(count($sectionArray) > 0){
						$assignments = $assignments->where(function($query) use ($sectionArray){
											while (list(, $value) = each($sectionArray)) {
												$query = $query->orWhere('sectionId','LIKE','%"'.$value.'"%');
											}
								        });
					}
					$assignments = $assignments->get();

					$assignmentsIds = array();
					foreach ($assignments as $assignment) {
						$assignmentsIds[] = $assignment->id;
						$toReturn['assignmentsAnswers'][$assignment->id] = array();

						$classId = json_decode($assignment->classId,true);
						$sectionId = json_decode($assignment->sectionId,true);

						reset($students);
						while (list(, $student) = each($students)) {
							if(in_array($student['studentClass'],$classId) AND in_array($student['studentSection'],$sectionId)){
								$toReturn['assignmentsAnswers'][$assignment->id][$student['id']] = $student;
							}
						}
					}

					if(count($assignmentsIds) > 0){
						$assignmentsAnswers = \DB::table('assignments_answers')
												->leftJoin('users', 'users.id', '=', 'assignments_answers.userId')
												->select('assignments_answers.id as id',
												'assignments_answers.userId as userId',
												'assignments_answers.assignmentId as assignmentId',
												'assignments_answers.userNotes as userNotes',
												'assignments_answers.userTime as userTime',
												'assignments_answers.fileName as AssignFile',
												'users.fullName as fullName')
												->whereIn('assignments_answers.userId',$ids)
												->whereIn('assignments_answers.assignmentId',$assignmentsIds)
												->get();

						foreach ($assignmentsAnswers as $answer) {
							$toReturn['assignmentsAnswers'][$answer->assignmentId][$answer->userId] = $answer;
						}
					}
				}
			}else{
				$assignments = $assignments->where(function($query) use ($classesArray){
									while (list($key, ) = each($classesArray)) {
										$query = $query->orWhere('classId','LIKE','%"'.$key.'"%');
									}
						        });
				$assignments = $assignments->get();
			}

			$toReturn['userRole'] = $this->data['users']->role;

			if(!isset($assignments)){
				return $toReturn;
			}

			foreach ($assignments as $key => $assignment) {
				$classId = json_decode($assignment->classId);
				if($this->data['users']->role == "student" AND !in_array($this->data['users']->studentClass, $classId)){
					continue;
				}
				$toReturn['assignments'][$key]['id'] = $assignment->id;
				$toReturn['assignments'][$key]['subjectId'] = $assignment->subjectId;
				$toReturn['assignments'][$key]['AssignTitle'] = $assignment->AssignTitle;
				$toReturn['assignments'][$key]['AssignDescription'] = $assignment->AssignDescription;
				$toReturn['assignments'][$key]['AssignFile'] = $assignment->AssignFile;
				$toReturn['assignments'][$key]['AssignDeadLine'] = $assignment->AssignDeadLine;
				$toReturn['assignments'][$key]['classes'] = "";

				while (list(, $value) = each($classId)) {
					if(isset($classesArray[$value])) {
						$toReturn['assignments'][$key]['classes'] .= $classesArray[$value].", ";
					}
				}
			}
		}

		return $toReturn;
	}

	public function download($id){
		$toReturn = assignments::where('id',$id)->first();
		if(file_exists('uploads/assignments/'.$toReturn->AssignFile)){
			$fileName = preg_replace('/[^a-zA-Z0-9-_\.]/','-',$toReturn->AssignTitle). "." .pathinfo($toReturn->AssignFile, PATHINFO_EXTENSION);
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=" . $fileName);
			echo file_get_contents('uploads/assignments/'.$toReturn->AssignFile);
		}
		exit;
	}

	public function delete($id){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;
		if ( $postDelete = assignments::where('id', $id)->first() )
        {
			@unlink("uploads/assignments/".$postDelete->AssignFile);
            $postDelete->delete();
            return $this->panelInit->apiOutput(true,$this->panelInit->language['delAssignment'],$this->panelInit->language['assignemntDel']);
        }else{
            return $this->panelInit->apiOutput(false,$this->panelInit->language['delAssignment'],$this->panelInit->language['assignemntNotExist']);
        }
	}

	public function create(){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;
		$assignments = new assignments();
		$assignments->classId = json_encode(Input::get('classId'));
		if($this->panelInit->settingsArray['enableSections'] == true){
			$assignments->sectionId = json_encode(Input::get('sectionId'));
		}
		$assignments->subjectId = Input::get('subjectId');
		$assignments->teacherId = Input::get('teacherId');
		$assignments->AssignTitle = Input::get('AssignTitle');
		$assignments->AssignDescription = Input::get('AssignDescription');
		$assignments->AssignDeadLine = $this->panelInit->dateToUnix(Input::get('AssignDeadLine'));
		$assignments->teacherId = $this->data['users']->id;
		$assignments->save();
		if (Input::hasFile('AssignFile')) {
			$fileInstance = Input::file('AssignFile');
			$newFileName = "assignments_".uniqid().".".$fileInstance->getClientOriginalExtension();
			$fileInstance->move('uploads/assignments/',$newFileName);

			$assignments->AssignFile = $newFileName;
			$assignments->save();
		}
		$classes = Input::get('classId');
		while (list(, $value) = each($classes)) {
			$this->panelInit->mobNotifyUser('class',$value,$this->panelInit->language['newAssigmentAdded']." ".Input::get('AssignTitle'));
		}

		return $this->panelInit->apiOutput(true,$this->panelInit->language['AddAssignments'],$this->panelInit->language['assignmentCreated'],$assignments->toArray());
	}

	function fetch($id){
		$toReturn = assignments::where('id',$id)->first();
		$DashboardController = new DashboardController();
		$toReturn['sections'] = $DashboardController->sectionsList(json_decode($toReturn->classId,true));
		$toReturn['subject'] = $DashboardController->subjectList(json_decode($toReturn->classId,true));
		return $toReturn;
	}

	function edit($id){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;
		$assignments = assignments::find($id);
		$assignments->classId = json_encode(Input::get('classId'));
		if($this->panelInit->settingsArray['enableSections'] == true){
			$assignments->sectionId = json_encode(Input::get('sectionId'));
		}
		$assignments->subjectId = Input::get('subjectId');
		$assignments->teacherId = Input::get('teacherId');
		$assignments->AssignTitle = Input::get('AssignTitle');
		$assignments->AssignDescription = Input::get('AssignDescription');
		$assignments->AssignDeadLine = $this->panelInit->dateToUnix(Input::get('AssignDeadLine'));
		if (Input::hasFile('AssignFile')) {
			@unlink("uploads/assignments/".$assignments->AssignFile);
			$fileInstance = Input::file('AssignFile');
			$newFileName = "assignments_".uniqid().".".$fileInstance->getClientOriginalExtension();
			$fileInstance->move('uploads/assignments/',$newFileName);

			$assignments->AssignFile = $newFileName;
			$assignments->save();
		}
		$assignments->save();

		return $this->panelInit->apiOutput(true,$this->panelInit->language['editAssignment'],$this->panelInit->language['assignmentModified'],$assignments->toArray());
	}

	function checkUpload(){
		$toReturn = assignments::where('id',Input::get('assignmentId'))->first();

		if($toReturn->AssignDeadLine < time()){
			return $this->panelInit->apiOutput(false,$this->panelInit->language['applyAssAnswer'],$this->panelInit->language['assDeadTime']);
		}

		$assignmentsAnswers = assignments_answers::where('assignmentId',Input::get('assignmentId'))->where('userId',$this->data['users']->id)->count();
		if($assignmentsAnswers > 0){
			return $this->panelInit->apiOutput(false,$this->panelInit->language['applyAssAnswer'],$this->panelInit->language['assAlreadySub']);
		}
		return array("canApply"=>"true");
	}

	function upload($id){
		if($this->data['users']->role == "admin" || $this->data['users']->role == "teacher") exit;
		$assignmentsAnswers = new assignments_answers();
		$assignmentsAnswers->assignmentId = $id;
		$assignmentsAnswers->userId = $this->data['users']->id;
		$assignmentsAnswers->userNotes = Input::get('userNotes');
		$assignmentsAnswers->userTime = time();
		if (!Input::hasFile('fileName')) {
			return $this->panelInit->apiOutput(false,$this->panelInit->language['applyAssAnswer'],$this->panelInit->language['assNoFilesUploaded']);
		}elseif (Input::hasFile('fileName')) {
			$fileInstance = Input::file('fileName');
			$newFileName = "assignments_".uniqid().".".$fileInstance->getClientOriginalExtension();
			$fileInstance->move('uploads/assignmentsAnswers/',$newFileName);

			$assignmentsAnswers->fileName = $newFileName;
			$assignmentsAnswers->save();
		}
		$assignmentsAnswers->save();

		return $this->panelInit->apiOutput(true,$this->panelInit->language['applyAssAnswer'],$this->panelInit->language['assUploadedSucc']);
	}

	function listAnswers($id){
		if($this->data['users']->role == "student" || $this->data['users']->role == "parent") exit;

		$assignmentsAnswers = \DB::table('assignments_answers')
								->leftJoin('users', 'users.id', '=', 'assignments_answers.userId')
								->leftJoin('classes', 'classes.id', '=', 'users.studentClass')
								->select('assignments_answers.id as id',
								'assignments_answers.userId as userId',
								'assignments_answers.userNotes as userNotes',
								'assignments_answers.userTime as userTime',
								'assignments_answers.fileName as AssignFile',
								'users.fullName as fullName',
								'classes.className as className')
								->where('assignmentId',$id)
								->get();

		return $assignmentsAnswers;
	}

	public function downloadAnswer($id){
		$toReturn = assignments_answers::where('id',$id)->first();
		$user = User::where('id',$toReturn->userId)->first();
		if(file_exists('uploads/assignmentsAnswers/'.$toReturn->fileName)){
			$fileName = preg_replace('/[^a-zA-Z0-9-_\.]/','-',$user->fullName). "." .pathinfo($toReturn->fileName, PATHINFO_EXTENSION);
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=" . $fileName);
			echo file_get_contents('uploads/assignmentsAnswers/'.$toReturn->fileName);
		}
		exit;
	}

}
