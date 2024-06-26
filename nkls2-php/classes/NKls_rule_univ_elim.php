<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_univ_elim extends NKls_rule{
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		
		global $start;
		$startrun = microtime(true);
		
		//get lines with main operator ∀ 
		$newstructures = array();
		$lines_examined = 0;
		static $line1_min = -1;
		
		$strings_here = array();
		
		foreach($lines as $l=>$line){
			if($line1_min >= $l) continue;
			$line1_min = $l;
			
			if($line->formula()->op() != '∀') continue;
			//var_dump(NKls_formula::$constants_used);
			foreach(array_keys(NKls_formula::$constants_used) as $const){
				
				$nextf = $line->formula()->subformula1()->clone_replaced([$line->formula()->op_variable() => $const]);
				if(!$nextf) continue;
				$nextstring = $nextf->string();
				$nextstringcheck = $nextstring.'-'.implode(',',$line->base_lines());
				//var_dump($nextstringcheck);
				if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
					$newstructures[] = array(
							'formula' => $nextf,
							'base_lines' => $line->base_lines(),
							'source_lines' => array($line->line_number())
						);	
					$strings_here[$nextstringcheck] = true;	
						
					$is_target = $nextstring == $this->system->target->string() && !array_diff($line->base_lines(),$this->system->premises_base_lines());
					if($is_target){
						$newstructures = array(end($newstructures));
						break;
					}
				}	
			}
			
			$lines_examined++;
		}
		$this->system->log('E∀'.count($newstructures).'(lex '.($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;	
	}
}
?>