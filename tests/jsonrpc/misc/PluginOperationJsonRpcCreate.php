<?php
namespace tests\jsonrpc\misc;

use extas\components\http\THasHttpIO;
use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\stages\IStageOperationJsonRpcCreate;

/**
 * Class PluginOperationJsonRpcCreate
 *
 * @package tests\jsonrpc\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginOperationJsonRpcCreate extends Plugin implements IStageOperationJsonRpcCreate
{
    use THasHttpIO;

    /**
     * @param IItem $item
     * @return IItem
     */
    public function __invoke(IItem $item): IItem
    {
        $item['test'] = 'is ok';

        return $item;
    }
}
