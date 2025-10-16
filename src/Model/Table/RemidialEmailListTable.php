<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RemidialEmailListTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('remidial_email_lists');
        $this->setPrimaryKey('id');
    }
}
?>
