<?php
namespace tests\jsonrpc;

use Dotenv\Dotenv;
use extas\components\api\jsonrpc\operations\OperationRunner;
use extas\components\http\TSnuffHttp;
use extas\components\items\SnuffItem;
use extas\components\operations\jsonrpc\Create;
use extas\components\operations\JsonRpcOperation;
use extas\components\plugins\Plugin;
use extas\components\plugins\PluginRepository;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\stages\IStageOperationJsonRpcCreate;
use PHPUnit\Framework\TestCase;
use tests\jsonrpc\misc\MissedPkMethod;
use tests\jsonrpc\misc\PluginOperationJsonRpcCreate;

/**
 * Class CreateTest
 *
 * @package tests\jsonrpc
 * @author jeyroik <jeyroik@gmail.com>
 */
class CreateTest extends TestCase
{
    use TSnuffHttp;
    use TSnuffRepositoryDynamic;
    use TSnuffPlugins;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->createSnuffDynamicRepositories([
            ['snuffRepo', 'name', SnuffItem::class]
        ]);
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
    }

    public function testCreateOperation()
    {
        $operation = $this->getOperation();

        $result = $operation([
            IHasHttpIO::FIELD__PSR_REQUEST => $this->getPsrRequest(),
            IHasHttpIO::FIELD__PSR_RESPONSE => $this->getPsrResponse(),
            IHasHttpIO::FIELD__ARGUMENTS => ['version' => 0]
        ]);

        $this->assertEquals(
            [
                'name' => 'test',
                'description' => 'is ok'
            ],
            $result,
            'Incorrect result: ' . print_r($result, true)
        );
    }

    public function testMissedPkMethod()
    {
        $operation = $this->getOperation(MissedPkMethod::class);

        $this->expectExceptionMessage('Missed or unknown primary key method "getName"');
        $operation([
            IHasHttpIO::FIELD__PSR_REQUEST => $this->getPsrRequest(),
            IHasHttpIO::FIELD__PSR_RESPONSE => $this->getPsrResponse(),
            IHasHttpIO::FIELD__ARGUMENTS => ['version' => 0]
        ]);
    }

    public function testAlreadyExist()
    {
        $operation = $this->getOperation();

        $operation([
            IHasHttpIO::FIELD__PSR_REQUEST => $this->getPsrRequest(),
            IHasHttpIO::FIELD__PSR_RESPONSE => $this->getPsrResponse(),
            IHasHttpIO::FIELD__ARGUMENTS => ['version' => 0]
        ]);

        $this->expectExceptionMessage('Snuff.item already exists');
        $operation([
            IHasHttpIO::FIELD__PSR_REQUEST => $this->getPsrRequest(),
            IHasHttpIO::FIELD__PSR_RESPONSE => $this->getPsrResponse(),
            IHasHttpIO::FIELD__ARGUMENTS => ['version' => 0]
        ]);
    }

    public function testPluginCreate()
    {
        $operation = $this->getOperation();

        $this->createSnuffPlugin(PluginOperationJsonRpcCreate::class, [IStageOperationJsonRpcCreate::NAME]);

        $result = $operation([
            IHasHttpIO::FIELD__PSR_REQUEST => $this->getPsrRequest(),
            IHasHttpIO::FIELD__PSR_RESPONSE => $this->getPsrResponse(),
            IHasHttpIO::FIELD__ARGUMENTS => ['version' => 0]
        ]);

        $this->assertEquals(
            [
                'name' => 'test',
                'description' => 'is ok',
                'test' => 'is ok'
            ],
            $result,
            'Incorrect result: ' . print_r($result, true)
        );
    }

    /**
     * @param string $itemClass
     * @return OperationRunner
     */
    protected function getOperation(string $itemClass = SnuffItem::class): OperationRunner
    {
        return new Create([
            Create::FIELD__OPERATION => new JsonRpcOperation([
                JsonRpcOperation::FIELD__PARAMETERS => [
                    JsonRpcOperation::PARAM__ITEM_REPOSITORY => [
                        ISampleParameter::FIELD__NAME => JsonRpcOperation::PARAM__ITEM_REPOSITORY,
                        ISampleParameter::FIELD__VALUE => 'snuffRepo'
                    ],
                    JsonRpcOperation::PARAM__ITEM_CLASS => [
                        ISampleParameter::FIELD__NAME => JsonRpcOperation::PARAM__ITEM_CLASS,
                        ISampleParameter::FIELD__VALUE => $itemClass
                    ],
                    JsonRpcOperation::PARAM__ITEM_NAME => [
                        ISampleParameter::FIELD__NAME => JsonRpcOperation::PARAM__ITEM_NAME,
                        ISampleParameter::FIELD__VALUE => 'snuff.item'
                    ],
                    JsonRpcOperation::PARAM__METHOD => [
                        ISampleParameter::FIELD__NAME => JsonRpcOperation::PARAM__METHOD,
                        ISampleParameter::FIELD__VALUE => 'create'
                    ]
                ]
            ])
        ]);
    }
}
