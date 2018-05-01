<?php
/**
 * Created by PhpStorm.
 * User: Jacob
 * Date: 4/25/18
 * Time: 11:12 PM
 */

class Game
{
    private $id;
    private $name;
    private $secretEnvelope;



    public function __construct($id, $name, $secretEnvelope)
    {
        $this->id = $id;
        $this->name = $name;
        $this->secretEnvelope = $secretEnvelope;

    }


    public function getID()
    {
        return $this->id;
    }


    public function getName()
    {
        return $this->name;
    }

    public function getSecretEnvelope()
    {
        return $this->secretEnvelope;
    }

}
