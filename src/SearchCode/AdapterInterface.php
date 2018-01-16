<?php

namespace SearchCode;

interface AdapterInterface
{
    public function configure() : AdapterInterface;
    public function authenticate() : AdapterInterface;
    public function searchCode() : Array;
}