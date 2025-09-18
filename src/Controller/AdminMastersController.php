<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;
use Cake\I18n\FrozenTime;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Response;

/**
 * AdminMastersController
 *
 * Converted from CakePHP 2 style to CakePHP 3.8+ / compatible with CakePHP 4 style methods.
 * Notes:
 * - Models are accessed via $this->loadModel('AdminMasters') and referenced as $this->AdminMasters.
 * - Session access uses $this->request->getSession().
 * - Request data uses $this->request->getData().
 * - Flash messages use $this->Flash.
 * - For authentication you should wire the Authentication plugin (not shown here). This controller keeps the original logic
 *   but written using modern controller conventions.
 */
class AdminMastersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // Load required models explicitly
        $this->loadModel('AdminMasters');
        $this->loadModel('RoleMasters');
        // other models referenced in original controller can be loaded similarly when needed

        // Components and helpers (if using CakePHP 3.8)
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        // If you have a custom mailer component like PhpMailerEmail, register it as a component or use Mailer.
        if (method_exists($this, 'loadComponent')) {
            // example: $this->loadComponent('PhpMailerEmail');
        }

        // Allow actions that don't require authentication here if using Authentication/Authorization plugin
        // $this->Authentication->addUnauthenticatedActions(['index','forgotPassword','forgotpass','language']);
    }

    // Switch language and redirect back
    public function language(string $language_code = 'eng')
    {
        $this->request->getSession()->write('Config.language', $language_code);
        return $this->redirect($this->referer());
    }

    // Administrator index / login
    public function index()
    {
        // layout naming is application specific; in Cake 3/4 you can set layout with viewBuilder
        $this->viewBuilder()->setLayout('before_adminlogin_template');
        $this->set('login_error', '');
        $this->set('err', '');

        $session = $this->request->getSession();

        // If already logged in (legacy _checkAdminLogin), you should replace with Authentication plugin check.
        if ($session->read('admin_id')) {
            return $this->redirect(['controller' => 'Reports', 'action' => 'reportHsseList']);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Build conditions similar to original
            $conditions = [
                'AdminMasters.isblocked' => 'N',
                'AdminMasters.isdeleted' => 'N',
                'AdminMasters.admin_user' => $data['AdminMaster']['admin_user'] ?? null,
                'AdminMasters.admin_pass' => md5($data['AdminMaster']['admin_pass'] ?? ''),
            ];

            $adminDataCount = $this->AdminMasters->find()
                ->where($conditions)
                ->contain(['RoleMasters' => function ($q) {
                    return $q->where(['RoleMasters.isblocked' => 'N', 'RoleMasters.isdeleted' => 'N']);
                }])
                ->count();

            if ($adminDataCount > 0) {
                $adminData = $this->AdminMasters->find()
                    ->where($conditions)
                    ->contain(['RoleMasters'])
                    ->first();

                // write legacy session keys
                $session->write('adminData', $adminData);
                $session->write('sess_id', session_id());
                $session->write('admin_id', $adminData->id);
                $session->write('admin_user_name', $adminData->admin_user);
                $session->write('admin_email', $adminData->admin_email);

                return $this->redirect(['controller' => 'Reports', 'action' => 'reportHsseList']);
            }

            $this->set('err', 1);
            $this->set('login_error', "<span style='color:red;'> The username or password is not correct.</span>");
        }
    }

    // show forgot password page
    public function forgotPassword()
    {
        $this->viewBuilder()->setLayout('before_adminlogin_template');
    }

    // AJAX: check if provided email exists (legacy forgotpass)
    public function forgotpass()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setLayout('ajax');

        $email = $this->request->getData('AdminMaster.emailid');
        $forgotAdminData = $this->AdminMasters->find()->where(['admin_email' => $email])->first();

        if ($forgotAdminData) {
            $payload = 'success~' . $email;
            $response = $this->response->withType('text')->withStringBody($payload);
            return $response;
        }

        return $this->response->withType('text')->withStringBody('fail');
    }

    // placeholder list_all
    public function listAll()
    {
        // replace legacy checks with your auth/permission logic
        $this->_checkAdminSession();
        $this->_getRoleMenuPermission();
        $this->viewBuilder()->setLayout('after_adminlogin_template');
    }

    public function faqCategory()
    {
        $this->_checkAdminSession();
        $this->_getRoleMenuPermission();
        $this->viewBuilder()->setLayout('after_adminlogin_template');
    }

    // Logout
    public function logout()
    {
        $session = $this->request->getSession();
        $adminId = $session->read('admin_id');
        if ($adminId) {
            try {
                $admin = $this->AdminMasters->get($adminId);
                $admin->last_login_time = FrozenTime::now();
                $admin->last_login_ip = '';
                $this->AdminMasters->save($admin);
            } catch (RecordNotFoundException $e) {
                // ignore
            }
        }

        $session->delete('sess_id');
        $session->delete('admin_id');
        $session->delete('admin_user_name');

        return $this->redirect('/admin');
    }

    // User listing page
    public function userList()
    {
        $this->_checkAdminSession();
        $this->_getRoleMenuPermission();
        $this->grid_access();
        $this->viewBuilder()->setLayout('after_adminlogin_template');

        $data = $this->request->getData();
        if (!empty($data)) {
            $action = $data['AdminMaster']['action'] ?? 'all';
            $limit = $data['AdminMaster']['limit'] ?? 50;
        } else {
            $action = 'all';
            $limit = 50;
        }

        $session = $this->request->getSession();
        $session->write('action', $action);
        $session->write('limit', $limit);

        // clear legacy filters if any
        $session->delete('filter');
        $session->delete('value');
    }

    // AJAX: get_all_user
    public function getAllUser($action = 'all')
    {
        Configure::write('debug', 2);
        $this->request->allowMethod(['get', 'post']);
        $this->viewBuilder()->setLayout('ajax');

        // build conditions
        $conditions = ['AdminMasters.isdeleted' => 'N'];

        $filter = $this->request->getQuery('filter') ?? $this->request->getData('filter');
        $value = $this->request->getQuery('value') ?? $this->request->getData('value');

        if ($filter && $value !== null) {
            switch ($filter) {
                case 'name':
                    $parts = preg_split('/\s+/', trim($value));
                    if (count($parts) >= 2) {
                        $conditions[] = ['first_name LIKE' => $parts[0] . '%', 'last_name LIKE' => $parts[1] . '%'];
                    } else {
                        $conditions['OR'] = [
                            'first_name LIKE' => $value . '%',
                            'last_name LIKE' => $value . '%'
                        ];
                    }
                    break;
                case 'role_master_id':
                    $role = $this->RoleMasters->find()->where(['role_name' => $value])->first();
                    if ($role) {
                        $conditions['role_master_id'] = $role->id;
                    }
                    break;
                default:
                    $conditions["UPPER(AdminMasters.$filter) LIKE"] = strtoupper(trim($value)) . '%';
                    break;
            }
        }

        $count = $this->AdminMasters->find()->where($conditions)->count();

        $limitParam = $this->request->getQuery('limit') ?? $this->request->getData('limit');
        $start = (int)($this->request->getQuery('start') ?? $this->request->getData('start') ?? 0);

        $query = $this->AdminMasters->find()
            ->where($conditions)
            ->contain(['RoleMasters'])
            ->order(['AdminMasters.id' => 'DESC']);

        if ($limitParam !== 'all') {
            $limitVal = (int)($limitParam ?: 50);
            $query = $query->limit($limitVal)->offset($start);
        }

        $adminList = $query->all();

        $results = [];
        foreach ($adminList as $admin) {
            $isBlocked = ($admin->isblocked === 'N');
            $results[] = [
                'id' => $admin->id,
                'blockHideIndex' => $isBlocked ? 'true' : 'false',
                'unblockHideIndex' => $isBlocked ? 'false' : 'true',
                'name' => trim($admin->first_name . ' ' . $admin->last_name),
                'seniority' => $admin->seniority,
                'position' => $admin->position,
                'roll_id' => $admin->role_master_id,
                'roll_name' => $admin->role_master ? $admin->role_master->role_name : null,
            ];
        }

        $payload = ['total' => $count, 'admins' => $results, 'status' => $action];
        $json = json_encode($payload);
        return $this->response->withType('application/json')->withStringBody($json);
    }

    // AJAX: userprocess -> add / update user
    public function userProcess()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setLayout('ajax');
        $this->_checkAdminSession();

        $data = $this->request->getData();

        $addForm = $data['add_user_form'] ?? null;
        $userName = $data['user_name'] ?? null;
        $email = $data['email'] ?? null;

        $res = 'add';
        if (empty($addForm['id']) || $addForm['id'] == 0) {
            // add
            $exists = $this->AdminMasters->find()->where(['admin_user' => $userName, 'isdeleted' => 'N'])->count();
            if ($exists) {
                return $this->response->withStringBody('useravl');
            }
            $existsEmail = $this->AdminMasters->find()->where(['admin_email' => $email, 'isdeleted' => 'N'])->count();
            if ($existsEmail) {
                return $this->response->withStringBody('emailavl');
            }
        } else {
            $res = 'update';
            $id = (int)$addForm['id'];
            $exists = $this->AdminMasters->find()
                ->where(['admin_user' => $userName, 'id !=' => $id, 'isdeleted' => 'N'])
                ->count();
            if ($exists) {
                return $this->response->withStringBody('useravl');
            }
            $existsEmail = $this->AdminMasters->find()
                ->where(['admin_email' => $email, 'id !=' => $id, 'isdeleted' => 'N'])
                ->count();
            if ($existsEmail) {
                return $this->response->withStringBody('emailavl');
            }
        }

        // Prepare entity
        if (!empty($addForm['id'])) {
            $entity = $this->AdminMasters->get((int)$addForm['id']);
        } else {
            $entity = $this->AdminMasters->newEmptyEntity();
        }

        $seniority_date = null;
        if (!empty($data['seniority_date'])) {
            $seniority_date = date('Y-m-d', strtotime($data['seniority_date']));
        }

        $entity->admin_user = $userName;
        if (!empty($data['password'])) {
            $entity->admin_pass = md5($data['password']);
        }
        $entity->first_name = $data['fname'] ?? null;
        $entity->last_name = $data['lname'] ?? null;
        $entity->admin_email = $email;
        $entity->phone = $data['phone'] ?? null;
        $entity->position = $data['position'] ?? null;
        $entity->seniority = $seniority_date;
        $entity->role_master_id = $data['roll_type'] ?? null;

        if ($this->AdminMasters->save($entity)) {
            // send mail if add or password changed - replace with Mailer implementation
            if ($res === 'add' || !empty($data['password'])) {
                // build email message or use Mailer class
                // Example (simple):
                // $mailer = new Mailer('default');
                // $mailer->setTo($email)->setSubject('Your login detail')->deliver($body);
            }
            return $this->response->withStringBody($res);
        }

        return $this->response->withStringBody('fail');
    }

    // block users (accepts id or ^ separated ids)
    public function userBlock(?string $id = null)
    {
        if (empty($id)) {
            return $this->redirect(['action' => 'userList']);
        }

        $ids = explode('^', $id);
        foreach ($ids as $one) {
            $one = (int)$one;
            try {
                $entity = $this->AdminMasters->get($one);
                $entity->isblocked = 'Y';
                $this->AdminMasters->save($entity);
            } catch (RecordNotFoundException $e) {
                // skip
            }
        }

        // mimic original behavior (no render)
        return $this->response->withStringBody('ok');
    }

    public function userUnblock(?string $id = null)
    {
        if (empty($id)) {
            return $this->redirect(['action' => 'userList']);
        }

        $ids = explode('^', $id);
        foreach ($ids as $one) {
            $one = (int)$one;
            try {
                $entity = $this->AdminMasters->get($one);
                $entity->isblocked = 'N';
                $this->AdminMasters->save($entity);
            } catch (RecordNotFoundException $e) {
                // skip
            }
        }

        return $this->response->withStringBody('ok');
    }

    // AJAX: soft-delete users
    public function userDelete()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setLayout('ajax');

        $idsRaw = $this->request->getData('id');
        if (empty($idsRaw)) {
            return $this->redirect(['action' => 'userList']);
        }

        $ids = explode('^', $idsRaw);
        foreach ($ids as $one) {
            $one = (int)$one;
            try {
                $entity = $this->AdminMasters->get($one);
                $entity->isdeleted = 'Y';
                $this->AdminMasters->save($entity);
            } catch (RecordNotFoundException $e) {
                // skip
            }
        }

        return $this->response->withStringBody('ok');
    }

    // Add / edit user form
    public function addUser(?string $id = null)
    {
        $this->_checkAdminSession();
        $this->_getRoleMenuPermission();
        $this->viewBuilder()->setLayout('after_adminlogin_template');

        $roleList = $this->RoleMasters->find()->where(['isblocked' => 'N', 'isdeleted' => 'N'])->all();
        $this->set('roleList', $roleList);

        if (!empty($id)) {
            $idVal = (int)base64_decode($id);
            $user = $this->AdminMasters->get($idVal);
            $this->set('id', $user->id);
            $this->set('user_name', $user->admin_user);
            $this->set('fname', $user->first_name);
            $this->set('lname', $user->last_name);
            $this->set('email', $user->admin_email);
            $this->set('phone', $user->phone);
            $this->set('position', $user->position);
            $this->set('seniority_date_val', $user->seniority);
            $this->set('rollid', $user->role_master_id);
            $this->set('button', 'Update');
        } else {
            $this->set('id', 0);
            $this->set('user_name', '');
            $this->set('fname', '');
            $this->set('lname', '');
            $this->set('email', '');
            $this->set('phone', '');
            $this->set('seniority_date_val', '');
            $this->set('position', '');
            $this->set('rollid', '');
            $this->set('button', 'Submit');
        }
    }

    // Change password for current admin
    public function changePassword(?string $id = null)
    {
        $this->_checkAdminSession();
        $this->_getRoleMenuPermission();
        $session = $this->request->getSession();
        $admin_id = $session->read('admin_id');
        $this->viewBuilder()->setLayout('after_adminlogin_template');

        $seid = !empty($id) ? $id : $admin_id;
        $this->set('id', $seid);

        if ($this->request->is(['post', 'put'])) {
            $admin = $this->AdminMasters->get($admin_id);
            $admin->admin_pass = md5($this->request->getData('AdminMaster.admin_pass'));
            $this->AdminMasters->save($admin);
            $this->Flash->success(__('Password successfully changed'));
            return $this->redirect(['controller' => 'AdminMasters', 'action' => 'changePassword']);
        }
    }

    // change password for a user (legacy used User model)
    public function changePasswordUser(?string $id = null)
    {
        $this->_checkAdminSession();
        $this->_getRoleMenuPermission();
        $session = $this->request->getSession();
        $admin_id = $session->read('admin_id');
        $this->viewBuilder()->setLayout('fancybox_template');

        $this->set('id', $id);

        if ($this->request->is(['post', 'put'])) {
            // Assuming existence of Users table
            $this->loadModel('Users');
            $user = $this->Users->get($admin_id);
            $user->user_pass = md5($this->request->getData('AdminMaster.admin_pass'));
            $this->Users->save($user);
            $this->Flash->success(__('Password successfully changed'));
            return $this->redirect(['controller' => 'AdminMasters', 'action' => 'changePasswordUser']);
        }
    }

    // check_pass -> verify given password against admin id
    public function checkPass(?string $pass = null)
    {
        $this->request->allowMethod(['get']);
        if ($pass === null) {
            return $this->response->withStringBody('2');
        }

        $exp = explode('"_"', $pass);
        if (count($exp) < 2) {
            return $this->response->withStringBody('2');
        }

        $given = $exp[0];
        $id = (int)$exp[1];

        $count = $this->AdminMasters->find()->where(['id' => $id, 'admin_pass' => md5($given)])->count();
        return $this->response->withStringBody($count > 0 ? '1' : '2');
    }

    // ----------------------
    // Legacy / helper methods
    // ----------------------
    public function _checkAdminSession()
    {
        $session = $this->request->getSession();
        if (!$session->check('admin_id')) {
            return $this->redirect(['action' => 'index']);
        }
        return true;
    }

    public function _getRoleMenuPermission(): void
    {
        // Implement your role permission fetching logic here
        return;
    }

    public function grid_access()
    {
        // Implement grid access logic or permissions
        return;
    }
}
