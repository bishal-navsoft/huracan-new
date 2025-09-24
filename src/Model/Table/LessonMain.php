<?PHP
class LessonMain extends AppModel
{
	public $name = 'LessonMain';
	
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
		 'LessonType' => array(
		    'className'    => 'LessonType',
		    'foreignKey'   => 'type' 
		    
		)
     
     
	);
  	public $hasMany = array(
			'LessonAttachment' => array(
			      'className'    => 'LessonAttachment',
			      'foreignKey'   => 'report_id',
			      'conditions'   => array('isdeleted'=>'N')
			  
			),
			'LessonLink' => array(
			      'className'    => 'LessonLink',
			      'foreignKey'   => 'report_id'
			    
			  
			)
		);	
    
}
?>
