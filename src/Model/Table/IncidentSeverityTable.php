<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class IncidentSeverityTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('incident_severities');
        $this->setPrimaryKey('id');
        $this->setDisplayField('type');
    }
}

