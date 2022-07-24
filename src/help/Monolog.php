<?php

namespace Src\help;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Monolog
{

    public function logger(string $mesage, string $mode = "info")
    {
        $logger = new Logger('logs');
      
        $logger->pushHandler(new StreamHandler(dirname(__FILE__) . "../../../logs.txt"));
        switch ($mode) {
            case 'warning':
                $logger->warning($mesage);
                break;
            case 'error':
                $logger->error($mesage);
                break;
            case 'debug':
                $logger->error($mesage);
                break;
            case 'notice':
                $logger->error($mesage);
                break;
            case 'critical':
                $logger->error($mesage);
                break;
            case 'alert':
                $logger->error($mesage);
                break;
            case 'emergency':
                $logger->error($mesage);
                break;
            default:
                $logger->info($mesage);
                break;
        }
    }
}
