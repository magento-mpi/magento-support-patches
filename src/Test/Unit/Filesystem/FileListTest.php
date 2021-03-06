<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SupportPatches\Test\Unit\Filesystem;

use Magento\SupportPatches\Filesystem\DirectoryList;
use Magento\SupportPatches\Filesystem\FileList;
use PHPUnit\Framework\TestCase;

/**
 * @inheritDoc
 */
class FileListTest extends TestCase
{
    /**
     * @var FileList
     */
    private $fileList;

    /**
     * @var DirectoryList
     */
    private $directoryListMock;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->directoryListMock = $this->createMock(DirectoryList::class);

        $this->directoryListMock->method('getRoot')
            ->willReturn('root');

        $this->fileList = new FileList(
            $this->directoryListMock
        );
    }

    public function testGetPatches()
    {
        $this->assertSame(
            'root/patches.json',
            $this->fileList->getPatches()
        );
    }
}
