<?PHP
class JobReportMain extends AppModel
{
	public $name = 'JobReportMain';
	public $belongsTo = array(
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
	       'Client' => array(
		    'className'    => 'Client',
		    'foreignKey'   => 'client' 
		    
		)
     
	);
	public $hasMany = array(
			'JobWellData' => array(
		        'className'    => 'JobWellData',
		        'foreignKey'   => 'report_id'
		    
		      ),
			'JobRemidial' => array(
			      'className'    => 'JobRemidial',
			      'foreignKey'   => 'report_no',
			       'conditions'   => array('isdeleted'=>'N')
			),
			'GyroJobData' => array(
			      'className'    => 'GyroJobData',
			      'foreignKey'   => 'report_id',
			      
			  
			),
			'GaugeData' => array(
			      'className'    => 'GaugeData',
			      'foreignKey'   => 'report_id',
			      'conditions'   => array('isdeleted'=>'N')
			  
			),
			'JobAttachment' => array(
			      'className'    => 'JobAttachment',
			      'foreignKey'   => 'report_id',
			      'conditions'   => array('isdeleted'=>'N')
			  
			),
			'JobLink' => array(
			      'className'    => 'JobLink',
			      'foreignKey'   => 'report_id'
			    
			  
			),
			'JobCustomerFeedback' => array(
			      'className'    => 'JobCustomerFeedback',
			      'foreignKey'   => 'report_id'
			  
			)
		);
	}
?>
