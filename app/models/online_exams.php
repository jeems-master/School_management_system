<?php
class online_exams extends Eloquent {
	public $timestamps = false;
	protected $table = 'online_exams';

    public function passages(){
        return $this->hasMany('online_exams_passages', 'examId', 'id');
    }
}
