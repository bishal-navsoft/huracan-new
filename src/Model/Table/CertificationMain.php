<?PHP
class CertificationMain extends AppModel
{
	public $name = 'CertificationMain';
	
	public $belongsTo = array(

		'CertificationList' => array(
		    'className'    => 'CertificationList',
		    'foreignKey'   => 'cretficate_id'
		
		    
		),
		'AdminMaster' => array(
		    'className'    => 'AdminMaster',
		    'foreignKey'   => 'certificate_user'
		
		    
		)
		
	);
  	/*public $hasMany = array(
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
		*/
    
}
?>
