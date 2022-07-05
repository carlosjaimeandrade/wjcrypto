<?php

namespace Src\help;

class Request
{
    /**
     * request param value
     *
     * @param integer $param
     * @return string
     */
    public function getId(int $param): string 
    {
        $urlId = $_GET['url'];
        $urlId = explode("/", $urlId);
        $num = 1 + $param;
        if (isset($urlId[$num])) {
            $urlId = $urlId[$num];
            return $urlId;
        }else{
            return null;
        }
    }

}
