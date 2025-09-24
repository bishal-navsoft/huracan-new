<?PHP
class HsseIncident extends AppModel
{
	public $name = 'HsseIncident';
	var $hasMany = array(
		'HsseInvestigationData' => array(
			'className'    	=> 'HsseInvestigationData',
			'foreignKey'    => 'incident_id'
		)
	);
       public $belongsTo = array(
	'Loss' => array(
            'className'    => 'Loss',
            'foreignKey'   => 'incident_loss'
	    
	),
	'IncidentSeverity' => array(
            'className'    => 'IncidentSeverity',
            'foreignKey'   => 'incident_severity',
	    'conditions'   => array('servrity_type'=>'hsse')
	)
    );
	
}
?>