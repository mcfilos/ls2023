<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_equiv_intr extends NKls_rule{
	
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		global $start;
		$startrun = microtime(true);
		$lines_examined = 0;
		static $line1_min = -1;
		static $line2_min = -1;
		$strings_here = array();
		
		$newstructures = array();
		static $newsubformulas;

		if(!is_array($newsubformulas)){
			$newsubformulas = array();
			foreach($subformulas as $string=>$subformula){
				if($subformula->op() == '<->'){
					$props = array(
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
			if($line->formula()->op() != '->') continue;
			
			foreach($lines as $ll=>$line2){
				if($line1_min >= $l && $line2_min >= $ll) continue;
				$line1_min = $l;
				$line2_min = $ll;
				
				if($line2->formula()->op() != '->') continue;
				
				
				if($line->formula()->subformula1()->string() == $line2->formula()->subformula2()->string()
					&& $line->formula()->subformula2()->string() == $line2->formula()->subformula1()->string() ) {
						
					$newform = new NKls_formula($line->formula()->subformula1(),'<->',$line->formula()->subformula2());
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
					}
				}
				$lines_examined++;	
			}
		}
		$this->system->log('I<->'.count($newstructures).'(lex '.($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;	
	}
}

?>