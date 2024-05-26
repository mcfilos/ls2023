<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_seq_dn extends NKls_rule{	
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		$lines_examined = 0;
		static $line1_min = -1;
		$strings_here = array();
		
		$newstructures = array();
		global $start;
		$startrun = microtime(true);
		
		
		static $subformsintrod;
		//not in forbes
		if(false && !is_array($subformsintrod)){
			$subformsintrod = array();
			foreach($subformulas as $string=>$subformula){
				if($subformula->op() == '~' && $subformula->subformula1()->op() == '~'){
					$subformsintrod[$subformula->subformula1()->subformula1()->string()] = $subformula->subformula1()->subformula1();
				}
			}
		}
		
		foreach($lines as $l=>$line){
			if($line1_min >= $l) continue;
			$line1_min = $l;
			
			//elim
			if($line->formula()->op() == '~' && $line->formula()->subformula1()->op() == '~' ){
				$newform = $line->formula()->subformula1()->subformula1();
				$nextstring = $newform->string();
				$nextstringcheck = $nextstring.'-'.implode(',',$line->base_lines());		
				
				if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
					$newstructures[] = array(
							'formula' => $newform,
							'base_lines' => $line->base_lines(),
							'source_lines' => array($line->line_number())
						);
				
				
					$strings_here[$nextstringcheck] = true;
				}
			}
			//introd - missing in forbes
			if(false &&  isset($subformsintrod[$line->line_formula()])){
				$newform = new NKls_formula(new NKls_formula($line->formula(),'~'),'~');
				$nextstring = $newform->string();
				$nextstringcheck = $nextstring.'-'.implode(',',$line->base_lines());		
			
				if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
					
					
					
					$newstructures[] = array(
							'formula' => $newform,
							'base_lines' => $line->base_lines(),
							'source_lines' => array($line->line_number())
						);
				
				
					$strings_here[$nextstringcheck] = true;
				}	
			}
			
			$lines_examined++;	
		}
		$this->system->log('DN'.count($newstructures).'(lex '.($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		
		return $error ?: $newstructures;
	}
}
?>