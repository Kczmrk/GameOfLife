<?php

namespace App;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;

class GameOfLife
{
    //grid size
    private $height = 10;
    private $width = 10;

    public function newGame($seeds)
    {
        //check if number of seeds isn't higher than number of cells on grid
        if($seeds > $this->height * $this->width) {
            throw new \Exception("Too many seeds for that kind of grid!");
        }
        $count = 0;
        $grid = [];

        //randomize new grid
        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                if ($count < $seeds) {
                    $grid[$x][$y] = rand(0,1);
                    if($grid[$x][$y] == 1) $count++;
                } else {
                    $grid[$x][$y] = 0;
                }
            }
        }
        shuffle($grid);


        $grid = json_encode($grid);
        $this->save($grid);

        return new JsonResponse(array('grid' => $grid));
    }

    //one step
    public function tick()
    {
        //get actual state
        $grid = file_get_contents('file.json');
        $grid = json_decode($grid);

        //play and set new grid...
        $newGrid = $grid;
        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                $aliveNeighbors = $this->countAliveNeighbors($x, $y, $grid);
                if($grid[$x][$y] == 0 && $aliveNeighbors == 3) {
                    $newGrid[$x][$y] = 1;
                } else if($grid[$x][$y] == 1 && ($aliveNeighbors  <2 || $aliveNeighbors > 3)) {
                    $newGrid[$x][$y] = 0;
                } else {
                    $newGrid[$x][$y] = $grid[$x][$y];
                }
            }
        }

        //save new grid
        $newGrid = json_encode($newGrid);
        $this->save($newGrid);
        return new JsonResponse(array('grid' => $newGrid));
    }


    public function countAliveNeighbors($x, $y, $grid)
    {
        $aliveCount = 0;
        for ($x2 = $x - 1; $x2 <= $x + 1; $x2++) {
            if ($x2 < 0 || $x2 >= $this->width) {
                continue; //out of range
            }
            for ($y2 = $y - 1; $y2 <= $y + 1; $y2++) {
                if ($x2 == $x && $y2 == $y) {
                    continue;
                }
                if ($y2 < 0 || $y2 >= $this->height) {
                    continue; //out of range
                }
                if ($grid[$x2][$y2]) {
                    $aliveCount += 1;
                }
            }
        }
        return $aliveCount;
    }

    public function save($grid)
    {
        $fileSystem = new Filesystem();
        if($fileSystem->exists('file.json')) {
            $fileSystem->remove('file.json');
            $fileSystem->touch('file.json');
            $fileSystem->dumpFile('file.json', $grid);
        } else {
            $fileSystem->touch('file.json');
            $fileSystem->dumpFile('file.json', $grid);
        }
    }

}