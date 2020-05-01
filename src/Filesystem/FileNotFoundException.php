<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SupportPatches\Filesystem;

use Magento\SupportPatches\App\GenericException;

/**
 * Exception if file can not be found
 */
class FileNotFoundException extends GenericException
{
}
