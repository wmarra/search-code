<?php

namespace SearchCode\Adapter;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractAdapter
{
    protected $query = "";
    protected $sort  = "score";
    protected $page  = 1;
    protected $hits  = 25;

    abstract public function parseRequest(Request $request) : AbstractAdapter;
}