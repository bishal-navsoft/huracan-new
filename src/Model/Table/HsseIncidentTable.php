<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class HsseIncidentTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Set the table name (optional if follows Cake's naming convention)
        $this->setTable('hsse_incidents');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');

        // Define associations
        $this->hasMany('HsseInvestigationData', [
            'foreignKey' => 'incident_id',
            'className'  => 'App\Model\Table\HsseInvestigationDataTable',
        ]);

        $this->belongsTo('Loss', [
            'foreignKey' => 'incident_loss',
            'className'  => 'App\Model\Table\LossesTable',
        ]);

        $this->belongsTo('IncidentSeverity', [
            'foreignKey' => 'incident_severity',
            'className'  => 'App\Model\Table\IncidentSeverityTable',
            'propertyName' => 'incidentSeverityAssoc',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        // Add your validation rules here if needed
        return $validator;
    }
}

?>
