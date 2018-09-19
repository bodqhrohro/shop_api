<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiController extends Controller
{
    protected const ORDER_ORIENTATION = ['ASC', 'DESC'];
    protected const DATETIME_FORMAT = '!Y-m-d';

    protected function getRequestData(Request $request) : ?Object
    {
        $input = $request->getContent();
        if (!empty($input)) {
            return json_decode($input);
        } else {
            return null;
        }
    }
}
