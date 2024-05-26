<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_line{
	protected $system;
	protected $formula = NULL;
	public $source_type = 'assumption';
	public $source_rule = NULL;
	public $source_lines = array();
	public $base_lines = array();
	public $pass_info = '';
	protected $ln  = NULL;
	
	//$source_type [premise, assumption, rule]
	public function __construct($system, $formula, $source_type, $base_lines = array(), $source_rule = NULL, $source_lines = array(), $pass_info = ''){
		
		

		$this->system = $system;
		$this->system->lines[] = $this;
		$this->pass_info = $pass_info;
		
		$this->formula = $formula;
		if(in_array($source_type,array('assumption','premise','rule'))){
			$this->source_type = $source_type;
			if($source_type == 'rule'){
				$this->source_rule = $source_rule;
				$this->source_lines = $source_lines;
				$this->base_lines = $base_lines;
			}
			
		}
		if(!$this->base_lines && in_array($source_type,array('assumption','premise'))){
			$this->base_lines = array($this->line_number());
		}
	}
	
	public function formula(){
		return $this->formula;
	}	
	
	public function line_number($from1 = false){
		$add = $from1 ? 1 : 0;
		if(!is_numeric($this->ln)){
			
			$k = array_search($this,$this->system->lines,true);
			$this->ln = $k !== false ? $k : false;
		}
		return $this->ln+$add;
	}
	
	public function line_formula(){
		return $this->formula->string();
	}
	public function base_lines(){
		return $this->base_lines;
	}	
	public function line_pass_info(){
		return $this->pass_info;
	}	
	
	
	public function line_base_lines(){
		$news = array();
		foreach($this->base_lines as $bl){
			$news[] = $bl+1;
		}
		return implode(', ',$news);
	}
	
	public function line_source_info(){
		$ret = 'As';
		if($this->source_type == 'premise'){
			$ret = 'Pr';
		}
		elseif($this->source_type == 'rule'){
			$news = array();
			foreach($this->source_lines as $bl){
				$news[] = $bl+1;
			}
						
			$ret = $this->source_rule->rule_short().' '.implode(', ',$news);
		}
		return $ret;
	}
	
}

?>