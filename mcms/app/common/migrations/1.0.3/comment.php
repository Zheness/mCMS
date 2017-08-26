<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class CommentMigration_103
 */
class CommentMigration_103 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('comment', [
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
                        'content',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'dateCreated',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'content'
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
                        'ipAddress',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                            'after' => 'updatedBy'
                        ]
                    ),
                    new Column(
                        'username',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 70,
                            'after' => 'ipAddress'
                        ]
                    ),
                    new Column(
                        'parentId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'username'
                        ]
                    ),
                    new Column(
                        'articleId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'parentId'
                        ]
                    ),
                    new Column(
                        'albumId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'articleId'
                        ]
                    ),
                    new Column(
                        'pageId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'albumId'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_page_member1_idx', ['createdBy'], null),
                    new Index('fk_page_member2_idx', ['updatedBy'], null),
                    new Index('fk_comment_comment1_idx', ['parentId'], null),
                    new Index('fk_comment_article1_idx', ['articleId'], null),
                    new Index('fk_comment_album1_idx', ['albumId'], null),
                    new Index('fk_comment_page1_idx', ['pageId'], null)
                ],
                'references' => [
                    new Reference(
                        'fk_comment_album1',
                        [
                            'referencedTable' => 'album',
                            'referencedSchema' => 'mcms',
                            'columns' => ['albumId'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'fk_comment_article1',
                        [
                            'referencedTable' => 'article',
                            'referencedSchema' => 'mcms',
                            'columns' => ['articleId'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'fk_comment_comment1',
                        [
                            'referencedTable' => 'comment',
                            'referencedSchema' => 'mcms',
                            'columns' => ['parentId'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'fk_comment_page1',
                        [
                            'referencedTable' => 'page',
                            'referencedSchema' => 'mcms',
                            'columns' => ['pageId'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'fk_page_member14',
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
                        'fk_page_member23',
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
                    'AUTO_INCREMENT' => '33',
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
