<?php

class DemoController extends BaseController
{

    public function listAction()
    {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $stringParam = $this->getUriSegments()[4];
        $fp = file_get_contents("test.php");
        $fpUnserialized = unserialize($fp);

        $result = [];
        foreach ($fpUnserialized as $car) {
            if ($this->string_contains($car['make'], $stringParam)) {
                $result[] = $car;
            }
        }

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $responseData = json_encode($result);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong.';
                $strErrorHeader = 'HTTP 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP 422 Unprocessable';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function importAction()
    {
        $fp = fopen("test.php", "w");
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                file_put_contents('test.php', serialize(json_decode(file_get_contents('php://input'), TRUE)), FILE_APPEND);
                $responseData = "imported";
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong.';
                $strErrorHeader = 'HTTP 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP 422 Unprocessable';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function string_contains($haystack, $needle)
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}
