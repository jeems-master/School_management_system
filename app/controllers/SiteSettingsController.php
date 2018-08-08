<?php

class SiteSettingsController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();
		if($this->data['users']->role != "admin") exit;

		if(!$this->data['users']->hasThePerm('generalSettings')){
			exit;
		}
	}

	public function listAll($title)
	{
		if($title == "siteSettings"){
			$settingsArray = array();

			$languages = languages::get();
			foreach ($languages as $language) {
				$settingsArray['languages'][$language->id] = $language->languageTitle;
			}

			$settings = settings::get();
			foreach ($settings as $setting) {
				$settingsArray['settings'][$setting->fieldName] = $setting->fieldValue;
			}
			$settingsArray['settings']['activatedModules'] = json_decode($settingsArray['settings']['activatedModules'],true);
			if(!is_array($settingsArray['settings']['activatedModules'])){
				$settingsArray['settings']['activatedModules'] = array();
			}
			$settingsArray['settings']['officialVacationDay'] = json_decode($settingsArray['settings']['officialVacationDay'],true);
			if(!is_array($settingsArray['settings']['officialVacationDay'])){
				$settingsArray['settings']['officialVacationDay'] = array();
			}else{
				if(count($settingsArray['settings']['officialVacationDay']) > 0){
					while (list($key_, $date) = each($settingsArray['settings']['officialVacationDay'])) {
						$settingsArray['settings']['officialVacationDay'][$key_] = $this->panelInit->unixToDate($date);
					}
				}
			}
			$settingsArray['settings']['daysWeekOff'] = json_decode($settingsArray['settings']['daysWeekOff'],true);
			if(!is_array($settingsArray['settings']['daysWeekOff'])){
				$settingsArray['settings']['daysWeekOff'] = array();
			}
			$settingsArray['smsProvider'] = json_decode($this->panelInit->settingsArray['smsProvider']);
			$settingsArray['mailProvider'] = json_decode($this->panelInit->settingsArray['mailProvider']);
			return $settingsArray;
		}
		if($title == "terms"){
			$settings = settings::where('fieldName','schoolTerms')->first()->toArray();
			$settings['fieldValue'] = htmlspecialchars_decode($settings['fieldValue'],ENT_QUOTES);
			return $settings;
		}

	}

	public function langs()
	{

		$settingsArray = array();

		$languages = languages::get();
		foreach ($languages as $language) {
			$settingsArray['languages'][$language->id] = $language->languageTitle;
		}
		return $settingsArray;

	}

	public function save($title){
		if($title == "siteSettings"){
			$settingsData = Input::All();

			while (list($key, $value) = each($settingsData)) {

				$settings = settings::where('fieldName',$key)->first();
				if($key == "activatedModules"){
					$settings->fieldValue = json_encode($value);
				}elseif ($key == "officialVacationDay") {
					if(count($value) > 0){
						while (list($key_, $date) = each($value)) {
							$value[$key_] = $this->panelInit->dateToUnix($date);
						}
					}
					$settings->fieldValue = json_encode($value);
				}elseif ($key == "daysWeekOff") {
					$settings->fieldValue = json_encode($value);
				}elseif ($key == "smsProvider") {
					$settings->fieldValue = json_encode($value);
				}elseif ($key == "mailProvider") {
					$settings->fieldValue = json_encode($value);
				}else{
					$settings->fieldValue = $value;
				}
				$settings->save();

			}
			return $this->panelInit->apiOutput(true,$this->panelInit->language['editSettings'],$this->panelInit->language['settSaved']);
		}
		if($title == "terms"){
			$settings = settings::where('fieldName','schoolTerms')->first();
			$settings->fieldValue = htmlspecialchars(Input::get('fieldValue'),ENT_QUOTES);
			$settings->save();

			return $this->panelInit->apiOutput(true,$this->panelInit->language['editSettings'],$this->panelInit->language['settSaved']);
		}
	}
}
