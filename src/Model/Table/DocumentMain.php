<?PHP
class DocumentMain extends AppModel
{
	public $name = 'DocumentMain';
	
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
		'DocumentationType' => array(
		    'className'    => 'DocumentationType',
		    'foreignKey'   => 'd_type' 
		    
		)
     
	);
  	public $hasMany = array(
			'DocumentAttachment' => array(
			      'className'    => 'DocumentAttachment',
			      'foreignKey'   => 'report_id',
			      'conditions'   => array('isdeleted'=>'N')
			  
			)
		);
		
    
}
?>
