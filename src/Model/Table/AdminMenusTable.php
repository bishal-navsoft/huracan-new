<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AdminMenusTable extends Table
{
	public $name = 'AuditAttachment';
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('admin_menus');
        $this->setPrimaryKey('id');
    }
}