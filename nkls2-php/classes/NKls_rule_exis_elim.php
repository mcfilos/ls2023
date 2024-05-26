<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_exis_elim extends NKls_rule{

	protected $assumptions = array();
	protected $exislines = array();
	
	
	public function line_back($line,$index,$const){
		if(isset($this->assumptions[$index])){
			$this->assumptions[$index][] = $line->line_number();
			$this->assumptions[$index][] = $const;
			
		}
	}
	
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		$lines_examined = array();
		$exis_examined = 0;
		static $line1_min = -1;
		static $line2_min = -1;
		static $line_as = -1;
		
		
		global $start;
		$startrun = microtime(true);
		$newstructures = array();
		$strings_here = array();
		
		
		if($this->assumptions ){
			foreach($lines as $l=>$line){


				foreach($lines as $ll=>$line2){
					$consts = $line2->formula()->constants_flat(false);
					
					if($l == $ll || ($line1_min >= $l && $line2_min >= $ll)) continue;
					
					//same formula and not premises
					if($line2->source_type=='premise'|| $line2->source_type=='assumption') continue;
					
					//one of the two should rely on an assumption
					$indexes = array();
					foreach($this->assumptions as $index=>$pair){
						$cond1 = in_array($pair[0],$line2->base_lines);
						$cond2 = !in_array($pair[1],$consts);
						if($cond1 && $cond2){
							$indexes[] = $index;		
						}
					}	
					if(!$indexes) {
						//die;
					}
					
					
					//avoid duplicates
					$ind = $l < $ll ? $l.'-'.$ll : $ll.'-'.$l;
					if(isset($lines_examined[$ind])) continue;
					$lines_examined[$ind] = true; 
					
					$newforms = $nextstrings = array();
					foreach($indexes as $index){
						$bls = array_unique(
									array_merge(
										array_diff($line2->base_lines(),array($this->assumptions[$index][0])),
										$this->exislines[$index]->base_lines
									)	
								);
						$sls = array($index,$this->assumptions[$index][0],$line2->line_number());
						sort($bls);
						sort($sls);
					
						$nextstring = $line2->line_formula();
						$nextstringcheck = $nextstring.'-'.implode(',',$bls);				
					
						if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
							$newstructures[] = array(
										'formula' => $line2->formula(),
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
					}
				}

				//ACUM la EXIST
				break;

			}		
		}
		
		
		
		$obj = $this;
		foreach($lines as $l=>$line){
			if($line_as >= $l) continue;
			$line_as = $l;
			
			if($line->formula()->op() != '∃' || strpos($line->line_source_info(),'I∃') ===0) continue;
			foreach(array_keys(NKls_formula::$constants_used) as $const){
				$nextf = $line->formula()->subformula1()->clone_replaced([$line->formula()->op_variable() => $const]);
				$nextstring = $nextf->string();
				$this->assumptions[$l] = array();
				$this->exislines[$l] = $line;
			
				if(true || !isset($already_strings[$nextstring])){
					$newstructures[] = array(
							'formula' => $nextf,
							'base_lines' => $line->base_lines(),
							'source_lines' => array($line->line_number()),
							'assumption' => true,
							'line_callback' => function($line) use($obj,$l,$const){
												$obj->line_back($line,$l,$const);
										}
						);	
						
				}
			}	
				
			$exis_examined++;
		}
		
		
		$this->system->log('E∃'.count($newstructures).'(lex '.count($lines_examined).','.$exis_examined.'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;	
	}
}
?>