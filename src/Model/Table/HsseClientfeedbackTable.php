<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class HsseClientfeedbackTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('hsse_clientfeedbacks');
        $this->setPrimaryKey('id');
    }
}
?>
