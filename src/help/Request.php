<?php

namespace Src\help;

class Request
{
    
    /**
     * request param value
     *
     * @param integer $param
     * @return void
     */
    public function get_id(int $param): string 
    {
        $url_id = $_GET['url'];
        $url_id = explode("/", $url_id);
        $num = 1 + $param;
        if (isset($url_id[$num])) {
            $url_id = $url_id[$num];
            return $url_id;
        }else{
            return null;
        }
    }



}
