<?PHP
class AuditReportMain extends AppModel
{
	public $name = 'AuditReportMain';
	public $belongsTo = array(
		'Client' => array(
		    'className'    => 'Client',
		    'foreignKey'   => 'client'
		    
		),
		'AuditType' => array(
		    'className'    => 'AuditType',
		    'foreignKey'   => 'audit_type'
		    
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
     
	);
	public $hasMany = array(
		      'AuditClient' => array(
			  'className'    => 'AuditClient',
			  'foreignKey'   => 'report_id'
		      ),
		      'AuditRemidial' => array(
			  'className'    => 'AuditRemidial',
			  'foreignKey'   => 'report_no',
			   'conditions'   => array('isdeleted'=>'N')
		    
		         ),
		      'AuditAttachment' => array(
			'className'    => 'AuditAttachment',
			'foreignKey'   => 'report_id',
			'conditions'   => array('isdeleted'=>'N')
		    
		      )
		    );  
    
}
?>
