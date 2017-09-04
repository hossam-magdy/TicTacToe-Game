<?php
/**
 * User: Hossam
 * Since: 2017-09-04 7:37 PM
 */

namespace TicTacToe\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TicTacToe\TicTacToe;

class TTTController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
//        define('DEBUG', $this->get('kernel')->isDebug());
//
//        $boardState = [['X', 'O', ''],
//            ['X', 'O', 'O'],
//            ['', '', '']];
//        $ticTacToe = new TicTacToe();
//
//        $emptyChar = "-";
//        echo sprintf("Board:\r\n" .
//            "%-'{$emptyChar}1s %-'{$emptyChar}1s %-'{$emptyChar}1s\r\n" .
//            "%-'{$emptyChar}1s %-'{$emptyChar}1s %-'{$emptyChar}1s\r\n" .
//            "%-'{$emptyChar}1s %-'{$emptyChar}1s %-'{$emptyChar}1s",
//            $boardState[0][0], $boardState[0][1], $boardState[0][2],
//            $boardState[1][0], $boardState[1][1], $boardState[1][2],
//            $boardState[2][0], $boardState[2][1], $boardState[2][2]
//        );
//        echo("\n=================\n");
//        var_export($ticTacToe->makeMove($boardState));
        return $this->render('TicTacToe.html.twig');
    }

    /**
     * @Route("/api/makeMove", name="make_move")
     * @Method("POST")
     */
    public function makeMoveAction(Request $request)
    {
        $boardState = json_decode($request->request->get('boardState'), true);
        $playerUnit = $request->request->get('playerUnit') ? $request->request->get('playerUnit') : 'O';
        $ticTacToe = new TicTacToe();
        $array = ['move' => $ticTacToe->makeMove($boardState, $playerUnit)];
        if ($ticTacToe->getWinner()) {
            $array['winner'] = $ticTacToe->getWinner();
            $array['winningCase'] = $ticTacToe->getWinningCase();
        }
        return new JsonResponse($array);
    }

}