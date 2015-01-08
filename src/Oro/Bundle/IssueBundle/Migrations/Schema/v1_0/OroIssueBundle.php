<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroIssueBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createOroIssueTable($schema);
        $this->createOroIssueCollaboratorsTable($schema);
        $this->createOroIssuePriorityTable($schema);
        $this->createOroIssueRelatedTable($schema);
        $this->createOroIssueResolutionTable($schema);

        /** Foreign keys generation **/
        $this->addOroIssueForeignKeys($schema);
        $this->addOroIssueCollaboratorsForeignKeys($schema);
        $this->addOroIssueRelatedForeignKeys($schema);
    }

    /**
     * Create oro_issue table
     *
     * @param Schema $schema
     */
    protected function createOroIssueTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('resolution_name', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('priority_name', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('code', 'string', ['length' => 30]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);
        $table->addColumn('issue_type', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['priority_name'], 'IDX_DF0F9E3B965BD3DF', []);
        $table->addIndex(['resolution_name'], 'IDX_DF0F9E3B8EEEA2E1', []);
        $table->addIndex(['reporter_id'], 'IDX_DF0F9E3BE1CFE6F5', []);
        $table->addIndex(['assignee_id'], 'IDX_DF0F9E3B59EC7D60', []);
        $table->addIndex(['parent_id'], 'IDX_DF0F9E3B727ACA70', []);
    }

    /**
     * Create oro_issue_collaborators table
     *
     * @param Schema $schema
     */
    protected function createOroIssueCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_9DBAC525E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_9DBAC52A76ED395', []);
    }

    /**
     * Create oro_issue_priority table
     *
     * @param Schema $schema
     */
    protected function createOroIssuePriorityTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_priority');
        $table->addColumn('name', 'string', ['length' => 32]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->addColumn('priority', 'integer', []);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'UNIQ_CF28BF98EA750E8');
    }

    /**
     * Create oro_issue_related table
     *
     * @param Schema $schema
     */
    protected function createOroIssueRelatedTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_related');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('related_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'related_id']);
        $table->addIndex(['issue_id'], 'IDX_89BC89FA5E7AA58C', []);
        $table->addIndex(['related_id'], 'IDX_89BC89FA4162C001', []);
    }

    /**
     * Create oro_issue_resolution table
     *
     * @param Schema $schema
     */
    protected function createOroIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_resolution');
        $table->addColumn('name', 'string', ['length' => 32]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->addColumn('priority', 'integer', []);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'UNIQ_4A352091EA750E8');
    }

    /**
     * Add oro_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_resolution'),
            ['resolution_name'],
            ['name'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_priority'),
            ['priority_name'],
            ['name'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    /**
     * Add oro_issue_collaborators foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroIssueCollaboratorsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue_collaborators');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add oro_issue_related foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroIssueRelatedForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue_related');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['related_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
}
