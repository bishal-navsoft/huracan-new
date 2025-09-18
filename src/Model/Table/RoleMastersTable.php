<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RoleMastersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Map to DB table
        $this->setTable('role_masters');
        $this->setPrimaryKey('id');
        $this->setDisplayField('role_name'); // adjust to your column

        // Define association: one role has many admins
        $this->hasMany('AdminMasters', [
            'foreignKey' => 'role_master_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('role_name', 'Role name is required');

        return $validator;
    }
}

