<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_conj_intr extends NKls_rule{
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		$lines_examined = 0;
		static $line1_min = -1;
		static $line2_min = -1;
		$strings_here = array();
		
		global $start;
		$startrun = microtime(true);
		
		$newstructures = array();
		
		static $newsubformulas;
		/*
		if(!is_array($newsubformulas)){
			$newsubformulas = array();
			foreach($subformulas as $string=>$subformula){
				if($subformula->op() == '&'){
					$newsubformulas[$subformula->subformula1()->string()] = $subformula->subformula1();
					$newsubformulas[$subformula->subformula2()->string()] = $subformula->subformula2();
					$newsubformulas[$string] = $subformula;
				}
			}
		}*/
		if(!is_array($newsubformulas)){
			$newsubformulas = array();
			foreach($subformulas as $string=>$subformula){
				if($subformula->op() == '&'){
					$props = array(
								$subformula->subformula1(),
								$subformula->subformula2(),
								$subformula
							);
					foreach($props as $pk=> $prop){
						if($freevars = $prop->variables_free_flat(false)){

							$newsubformulas =  array_merge($newsubformulas,$prop->mass_clone(true));
							
						}
						else{
							$newsubformulas[$prop->string()] = $prop;
						}						
					}
				}
			}
		}
		
		
		
		foreach($lines as $l=>$line){
			$str = $line->line_formula();
			if(!isset($newsubformulas[$str]) ) continue;
			
			foreach($lines as $ll=>$line2){
				if($line1_min >= $l && $line2_min >= $ll) continue;
				$line1_min = $l;
				$line2_min = $ll;
				
				$str2 = $line2->line_formula();
				if(!isset($newsubformulas[$str2]) ) continue;
					
				$newform = new NKls_formula($line->formula(),'&',$line2->formula());
				
				$nextstring = $newform->string();
			
				$bls = array_unique(array_merge($line->base_lines(),$line2->base_lines()));
				$sls = array($line->line_number(), $line2->line_number());
				sort($bls);
				sort($sls);
				$imp = implode(',',$bls);
				$nextstringcheck = $nextstring.'-'.$imp;
				
				if(isset($newsubformulas[$nextstring]) && !isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
					$newstructures[] = array(
								'formula' => $newform,
								'base_lines' => $bls,
								'source_lines' => $sls
							);
					$strings_here[$nextstringcheck] = true;	
					
					$is_target = $nextstring == $this->system->target->string() && !array_diff($bls,$this->system->premises_base_lines());
					if($is_target){
						$newstructures = array(end($newstructures));
						break(2);
					}
				}
				
				$lines_examined++;	
			}
		}
		$this->system->log('I&'.count($newstructures).'(lex '.($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;	
	}
}
?>