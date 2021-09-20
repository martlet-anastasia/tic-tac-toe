<?php
$winningCombination = [
    [ 0, 1, 2 ],
    [ 3, 4, 5 ],
    [ 6, 7, 8 ],
    [ 0, 3, 6 ],
    [ 1, 4, 7 ],
    [ 2, 5, 8 ],
    [ 0, 4, 8 ],
    [ 2, 4, 6 ],
];

$currentUser = intval($_POST['currentUser']);
$userCombination = $_POST['userCombination'];
$clickedCell = $_POST['clickedCell'];

foreach ($winningCombination as $elem) {
    if(count(array_intersect($elem, $userCombination)) === 3) {

        $response = [
            "nextUserId" => null,
            "clickedCell" => $clickedCell,
            "gameFinished" => true,
            "winner" => $currentUser % 2 === 1 ? 1 : 2,
            "combination" => array_intersect($elem, $userCombination),
        ];
        echo(json_encode($response));
        exit;
    }
}

if ($currentUser === 9) {
    $response = [
        "nextUserId" => null,
        "clickedCell" => $clickedCell,
        "gameFinished" => true,
        "winner" => null,
        "combination" => null,
    ];
} else {
    $response = [
        "nextUserId" => $currentUser+1,
        "clickedCell" => $clickedCell,
        "gameFinished" => false,
        "winner" => null,
        "combination" => $userCombination,
    ];
}

echo(json_encode($response));

