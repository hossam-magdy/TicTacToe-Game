<?php

require __DIR__ . '/../vendor/autoload.php';

define('DEBUG', true);

use TicTacToe\TicTacToe;

$boardState = [['X', 'O', ''],
    ['X', 'O', 'O'],
    ['', '', '']];
$ticTacToe = new TicTacToe();

$emptyChar = "-";
echo sprintf("Board:\r\n" .
    "%-'{$emptyChar}1s %-'{$emptyChar}1s %-'{$emptyChar}1s\r\n" .
    "%-'{$emptyChar}1s %-'{$emptyChar}1s %-'{$emptyChar}1s\r\n" .
    "%-'{$emptyChar}1s %-'{$emptyChar}1s %-'{$emptyChar}1s",
    $boardState[0][0], $boardState[0][1], $boardState[0][2],
    $boardState[1][0], $boardState[1][1], $boardState[1][2],
    $boardState[2][0], $boardState[2][1], $boardState[2][2]
);
echo("\n=================\n");
var_export($ticTacToe->makeMove($boardState));
