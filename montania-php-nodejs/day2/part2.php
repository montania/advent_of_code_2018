<?php
$input = file_get_contents('input.json');
$input = json_decode($input);
$result = '';
$strings = [];
$currentDiff = 100;
foreach ($input as $value){
    foreach ($input as $item){
        $diff = findDiff($value, $item);
        $diffCount = count($diff);
        if($diffCount < $currentDiff && $diff){
            $result = compareStrings($diff,$value, $item);
            $currentDiff = $diffCount;
        }
    }
}
echo 'The most correct boxes are ' . $result;

function compareStrings($diff, $string1, $string2){
    $string1 = str_split($string1);
    $string2 = str_split($string2);
    foreach ($diff as $key => $value){
        unset($string1[$key]);
        unset($string2[$key]);
    }
    $string1 = implode('', $string1);
    $string2 = implode('', $string2);

    if($string1 === $string2){
        return $string1;
    }
    return '';
}

function findDiff($string1, $string2){
    $string1 = str_split($string1);
    $string2 = str_split($string2);
    return array_diff_assoc($string1,$string2);
}