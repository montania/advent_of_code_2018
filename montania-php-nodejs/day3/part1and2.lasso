[

// test input
local('input'=
'#1 @ 1,3: 4x4
#2 @ 3,1: 4x4
#3 @ 5,5: 2x2' -> split('\n'));

// real input
local('input'=include_raw('input.txt') -> split('\n'));

local('claimsquares'=map,
	'claimids'=set,
	'overlapsquares'=map,
	'overlapids'=set);

define_tag('claimToSquares', -required='claim', -copy);
	local('claimarea'=array);
	#claim=#claim->split(' ');
	if(#claim->size==4);
		local('id'=#claim->get(1)->removeleading('#')&);
		local('start'=#claim->get(3)->split(','));
		local('size'=#claim->get(4)->split('x'));
		#start=pair(integer(#start->first) = integer(#start->second));
		#size=pair(integer(#size->first) = integer(#size->second));
		loop(#size->second);
			local('y'=loop_count);
			loop(#size->first);
				local('x'=loop_count);
				#claimarea->insert((#start->first + #x) + ',' + (#start->second + #y));
			/loop;
		/loop;
		return(pair(#id=#claimarea));
	else;
		return(array);
	/if;
/define_tag;

// Loop the input
iterate(#input, local('row'));
	// For each claim, return array of all squares occupied by claim
	local('claimresult'=claimToSquares(#row));
	// check for overlaps between claimed squares and existing squares
	if(#claimresult->size > 0);
		iterate(#claimresult->value, local('claimsquare'));
			// Keep track of overlapped squares
			if(#claimsquares>>#claimsquare);
				#overlapsquares->insert(#claimsquare=#claimresult->name);
				#overlapids->insert(integer(#claimresult->name));
				// the existing claimsquare should also be marked as overlap
				#overlapids->insert(integer(#claimsquares->find(#claimsquare)));
			else;
				#claimsquares->insert(#claimsquare=#claimresult->name);
				#claimids->insert(integer(#claimresult->name));
			/if;
		/iterate;
	/if;
	//loop_count> 100 ? loop_abort;
/iterate;

'<br>Part 1: squares with overlap ';
#overlapsquares->size;

'<br><br>';
'Part 2: claims without overlap <br>';
#claimids->difference(#overlapids)->join(', ');

]
