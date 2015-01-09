<?php

namespace Oro\Bundle\IssueBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContextInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Oro\Bundle\EntityConfigBundle\DependencyInjection\Utils\ServiceLink;
use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueListener
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContextLink;

    /**
     * @param ServiceLink $securityContextLink
     */
    public function __construct(ServiceLink $securityContextLink)
    {
        $this->securityContextLink = $securityContextLink;
    }

    /**
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->securityContextLink->getService();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$this->isIssueEntity($entity)) {
            return;
        }

        /** @var Issue $entity */
        $user = $this->getSecurityContext()->getToken()->getUser();

        //add reporter as collaborator
        $entity->setReporter($user);
        $entity->addCollaborator($user);

        //add assignee as collaborator
        $entity->addCollaborator($entity->getAssignee());
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates()
        );

        foreach ($entities as $entity) {
            if (!$this->isIssueEntity($entity)) {
                continue;
            }

            $entity->addCollaborator($entity->getAssignee());
            $meta = $em->getClassMetadata(get_class($entity));
            $uow->computeChangeSet($meta, $entity);
        }
    }

    /**
     * @param mixed $entity
     *
     * @return bool
     */
    protected function isIssueEntity($entity)
    {
        return $entity instanceof Issue;
    }
}
