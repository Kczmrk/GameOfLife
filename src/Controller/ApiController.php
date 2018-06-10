<?php

namespace App\Controller;

use App\GameOfLife;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ApiController
{
    /**
     * @Route("/new/{seeds}", requirements={"seeds"="\d+"})
     */
    public function newGame($seeds)
    {
        $gameApp = new GameOfLife();

        return $gameApp->newGame($seeds);
    }

    /**
     * @Route("/tick")
     */
    public function tick()
    {
        $gameApp = new GameOfLife();

        return $gameApp->tick();
    }
}