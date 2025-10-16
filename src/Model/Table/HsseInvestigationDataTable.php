<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class HsseInvestigationDataTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('hsse_investigation_datas');
        $this->setPrimaryKey('id');

        // Timestamp behavior for created & modified
        $this->addBehavior('Timestamp');

        // Relationship: each investigation data belongs to one investigation
        $this->belongsTo('HsseInvestigation', [
            'foreignKey' => 'report_id',
            'joinType' => 'INNER',
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
            ->integer('incident_id')
            ->requirePresence('incident_id', 'create')
            ->notEmptyString('incident_id');

        $validator
            ->integer('incident_no')
            ->requirePresence('incident_no', 'create')
            ->notEmptyString('incident_no');

        $validator
            ->integer('investigation_no')
            ->requirePresence('investigation_no', 'create')
            ->notEmptyString('investigation_no');

        $validator
            ->scalar('immediate_cause')
            ->maxLength('immediate_cause', 100)
            ->requirePresence('immediate_cause', 'create')
            ->notEmptyString('immediate_cause');

        $validator
            ->scalar('root_cause_id')
            ->maxLength('root_cause_id', 100)
            ->requirePresence('root_cause_id', 'create')
            ->notEmptyString('root_cause_id');

        $validator
            ->scalar('remedila_action_id')
            ->maxLength('remedila_action_id', 100)
            ->requirePresence('remedila_action_id', 'create')
            ->notEmptyString('remedila_action_id');

        $validator
            ->scalar('comments')
            ->requirePresence('comments', 'create')
            ->notEmptyString('comments');

        $validator
            ->inList('isdeleted', ['N', 'Y'])
            ->requirePresence('isdeleted', 'create')
            ->notEmptyString('isdeleted');

        $validator
            ->inList('isblocked', ['N', 'Y'])
            ->requirePresence('isblocked', 'create')
            ->notEmptyString('isblocked');

        return $validator;
    }
}

