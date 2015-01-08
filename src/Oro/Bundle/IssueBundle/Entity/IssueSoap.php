<?php

namespace Oro\Bundle\IssueBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;

use Oro\Bundle\SoapBundle\Entity\SoapEntityInterface;

/**
 * @Soap\Alias("Oro.Bundle.IssueBundle.Entity.Issue")
 */
class IssueSoap extends Issue implements SoapEntityInterface
{
    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $id;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $summary;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $code;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $description;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $priority;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $resolution;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $reporter;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $assignee;

    /**
     * @Soap\ComplexType("dateTime", nillable=true)
     */
    protected $createdAt;

    /**
     * @Soap\ComplexType("dateTime", nillable=true)
     */
    protected $updatedAt;

    /**
     * @param Issue $issue
     */
    public function soapInit($issue)
    {
        $this->id = $issue->id;
        $this->summary = $issue->summary;
        $this->code = $issue->code;
        $this->description = $issue->description;
        $this->priority = $issue->priority ? $issue->priority->getName() : null;
        $this->resolution = $issue->resolution ? $issue->resolution->getName() : null;
        $this->assignee = $this->getEntityId($issue->assignee);
        $this->reporter = $this->getEntityId($issue->reporter);
        $this->createdAt = $issue->createdAt;
        $this->updatedAt = $issue->updatedAt;
    }

    /**
     * @param object $entity
     *
     * @return integer|null
     */
    protected function getEntityId($entity)
    {
        if ($entity) {
            return $entity->getId();
        }

        return null;
    }
}
