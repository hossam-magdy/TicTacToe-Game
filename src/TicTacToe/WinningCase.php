<?php
/**
 * User: Hossam
 * Since: 2017-09-04 3:43 PM
 */

namespace TicTacToe;


class WinningCase
{
    private $isBlocked = false;
    public $remainingMoves = array(); // in x,y order
    public $winCase;

    public function __construct($boardState, $winCase, $playerUnit)
    {
        $this->winCase = $winCase;
        foreach ($this->winCase as &$pos) {
            #$pos = json_decode($pos, true);

            if ($boardState[$pos[0]][$pos[1]] != '' && $boardState[$pos[0]][$pos[1]] != $playerUnit)
                $this->isBlocked = true;

            if ($boardState[$pos[0]][$pos[1]] == '') {
                array_push($this->remainingMoves, ([$pos[1], $pos[0]]));
            }
        }
    }

    public function getRemainingMoves()
    {
        return $this->remainingMoves;
    }

    public function countRemainingMoves()
    {
        return count($this->remainingMoves);
    }

    public function isBlocked()
    {
        return $this->isBlocked;
    }
}