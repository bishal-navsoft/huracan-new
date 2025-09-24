<?PHP
class SqReportIncident extends AppModel
{
	public $name = 'SqReportIncident';
	public $belongsTo = array(
        'SqService' => array(
            'className'    => 'SqService',
            'foreignKey'   => 'affected_service'
	    
	),
	'SqDamage' => array(
            'className'    => 'SqDamage',
            'foreignKey'   => 'damage_category'
	    
	),
	'IncidentSeverity' => array(
            'className'    => 'IncidentSeverity',
            'foreignKey'   => 'incident_servirity',
	    'conditions'   => array('servrity_type'=>'sq')
	),
       'IncidentSeverity' => array(
            'className'    => 'IncidentSeverity',
            'foreignKey'   => 'incident_servirity',
	    'conditions'   => array('servrity_type'=>'sq')
	)
 
    );

}
?>
