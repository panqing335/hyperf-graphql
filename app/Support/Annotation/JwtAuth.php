<?php


namespace App\Support\Annotation;

use App\Support\Auth\JwtAuthMiddleware;
use Doctrine\Common\Annotations\Annotation\Target;
use Hyperf\Di\Annotation\AbstractAnnotation;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * @Annotation
 * @Target({"ALL"})
 */
class JwtAuth extends AbstractAnnotation
{
    public function __construct($value = null)
    {
        parent::__construct($value);
        $this->bindMainProperty('middleware', [JwtAuthMiddleware::class]);
    }

    /**
     * 给Class追加注解
     * @param string $className
     */
    public function collectClass(string $className): void
    {
        parent::collectClass($className);
        AnnotationCollector::collectClass(
            $className,
            Middleware::class,
            new Middleware([
                'middleware' => JwtAuthMiddleware::class,
            ])
        );
    }

    /**
     * 给Method追加注解
     * @param string $className
     * @param string|null $target
     */
    public function collectMethod(string $className, ?string $target): void
    {
        parent::collectMethod($className, $target);
        AnnotationCollector::collectMethod(
            $className,
            $target,
            Middleware::class,
            new Middleware([
                'middleware' => JwtAuthMiddleware::class,
            ])
        );
    }
}
