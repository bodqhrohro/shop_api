<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ApiController extends Controller
{
    protected function getRequestData(Request $request) : ?Object
    {
        $input = $request->getContent();
        if (!empty($input)) {
            $json = json_decode($input);
            if ($json) {
                return $json;
            } else {
                throw new HttpException(400, "Invalid JSON input");
            }
        } else {
            throw new HttpException(400, "Empty input");
        }
    }
}
