<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdminMastersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Map to DB table
        $this->setTable('admin_masters');
        $this->setPrimaryKey('id');
        $this->setDisplayField('admin_user'); // or whatever column you want

        // Define association: many admins belong to one role
        $this->belongsTo('RoleMasters', [
            'foreignKey' => 'role_master_id',
            'joinType'   => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('admin_user', 'Username is required')
            ->notEmptyString('admin_pass', 'Password is required');

        return $validator;
    }
}

