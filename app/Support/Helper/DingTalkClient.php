<?php


namespace App\Support\Helper;


use DingNotice\DingTalk;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class DingTalkClient
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function creat()
    {
        $config = $this->container->get(ConfigInterface::class);
        return $this->container->make(DingTalk::class, [$config->get('ding')]);
    }
}