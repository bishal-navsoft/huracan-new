<?PHP
class JnReportMain extends AppModel
{
	public $name = 'JnReportMain';
	public $belongsTo = array(
		  'Client' => array(
			    'className'    => 'Client',
			    'foreignKey'   => 'client'
			    
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
			     
			  )
		);
	public $hasMany = array(
		'JnPersonnel' => array(
		    'className'    => 'JnPersonnel',
		    'foreignKey'   => 'report_id',
		    'conditions'   => array('isdeleted'=>'N','isblocked'=>'N')
		    
		),
		'JnChecklist' => array(
		    'className'    => 'JnChecklist',
		    'foreignKey'   => 'report_id'
		),
		'JnAttachment' => array(
		    'className'    => 'JnAttachment',
		    'foreignKey'   => 'report_id',
		    'conditions'   => array('isdeleted'=>'N','isblocked'=>'N')
		)
		);
}
?>
