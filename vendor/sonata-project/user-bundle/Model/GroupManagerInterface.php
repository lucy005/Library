<?php
/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sonata\UserBundle\Model;

use FOS\UserBundle\Model\GroupManagerInterface as BaseInterface;
use FOS\UserBundle\Model\GroupInterface;

/**
 * Class GroupManagerInterface
 *
 * @package Sonata\UserBundle\Model
 *
 * @author Hugo Briand <briand@ekino.com>
 */
interface GroupManagerInterface extends BaseInterface
{
    /**
     * Alias for the repository method
     *
     * @param array|null $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return GroupInterface[]
     */
    public function findGroupsBy(array $criteria = null, array $orderBy = null, $limit = null, $offset = null);
}