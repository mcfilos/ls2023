<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_dict{
	public function values(){
		$dict = array();
$dict['Forbes-exNECUN-2'] = "
A = FORMULA['A']
B = FORMULA['B']
NA = FORMULA[A,'~']
AB = FORMULA[A,'&',B]
ABI = FORMULA[A,'->',B]
ABB = FORMULA[ABI,'->',B]

PREMISE[AB]
TARGET[ABB]";



$dict['Il-p92-3'] = 
"A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
BC = FORMULA[B,'&',C]
BA = FORMULA[B,'&',A]
ABC = FORMULA[A,'&',BC]
CBA = FORMULA[C,'&',BA]

PREMISE[ABC]
TARGET[CBA]";

$dict['Il-p93-4'] = 
"A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
D = FORMULA['D']
CD = FORMULA[C,'->',D]
BCD = FORMULA[B,'->',CD]
ABCD = FORMULA[A,'->',BCD]

AB= FORMULA[A,'&',B]
CAB = FORMULA[C,'&',AB]

PREMISE[ABCD]
PREMISE[CAB]
TARGET[D]";

$dict['Il-p95-3'] = "
B = FORMULA['B']
C = FORMULA['C']
D = FORMULA['D']
CD = FORMULA[C,'->',D]
BCD = FORMULA[B,'->',CD]
BD= FORMULA[B,'->',D]

PREMISE[BCD]
PREMISE[C]
TARGET[BD]";

$dict['Il-p96-5'] = 
"A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
D = FORMULA['D']
AB = FORMULA[A,'&',B]
ABC = FORMULA[AB,'&',C]
ABCD = FORMULA[ABC,'->',D]

AD = FORMULA[A,'->',D]
BAD = FORMULA[B,'->',AD]
CBAD = FORMULA[C,'->',BAD]

PREMISE[ABCD]
TARGET[CBAD]";

$dict['Il-p98-4'] = "A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
D = FORMULA['D']

AB = FORMULA[A,'->',B]
AC = FORMULA[A,'->',C]
ABAC = FORMULA[AB,'->',AC]

BC = FORMULA[B,'->',C]
ABC = FORMULA[A,'->',BC]

PREMISE[ABC]
TARGET[ABAC]";


$dict['Il-p105-3'] = "A = FORMULA['A']
B = FORMULA['B']
NB = FORMULA[B,'~']

AB = FORMULA[A,'&',B]
NAB = FORMULA[AB,'~']

PREMISE[NB]
TARGET[NAB]";
$dict['Il-p105-2-3'] = "A = FORMULA['A']
B = FORMULA['B']
NB = FORMULA[B,'~']
AiB = FORMULA[A,'->',NB]

AB = FORMULA[A,'&',B]
NAB = FORMULA[AB,'~']

PREMISE[NAB]
TARGET[AiB]";


$dict['Ex-p108-1-3'] ="B = FORMULA['B']
A = FORMULA['A']
NA = FORMULA[A,'~']
NB = FORMULA[B,'~']
ANB = FORMULA[A,'->',NB]
BNA = FORMULA[B,'->',NA]
PREMISE[ANB]
TARGET[BNA]";

$dict['Ex-p108-18-4'] ="B = FORMULA['B']
A = FORMULA['A']
NA = FORMULA[A,'~']
NB = FORMULA[B,'~']
ANB = FORMULA[A,'&',NB]
ANBNA = FORMULA[ANB,'->',NA]
AB = FORMULA[A,'->',B]
PREMISE[ANBNA]
TARGET[AB]";
$dict['Ex-p108-19-2'] ="L = FORMULA['λ']
NL = FORMULA[L,'~']
TARGET[NL]";
$dict['Ex-p108-20-1-2'] ="L = FORMULA['λ']
A = FORMULA['A']
NA = FORMULA[A,'~']
AL = FORMULA[A,'->',L]
PREMISE[NA]
TARGET[AL]";
$dict['Ex-p108-20-2-3'] ="L = FORMULA['λ']
A = FORMULA['A']
NA = FORMULA[A,'~']
AL = FORMULA[A,'->',L]
PREMISE[AL]
TARGET[NA]";
$dict['Ex-p108-8-4'] ="B = FORMULA['B']
A = FORMULA['A']
NA = FORMULA[A,'~']
ANA = FORMULA[A,'&',NA]
ANAB = FORMULA[ANA,'->',B]
TARGET[ANAB]";

$dict['NegImp-Ex-p108-22-1-5'] = "
A = FORMULA['A']
B = FORMULA['B']
nB = FORMULA[B,'~']
ABI = FORMULA[A,'->',B]
NABI = FORMULA[ABI,'~']
ANB = FORMULA[A,'&',nB]

PREMISE[NABI]
TARGET[ANB]";

$dict['NegImp-Ex-p108-22-2-3'] = "
A = FORMULA['A']
B = FORMULA['B']
nB = FORMULA[B,'~']
ABI = FORMULA[A,'->',B]
NABI = FORMULA[ABI,'~']
ANB = FORMULA[A,'&',nB]

PREMISE[ANB]
TARGET[NABI]";

$dict['Ex-p108-24-4'] ="A = FORMULA['A']
B = FORMULA['B']
NA = FORMULA[A,'~']
NB = FORMULA[B,'~']
L = FORMULA['λ']

AB = FORMULA[A,'&',B]
NAB = FORMULA[AB,'~']
ANA = FORMULA[A,'->',NA]
NANA = FORMULA[ANA,'~']
BNB = FORMULA[B,'->',NB]
NBNB = FORMULA[BNB,'~']


PREMISE[NANA]
PREMISE[NBNB]
PREMISE[NAB]
TARGET[L]";

$dict['PBLUNG-Ex-p108-26-6'] ="A = FORMULA['A']
B = FORMULA['B']
NA = FORMULA[A,'~']
NB = FORMULA[B,'~']

AB = FORMULA[A,'&',B]
AAB = FORMULA[NA,'&',B]
AABB = FORMULA[NA,'&',NB]
NAAB = FORMULA[AAB,'~']
NAABB = FORMULA[AABB,'~']
NAABNAABB = FORMULA[NAAB,'&',NAABB]


NAB = FORMULA[AB,'~']

ANB = FORMULA[A,'&',NB]
NANB = FORMULA[ANB,'~']
NABNANB  = FORMULA[NAB,'&',NANB]

FIN = FORMULA[NABNANB,'&',NAABNAABB]
NFIN = FORMULA[FIN,'~']
TARGET[NFIN]";

$dict['Il-p109-2'] ="A = FORMULA['A']
B = FORMULA['B']
D = FORMULA['D']
E = FORMULA['E']
AB = FORMULA[A,'->',B]
DE = FORMULA[D,'&',E]
ADE = FORMULA[A,'V',DE]
ADEB = FORMULA[ADE,'->',B]

PREMISE[ADEB]
TARGET[AB]";
$dict['Il-p111-0'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']

AB = FORMULA[A,'&',B]
AC = FORMULA[A,'&',C]
ABAC = FORMULA[AB,'V',AC]

PREMISE[ABAC]
TARGET[A]";
$dict['PBDN-Il-p112-2'] ="A = FORMULA['A']
B = FORMULA['B']
AB = FORMULA[A,'V',B]
NB = FORMULA[B,'~']

PREMISE[AB]
PREMISE[NB]
TARGET[A]";
$dict['Il-p114-3'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']

AB = FORMULA[A,'&',B]
AC = FORMULA[A,'&',C]
BC = FORMULA[B,'V',C]
ABC = FORMULA[A,'&',BC]
ABAC = FORMULA[AB,'V',AC]

PREMISE[ABC]
TARGET[ABAC]";
$dict['DEM-Il-p115-4'] ="A = FORMULA['A']
B = FORMULA['B']
NA = FORMULA[A,'~']
NB = FORMULA[B,'~']

NANB = FORMULA[NA,'&',NB]
AB = FORMULA[A,'V',B]
NAB = FORMULA[AB,'~']

PREMISE[NANB]
TARGET[NAB]";
$dict['PBAS-Il-p115-4'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
AB = FORMULA[A,'V',B]
BC = FORMULA[B,'V',C]
ABC = FORMULA[A,'V',BC]
AdBC = FORMULA[AB,'V',C]

PREMISE[ABC]
TARGET[AdBC]";
$dict['LEM-Il-p116-4'] ="A = FORMULA['A']
NA = FORMULA[A,'~']
ANA = FORMULA[A,'V',NA]

TARGET[ANA]";
$dict['NC'] ="A = FORMULA['A']
NA = FORMULA[A,'~']
ANA = FORMULA[A,'&',NA]
NANA = FORMULA[ANA,'~']

TARGET[NANA]";
$dict['Ex-p116-4-3'] ="A = FORMULA['A']
B = FORMULA['B']
NB = FORMULA[B,'~']
NNB = FORMULA[NB,'~']
ANNB = FORMULA[A,'V',NNB]
AB = FORMULA[A,'V',B]

TARGET[AB]
PREMISE[ANNB]";
$dict['Ex-p116-10-3'] ="A = FORMULA['A']
B = FORMULA['B']
NA = FORMULA[A,'~']
AB = FORMULA[A,'V',B]
ABI = FORMULA[A,'->',B]
ABB = FORMULA[ABI,'->',B]

PREMISE[AB]
TARGET[ABB]";
$dict['DEM-Ex-p116-13-3'] ="A = FORMULA['A']
B = FORMULA['B']
NA = FORMULA[A,'~']
NB = FORMULA[B,'~']

NANB = FORMULA[NA,'&',NB]
NNANB = FORMULA[NANB,'~']
AB = FORMULA[A,'V',B]

PREMISE[AB]
TARGET[NNANB]";
$dict['nDEM-3'] ="A = FORMULA['A']
B = FORMULA['B']
NA = FORMULA[A,'~']
NB = FORMULA[B,'~']

NANB = FORMULA[NA,'V',NB]
AB = FORMULA[A,'&',B]
NAB = FORMULA[AB,'~']

TARGET[NAB]
PREMISE[NANB]";
$dict['PBNC-Ex-p116-2-4'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
D = FORMULA['D']
NA = FORMULA[A,'~']

AB = FORMULA[A,'->',B]
BC = FORMULA[B,'V',C]
BCD = FORMULA[BC,'->',D]
DNA = FORMULA[D,'->',NA]

PREMISE[AB]
PREMISE[BCD]
PREMISE[DNA]
TARGET[NA]
";
$dict['PBNC-Ex-p116-11-3'] ="A = FORMULA['A']
NA = FORMULA[A,'~']
B = FORMULA['B']
L = FORMULA['λ']
AL = FORMULA[A,'->',L]
BL = FORMULA[B,'->',L]
ALBL = FORMULA[AL,'V',BL]

PREMISE[ALBL]
PREMISE[B]
TARGET[NA]
";

$dict['Imp-Ex-p116-17-5'] ="A = FORMULA['A']
NA = FORMULA[A,'~']
B = FORMULA['B']
AB = FORMULA[A,'->',B]
NAB = FORMULA[NA,'V',B]

PREMISE[AB]
TARGET[NAB]
";
$dict['PBLUNG-Ex-p116-21-4'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
AB = FORMULA[A,'V',B]
AC = FORMULA[A,'V',C]
BC = FORMULA[B,'&',C]
NAB = FORMULA[AB,'~']
NAC = FORMULA[AC,'~']
NABNAC = FORMULA[NAB,'V',NAC]
ABC = FORMULA[A,'V',BC]
NABC = FORMULA[ABC,'~']


PREMISE[NABC]
TARGET[NABNAC]
";

$dict['PBLUNG-SIIMILAR-Ex-p116-21-4'] ="
A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
NB = FORMULA[B,'~']
NC = FORMULA[C,'~']
D = FORMULA['D']
ND = FORMULA[D,'~']
NBNC = FORMULA[NB,'V',NC]
ANBNC = FORMULA[A,'->',NBNC]
NCND = FORMULA[NC,'->',ND]


PREMISE[ANBNC]
PREMISE[NCND]
PREMISE[B]
TARGET[B]";



$dict['PBMAIUSORASID-Ex-p116-22-5'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
NA = FORMULA[A,'~']
NB = FORMULA[B,'~']
NC = FORMULA[C,'~']
D = FORMULA['D']
ND = FORMULA[D,'~']
NBNC = FORMULA[NB,'V',NC]
ANBNC = FORMULA[A,'->',NBNC]
NCND = FORMULA[NC,'->',ND]
NAND = FORMULA[NA,'V',ND]

PREMISE[ANBNC]
PREMISE[NCND]
PREMISE[B]
TARGET[NAND]
";
$dict['Il-p118-0'] ="A = FORMULA['A']
B = FORMULA['B']
NB = FORMULA[B,'~']
ANB = FORMULA[A,'<->',NB]
AB = FORMULA[A,'<->',B]
NAB = FORMULA[AB,'~']

PREMISE[ANB]
TARGET[NAB]
";
$dict['Ex-p119-4'] ="A = FORMULA['A']
B = FORMULA['B']
AB = FORMULA[A,'V',B]
ABA = FORMULA[AB,'<->',A]
BA = FORMULA[B,'->',A]

PREMISE[ABA]
TARGET[BA]
";
$dict['Ex-p119-10'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
AB = FORMULA[A,'V',B]
ABC = FORMULA[AB,'V',C]
BC = FORMULA[B,'<->',C]
CA = FORMULA[C,'V',A]

PREMISE[ABC]
PREMISE[BC]
TARGET[CA]
";
$dict['PBLEM-p125-4'] ="A = FORMULA['A']
B = FORMULA['B']
AB = FORMULA[A,'->',B]
BA = FORMULA[B,'->',A]
ABA = FORMULA[AB,'V',BA]

TARGET[ABA]
";
$dict['Ex-p127-4-4'] ="A = FORMULA['A']
B = FORMULA['B']
NB = FORMULA[B,'~']
NBA = FORMULA[NB,'->',A]
BA = FORMULA[B,'->',A]
BAA = FORMULA[BA,'->',A]

PREMISE[NBA]
TARGET[BAA]
";
$dict['LUNG-Ex-p127-8-4'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
AB = FORMULA[A,'V',B]
AC = FORMULA[A,'V',C]
ABAC = FORMULA[AB,'->',AC]

BC = FORMULA[B,'->',C]
ABC = FORMULA[A,'V',BC]
PREMISE[ABAC]
TARGET[ABC]
";
$dict['LUNG-Ex-p128-13-0'] ="A = FORMULA['A']
B = FORMULA['B']
C = FORMULA['C']
D = FORMULA['D']


AB = FORMULA[A,'<->',B]
CD = FORMULA[C,'<->',D]
AC = FORMULA[A,'<->',C]
BD = FORMULA[B,'<->',D]
ABCD = FORMULA[AB,'<->',CD]
ACBD = FORMULA[AC,'<->',BD]


PREMISE[ABCD]
TARGET[ACBD]";
$dict['Ex-p127-3-4'] ="A = FORMULA['A']
B = FORMULA['B']
AB = FORMULA[A,'->',B]
AAB = FORMULA[A,'V',AB]
TARGET[AAB]
";

$dict['MONPRED-E1'] ="Px = FORMULA['Px']
APx = FORMULA[Px,'Ax']
Pa = FORMULA['Pa']
PREMISE[APx]
TARGET[Pa]
";
$dict['MONPRED-E2'] ="Px = FORMULA['Px']
Py = FORMULA['Py']
Conj = FORMULA[Px,'&',Py]
AConj = FORMULA[Conj,'Ax']
EAConj = FORMULA[AConj,'Ey']
Pr = FORMULA['Pr']
PREMISE[EAConj]
TARGET[Pr]
";
$dict['MONPRED-E3'] ="Px = FORMULA['Px']
Py = FORMULA['Py']
Conj = FORMULA[Px,'&',Py]
AConj = FORMULA[Conj,'Ax']
AAConj = FORMULA[AConj,'Ay']
Pr = FORMULA['Pr']
PREMISE[AAConj]
TARGET[Pr]
";
$dict['MONPRED-E4'] ="Px = FORMULA['Px']
Py = FORMULA['Py']
Conj = FORMULA[Px,'&',Py]
AConj = FORMULA[Conj,'Ax']
AAConj = FORMULA[AConj,'Ay']
EPy = FORMULA[Py,'Ey']
PREMISE[AAConj]
TARGET[EPy]
";
$dict['MONPRED-E5'] ="Px = FORMULA['Px']
Py = FORMULA['Py']
Pz = FORMULA['Pz']
Conj = FORMULA[Px,'&',Py]
AConj = FORMULA[Conj,'Ax']
AAConj = FORMULA[AConj,'Ay']
EPz = FORMULA[Pz,'Ez']
PREMISE[AAConj]
TARGET[EPz]
";
$dict['MONPRED-E6'] ="Px = FORMULA['Px']
Py = FORMULA['Py']
Pz = FORMULA['Pz']
Pt = FORMULA['Pt']
DP2 = FORMULA[Pz,'V',Pt]
Conj = FORMULA[Px,'&',Py]
AConj = FORMULA[Conj,'Ax']
AAConj = FORMULA[AConj,'Ay']
EPz = FORMULA[DP2,'Ez']
EEPz = FORMULA[EPz,'Et']
PREMISE[AAConj]
TARGET[EEPz]
";
$dict['MCNotSem12-4.1-2'] ="Px = FORMULA['Px']
Rx = FORMULA['Rx']
Conj = FORMULA[Px,'&',Rx]
EConj = FORMULA[Conj,'Ex']

EPx = FORMULA[Px,'Ex']

PREMISE[EConj]
TARGET[EPx]
";
$dict['MCNotSem12-3.2(2)-1'] ="Px = FORMULA['Px']
APx = FORMULA[Px,'Ax']

Pz = FORMULA['Pz']
Py = FORMULA['Py']
Conj = FORMULA[Py,'&',Pz]
Conj2 = FORMULA[Px,'&',Conj]
AConj = FORMULA[Conj2,'Ez']
AConj2 = FORMULA[AConj,'Ey']
AConj3 = FORMULA[AConj2,'Ex']


PREMISE[APx]
TARGET[AConj3]
";
$dict['MCNotSem12-4.1(2)-2'] ="Px = FORMULA['Px']
Py = FORMULA['Py']
EPx = FORMULA[Px,'Ex']
Conj = FORMULA[Px,'&',Py]
EConj = FORMULA[Conj,'Ey']
EConj2 = FORMULA[EConj,'Ex']

PREMISE[EPx]
TARGET[EConj2]
";


$dict['MONPRED-10'] ="Px = FORMULA['Px']
Py = FORMULA['Py']
Conj = FORMULA[Px,'&',Py]
EPx = FORMULA[Px,'Ex']
EPy = FORMULA[Conj,'Ey']
EPy2 = FORMULA[EPy,'Ex']

PREMISE[EPx]
TARGET[EPy2]
";




		return $dict;
	}
	
	public function parse($input){
		$strings = explode("\n",$input);
		$errors = array();
		$codes = array();
		
		$lines = $variables = array();
		$has_target = false;
		foreach($strings as $k=>$string){
			$string = str_replace(array('(',')',';'),' ', trim($string,"\r ;.\t"));
			if(substr_count($string,"[") != 1 || substr_count($string,"]")!= 1) continue;
			
			$is_premise =  substr($string,0,8) == 'PREMISE[' && substr($string,-1) == ']' ; 
			$is_target =  substr($string,0,7) == 'TARGET[' && substr($string,-1) == ']'; 
			$pcs = explode('=',$string);
			foreach($pcs as &$pc){
				$pc = str_replace(array('(',')',';'),' ', trim($pc,"\r ;.\t"));
			}
			$is_formula = count($pcs) == 2 && substr($pcs[1],0,8) == 'FORMULA[' && substr($pcs[1],-1) == ']' 
						 && in_array(substr_count($pcs[1],"'"),array(0, 2),true) ;
			$is_formula_atomic = $is_formula && substr($pcs[1],8,1) == "'"&& substr($pcs[1],-2,1) == "'" && substr_count($pcs[1],"'") == 2;
			
			if($is_target){
				$has_target =  true;
			}
			
			if($is_premise || $is_target || $is_formula){
				$php = '';
				$subs = array();
				if($is_formula){
					
					usort($variables, function($a, $b){
						return strlen($a) < strlen($b);
					});
					
					if(!$is_formula_atomic){
						$subs = explode('[',$pcs[1]);
						
						foreach($variables as $v=>$var){
							$subs[1] = str_replace($var,'|'.$v, $subs[1]);
						}
						
						foreach(array_reverse($variables,true) as $v=>$var){
							$subs[1] = str_replace('|'.$v,'$'.$var, $subs[1]);
						}
						$pcs[1] = implode('[',$subs);
						
					}
					
					$php = '$'.$pcs[0].' = '.str_replace(array('FORMULA','[',']'),array('new NKls_formula','(',')'),$pcs[1]);
					$variables[] = $pcs[0];
				}
				elseif($is_premise){
					$php = str_replace(array('PREMISE[',']'),array('$system->line_add($',',"premise")'),$string);
				}
				elseif($is_target){
					$php = str_replace(array('TARGET[',']'),array('$system->target_set($',')'),$string);
				}
				
				$lines[] = array(
						'string' => $string,
						'type' => $is_premise ? 'premise' : ($is_target ? 'target' : 'formula'),
						'pieces' => $is_formula ? $pcs : array(),
						'php' => $php
					);
				$codes[] = $php.';';
			}
		}
		
		if(!$has_target){
			$errors[] = 'No conclusion set';
		}
		
		return array(implode("\n",$codes),$errors);
		
	}
}

?>