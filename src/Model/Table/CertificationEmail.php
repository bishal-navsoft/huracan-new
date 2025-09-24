<?PHP
class CertificationEmail extends AppModel
{
	public $name = 'CertificationEmail';
	
	public $belongsTo = array(
		      'CertificationList' => array(
			  'className'    => 'CertificationList',
			  'foreignKey'   => 'cert_id',
		    
		         ),
		      'AdminMaster' => array(
		       'className'    => 'AdminMaster',
		       'foreignKey'   => 'send_to'
		    
		      ),
		      'CertificationMain' => array(
			  'className'    => 'CertificationMain',
			  'foreignKey'   => 'cid'
		      )
	   ); 
	
}
?>