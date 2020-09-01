<?php
namespace tests\jsonrpc\misc;

use extas\components\Item;

/**
 * Class MissedPkMethod
 *
 * @package tests\jsonrpc\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class MissedPkMethod extends Item
{
    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return 'missed.pk.method';
    }
}
