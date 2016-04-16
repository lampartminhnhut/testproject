<?php
class indexController extends baseController {

	public function index($arg = array()){
	    $a = 5;
	    $b = 6;
	    if($a > $b && $a == 1){
	        echo 'echo ra cai echo ';
	    }
	    return;
	}
}
?>