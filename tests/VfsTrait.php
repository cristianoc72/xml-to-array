<?php declare(strict_types=1);
/*
 * Apache-2 License.
 * This file is part of susina/config-builder package, release under the APACHE-2 license.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Susina\XmlToArray\Tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

trait VfsTrait
{
    private ?vfsStreamDirectory $root = null;

    public function getRoot(): vfsStreamDirectory
    {
        if ($this->root === null) {
            $this->root = vfsStream::setup();
        }

        return $this->root;
    }
}
