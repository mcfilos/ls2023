<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_equiv_elim extends NKls_rule{
		
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		//get lines with main operator & 
		$newstructures = array();
		$lines_examined = 0;
		static $line1_min = -1;
		global $start;
		$startrun = microtime(true);
		$strings_here = array();
		
		foreach($lines as $l=>$line){
			if($line1_min >= $l) continue;
			$line1_min = $l;
			
			if($line->formula()->op() != '<->' ) continue;	
			
			
			$newform = new NKls_formula($line->formula()->subformula1(),'->',$line->formula()->subformula2());
			$newform2 = new NKls_formula($line->formula()->subformula2(),'->',$line->formula()->subformula1());
			
			$nextstring = $newform->string();
			$nextstring2 = $newform2->string();
			
			$nextstringcheck = $nextstring.'-'.implode(',',$line->base_lines());
			$nextstringcheck2 = $nextstring2.'-'.implode(',',$line->base_lines());			
			
			if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
				$newstructures[] = array(
							'formula' => $newform,
							'base_lines' => $line->base_lines(),
							'source_lines' => array($line->line_number())
						);	
				$strings_here[$nextstringcheck] = true;	
			}
			
			if(!isset($already_strings[$nextstringcheck2]) &&!isset($already_strings[$nextstring2]) && !isset($strings_here[$nextstringcheck2])){			
				$newstructures[] = array(
							'formula' => $newform2,
							'base_lines' => $line->base_lines(),
							'source_lines' => array($line->line_number())
						);		
				$strings_here[$nextstringcheck2] = true;	
			}
			$lines_examined++;	
		}
		$this->system->log('E<->'.count($newstructures).'(lex '.($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;
	}
	
}

?>