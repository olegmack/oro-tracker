<?php

namespace Oro\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\IssueBundle\Entity\IssueResolution;

class LoadIssueResolutionData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = array(
        IssueResolution::CODE_UNRESOLVED  => 'Unresolved',
        IssueResolution::CODE_FIXED       => 'Fixed',
        IssueResolution::CODE_DUPLICATE   => 'Duplicate',
        IssueResolution::CODE_WONTFIX     => 'Won\'t fix',
        IssueResolution::CODE_DONE        => 'Done',
    );

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $priority = 10;
        foreach ($this->data as $name => $label) {
            $issueResolution = new IssueResolution($name);
            $issueResolution->setLabel($label);
            $issueResolution->setPriority($priority);
            $priority += 10;
            $manager->persist($issueResolution);
        }

        $manager->flush();
    }
}
