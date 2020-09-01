<?php
namespace extas\components\operations\jsonrpc;

use extas\components\api\jsonrpc\operations\OperationRunner;
use extas\components\exceptions\AlreadyExist;
use extas\components\exceptions\MissedOrUnknown;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;
use extas\interfaces\operations\IJsonRpcOperation;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageOperationJsonRpcCreate;

/**
 * Class Create
 *
 * @package extas\components\jsonrpc
 * @author jeyroik@gmail.com
 */
class Create extends OperationRunner
{
    /**
     * @return array
     * @throws AlreadyExist
     * @throws MissedOrUnknown
     */
    public function run(): array
    {
        /**
         * @var IJsonRpcOperation $op
         * @var $item IItem|IHasName
         */
        $op = $this->getOperation();
        $repo = $op->getItemRepository();
        $item = $this->getItem();
        $pkMethod = $this->getPkMethod($repo);

        if (!method_exists($item, $pkMethod)) {
            throw new MissedOrUnknown('primary key method "' . $pkMethod . '"');
        }

        $exist = $repo->one([$repo->getPk() => $item->$pkMethod()]);

        if ($exist) {
            throw new AlreadyExist($op->getItemName());
        }

        $item = $repo->create($this->runBeforeStage($item));

        return $item->__toArray();
    }

    /**
     * @param IItem $item
     * @return IItem
     */
    protected function runBeforeStage(IItem $item): IItem
    {
        foreach ($this->getPluginsByStage(IStageOperationJsonRpcCreate::NAME, $this->getHttpIO()) as $plugin) {
            $item = $plugin($item);
        }

        return $item;
    }

    /**
     * @param IRepository $repo
     * @return string
     */
    protected function getPkMethod(IRepository $repo): string
    {
        return 'get' . ucfirst($repo->getPk());
    }

    /**
     * @return IItem
     */
    protected function getItem(): IItem
    {
        $op = $this->getOperation();
        $request = $this->getJsonRpcRequest();

        $itemClass = $op->getItemClass();
        return new $itemClass($request->getData());
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return 'extas.operation.jsonrpc.create';
    }
}
