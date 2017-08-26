<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class EmailMigration_103
 */
class EmailMigration_103 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('email', [
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
                        'subject',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 150,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'toEmail',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 150,
                            'after' => 'subject'
                        ]
                    ),
                    new Column(
                        'content',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'toEmail'
                        ]
                    ),
                    new Column(
                        'response',
                        [
                            'type' => Column::TYPE_TEXT,
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
                            'after' => 'response'
                        ]
                    ),
                    new Column(
                        'fromMemberId',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'dateCreated'
                        ]
                    ),
                    new Column(
                        'fromMemberUsername',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'fromMemberId'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
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
