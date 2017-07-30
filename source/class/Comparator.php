<?php

namespace CKBT;

abstract class Comparator
{

    /**
     * @var File[]
     */
    protected $files = array();
    protected $comparator = null;

    public function __construct()
    {

    }


    abstract function compareAll();


    public function setComparator($function)
    {
        $this->comparator = $function;
        return $this;
    }


    public function addFile(File $file)
    {
        $this->files[] = $file;
        return $this;
    }

}




