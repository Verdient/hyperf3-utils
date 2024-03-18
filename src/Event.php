<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Utils;

use Hyperf\Context\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * 事件
 * @author Verdient。
 */
class Event
{
    /**
     * 触发事件
     * @param object $event 事件
     * @return object
     * @author Verdeint。
     */
    public static function trigger(object $event)
    {
        if (ApplicationContext::hasContainer()) {
            /** @var EventDispatcherInterface */
            $eventDispatcher = ApplicationContext::getContainer()->get(EventDispatcherInterface::class);
            return $eventDispatcher->dispatch($event);
        }
        return $event;
    }
}
