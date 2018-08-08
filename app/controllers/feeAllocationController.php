<?php

class feeAllocationController extends \BaseController {

	var $data = array();
	var $panelInit ;
	var $layout = 'dashboard';

	public function __construct(){
		$this->panelInit = new \DashboardInit();
		$this->data['panelInit'] = $this->panelInit;
		$this->data['breadcrumb']['Settings'] = \URL::to('/dashboard/languages');
		$this->data['users'] = \Auth::user();
		if($this->data['users']->role != "admin") exit;

		if(!$this->data['users']->hasThePerm('accounting')){
			exit;
		}
	}

	public function listAll()
	{
		$toReturn = array();
		$toReturn['classes'] = array();
		$toReturn['classAllocation'] = array();
		$classesIn = array();

		$classes = classes::where('classAcademicYear',$this->panelInit->selectAcYear)->select('id','className')->get();
		foreach ($classes as $class) {
			$toReturn['classes'][$class->id] = $class->className;
			$classesIn[] = $class->id;
		}

		if(count($classesIn) > 0){
			$toReturn['classAllocation'] = fee_allocation::where('allocationType','class')->whereIn('allocationId',$classesIn)->get()->toArray();
		}

		$toReturn['StudentAllocation'] = \DB::table('fee_allocation')
								->leftJoin('users', 'users.id', '=', 'fee_allocation.allocationId')
								->select('fee_allocation.id as id',
								'fee_allocation.allocationTitle as allocationTitle',
								'fee_allocation.allocationId as userId',
								'fee_allocation.allocationWhen as allocationWhen',
								'fee_allocation.allocationDate as allocationDate',
								'users.fullName as fullName')
								->where('fee_allocation.allocationType','student')->get();

		$toReturn['feeType'] = fee_type::get()->toArray();
		return $toReturn;
	}

	public function delete($id){
		if ( $postDelete = fee_allocation::where('id', $id)->first() )
        {
            $postDelete->delete();
            return $this->panelInit->apiOutput(true,$this->panelInit->language['delFeeAllocation'],$this->panelInit->language['feeAllocationDeleted']);
        }else{
            return $this->panelInit->apiOutput(false,$this->panelInit->language['delFeeAllocation'],$this->panelInit->language['feeAllocationNotExist']);
        }
	}

	public function create(){
		if(Input::get('allocationType') == "student" AND ( !is_array(Input::get('paymentStudent')) OR count(Input::get('paymentStudent')) == 0 ) ){
			return $this->panelInit->apiOutput(false,"Add fee allocation","No students selected" );
		}

		$allocationValues = Input::get('allocationValues');
		if(count($allocationValues) == 0){
			return;
		}else{
			$dbAllocationValues = array();
			foreach ($allocationValues as $allocation) {
				$dbAllocationValues[$allocation['id']] = $allocation['feeDefault'];
			}
		}

		if(Input::get('allocationType') == "student" AND count($studentsIds) > 0){
			foreach ($studentsIds as $key => $item) {
				$feeAllocation = new fee_allocation();
				$feeAllocation->allocationTitle = Input::get('allocationTitle');
				$feeAllocation->allocationType = Input::get('allocationType');
				$feeAllocation->allocationId = $item;
				$feeAllocation->allocationValues = json_encode($dbAllocationValues);
				$feeAllocation->allocationWhen = Input::get('allocationWhen');
				if(Input::has('allocationDate')){
					$feeAllocation->allocationDate = $this->panelInit->dateToUnix(Input::get('allocationDate'));
				}
				if(Input::has('allocationRecMon') AND Input::has('allocationRecDate')){
					$feeAllocation->allocationRec = Input::get('allocationRecDate').".".Input::get('allocationRecMon');
				}
				$feeAllocation->save();
			}
		}
		if(Input::get('allocationType') == "class"){
			$feeAllocation = new fee_allocation();
			$feeAllocation->allocationTitle = Input::get('allocationTitle');
			$feeAllocation->allocationType = Input::get('allocationType');
			$feeAllocation->allocationId = Input::get('allocationId');
			$feeAllocation->allocationValues = json_encode($dbAllocationValues);
			$feeAllocation->allocationWhen = Input::get('allocationWhen');
			if(Input::has('allocationDate')){
				$feeAllocation->allocationDate = $this->panelInit->dateToUnix(Input::get('allocationDate'));
			}
			if(Input::has('allocationRecMon') AND Input::has('allocationRecDate')){
				$feeAllocation->allocationRec = Input::get('allocationRecDate').".".Input::get('allocationRecMon');
			}
			$feeAllocation->save();
		}
		return $this->panelInit->apiOutput(true,$this->panelInit->language['addFeeAllocation'],$this->panelInit->language['feeAllocationAdded'] );
	}

	function fetch($id){
		$feeAllocation = fee_allocation::where('id',$id)->first()->toArray();
		if($feeAllocation['allocationType'] == "student"){
			$user = User::where('id',$feeAllocation['allocationId'])->first();
			$feeAllocation['fullName'] = $user->fullName;
		}
		$feeAllocation['allocationValues'] = json_decode($feeAllocation['allocationValues'],true);
		$feeAllocation['allocationRec'] = explode(".",$feeAllocation['allocationRec']);
		if(is_array($feeAllocation['allocationRec']) AND count($feeAllocation['allocationRec']) == 2){
			$feeAllocation['allocationRecDate'] = $feeAllocation['allocationRec'][0];
			$feeAllocation['allocationRecMon'] = $feeAllocation['allocationRec'][1];
		}
		return $feeAllocation;
	}

	function edit($id){
		$feeAllocation = fee_allocation::find($id);
		$feeAllocation->allocationTitle = Input::get('allocationTitle');
		$feeAllocation->allocationId = Input::get('allocationId');
		$feeAllocation->allocationValues = json_encode(Input::get('allocationValues'));
		$feeAllocation->allocationWhen = Input::get('allocationWhen');
		if(Input::has('allocationDate')){
			$feeAllocation->allocationDate = $this->panelInit->dateToUnix(Input::get('allocationDate'));
		}
		if(Input::has('regenerate') AND Input::get('regenerate') == "1"){
			$feeAllocation->isProcessed = 0;
		}
		if(Input::has('allocationRecMon') AND Input::has('allocationRecDate')){
			$feeAllocation->allocationRec = Input::get('allocationRecDate').".".Input::get('allocationRecMon');
		}
		$feeAllocation->save();

		return $this->panelInit->apiOutput(true,$this->panelInit->language['editFeeAllocation'],$this->panelInit->language['feeAllocationUpdated'],$feeAllocation->toArray() );
	}
}
