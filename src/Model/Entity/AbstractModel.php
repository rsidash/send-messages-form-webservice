<?php

namespace App\Model\Entity;

abstract class AbstractModel
{
    public abstract function toArray(): array;
}