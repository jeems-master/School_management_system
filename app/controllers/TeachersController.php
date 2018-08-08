<?php

class TeachersController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();
		if($this->data['users']->role != "admin") exit;

		if(!$this->data['users']->hasThePerm('teachers')){
			exit;
		}
	}

	function waitingApproval(){
		return User::where('role','teacher')->where('activated','0')->orderBy('id','DESC')->get();
	}

	function approveOne($id){
		$user = User::find($id);
		$user->activated = 1;
		$user->save();

		return $this->panelInit->apiOutput(true,$this->panelInit->language['approveTeacher'],$this->panelInit->language['teacherApproved'],array("user"=>$user->id));
	}

	public function listAll($page = 1)
	{
		$toReturn = array();
		$toReturn['teachers'] = User::where('role','teacher')->where('activated','1')->orderBy('id','DESC')->take('20')->skip(20* ($page - 1) )->get()->toArray();
		$toReturn['transports'] =  transportation::get()->toArray();
		$toReturn['totalItems'] = User::where('role','teacher')->where('activated','1')->count();
		return $toReturn;
	}

	public function search($keyword,$page = 1){
		$toReturn = array();
		$toReturn['teachers'] = User::where('role','teacher')->where('activated','1')->where(function($query) use ($keyword){
															$query->where('fullName','like','%'.$keyword.'%')->orWhere('username','like','%'.$keyword.'%')->orWhere('email','like','%'.$keyword.'%');
													})->orderBy('id','DESC')->take('20')->skip(20* ($page - 1) )->get()->toArray();


		$toReturn['totalItems'] = User::where('role','teacher')->where('activated','1')->where(function($query) use ($keyword){
															$query->where('fullName','like','%'.$keyword.'%')->orWhere('username','like','%'.$keyword.'%')->orWhere('email','like','%'.$keyword.'%');
													})->count();
		return $toReturn;
	}

	public function export(){
		if($this->data['users']->role != "admin") exit;
		$data = array(1 => array ( 'Nome completo','Nome de usuario','E-mail','Genero','Endereco','Telefone','Celular','Nascimento','Senha'));
		$student = User::where('role','teacher')->get();
		foreach ($student as $value) {
			$birthday = "";
			if($value->birthday != 0){
				$birthday = $this->panelInit->unixToDate($value->birthday);
			}
			$data[] = array ($value->fullName,$value->username,$value->email,$value->gender,$value->address,$value->phoneNo,$value->mobileNo,$birthday,"");
		}

		$xls = new Excel_XML('UTF-8', false, 'Teachers Sheet');
		$xls->addArray($data);
		$xls->generateXML('Professores');
		exit;
	}

	public function exportpdf(){
		if($this->data['users']->role != "admin") exit;
		$header = array ('Nome completo','Nome de usuario','E-mail','Genero','Endereco','Telefone','Celular');
		$data = array();
		$student = User::where('role','teacher')->get();
		foreach ($student as $value) {
			$data[] = array ($value->fullName,$value->username ,$value->email,$value->gender,$value->address,$value->phoneNo,$value->mobileNo );
		}

		$pdf = new FPDF();
		$pdf->SetFont('Arial','',10);
		$pdf->AddPage();
		// Header
		foreach($header as $col)
			$pdf->Cell(40,7,$col,1);
		$pdf->Ln();
		// Data
		foreach($data as $row)
		{
			foreach($row as $col)
				$pdf->Cell(40,6,$col,1);
			$pdf->Ln();
		}
		$pdf->Output();
		exit;
	}

	public function import($type){
		if($this->data['users']->role != "admin") exit;

		if (Input::hasFile('excelcsv')) {
			  if ( $_FILES['excelcsv']['tmp_name'] )
			  {
				  $data = new Spreadsheet_Excel_Reader();
				  $data->setOutputEncoding('CP1251');
				  $readExcel = $data->read( $_FILES['excelcsv']['tmp_name']);

				  if(!is_array($readExcel)){
					  $readExcel = $data->sheets[0]['cells'];
				  }

				  $first_row = true;

				  $dataImport = array("ready"=>array(),"revise"=>array());
				  foreach ($readExcel as $row)
				  {
					  if ( !$first_row )
					  {

						  $importItem = array();
						  if(isset($row[1])){
							  $importItem['fullName'] = $row[1];
						  }
						  if(isset($row[2])){
							  $importItem['username'] = $row[2];
						  }
						  if(isset($row[3])){
							  $importItem['email'] = $row[3];
						  }
						  if(isset($row[4])){
							  $importItem['gender'] = $row[4];
						  }
						  if(isset($row[5])){
							  $importItem['address'] = $row[5];
						  }
						  if(isset($row[6])){
							  $importItem['phoneNo'] = $row[6];
						  }
						  if(isset($row[7])){
							  $importItem['mobileNo'] = $row[7];
						  }
						  if(isset($row[8]) AND $row[8] != ""){
							  if($row[8] == ""){
								  $importItem['birthday'] = "";
							  }else{
								  $importItem['birthday'] = $this->panelInit->dateToUnix($row[8]);
							  }
						  }
						  if(isset($row[9])){
							  $importItem['password'] = $row[9];
						  }


						  $checkUser = User::where('username',$importItem['username'])->orWhere('email',$importItem['email']);
						  if($checkUser->count() > 0){
							  $checkUser = $checkUser->first();
							  if($checkUser->username == $importItem['username']){
								  $importItem['error'][] = "username";
							  }
							  if($checkUser->email == $importItem['email']){
								  $importItem['error'][] = "email";
							  }
							  $dataImport['revise'][] = $importItem;
						  }else{
							  $dataImport['ready'][] = $importItem;
						  }

					  }
					  $first_row = false;
				  }
				  return $dataImport;
			  }

		}else{
			return json_encode(array("jsTitle"=>$this->panelInit->language['Import'],"jsStatus"=>"0","jsMessage"=>$this->panelInit->language['specifyFileToImport'] ));
			exit;
		}
		exit;
	}

	public function reviewImport(){
		if($this->data['users']->role != "admin") exit;

		if(input::has('importReview')){
			$importReview = input::get('importReview');
			$importReview = array_merge($importReview['ready'], $importReview['revise']);

			$dataImport = array("ready"=>array(),"revise"=>array());
			while (list(, $row) = each($importReview)) {
				unset($row['error']);
				$checkUser = User::where('username',$row['username'])->orWhere('email',$row['email']);
				if($checkUser->count() > 0){
					$checkUser = $checkUser->first();
					if($checkUser->username == $row['username']){
						$row['error'][] = "username";
					}
					if($checkUser->email == $row['email']){
						$row['error'][] = "email";
					}
					$dataImport['revise'][] = $row;
				}else{
					$dataImport['ready'][] = $row;
				}
			}

			if(count($dataImport['revise']) > 0){
				return $this->panelInit->apiOutput(false,$this->panelInit->language['Import'],$this->panelInit->language['reviseImportData'],$dataImport);
			}else{
				while (list(, $value) = each($dataImport['ready'])) {
					$User = new User();
					if(isset($value['email'])){
						$User->email = $value['email'];
					}
					if(isset($value['username'])){
						$User->username = $value['username'];
					}
					if(isset($value['fullName'])){
						$User->fullName = $value['fullName'];
					}
					if(isset($value['password']) AND $value['password'] != ""){
						$User->password = Hash::make($value['password']);
					}
					$User->role = "teacher";
					if(isset($value['gender'])){
						$User->gender = $value['gender'];
					}
					if(isset($value['address'])){
						$User->address = $value['address'];
					}
					if(isset($value['phoneNo'])){
						$User->phoneNo = $value['phoneNo'];
					}
					if(isset($value['mobileNo'])){
						$User->mobileNo = $value['mobileNo'];
					}
					if(isset($value['birthday'])){
						$User->birthday = $value['birthday'];
					}
					$User->save();
				}
				return $this->panelInit->apiOutput(true,$this->panelInit->language['Import'],$this->panelInit->language['dataImported']);
			}
		}else{
			return $this->panelInit->apiOutput(true,$this->panelInit->language['Import'],$this->panelInit->language['noDataImport']);
			exit;
		}
		exit;
	}

	public function delete($id){
		if ( $postDelete = User::where('role','teacher')->where('id', $id)->first() )
        {
            $postDelete->delete();
            return $this->panelInit->apiOutput(true,$this->panelInit->language['delTeacher'],$this->panelInit->language['teacherDel']);
        }else{
            return $this->panelInit->apiOutput(false,$this->panelInit->language['delTeacher'],$this->panelInit->language['teacherNotExist']);
        }
	}

	function leaderboard($id){
		if($this->data['users']->role != "admin") exit;

		$user = User::where('id',$id)->first();
		$user->isLeaderBoard = Input::get('isLeaderBoard');
		$user->save();

		$this->panelInit->mobNotifyUser('users',$user->id,$this->panelInit->language['notifyIsLedaerBoard']);

		return $this->panelInit->apiOutput(true,$this->panelInit->language['teacLeaderBoard'],$this->panelInit->language['teacIsLeader']);
	}

	function leaderboardRemove($id){
		if($this->data['users']->role != "admin") exit;
		if ( $postDelete = User::where('role','teacher')->where('id', $id)->where('isLeaderBoard','!=','')->first() )
        {
            User::where('role','teacher')->where('id', $id)->update(array('isLeaderBoard' => ''));
            return $this->panelInit->apiOutput(true,$this->panelInit->language['teacLeaderBoard'],$this->panelInit->language['teacLeaderDel']);
        }else{
            return $this->panelInit->apiOutput(false,$this->panelInit->language['teacLeaderBoard'],$this->panelInit->language['teachNotLeader']);
        }
	}

	public function create(){
		if(User::where('username',trim(Input::get('username')))->count() > 0){
			return $this->panelInit->apiOutput(false,$this->panelInit->language['addTeacher'],$this->panelInit->language['usernameUsed']);
		}
		if(User::where('email',Input::get('email'))->count() > 0){
			return $this->panelInit->apiOutput(false,$this->panelInit->language['addTeacher'],$this->panelInit->language['mailUsed']);
		}
		$User = new User();
		$User->username = Input::get('username');
		$User->email = Input::get('email');
		$User->fullName = Input::get('fullName');
		$User->password = Hash::make(Input::get('password'));
		$User->role = "teacher";
		$User->gender = Input::get('gender');
		$User->address = Input::get('address');
		$User->phoneNo = Input::get('phoneNo');
		$User->mobileNo = Input::get('mobileNo');
		$User->transport = Input::get('transport');
		$User->isLeaderBoard = "";
		if(Input::get('birthday')){
			$User->birthday = $this->panelInit->dateToUnix(Input::get('birthday'));
		}
		$User->save();

		if (Input::hasFile('photo')) {
			$fileInstance = Input::file('photo');
			$newFileName = "profile_".$User->id.".jpg";
			$file = $fileInstance->move('uploads/profile/',$newFileName);

			$User->photo = "profile_".$User->id.".jpg";
			$User->save();
		}

		return $this->panelInit->apiOutput(true,$this->panelInit->language['addTeacher'],$this->panelInit->language['teacherCreated'],$User->toArray());
	}

	function fetch($id){
		$data = User::where('role','teacher')->where('id',$id)->first()->toArray();
		$data['birthday'] = $this->panelInit->unixToDate($data['birthday']);
		return json_encode($data);
	}

	function edit($id){
		if(User::where('username',trim(Input::get('username')))->where('id','<>',$id)->count() > 0){
			return $this->panelInit->apiOutput(false,$this->panelInit->language['EditTeacher'],$this->panelInit->language['usernameUsed']);
		}
		if(User::where('email',Input::get('email'))->where('id','!=',$id)->count() > 0){
			return $this->panelInit->apiOutput(false,$this->panelInit->language['EditTeacher'],$this->panelInit->language['mailUsed']);
		}
		$User = User::find($id);
		$User->username = Input::get('username');
		$User->email = Input::get('email');
		$User->fullName = Input::get('fullName');
		if(Input::get('password') != ""){
			$User->password = Hash::make(Input::get('password'));
		}
		$User->gender = Input::get('gender');
		$User->address = Input::get('address');
		$User->phoneNo = Input::get('phoneNo');
		$User->mobileNo = Input::get('mobileNo');
		$User->transport = Input::get('transport');
		if(Input::get('birthday') != ""){
			$User->birthday = $this->panelInit->dateToUnix(Input::get('birthday'));
		}
		if (Input::hasFile('photo')) {
			$fileInstance = Input::file('photo');
			$newFileName = "profile_".$User->id.".jpg";
			$file = $fileInstance->move('uploads/profile/',$newFileName);

			$User->photo = "profile_".$User->id.".jpg";
		}
		$User->save();

		return $this->panelInit->apiOutput(true,$this->panelInit->language['EditTeacher'],$this->panelInit->language['teacherUpdated'],$User->toArray());
	}

	function profile($id){
		$data = User::where('role','teacher')->where('id',$id)->first()->toArray();
		$data['birthday'] = $this->panelInit->unixToDate($data['birthday']);

		$return = array();
		$return['title'] = $data['fullName']." ".$this->panelInit->language['Profile'];

		$return['content'] = "<div class='text-center'>";

		$return['content'] .= "<img alt='".$data['fullName']."' class='user-image img-circle' style='width:70px; height:70px;' src='dashboard/profileImage/".$data['id']."'>";

		$return['content'] .= "</div>";

		$return['content'] .= "<h4>".$this->panelInit->language['teacherInfo']."</h4>";

		$return['content'] .= "<table class='table table-bordered'><tbody>
                          <tr>
                              <td>".$this->panelInit->language['FullName']."</td>
                              <td>".$data['fullName']."</td>
                          </tr>
                          <tr>
                              <td>".$this->panelInit->language['username']."</td>
                              <td>".$data['username']."</td>
                          </tr>
                          <tr>
                              <td>".$this->panelInit->language['email']."</td>
                              <td>".$data['email']."</td>
                          </tr>
                          <tr>
                              <td>".$this->panelInit->language['Birthday']."</td>
                              <td>".$data['birthday']."</td>
                          </tr>
                          <tr>
                              <td>".$this->panelInit->language['Gender']."</td>
                              <td>".$data['gender']."</td>
                          </tr>
                          <tr>
                              <td>".$this->panelInit->language['Address']."</td>
                              <td>".$data['address']."</td>
                          </tr>
                          <tr>
                              <td>".$this->panelInit->language['phoneNo']."</td>
                              <td>".$data['phoneNo']."</td>
                          </tr>
                          <tr>
                              <td>".$this->panelInit->language['mobileNo']."</td>
                              <td>".$data['mobileNo']."</td>
                          </tr>

                          </tbody></table>";

		return $return;
	}
}
