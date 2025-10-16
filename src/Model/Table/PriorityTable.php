<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PriorityTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Explicit table name (matches DB table)
        $this->setTable('priorities');

        // Primary key (optional if it's `id`)
        $this->setPrimaryKey('id');

        // Display field (for dropdowns, debugging, etc.)
        $this->setDisplayField('name');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('name', 'Priority name is required');

        return $validator;
    }
}

