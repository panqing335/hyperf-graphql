<?php


namespace App\Support\Log;


use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Monolog\Formatter\JsonFormatter;
use Psr\Http\Message\ServerRequestInterface;

class OverWriteJsonFormatter extends JsonFormatter
{
    public function format(array $record)
    {
        $record['requestId'] = $this->getRequestId();
        return parent::format($record); // TODO: Change the autogenerated stub
    }

    private function getRequestId()
    {
        if ($request =  Context::get(ServerRequestInterface::class)) {
            return $request->getAttribute(config('logger.request_id'), '');
        }
        return null;
    }
}