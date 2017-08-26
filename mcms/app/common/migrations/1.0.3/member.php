<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class MemberMigration_103
 */
class MemberMigration_103 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('member', [
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
                        'firstname',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 70,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'lastname',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 70,
                            'after' => 'firstname'
                        ]
                    ),
                    new Column(
                        'email',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 100,
                            'after' => 'lastname'
                        ]
                    ),
                    new Column(
                        'password',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'email'
                        ]
                    ),
                    new Column(
                        'username',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 70,
                            'after' => 'password'
                        ]
                    ),
                    new Column(
                        'role',
                        [
                            'type' => Column::TYPE_CHAR,
                            'default' => "member",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'username'
                        ]
                    ),
                    new Column(
                        'dateCreated',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'role'
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
                        'token',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'updatedBy'
                        ]
                    ),
                    new Column(
                        'profilePicture',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'token'
                        ]
                    ),
                    new Column(
                        'status',
                        [
                            'type' => Column::TYPE_CHAR,
                            'default' => "active",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'profilePicture'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fk_member_member_idx', ['createdBy'], null),
                    new Index('fk_member_member1_idx', ['updatedBy'], null),
                    new Index('fk_member_image1_idx', ['profilePicture'], null)
                ],
                'references' => [
                    new Reference(
                        'fk_member_image1',
                        [
                            'referencedTable' => 'image',
                            'referencedSchema' => 'mcms',
                            'columns' => ['profilePicture'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'SET NULL'
                        ]
                    ),
                    new Reference(
                        'fk_member_member',
                        [
                            'referencedTable' => 'member',
                            'referencedSchema' => 'mcms',
                            'columns' => ['createdBy'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'SET NULL'
                        ]
                    ),
                    new Reference(
                        'fk_member_member1',
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
