<?php

namespace Oro\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\IssueBundle\Entity\IssuePriority;

class LoadIssuePriority extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = array(
        IssuePriority::CODE_TRIVIAL  => 'Trivial',
        IssuePriority::CODE_MINOR    => 'Minor',
        IssuePriority::CODE_MAJOR    => 'Major',
        IssuePriority::CODE_CRITICAL => 'Critical',
        IssuePriority::CODE_BLOCKER  => 'Blocker',
    );

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $priority = 10;
        foreach ($this->data as $name => $label) {
            $issuePriority = new IssuePriority($name);
            $issuePriority->setLabel($label);
            $issuePriority->setPriority($priority);
            $priority += 10;
            $manager->persist($issuePriority);
        }

        $manager->flush();
    }
}
