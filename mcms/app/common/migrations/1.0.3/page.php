<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class PageMigration_103
 */
class PageMigration_103 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('page', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'title',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 90,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'slug',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 90,
                            'after' => 'title'
                        ]
                    ),
                    new Column(
                        'content',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'slug'
                        ]
                    ),
                    new Column(
                        'commentsOpen',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'content'
                        ]
                    ),
                    new Column(
                        'dateCreated',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'commentsOpen'
                        ]
                    ),
                    new Column(
                        'dateUpdated',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'dateCreated'
                        ]
                    ),
                    new Column(
                        'createdBy',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'dateUpdated'
                        ]
                    ),
                    new Column(
                        'updatedBy',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'createdBy'
                        ]
                    ),
                    new Column(
                        'isPrivate',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'size' => 1,
                            'after' => 'updatedBy'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_page_member1_idx', ['createdBy'], null),
                    new Index('fk_page_member2_idx', ['updatedBy'], null)
                ],
                'references' => [
                    new Reference(
                        'fk_page_member1',
                        [
                            'referencedTable' => 'member',
                            'referencedSchema' => 'mcms',
                            'columns' => ['createdBy'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'fk_page_member2',
                        [
                            'referencedTable' => 'member',
                            'referencedSchema' => 'mcms',
                            'columns' => ['updatedBy'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'SET NULL'
                        ]
                    )
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '5',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_unicode_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
