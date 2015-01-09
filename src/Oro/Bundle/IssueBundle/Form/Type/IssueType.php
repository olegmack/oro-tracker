<?php

namespace Oro\Bundle\IssueBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.summary.label'
                ]
            )
            ->add(
                'code',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.code.label'
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label' => 'oro.issue.description.label'
                ]
            );

        // tags
        $builder->add(
            'tags',
            'oro_tag_select',
            [
                'label' => 'oro.tag.entity_plural_label'
            ]
        );

        $builder
            ->add(
                'assignee',
                'oro_user_select',
                [
                    'required'      => true,
                    'label'         => 'oro.issue.assignee.label',
                ]
            )
            ->add(
                'issueType',
                'choice',
                [
                    'label'   => 'oro.issue.issue_type.label',
                    'choices' => [
                        Issue::TYPE_TASK    => 'oro.issue.issue_type.task',
                        Issue::TYPE_STORY   => 'oro.issue.issue_type.story',
                        Issue::TYPE_SUBTASK => 'oro.issue.issue_type.subtask',
                        Issue::TYPE_BUG     => 'oro.issue.issue_type.bug'
                    ],
                    'required' => true
                ]
            )
            ->add(
                'priority',
                'entity',
                [
                    'label'    => 'oro.issue.priority.label',
                    'class'    => 'Oro\Bundle\IssueBundle\Entity\IssuePriority',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('priority')->orderBy('priority.priority');
                    }
                ]
            );

        $builder
            ->add(
                'resolution',
                'entity',
                [
                    'label'    => 'oro.issue.resolution.label',
                    'class'    => 'Oro\Bundle\IssueBundle\Entity\IssueResolution',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('resolution')->orderBy('resolution.priority');
                    }
                ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Oro\Bundle\IssueBundle\Entity\Issue'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oro_issue_form_issue';
    }
}
