<?php
/**
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Install\App\Action\Plugin;

use Magento\Filesystem,
    Magento\Filesystem\FilesystemException,
    Magento\Filesystem\Directory\Write,
    Magento\App\State,
    Magento\Logger;

class Dir
{
    /**
     * Application state
     *
     * @var State
     */
    protected $appState;

    /**
     * Directory list
     *
     * @var Write
     */
    protected $varDirectory;

    /**
     * Logger
     *
     * @var Logger
     */
    protected $logger;

    /**
     * @param State $state
     * @param Filesystem $filesystem
     * @param Logger $logger
     */
    public function __construct(State $state, Filesystem $filesystem, Logger $logger)
    {
        $this->appState = $state;
        $this->varDirectory = $filesystem->getDirectoryWrite(Filesystem::VAR_DIR);
        $this->logger = $logger;
    }

    /**
     * Clear temporary directories
     *
     * @param $arguments
     * @return mixed
     */
    public function beforeDispatch($arguments)
    {
        if (!$this->appState->isInstalled()) {
            foreach ($this->varDirectory->read() as $dir) {
                if ($this->varDirectory->isDirectory($dir)) {
                    try {
                        $this->varDirectory->delete($dir);
                    } catch (FilesystemException $exception) {
                        $this->logger->log($exception->getMessage());
                    }
                }
            }
        }
        return $arguments;
    }
} 