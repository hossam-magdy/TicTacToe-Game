<?php

namespace TicTacToe;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TicTacToe extends Bundle implements MoveInterface
{

    static $winningCases = [
        [[0, 0], [1, 1], [2, 2]], // Diagonal 1
        [[0, 2], [1, 1], [2, 0]], // Diagonal 2
        [[0, 0], [0, 1], [0, 2]], // Row 1
        [[1, 0], [1, 1], [1, 2]], // Row 2
        [[2, 0], [2, 1], [2, 2]], // Row 3
        [[0, 0], [1, 0], [2, 0]], // Column Left
        [[0, 1], [1, 1], [2, 1]], // Column Middle
        [[0, 2], [1, 2], [2, 2]], // Column Right
    ];

    private $winner;

    private $winningCase;

    /**
     * Makes a move using the $boardState
     * $boardState contains 2 dimensional array of the game field
     * X represents one team, O - the other team, empty string means field is not yet taken.
     * example
     * [['X', 'O', '']
     * ['X', 'O', 'O']
     * ['', '', '']]
     * Returns an array, containing x and y coordinates for next move, and the unit that now occupies it.
     * Example: [2, 0, 'O'] - upper right corner - O player
     *
     * @param array $boardState Current board state
     * @param string $playerUnit Player unit representation
     *
     * @return array
     */
    public function makeMove($boardState, $playerUnit = 'X')
    {
        $opponentUnit = ($playerUnit == 'X' ? 'O' : 'X');
        $playerWinCases = array();
        $opponentWinCases = array();
        $choosePlayerWinCase = -1;
        $chooseOpponentWinCase = -1;
        foreach (self::$winningCases as $key => &$winCase) {

            $playerWinCase = new WinningCase($boardState, $winCase, $playerUnit);
            if (!$playerWinCase->isBlocked()) {
                if ($playerWinCase->countRemainingMoves() == 0) {
                    $this->winner = $playerUnit;
                    $this->winningCase = $winCase;
                    return;
                } else if (!$choosePlayerWinCase instanceof WinningCase
                    || (
                        $choosePlayerWinCase instanceof WinningCase
                        && $playerWinCase->countRemainingMoves() < $choosePlayerWinCase->countRemainingMoves()
                    )
                ) {
                    $choosePlayerWinCase = $playerWinCase;
                }
            }
            if ($playerWinCase->countRemainingMoves() > 0) {
                $playerWinCases[$playerWinCase->countRemainingMoves()] = $playerWinCase;
//                array_push($playerWinCases, $playerWinCase);
            }

            $opponentWinCase = new WinningCase($boardState, $winCase, $opponentUnit);
            if (!$opponentWinCase->isBlocked()) {
                if ($opponentWinCase->countRemainingMoves() == 0) {
                    $this->winner = $opponentUnit;
                    $this->winningCase = $winCase;
                    return;
                } else if ($opponentWinCase->countRemainingMoves() == 1) {
                    $chooseOpponentWinCase = $opponentWinCase;
                }
            }
            if ($opponentWinCase->countRemainingMoves() > 0) {
                $opponentWinCases[$opponentWinCase->countRemainingMoves()] = $opponentWinCase;
//                array_push($opponentWinCases, $opponentWinCase);
            }
        }

        // Winning case
        if ($choosePlayerWinCase instanceof WinningCase && $choosePlayerWinCase->countRemainingMoves() == 1) {
            #if (DEBUG) echo 'Winning case:' . "\r\n";
            $this->winner = $playerUnit;
            $this->winningCase = $choosePlayerWinCase->winCase;
            return array_merge($choosePlayerWinCase->getRemainingMoves()[0], [$playerUnit]);
        }

        // Block opponent winning case (to avoid loss)
        if ($chooseOpponentWinCase instanceof WinningCase && $chooseOpponentWinCase->countRemainingMoves() > 0) {
            #if (DEBUG) echo 'Block opponent winning case -> Avoid loss:' . "\r\n";
            return array_merge($chooseOpponentWinCase->getRemainingMoves()[0], [$playerUnit]);
        }

        #if (DEBUG) echo 'No immediate winnning move found -> Best move:' . "\r\n";
        if ($choosePlayerWinCase instanceof WinningCase && $choosePlayerWinCase->countRemainingMoves() > 0) {
            return array_merge($choosePlayerWinCase->getRemainingMoves()[0], [$playerUnit]);
        }

        $this->winner = 'Draw';
        return;
    }

    public function getWinner()
    {
        return $this->winner;
    }

    public function getWinningCase()
    {
        return $this->winningCase;
    }
}
