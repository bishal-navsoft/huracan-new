<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class HssePersonnelTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('hsse_personnels'); // Your DB table
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');

        // Define associations
        $this->belongsTo('AdminMasters', [
            'foreignKey' => 'personal_data',
            'joinType' => 'INNER',
        ]);

        $this->AdminMasters->belongsTo('RoleMasters', [
            'foreignKey' => 'role_master_id',
            'joinType' => 'LEFT',
        ]);
    }
}
	

?>
