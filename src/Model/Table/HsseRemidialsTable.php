<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class HsseRemidialsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('hsse_remidials');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');
    }
}

