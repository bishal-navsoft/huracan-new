<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class HsseClientTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('hsse_clients');
        $this->setPrimaryKey('id');
    }
}
?>
