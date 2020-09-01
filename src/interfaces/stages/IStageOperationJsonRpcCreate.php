<?php
namespace extas\interfaces\stages;

use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\IItem;

/**
 * Interface IStageOperationJsonRpcCreate
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageOperationJsonRpcCreate extends IHasHttpIO
{
    public const NAME = 'extas.operation.jsonrpc.create';

    /**
     * @param IItem $item
     * @return IItem
     */
    public function __invoke(IItem $item): IItem;
}
