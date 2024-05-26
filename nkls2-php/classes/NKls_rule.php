<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
abstract class NKls_rule{	
	
	public static $seqs = array(
					'dn' => array('key' => 'dn', 'short' => 'dn', 'name' => 'DN'),
					'as' => array('key' => 'as', 'short' => 'as', 'name' => 'AS'),
				);				
	
	public function __construct($system){
		$this->system = $system;
	}
	
	
	public static function seq_by_short($short){
		$ret = false;
		foreach(static::$seqs as $op){
			if($op['short'] == $short){
				$ret = $op;
				break;
			}
		}
		return $ret;
	}	
	
	public function op(){
		$ret = false;
		$cs = explode('_',get_called_class());
		if(count($cs) == 4){
			$ret = NKls_formula::op_by_short($cs[2]);
		}
		return $ret;
	}
	
	public function seq(){
		$ret = false;
		$cs = explode('_',get_called_class());
		if(count($cs) == 4){
			$ret = static::seq_by_short($cs[3]);
		}
		return $ret;
	}	
	
	public function op_name(){
		$ret = false;
		if($op = $this->op()){
			$ret = ($this->is_elim() ? 'Elimination' : 'Introduction').' of '.$op['name'];
		}
		return $ret;
	}
	
	public function rule_short(){
		$ret = false;
		
		if($op = $this->op()){
			$ret = ($this->is_elim() ? 'E'.$op['key'] : 'I'.$op['key']);
		}
		elseif($seq = $this->seq()){
			$ret = $seq['name'];
		}
		return $ret;
	}
	
	public function op_type(){
		$cs = explode('_',get_called_class());
		if(count($cs) == 4){
			$ret = $cs[3];
		}
		return $ret;
	}
	
	public function op_type_short(){
		$ret = $this->op_type() == 'intr' ? 'I' : ($this->type() == 'elim' ?  'E' : false);
		return $ret;
	}
	
	public function is_elim(){
		return $this->op_type() == 'elim';
	}	
	
	public function is_introd(){
		return $this->op_type() == 'intr';
	}
	

	//returns new lines
	abstract public function process(&$lines, &$target, &$subformulas, &$already_strings);
	
	
}


?>