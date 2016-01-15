<?php
declare(strict_types = 1);
namespace DataTypes\Exception;

class IdAlreadyInCollectionException extends ObjectAlreadyInCollectionException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
