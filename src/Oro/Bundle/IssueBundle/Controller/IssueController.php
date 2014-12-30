<?php

namespace Oro\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueController extends Controller
{
    /**
     * @Route("/test", name="oro_issue_test")
     * @Template()
     */
    public function testAction()
    {
        return array();
    }

    /**
     * @Route(name="oro_issue_index")
     * @Acl(
     *      id="oro_issue_view",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'entity_class' => $this->container->getParameter('oro_issue.entity.issue.class')
        );
    }

    /**
     * @Route("/create", name="oro_issue_create")
     * @Acl(
     *      id="oro_issue_create",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template("OroIssueBundle:Issue:update.html.twig")
     */
    public function createAction()
    {
        $entity = $this->initEntity();
        return $this->update($entity);
    }

    /**
     * Update action
     *
     * @Route("/update/{id}", name="oro_issue_update", requirements={"id"="\d+"})
     * @Template()
     *
     * @param Issue $entity
     * @return array
     */
    public function updateAction(Issue $entity)
    {
        return $this->update($entity);
    }

    /**
     * Update action
     *
     * @Route("/delete/{id}", name="oro_issue_delete", requirements={"id"="\d+"})
     * @Template()
     *
     * @param Issue $entity
     * @return array
     */
    public function deleteAction(Issue $entity)
    {
        return $this->update($entity);
    }

    /**
     * Initialize new issue entity
     *
     * @return Issue
     */
    protected function initEntity()
    {
        $entity = new Issue();
        return $entity;
    }

    /**
     * Update issue entity
     *
     * @param Issue $entity
     * @return array
     */
    protected function update(Issue $entity)
    {
        if ($this->get('oro_issue.form.handler.issue')->process($entity)) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('oro.issue.controller.issue.saved.message')
            );

            return $this->get('oro_ui.router')->redirectAfterSave(
                ['route' => 'oro_issue_update', 'parameters' => ['id' => $entity->getId()]],
                ['route' => 'oro_issue_index'],
                $entity
            );
        }

        return array(
            'entity' => $entity,
            'form'   => $this->get('oro_issue.form.issue')->createView()
        );
    }

    /**
     * @Route("/view/{id}", name="oro_issue_view", requirements={"id"="\d+"})
     * @AclAncestor("oro_issue_view")
     * @Template
     *
     * @param Issue $entity
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        return array('entity' => $issue);
    }
}
