<?php
/**
 * User: Hossam
 * Since: 2017-09-04 2:21 PM
 */

namespace TicTacToe;

class TicTacToe implements MoveInterface
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

            $playerWinCase = new winningCase($boardState, $winCase, $playerUnit);
            if (!$playerWinCase->isBlocked()
                && (!$choosePlayerWinCase instanceof winningCase
                    || (
                        $choosePlayerWinCase instanceof winningCase
                        && $playerWinCase->countRemainingMoves() < $choosePlayerWinCase->countRemainingMoves()
                    )
                )
            ) {
                $choosePlayerWinCase = $playerWinCase;
            }
            array_push($playerWinCases, $playerWinCase);

            $opponentWinCase = new winningCase($boardState, $winCase, $opponentUnit);
            if ($opponentWinCase->countRemainingMoves() == 1 && !$opponentWinCase->isBlocked()) {
                $chooseOpponentWinCase = $opponentWinCase;
            }
            array_push($opponentWinCases, $opponentWinCase);
        }

        // Winning case
        if ($choosePlayerWinCase instanceof winningCase && $choosePlayerWinCase->countRemainingMoves() == 1) {
            if (DEBUG) echo 'Winning case:' . "\r\n";
            return array_merge($choosePlayerWinCase->getRemainingMoves()[0], [$playerUnit]);
        }

        // Block opponent winning case (to avoid loss)
        if ($chooseOpponentWinCase instanceof winningCase) {
            if (DEBUG) echo 'Block opponent winning case -> Avoid loss:' . "\r\n";
            return array_merge($chooseOpponentWinCase->getRemainingMoves()[0], [$playerUnit]);
        }

        if (DEBUG) echo 'No immediate winnning move found -> Best move:' . "\r\n";
        return array_merge($choosePlayerWinCase->getRemainingMoves()[0], [$playerUnit]);
    }

}
