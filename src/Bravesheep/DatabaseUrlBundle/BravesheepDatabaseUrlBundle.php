<?php

namespace Bravesheep\DatabaseUrlBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BravesheepDatabaseUrlBundle extends Bundle
{
    /**
     * @var null|BravesheepDatabaseUrlExtension
     */
    protected $extension;

    /**
     * @return BravesheepDatabaseUrlExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new BravesheepDatabaseUrlExtension();
        }
        return $this->extension;
    }
}
