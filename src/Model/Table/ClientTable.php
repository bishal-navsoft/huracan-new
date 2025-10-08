<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ClientTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('clients');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
    }
}

