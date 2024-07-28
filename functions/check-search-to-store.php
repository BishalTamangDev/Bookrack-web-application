<?php

$ineligibleSearchToStore = [
    '',
    ' ',
    'a',
    'an',
    'the',
    'of'
];

function searchEligibleToStore($searchContent)
{
    global $ineligibleSearchToStore;
    return in_array($searchContent, $ineligibleSearchToStore) ? false : true;
}