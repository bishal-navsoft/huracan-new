<?PHP
class SuggestionMain extends AppModel
{
	public $name = 'SuggestionMain';
	
	public $belongsTo = array(

		'Incident' => array(
		    'className'    => 'Incident',
		    'foreignKey'   => 'incident_type',
		    'conditions'   => array('Incident.incident_type'=>'all')
		    
		),
		'AdminMaster' => array(
		    'className'    => 'AdminMaster',
		    'foreignKey'   => 'created_by'
		    
		),
		'BusinessType' => array(
		    'className'    => 'BusinessType',
		    'foreignKey'   => 'business_unit'
		    
		),
               'Fieldlocation' => array(
		    'className'    => 'Fieldlocation',
		    'foreignKey'   => 'field_location' 
		    
		),
               'Country' => array(
		    'className'    => 'Country',
		    'foreignKey'   => 'country' 
		    
		),
	        'Service' => array(
		    'className'    => 'Service',
		    'foreignKey'   => 'services' 
		    
		),
		 'SuggestionType' => array(
		    'className'    => 'SuggestionType',
		    'foreignKey'   => 'type' 
		    
		)
     
     
	);
  	public $hasMany = array(
			'SuggestionAttachment' => array(
			      'className'    => 'SuggestionAttachment',
			      'foreignKey'   => 'report_id',
			      'conditions'   => array('isdeleted'=>'N')
			  
			),
			'SuggestionLink' => array(
			      'className'    => 'SuggestionLink',
			      'foreignKey'   => 'report_id'
			    
			  
			),
			'SuggestionRemidial' => array(
			      'className'    => 'SuggestionRemidial',
			      'foreignKey'   => 'report_no'
			    
			  
			)
		);
		
    
}
?>
