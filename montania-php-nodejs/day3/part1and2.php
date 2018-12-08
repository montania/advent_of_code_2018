<?php

// test input
$input=explode("\n",
'#1 @ 1,3: 4x4
#2 @ 3,1: 4x4
#3 @ 5,5: 2x2');

// real input
$input=explode("\n", file_get_contents('input.txt'));

$claimsquares=array();
$claimids=array();
$overlapsquares=array();
$overlapids=array();

function claimToSquares($claim){
	$claimarea=array();
	$claim=explode(' ', $claim);
	if(count($claim)===4) {
		$id=trim($claim[0], '#');
		$start=explode(",", $claim[2]);
		$size=explode('x', $claim[3]);
		$start[0]=intval($start[0]);
		$start[1]=intval($start[1]);
		$size[0]=intval($size[0]);
		$size[1]=intval($size[1]);
		for($y=1; $y <= $size[1]; $y++){
			for($x=1; $x <= $size[0]; $x++){
				$claimarea[]=($start[0] + $x) . ',' . ($start[1] + $y);
			}
		}
		return array($id, $claimarea);
	} else {
		return array();
	}
}

// Loop the input
foreach($input as $row){
	// For each claim, return array of all squares occupied by claim
	$claimresult=claimToSquares($row);
	// check for overlaps between claimed squares and existing squares
	if(count($claimresult) > 0) {
		foreach($claimresult[1] as $claimsquare){
			// Keep track of overlapped squares
			if(array_key_exists($claimsquare, $claimsquares)) {
				$overlapsquares[$claimsquare]=$claimresult[0];
				!in_array(intval($claimresult[0]), $overlapids) ? $overlapids[]=intval($claimresult[0]) :null;
				// the existing claimsquare should also be marked as overlap
				!in_array(intval($claimsquares[$claimsquare]), $overlapids) ? $overlapids[]=intval($claimsquares[$claimsquare]) :null;
			} else {
				$claimsquares[$claimsquare]=$claimresult[0];
				!in_array(intval($claimresult[0]), $claimids) ? $claimids[]=intval($claimresult[0]) :null;
			}
		}
	}

}

echo '<br>Part 1: squares with overlap ';
echo count($overlapsquares);

echo '<br><br>';
echo 'Part 2: claims without overlap <br>';
echo join(', ', array_diff($claimids, $overlapids));


