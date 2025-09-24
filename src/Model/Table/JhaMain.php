<?PHP
class JhaMain extends AppModel
{
	public $name = 'JhaMain';
	
	public $belongsTo = array(

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
	       	 'JhaType' => array(
		    'className'    => 'JhaType',
		    'foreignKey'   => 'type' 
		    
		)
     
     
	);
  	public $hasMany = array(
		        'JhaAttachment' => array(
			      'className'    => 'JhaAttachment',
			      'foreignKey'   => 'report_id',
			      'conditions'   => array('isdeleted'=>'N')
			  
			),
			'JhaLink' => array(
			      'className'    => 'JhaLink',
			      'foreignKey'   => 'report_id'
			    
			  
			)
			/*'JhaRemidial' => array(
			      'className'    => 'JhaRemidial',
			      'foreignKey'   => 'report_no'
			    
			  
			)*/
		);
		
    
}
?>
