<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RolePermissionsTable extends Table
{
	public $name = 'RolePermission';
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('role_permissions');
        $this->setPrimaryKey('id');

        $this->belongsTo('AdminMenus', [
            'foreignKey' => 'admin_menu_id', // check DB schema
        ]);

        $this->belongsTo('RoleMasters', [
            'foreignKey' => 'role_id', // check schema
        ]);
    }
}
