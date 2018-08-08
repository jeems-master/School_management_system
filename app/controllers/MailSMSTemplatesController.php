<?php

class MailSMSTemplatesController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();
		if($this->data['users']->role != "admin") exit;

		if(!$this->data['users']->hasThePerm('mailsmsTemplates')){
			exit;
		}
	}

	public function listAll()
	{
		return mailsms_templates::get();
	}

	function fetch($id){
		return mailsms_templates::where('id',$id)->first();
	}

	function edit($id){
		$mailsmsTemplates = mailsms_templates::find($id);
		$mailsmsTemplates->templateMail = Input::get('templateMail');
		$mailsmsTemplates->templateSMS = Input::get('templateSMS');
		$mailsmsTemplates->save();

		return $this->panelInit->apiOutput(true,$this->panelInit->language['editTemplate'],$this->panelInit->language['templateUpdated'] );
	}
}
