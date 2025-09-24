<?PHP
class SqReportMain extends AppModel
{
	public $name = 'SqReportMain';
	public $belongsTo = array(
		'IncidentSeverity' => array(
		    'className'    => 'IncidentSeverity',
		    'foreignKey'   => 'severity',
		    'conditions'   => array('servrity_type'=>'sq')
		),
		'Client' => array(
		    'className'    => 'Client',
		    'foreignKey'   => 'client'
		    
		),
		'Incident' => array(
		    'className'    => 'Incident',
		    'foreignKey'   => 'incident_type'
		   
		    
		),
		'AdminMaster' => array(
		    'className'    => 'AdminMaster',
		    'foreignKey'   => 'created_by'
		    
		),
		'BusinessType' => array(
		    'className'    => 'BusinessType',
		    'foreignKey'   => 'business_unit',
		    'conditions'   => array('rtype'=>'all')
		    
		),
               'Fieldlocation' => array(
		    'className'    => 'Fieldlocation',
		    'foreignKey'   => 'field_location' 
		    
		),
               'Country' => array(
		    'className'    => 'Country',
		    'foreignKey'   => 'country' 
		    
		),
              'Incident_location' => array(
		    'className'    => 'Incident_location',
		    'foreignKey'   => 'incident_location' 
		    
		),
              'Potential' => array(
		    'className'    => 'Potential',
		    'foreignKey'   => 'potential' 
		    
		),
              'Residual' => array(
		    'className'    => 'Residual',
		    'foreignKey'   => 'residual' 
		    
		)
       
	);
	public $hasMany = array(
		'SqInvestigationData' => array(
		    'className'    => 'SqInvestigationData',
		    'foreignKey'   => 'report_id'
		    
		),
		'SqReportIncident' => array(
		    'className'    => 'SqReportIncident',
		    'foreignKey'   => 'report_id',
		     'conditions'   => array('isdeleted'=>'N')
		    
		),
		'SqRemidial' => array(
		    'className'    => 'SqRemidial',
		    'foreignKey'   => 'report_no',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),'SqPersonnel' => array(
		    'className'    => 'SqPersonnel',
		    'foreignKey'   => 'report_id',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		'SqInvestigation' => array(
		    'className'    => 'SqInvestigation',
		    'foreignKey'   => 'report_id'
		   
		    
		),
		'SqAttachment' => array(
		    'className'    => 'SqAttachment',
		    'foreignKey'   => 'report_id',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		
		'SqWellData' => array(
		    'className'    => 'SqWellData',
		    'foreignKey'   => 'report_id'
		    
		),
		 'SqClientfeedback' => array(
		    'className'    => 'SqClientfeedback',
		    'foreignKey'   => 'report_id'
		    
		)  
	);
    
}
?>
