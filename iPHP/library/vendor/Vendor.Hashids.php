<?php

defined('iPHP') OR exit('Oops, something went wrong');
defined('iPHP_LIB') OR exit('iPHP vendor need define iPHP_LIB');

iVendor::register('Hashids');

use Hashids\Hashids;

class Vendor_Hashids {
	public $instance;
	public function __construct($param=array()){
        empty($param['salt'])&& $param['salt'] = iPHP_KEY;
        empty($param['len']) && $param['len'] = 8;
    	$this->instance = new Hashids($param['salt'],$param['len']);
	}
    public function encode() {
        $numbers = func_get_args();
        return call_user_func_array(array($this->instance, 'encode'),$numbers);
    }
    public function decode($hash) {
    	return $this->instance->decode($hash);
    }
}

