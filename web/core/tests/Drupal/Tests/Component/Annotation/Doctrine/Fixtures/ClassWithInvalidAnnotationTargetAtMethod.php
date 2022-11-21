<?php

namespace Drupal\Tests\Component\Annotation\Doctrine\Fixtures;

/**
 * @AnnotationTargetClass("Some data")
 */
class ClassWithInvalidAnnotationTargetAtMethod
{

    /**
     * @AnnotationTargetClass("functionName")
     */
    public function functionName($param)
    {

    }
}
