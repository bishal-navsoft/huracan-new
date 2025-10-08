<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ReportsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('reports');
        $this->setPrimaryKey('id');
        $this->setDisplayField('report_no'); // since no "title" column exists

        // Incident Severities
        $this->belongsTo('IncidentSeverities', [
            'className'    => 'IncidentSeverities',
            'foreignKey'   => 'incident_severity',   // column in reports table
            'propertyName' => 'incident_severity_data', // avoid clash
        ]);

        // Clients
        $this->belongsTo('Clients', [
            'className'    => 'Clients',
            'foreignKey'   => 'client',   // column in reports table
            'propertyName' => 'client_data', // avoid clash
        ]);

        // Admin Masters
        $this->belongsTo('AdminMasters', [
            'className'  => 'AdminMasters',
            'foreignKey' => 'created_by',
            'propertyName' => 'admin_master',
        ]);

        // HSSE Remidials
        $this->hasMany('HsseRemidials', [
            'className'  => 'HsseRemidials',
            'foreignKey' => 'report_no',
            'bindingKey' => 'report_no', // ensure it links report_no <-> report_no
        ]);
        $this->belongsTo('Incidents');
        $this->belongsTo('BusinessTypes');
        $this->belongsTo('Fieldlocations');
        $this->belongsTo('Residuals');
        $this->belongsTo('Potentials');
        $this->belongsTo('Countries');
        $this->hasMany('HsseClients');
    }
}
