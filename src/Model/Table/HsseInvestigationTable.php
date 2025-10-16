<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class HsseInvestigationTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Table and primary key
        $this->setTable('hsse_investigations');
        $this->setPrimaryKey('id');

        // Timestamp behavior for created & modified
        $this->addBehavior('Timestamp');

        // Relationship: one investigation has many investigation data
        $this->hasMany('HsseInvestigationData', [
            'foreignKey' => 'report_id', // assuming report_id links
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('report_id')
            ->requirePresence('report_id', 'create')
            ->notEmptyString('report_id');

        $validator
            ->scalar('team_user_id')
            ->maxLength('team_user_id', 100)
            ->requirePresence('team_user_id', 'create')
            ->notEmptyString('team_user_id');

        return $validator;
    }
}

