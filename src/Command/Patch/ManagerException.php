<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SupportPatches\Command\Patch;

use Magento\SupportPatches\App\GenericException;

/**
 * Error for wrong format of constraints.
 */
class ManagerException extends GenericException
{
}
