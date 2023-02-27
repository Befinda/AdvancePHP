<?php

namespace Beffi\advancephp\Blog\Http\Actions;

use Beffi\advancephp\Blog\Http\Request;
use Beffi\advancephp\Blog\Http\Response;

interface ActionInterface{
    public function handle(Request $request):Response;
}