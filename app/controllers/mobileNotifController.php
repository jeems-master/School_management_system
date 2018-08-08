<?php

class mobileNotifController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['users'] = \Auth::user();
		if($this->data['users']->role != "admin") exit;

		if(!$this->data['users']->hasThePerm('mobileNotif')){
			exit;
		}
	}

	public function listAll()
	{
		$return = array();
		$mobNotifications = mob_notifications::get()->toArray();
		foreach ($mobNotifications as $value) {
			$value['notifData'] = htmlspecialchars_decode($value['notifData'],ENT_QUOTES);
			$return[] = $value;
		}
		return $return;
	}

	public function create(){
		$mobNotifications = new mob_notifications();

		if(Input::get('userType') == "users"){
			$mobNotifications->notifTo = "users";
			$mobNotifications->notifToIds = json_encode(Input::get('selectedUsers'));
		}elseif(Input::get('userType') == "students"){
			$mobNotifications->notifTo = "students";
			$mobNotifications->notifToIds = Input::get('classId');
		}else{
			$mobNotifications->notifTo = Input::get('userType');
			$mobNotifications->notifToIds = "";
		}

		$mobNotifications->notifData = htmlspecialchars(Input::get('notifData'),ENT_QUOTES);

		$mobNotifications->notifDate = time();
		$mobNotifications->notifSender = $this->data['users']->fullName . " [ " . $this->data['users']->id . " ] ";
		$mobNotifications->save();

		return $this->listAll();
	}

	public function delete($id){
		if ( $postDelete = mob_notifications::where('id', $id)->first() )
		{
			$postDelete->delete();
			return $this->panelInit->apiOutput(true,"Delete Notification","Notification deleted");
		}else{
			return $this->panelInit->apiOutput(false,"Delete Notification","Notification isn't exist");
		}
	}

}
