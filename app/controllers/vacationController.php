<?php

class vacationController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();
	}

	public function getVacation(){
		if($this->data['users']->role == "admin" || $this->data['users']->role == "parent") exit;

        $currentUserVacations = vacation::where('userid',$this->data['users']->id)->where('acYear',$this->panelInit->selectAcYear)->count();
		$daysList = $this->GetDays(Input::get('fromDate'),Input::get('toDate'));

        if($this->data['users']->role == "teacher" AND (count($daysList['days']) + $currentUserVacations) > $this->panelInit->settingsArray['teacherVacationDays'] ){
            return $this->panelInit->apiOutput(false,"Request Vacation","You Don't have enough balance for vacation");
        }

        if($this->data['users']->role == "student" AND (count($daysList['days']) + $currentUserVacations) > $this->panelInit->settingsArray['studentVacationDays'] ){
            return $this->panelInit->apiOutput(false,"Request Vacation","You Don't have enough balance for vacation");
        }

        return $this->panelInit->apiOutput(true,$this->panelInit->language['getVacation'],$this->panelInit->language['confirmVacation'],$daysList);
	}

    public function saveVacation(){
        if($this->data['users']->role == "admin" || $this->data['users']->role == "parent") exit;

        $daysList = Input::get('days');
        $currentUserVacations = vacation::where('userid',$this->data['users']->id)->where('acYear',$this->panelInit->selectAcYear)->count();

        if($this->data['users']->role == "teacher" AND (count($daysList) + $currentUserVacations) > $this->panelInit->settingsArray['teacherVacationDays'] ){
            return $this->panelInit->apiOutput(false,"Request Vacation","You Don't have enough balance for vacation");
        }

        if($this->data['users']->role == "student" AND (count($daysList) + $currentUserVacations) > $this->panelInit->settingsArray['studentVacationDays'] ){
            return $this->panelInit->apiOutput(false,"Request Vacation","You Don't have enough balance for vacation");
        }

        while (list(, $value) = each($daysList)) {
            $vacation = new vacation();
            $vacation->userid = $this->data['users']->id;
            $vacation->vacDate = $value;
            $vacation->acYear = $this->panelInit->selectAcYear;
			$vacation->role = $this->data['users']->role;
            $vacation->save();
        }

        return $this->panelInit->apiOutput(true,$this->panelInit->language['getVacation'],$this->panelInit->language['vacSubmitted']);
    }

	public function delete($id){
		if($this->data['users']->role != "admin") exit;
		if ( $postDelete = vacation::where('id', $id)->first() )
        {
            $postDelete->delete();
            return $this->panelInit->apiOutput(true,$this->panelInit->language['delVacation'],$this->panelInit->language['vacDel']);
        }else{
            return $this->panelInit->apiOutput(false,$this->panelInit->language['delVacation'],$this->panelInit->language['vacNotExist']);
        }
	}

    function GetDays($startDate, $endDate){
		$format = $this->panelInit->settingsArray['dateformat'];
		$daysWeekOff = json_decode($this->panelInit->settingsArray['daysWeekOff'],true);
		if(!is_array($daysWeekOff)){
			$daysWeekOff = array();
		}
        $officialVacationDay = json_decode($this->panelInit->settingsArray['officialVacationDay'],true);
		if(!is_array($officialVacationDay)){
			$officialVacationDay = array();
		}

		if($format == ""){
			$format = "d/m/Y";
		}

		$tmpDate = DateTime::createFromFormat($format, $startDate);
		$tmpDate->setTime(0,0,0);

		$tmpEndDate = DateTime::createFromFormat($format, $endDate);
		$tmpEndDate->setTime(0,0,0);

	    $outArray = array();
	    do {
			if(in_array($tmpDate->format("U"),$officialVacationDay)){
				$outArray['vacations'][] = $tmpDate->format("U");
				continue;
			}
			if(in_array($tmpDate->format("w"),$daysWeekOff)){
				$outArray['vacations'][] = $tmpDate->format("U");
				continue;
			}
	        $outArray['days'][] = $tmpDate->format("U");
	    } while ($tmpDate->modify('+1 day') <= $tmpEndDate);

	    return $outArray;
    }

}
