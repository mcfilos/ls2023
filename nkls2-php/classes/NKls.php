<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls{
	public $lines = array();
	public $lines_success = array();
	public $lines_strings = array();
	public $lines_premises_nos = array();
	
	public $target = false;
	protected $rules = array();
	public $log = array();
	
	
	public function __construct(){
		spl_autoload_register(array($this,'autoload'));
		$this->rules_add();
	}
	
	public function log($string){
		$this->log[] = $string;
	}
	public function lines_success($renumber = false){
		$ret = array();
		
		if($renumber){
			$map = array();
			$k = 1;
			foreach($this->lines_success as $line){
				$map[$line->line_number()] = $k;
				$k++;
			}
			
			
			$k = 1;
			foreach($this->lines_success as $line){		
				$line_base_lines  = array();
				foreach($line->base_lines as $b){
					$line_base_lines[] = $map[$b];
				}
				
				$sl = 'As';
				if($line->source_type == 'premise'){
					$sl = 'Pr';
				}
				elseif($line->source_type == 'rule'){
					$news = array();
					foreach($line->source_lines as $bl){
						$news[] = $map[$bl];
					}
					$sl = $line->source_rule->rule_short().' '.implode(', ',$news);
				}
				
				$ret[] =(object) array(
					'line_base_lines'  => implode(', ',$line_base_lines),
					'line_number'  => $k,
					'line_formula'  => $line->line_formula(),
					'line_source_info'  => $sl,
				);
				$k++;
			} 	
		}
		return $ret;
	}
	
	public function lines_premises(){
		$ret = array();
		foreach($this->lines_premises_nos as $no){
			$ret[] = $this->lines[$no];
		}	
		return $ret;
	}
	
	public function dict(){
		static $ret;
		if(!$ret){
			$ret = new NKls_dict();
		}
		return $ret;
	}

	protected function rules_add(){
		foreach(NKls_formula::$ops as $op){
			$classe = 'NKls_rule_'.$op['short'].'_elim';
			$classi = 'NKls_rule_'.$op['short'].'_intr';
			$this->rules[] = new $classe($this);
			$this->rules[] = new $classi($this); 
		}
		foreach(NKls_rule::$seqs as $seq){
			$class = 'NKls_rule_seq_'.$seq['short'];
			$this->rules[] = new $class($this); 
		}		
	}
	
	public function premises_base_lines(){
		static $ret;
		if(!is_array($ret)){
			$ret = array();
			foreach($this->lines as $line){
				if($line->source_type == 'premise'){
					$ret = array_merge($ret,$line->base_lines()); 
				}
			}
			
			sort($ret);
			$ret = array_unique($ret);
		}
		return $ret;
	}
	
	public function premisestarget_subformulas_flat($as_objects = false){
		$ret = $this->target ? $this->target->subformulas_flat($as_objects) : array();
		
		foreach($this->lines as $line){
			if($line->source_type == 'premise'){
				$sf = $line->formula()->subformulas_flat($as_objects);
				foreach($sf as $k=>$s){
					if(!isset($ret[$k])){
						$ret[$k] = $s;
					}
				} 
			}
		}
		return $ret;
	}
	
	
	public function compute_lines_success($newline){
		$log = array($newline->line_number() => $newline);
		$sort = array($newline->line_number() => $newline->line_number());
		
		$source_lines = $newline->source_lines;
		
		while(true){
			$break = true;
			foreach($source_lines as $no){
				if(!isset($log[$no])){
					$anotherline = $this->lines[$no];
					$log[$no] = $anotherline;
					$sort[$no] = $no;
					$source_lines = array_merge($source_lines,$anotherline->source_lines);
					$break = false;
				}
			}
			if($break){
				break;
			}
		}
		
		array_multisort($sort, SORT_ASC, $log);
		
		$this->lines_success = $log;
	}
	
	
	//returns false if the combination exists, 0 if the string exists and the base lines are included in an existing one, true if it can be added
	public function lines_strings_add($string,$base_lines = array(), $add = true, $news = false){
		$ret = false;

		if(!isset($this->lines_strings[$string])){
			$ret = true;
			if($add){
				$this->lines_strings[$string] = array($base_lines);
			}
		}
		else{
			$final = false;
			foreach($this->lines_strings[$string] as $bls){
				if($bls === $base_lines){
					$ret = false;
					$final = true;
					break;
				}
				elseif(!array_diff($bls,$base_lines) && array_intersect($bls,$base_lines) ){
					$ret = 0;
					$final = true;
					break;
				}
			}
			if(!$final){
				$ret = true;
				$this->lines_strings[$string][] = $base_lines;
			}
		}
		return $ret;
	}
	
	
	public function run($verbose = true,$run_param = array()){
		//1. if there is no line or target
		$error = false;
		if(!$this->target){
			$error = 'no target';
		}
		

		if($error){
			throw new Exception($error);
		}
		
		$emptypasses_limit = 1;
		$passlog = array();
		$emptypasses = $passes =  0;
		
		//initial
		global $start;
		$is_target = false;
		$start = microtime(true);
		$already_strings = array();
		foreach($this->lines as $line){
			$already_strings[$line->formula()->string().'-'.implode(',',$line->base_lines)] = true;
			if($line->source_type == 'premise'){
				$already_strings[$line->formula()->string()] = true;
				
				$is_target = $line->formula()->string()== $this->target->string();
				if($is_target){
					$passlog[0][] = 'TARGET SUCCESS FROM PREMISE with '.memory_get_peak_usage();
					$this->compute_lines_success($line);
				}
			}
			$this->lines_strings_add($line->formula()->string(),$line->base_lines);
		}
		
		$subformulas = $this->premisestarget_subformulas_flat(true);
		
		$dn = false;
		
		$rules = $this->rules;
		foreach($this->rules as $rule){
			if(in_array(get_class($rule),array('NKls_rule_seq_dn'))){
				$dn = $rule;
				break;
			}
		}
		if($run_param == 'dn' && $dn){
			$rules = array(); 
			foreach($this->rules as $rule){
				$rules[] = $rule;
				$rules[] = $dn;		
			}
		}
		
		
		while(!$is_target){
			$passlog[$passes] = array();
			$line_no = count($this->lines);
			foreach($rules as $rule){
				$extra_param = false;
				
				if(in_array(get_class($rule),array('NKls_rule_seq_as'))){
					if($run_param == 'noas'){
						continue;
					}
					elseif($run_param == 'nc'){
						$extra_param = 'nc';
					}
					elseif($run_param == 'osf'){
						$extra_param = 'osf';
					}
				}
				
				if(microtime(true) - $start > 3600){
					$passlog[$passes+1][] = 'GIVEN UP BECAUSE 3600 seconds passed with '.memory_get_peak_usage();
					break(2);
				}
				
				$newstructures = $rule->process($this->lines, $this->target,$subformulas,$already_strings,$extra_param) ?: array();
							
				if(is_string($newstructures)){
					$passlog[$passes+1][] = 'RULE '.get_class($rule).' GIVEN UP BECAUSE '.$newstructures.' with '.memory_get_peak_usage();
					break(2);					
				}
				
				foreach($newstructures as $newstructure){
					$formula =$newstructure['formula'];
					$formstr = $formula->string();
					
					$is_assumption = !empty($newstructure['assumption']);
					$bls = $is_assumption ? array(count($this->lines)) : $newstructure['base_lines'];
					
					$formstrcheck = $formstr.'-'.implode(',',$bls);
					if(isset($already_strings[$formstrcheck])){
						continue;
					}
					$already_strings[$formstrcheck] = true;
					
					
					if(!$this->lines_strings_add($formstr, $bls,true,$newstructure)){
						continue;
					}
					
					if($is_assumption){
						$newline = $this->line_add($formula,'assumption',array(),NULL,array(),$passes);
					}
					else{
						$newline = $this->line_add($formula,'rule',$newstructure['base_lines'],$rule,$newstructure['source_lines'],$passes);
					}
					
					/*if(false && count($this->lines) > 1700){
						//gc_collect_cycles();
						var_dump('max: '.memory_get_peak_usage().' now:'.memory_get_usage());
						die;
					}*/
					
					if(!empty($newstructure['line_callback'])){
						$newstructure['line_callback']($newline);
					}
					
					$is_target = $formstr == $this->target->string() && !array_diff($newline->base_lines,$this->premises_base_lines());
					$passlog[$passes][] = 'Added '.$newline->line_number(true).': '.$formstr.' with '.memory_get_peak_usage();
					if($is_target){
						$passlog[$passes+1][] = 'TARGET SUCCESS at pass '.$passes.'  with '.memory_get_peak_usage();
						$this->compute_lines_success($newline);
						break(3);
					}
				}
			}
			if(count($passlog) > 2000){
				$passlog[] = 'ABANDONED 2000';
				break;
			}
			
			if($line_no == count($this->lines)){
				$emptypasses++;
			}
			
			if($emptypasses == $emptypasses_limit){
				$passlog[] = 'ABANDONED';
				break;
			}
			if($passes == 10){
				$passlog[] = 'ABANDONED BECAUSE 10 passes';
				break;
			}
			$passes++;
		}
		return $passlog;
	}
	
	
	public function target_set($target){
		if($fv = $target->variables_free_flat(false)){
			throw new Exception('Cannot add open formula: '.$target->string());
		}		
		$this->target = $target;
	}
	
	public function target_found(){
		return $this->line_find($this->target->string());
	}	
	
	public function line_find($string){
		$ret = false;
		foreach($this->lines as $line){
			if($string == $line->line_formula()){
				$ret = $line;
				break;
			}
		}
		return $ret;
	}
	
	public function rule_get($short){
		$ret = false;
		foreach($this->rules as $rule){
			if($rule->op_short() == $short){
				$ret = $rule;
			} 
		}
		return $ret;
	}
	
	public function  line_add($formula, $source_type, $base_lines = array(), $source_rule = NULL, $source_lines = array(),$pass_info = ''){
		if($fv = $formula->variables_free_flat(false)){
			throw new Exception('Cannot add open formula: '.$formula->string());
		}
		
		$ret = new  NKls_line($this, $formula, $source_type,$base_lines,  $source_rule, $source_lines,$pass_info);
		if($source_type === 'premise'){
			$this->lines_premises_nos[] = $ret->line_number();
		}
		return $ret;	
	}
	
	public function autoload($class_name){
		$folders = array(
						'classes/',
					);
					
		foreach($folders as $folder){
			if(file_exists($folder.$class_name . '.php')){
				require_once $folder.$class_name . '.php';
				return;
			}
		}
	}	
}
?>