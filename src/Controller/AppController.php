<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure; 
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\Http\Response;

/**
 * AppController converted from CakePHP 2 -> CakePHP 3.8
 *
 * NOTE: This is a minimum-refactor conversion that keeps original method names
 * for easier migration. After verifying functionality you can modernize names
 * and improve structure (move HTML emails to templates, remove inline JS, etc.).
 */
class AppController extends Controller
{
    public $link_design = [
        'bi' => 'Button, Icon',
        'bit' => 'Button, Icon, Text',
        'bt' => 'Button, Text',
        't' => 'Text'
    ];

    public $speeches = ['Mr' => 'Mr', 'Ms' => 'Ms'];
    public $mobile_site_status = ['on' => 'online', 'off' => 'offline'];
    public $allow_scrolling = ['on' => 'online', 'off' => 'offline'];
    public $creditCardNames = ['visa' => 'Visa', 'mastercard' => 'Mastercard'];
    public $months = [
        '1' => 'January','2' => 'February','3' => 'March','4' => 'April',
        '5' => 'May','6' => 'June','7' => 'July','8' => 'August',
        '9' => 'September','10' => 'October','11' => 'November','12' => 'December'
    ];

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        // If you use authentication plugin, configure it here.
        // Keep legacy Auth commented until you migrate to Authentication plugin.
        /*
        $this->loadComponent('Auth', [
            'authorize' => ['Controller'],
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password']
                ]
            ],
            'loginAction' => ['controller' => 'Users', 'action' => 'login']
        ]);
        */
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // place for global beforeFilter logic
        
    }

    // --------------------- Utility methods ---------------------
    public function _assignArrayKeyValue(string $str = ''): array
    {
        $returnArray = [];
        if ($str !== '') {
            $explodeArray = explode('~', $str);
            foreach ($explodeArray as $explodeValue) {
                $returnArray[$explodeValue] = $explodeValue;
            }
        }
        return $returnArray;
    }

    public function _assignKeyValue(string $str = ''): array
    {
        $returnArray = [];
        if ($str !== '') {
            $tiltExplodedArray = explode('~', $str);
            foreach ($tiltExplodedArray as $tiltExplodedValue) {
                $tmpArray = explode('^', $tiltExplodedValue);
                if (isset($tmpArray[0]) && isset($tmpArray[1])) {
                    $returnArray[$tmpArray[0]] = $tmpArray[1];
                }
            }
        }
        return $returnArray;
    }

    public function _getModuleSettings(): void
    {
        // load ModuleSettings table (plural by convention)
        $this->loadModel('ModuleSettings');
        $modulesettings = $this->ModuleSettings->find()
            ->where(['id' => 1])
            ->first();

        if ($modulesettings) {
            $ms = $modulesettings->toArray();
            $font_size = $this->_assignArrayKeyValue((string)($ms['text_size'] ?? ''));
            $this->set('font_size', $font_size);

            $shadow_sizes = $this->_assignArrayKeyValue((string)($ms['shadow_size'] ?? ''));
            $this->set('shadow_sizes', $shadow_sizes);

            $link_option = $this->_assignArrayKeyValue((string)($ms['link_option'] ?? ''));
            $this->set('link_option', $link_option);

            $link_design = $this->_assignKeyValue((string)($ms['link_design'] ?? ''));
            $this->set('link_design', $link_design ?: $this->link_design);
        } else {
            $this->set('font_size', []);
            $this->set('shadow_sizes', []);
            $this->set('link_option', []);
            $this->set('link_design', $this->link_design);
        }
    }

    // --------------------- Session / access helpers ---------------------
    public function _checkUserType()
    {
        $session = $this->request->getSession();
        if ($session->read('user_type') === 'P') {
            return $this->redirect(['controller' => 'Partners', 'action' => 'home']);
        }
    }

    public function _checkPartnerType()
    {
        $session = $this->request->getSession();
        if ($session->read('user_type') === 'U') {
            return $this->redirect(['controller' => 'Users', 'action' => 'home']);
        }
    }

    public function _checkUserLogin(): bool
    {
        $session = $this->request->getSession();
        return (bool)$session->read('user_id');
    }

    public function _checkUserSession()
    {
        $session = $this->request->getSession();
        if (!$session->check('user_id')) {
            $this->Flash->error('You need to be logged in to access this area.');
            $requested = $this->request->getRequestTarget();
            if ($requested) {
                $session->write('referData', $requested);
            }
            return $this->redirect(['controller' => 'Users', 'action' => 'index']);
        }
    }

    public function _checkAdminLogin(): bool
    {
        $session = $this->request->getSession();
        return (bool)$session->read('admin_id');
    }

    public function _checkAdminSession()
    {
        $session = $this->request->getSession();
        //dd($session->read());
        if (!$session->check('admin_id')) {
            //dd("rftgyhjukil");
            $this->Flash->error('You need to be logged in to access this area.');
            $requested = $this->request->getRequestTarget();
            if ($requested) {
                $session->write('referAdminData', $requested);
            }
            return $this->redirect(['controller' => 'AdminMasters', 'action' => 'index']);
        }
        $adminData = $session->read('adminData');
        if (is_object($adminData)) 
        {
            $adminData = $adminData->toArray();
        }
        $finalData = [
            'AdminMaster' => $adminData,
            'RoleMaster' => [
                'id' => $adminData['role_master_id'] ?? null
            ]
        ];
        return $finalData;
        //return $session->read(); 
    }

    // --------------------- Role menu permission ---------------------
    public function _getRoleMenuPermission(): void
    {
        $session = $this->request->getSession();
        //dd($session->read()); 
        // dd("dfyhju");
         //$roleMasterId = $session->read('adminData.AdminMaster.role_master_id');
        $adminData = $session->read('adminData');
        //$roleMasterId = $adminData->role_master_id;
        $roleMasterId = $adminData['role_master_id'] ?? null;
         //debug($roleMasterId);
       
        if (!$roleMasterId) {
            $this->set('admin_menus_children', []);
            $this->set('admin_menus_parrentdata', []);
            return;
        }
        // debug("fuol;");

        $this->loadModel('RolePermissions');
        $this->loadModel('AdminMenus');

        $rpmData = $this->RolePermissions->find()
            ->where(['role_master_id' => $roleMasterId, 'view' => '1'])
            ->order(['id' => 'ASC'])
            ->all()
            ->toArray();
        
        $admin_menus_children = [];
        $admin_menus_parentId = [];
        $admin_menus_parrentdata = [];

        foreach ($rpmData as $rpmRow) {
            $adminMenuId = $rpmRow->admin_menu_id ?? null;
            if ($adminMenuId) {
                $admin_menus_data = $this->AdminMenus->find()
                    ->where(['id' => $adminMenuId])
                    ->all()
                    ->toArray();
                if (!empty($admin_menus_data)) {
                    $parentId = $admin_menus_data[0]->parent_id ?? null;
                    $admin_menus_children[$parentId][] = $admin_menus_data[0];
                    $admin_menus_parentId[] = $parentId;
                }
            }
        }

        $admin_menus_parentId = array_values(array_unique($admin_menus_parentId));
        foreach ($admin_menus_parentId as $pid) {
            $parent = $this->AdminMenus->find()->where(['id' => $pid])->all()->toArray();
            if (!empty($parent)) {
                $admin_menus_parrentdata[] = $parent[0];
            }
        }
        // dd($admin_menus_children,$admin_menus_parrentdata);
        $this->set('admin_menus_children', $admin_menus_children);
        $this->set('admin_menus_parrentdata', $admin_menus_parrentdata);
    }

    // --------------------- Report helpers ---------------------
    public function report_hsse_link(): void
    {
        $this->loadModel('Reports');
        $allHsseReport = $this->Reports->find()
            ->where(['isdeleted' => 'N'])
            ->order(['id' => 'DESC'])
            ->all()
            ->toArray();
        // dd("sdfghjkl",$allHsseReport);
        $this->request->getSession()->write('allHsseReport', $allHsseReport);
    }

    public function report_sq_link(): void
    {
        $this->loadModel('SqReportMains');
        $allSqReport = $this->SqReportMains->find()
            ->where(['isdeleted' => 'N'])
            ->order(['id' => 'DESC'])
            ->all()
            ->toArray();
        $this->request->getSession()->write('allSqReport', $allSqReport);
    }

    public function hsse_client_tab($encodedId = null)
    {
        $id = base64_decode((string)$encodedId);
        $this->loadModel('Reports');
        $clientTab = $this->Reports->find()->where(['id' => $id])->first();
        if ($clientTab) {
            $this->request->getSession()->write('clienttab', $clientTab->client);
        }
    }

    public function link_search()
    {
        // read from GET or POST 'value'
        $value = trim((string)($this->request->getQuery('value') ?? $this->request->getData('value') ?? ''));
        $rID = 0;
        $type = 'hsse';

        // check Reports
        $this->loadModel('Reports');
        $reportDetail = $this->Reports->find()->where(['report_no' => $value])->all()->toArray();
        if (!empty($reportDetail)) {
            $rID = $reportDetail[0]->id ?? $reportDetail[0]['id'] ?? 0;
            $type = 'hsse';
            return $rID . '~' . $type;
        }

        // check SqReportMains
        $this->loadModel('SqReportMains');
        $reportSqDetail = $this->SqReportMains->find()->where(['report_no' => $value])->all()->toArray();
        if (!empty($reportSqDetail)) {
            $rID = $reportSqDetail[0]->id ?? $reportSqDetail[0]['id'] ?? 0;
            $type = 'sq';
            return $rID . '~' . $type;
        }

        // check JnReportMains by trip_number
        $this->loadModel('JnReportMains');
        $reportJnDetail = $this->JnReportMains->find()->where(['trip_number' => $value])->all()->toArray();
        if (!empty($reportJnDetail)) {
            $rID = $reportJnDetail[0]->id ?? 0;
            $type = 'jn';
            return $rID . '~' . $type;
        }

        // check AuditReportMains
        $this->loadModel('AuditReportMains');
        $reportAuditDetail = $this->AuditReportMains->find()->where(['report_no' => $value])->all()->toArray();
        if (!empty($reportAuditDetail)) {
            $rID = $reportAuditDetail[0]->id ?? 0;
            $type = 'audit';
            return $rID . '~' . $type;
        }

        // check JobReportMains
        $this->loadModel('JobReportMains');
        $reportJobDetail = $this->JobReportMains->find()->where(['report_no' => $value])->all()->toArray();
        if (!empty($reportJobDetail)) {
            $rID = $reportJobDetail[0]->id ?? 0;
            $type = 'job';
            return $rID . '~' . $type;
        }

        // LessonMain
        $this->loadModel('LessonMains');
        $reportLessonDetail = $this->LessonMains->find()->where(['report_no' => $value])->all()->toArray();
        if (!empty($reportLessonDetail)) {
            $rID = $reportLessonDetail[0]->id ?? 0;
            $type = 'lesson';
            return $rID . '~' . $type;
        }

        // DocumentMain
        $this->loadModel('DocumentMains');
        $reportDocDetail = $this->DocumentMains->find()->where(['report_no' => $value])->all()->toArray();
        if (!empty($reportDocDetail)) {
            $rID = $reportDocDetail[0]->id ?? 0;
            $type = 'document';
            return $rID . '~' . $type;
        }

        // SuggestionMain
        $this->loadModel('SuggestionMains');
        $reportSuggestionDetail = $this->SuggestionMains->find()->where(['report_no' => $value])->all()->toArray();
        if (!empty($reportSuggestionDetail)) {
            $rID = $reportSuggestionDetail[0]->id ?? 0;
            $type = 'suggestion';
            return $rID . '~' . $type;
        }

        // JhaMain
        $this->loadModel('JhaMains');
        $reportJhaDetail = $this->JhaMains->find()->where(['report_no' => $value])->all()->toArray();
        if (!empty($reportJhaDetail)) {
            $rID = $reportJhaDetail[0]->id ?? 0;
            $type = 'jha';
            return $rID . '~' . $type;
        }

        return '0~hsse';
    }

    public function image_upload_type(): array
    {
        return [
            'image/gif', 'image/jpeg', 'image/jpg', 'application/pdf', 'image/pjpeg',
            'image/png', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.oasis.opendocument.presentation'
        ];
    }

    public function file_edit_list($filename, $modelName, $attchmentdetail)
    {
        $file_type_array = ['pdf', 'xlsx', 'xls', 'doc', 'docx', 'odt', 'ods', 'odp'];
        $filetype1 = explode('.', $filename);
        $filetype = strtolower(end($filetype1));

        $webroot = $this->request->getAttribute('webroot');
        if (in_array($filetype, $file_type_array, true)) {
            $path = $webroot . 'img/file_upload/' . $attchmentdetail[0][$modelName]['file_name'];
            $imagepath = '<span id=' . $attchmentdetail[0][$modelName]['file_name'] . '><a href=' . $path . ' >' . $attchmentdetail[0][$modelName]['file_name'] . '</a></span><br/>';
        } else {
            $path = $webroot . 'img/file_upload/' . $attchmentdetail[0][$modelName]['file_name'];
            $imagepath = '<span id=' . $attchmentdetail[0][$modelName]['file_name'] . ' class="picupload"><img src=' . $path . ' height="80" width="80" /></span>';
        }
        return $imagepath;
    }

    public function attachment_list($modleName, $rec)
    {
        $filetext1 = explode('.', $rec[$modleName]['file_name']);
        $filetext = strtolower(end($filetext1));
        $webroot = $this->request->getAttribute('webroot');

        switch ($filetext) {
            case 'pdf':
                $fileSRC = '<a href=' . $webroot . 'img/file_upload/' . $rec[$modleName]['file_name'] . ' ><img src=' . $webroot . 'img/file_upload/pdf.jpeg  height="80" width="80"></a>';
                break;
            case 'xlsx':
            case 'xls':
            case 'xlsm':
                $fileSRC = '<a href=' . $webroot . 'img/file_upload/' . $rec[$modleName]['file_name'] . '><img src=' . $webroot . 'img/file_upload/excel.jpeg  height="80" width="80"></a>';
                break;
            case 'doc':
            case 'docx':
            case 'odt':
            case 'ods':
            case 'odp':
                $fileSRC = '<a href=' . $webroot . 'img/file_upload/' . $rec[$modleName]['file_name'] . '><img src=' . $webroot . 'img/file_upload/doc.jpeg  height="80" width="80"></a>';
                break;
            default:
                $fileSRC = '<a href=' . $webroot . 'resize.php?src=img/file_upload/' . $rec[$modleName]['file_name'] . '&w=300&h=300 target="_blank"><img src=' . $webroot . 'img/file_upload/' . $rec[$modleName]['file_name'] . ' height="80" width="80"></a>';
                break;
        }
        return $fileSRC;
    }

    public function link_grid($adminA, $link_type, $modelName, $rec)
    {
        $webroot = $this->request->getAttribute('webroot');
        $link_report_no = '';

        switch ($link_type) {
            case 'hsse':
                $type = 'Hsse Report';
                $this->loadModel('Reports');
                $reportdetail = $this->Reports->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Reports/add_report_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->report_no ?? $reportdetail[0]['report_no']) . '</a>';
                }
                break;
            case 'sq':
                $type = 'Sq Report';
                $this->loadModel('SqReportMains');
                $reportdetail = $this->SqReportMains->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Sqreports/add_sqreport_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->report_no ?? $reportdetail[0]['report_no']) . '</a>';
                }
                break;
            case 'jn':
                $type = 'Journey Report';
                $this->loadModel('JnReportMains');
                $reportdetail = $this->JnReportMains->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Jrns/add_jrnreport_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->trip_number ?? $reportdetail[0]['trip_number']) . '</a>';
                }
                break;
            case 'audit':
                $type = 'Audit Report';
                $this->loadModel('AuditReportMains');
                $reportdetail = $this->AuditReportMains->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Audits/add_audit_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->report_no ?? $reportdetail[0]['report_no']) . '</a>';
                }
                break;
            case 'job':
                $type = 'Job Report';
                $this->loadModel('JobReportMains');
                $reportdetail = $this->JobReportMains->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Jobs/add_jobreport_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->report_no ?? $reportdetail[0]['report_no']) . '</a>';
                }
                break;
            case 'lesson':
                $type = 'Best Practice / Lesson Learnt';
                $this->loadModel('LessonMains');
                $reportdetail = $this->LessonMains->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Lessons/add_lsreport_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->report_no ?? $reportdetail[0]['report_no']) . '</a>';
                }
                break;
            case 'document':
                $type = 'Document Report';
                $this->loadModel('DocumentMains');
                $reportdetail = $this->DocumentMains->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Documents/add_dcreport_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->report_no ?? $reportdetail[0]['report_no']) . '</a>';
                }
                break;
            case 'suggestion':
                $type = 'Suggestion Report';
                $this->loadModel('SuggestionMains');
                $reportdetail = $this->SuggestionMains->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Suggestions/add_suggestionreport_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->report_no ?? $reportdetail[0]['report_no']) . '</a>';
                }
                break;
            case 'jha':
                $type = 'Job Hazard Analysis Report';
                $this->loadModel('JhaMains');
                $reportdetail = $this->JhaMains->find()->where(['id' => $rec[$modelName]['link_report_id']])->all()->toArray();
                if (!empty($reportdetail)) {
                    $link_report_no = '<a href=' . $webroot . 'Jhas/add_jha_view/' . base64_encode($reportdetail[0]->id ?? $reportdetail[0]['id']) . ' target="_blank" >' . ($reportdetail[0]->report_no ?? $reportdetail[0]['report_no']) . '</a>';
                }
                break;
        }

        return ($link_report_no ?? '') . '~' . ($type ?? '');
    }

    public function upload_image_func($upparname, $contr, $allowed_image)
    {
        // Minimal migration: keep similar behaviour but adapt to Cake3 request
        $data = $this->request->getData();
        $filepath = WWW_ROOT . 'img' . DIRECTORY_SEPARATOR . 'file_upload' . DIRECTORY_SEPARATOR;

        if ($upparname === 'upload') {
            if (empty($data[$contr]['file_upload']['name'])) {
                echo "<script>parent.document.getElementById('image_upload_res').innerHTML='<font style=\"color:red\">No file provided</font>';</script>";
                return;
            }
            $replacech = [' '];
            $replaceby = ['_'];
            $newFN = str_replace($replacech, $replaceby, $data[$contr]['file_upload']['name']);
            $fileName = time() . '_' . $newFN;
            $imagetype = $data[$contr]['file_upload']['type'];

            if (in_array($imagetype, $allowed_image, true)) {
                if (move_uploaded_file($data[$contr]['file_upload']['tmp_name'], $filepath . $fileName)) {
                    $iamgepath = $this->request->getAttribute('webroot') . 'img/file_upload/' . $fileName;
                    // Very small inline JS to match original behaviour
                    echo "<script language='javascript' type=\"text/javascript\">parent.document.getElementById('image_upload_res').innerHTML='<font style=\"color:green;\">Uploaded successfully</font>'; parent.document.getElementById('displayFile').style.display='block'; parent.document.getElementById('del').style.display='block'; parent.document.getElementById('displayFile').innerHTML='<span id=\"{$fileName}\"><a href=\"{$iamgepath}\">{$fileName}</a></span>'; parent.document.getElementById('hiddenFile').value='{$fileName}';</script>";
                }
            } else {
                echo "<script language='javascript' type=\"text/javascript\"> parent.document.getElementById('displayFile').innerHTML=''; parent.document.getElementById('image_upload_res').innerHTML='<font style=\"color:red\">Only jpeg, png, jpg,gif,xlsx,xls,Doc,Docx,Odt,Ods files are allowed to upload</font>'; parent.document.getElementById('del').style.display='none';</script>";
            }
        } elseif ($upparname === 'delete') {
            // deletion logic: expects $deleteImageName in scope in original; keep minimal
            // WARNING: path handling must be secure in production
            if (!empty($_GET['delete'])) {
                $deleteImageName = $_GET['delete'];
                @unlink(WWW_ROOT . 'img' . DIRECTORY_SEPARATOR . 'file_upload' . DIRECTORY_SEPARATOR . urldecode($deleteImageName));
                echo "<script language='javascript' type=\"text/javascript\"> parent.document.getElementById('hiddenFile').value=''; parent.document.getElementById('image_upload_res').innerHTML='<font style=\"color:green\">File deleted successfully</font>'; </script>";
            }
        }
    }

    public function derive_link_data($userDeatil)
    {
        $reportDeatil = [];
        // Input expected in Cake2 structure; attempt to handle arrays/objects
        if (!empty($userDeatil[0]['Report'])) {
            foreach ($userDeatil[0]['Report'] as $i => $rep) {
                $reportDeatil['Report'][$i]['report_name'][] = $rep['report_no'] ?? ($rep->report_no ?? null);
                $reportDeatil['Report'][$i]['type'][] = 'Hsse Report';
                $reportDeatil['Report'][$i]['id'][] = $rep['id'] ?? ($rep->id ?? null);
                $reportDeatil['Report'][$i]['summary'][] = $rep['summary'] ?? null;
            }
        }

        $mapTables = [
            'SqReportMain' => ['key' => 'SqReportMain', 'label' => 'Sq Report', 'summary_field' => 'summary'],
            'JnReportMain' => ['key' => 'JnReportMain', 'label' => 'Journey Report', 'name_field' => 'trip_number', 'summary_field' => 'summary'],
            'AuditReportMain' => ['key' => 'AuditReportMain', 'label' => 'Audit Report', 'summary_field' => 'summary'],
            'JobReportMain' => ['key' => 'JobReportMain', 'label' => 'Job Report', 'summary_field' => 'comment'],
            'LessonMain' => ['key' => 'LessonMain', 'label' => 'Best Practice Lesson Report', 'summary_field' => 'summary'],
            'DocumentMain' => ['key' => 'DocumentMain', 'label' => 'Document', 'summary_field' => 'summary'],
            'SuggestionMain' => ['key' => 'SuggestionMain', 'label' => 'Suggestion Report', 'summary_field' => 'summary'],
            'JhaMain' => ['key' => 'JhaMain', 'label' => 'Job Hazard Analysis Report', 'summary_field' => 'summary'],
        ];

        foreach ($mapTables as $tbl => $cfg) {
            if (!empty($userDeatil[0][$cfg['key']])) {
                foreach ($userDeatil[0][$cfg['key']] as $i => $row) {
                    $reportDeatil[$cfg['key']][$i]['report_name'][] = $row[$cfg['name_field'] ?? 'report_no'] ?? ($row->report_no ?? null);
                    $reportDeatil[$cfg['key']][$i]['type'][] = $cfg['label'];
                    $reportDeatil[$cfg['key']][$i]['id'][] = $row['id'] ?? ($row->id ?? null);
                    $reportDeatil[$cfg['key']][$i]['summary'][] = $row[$cfg['summary_field']] ?? null;
                }
            }
        }

        return $reportDeatil;
    }

    // --------------------- Email functions (converted) ---------------------
    public function remedial_email($to, $fullname, $remedialno, $rportno, $remidial_summery, $remidial_closure_date, $reporttype)
    {
        $from = 'jhollingworth@huracan.com.au';
        $subject = 'Your remedial action detail';

        $message = '<html><body>';
        $message .= '<p>Hello <strong>' . ucwords(strtolower($fullname)) . '</strong></p>';
        $message .= '<p><strong>Report No:</strong> ' . h($rportno) . '</p>';
        $message .= '<p>Report Type: ' . h($reporttype) . '</p>';
        $message .= '<p>Your Remedial Action No: ' . h($remedialno) . '</p>';
        $message .= '<p>Summary: ' . h($remidial_summery) . '</p>';
        $message .= '<p>Closure Date: ' . h($remidial_closure_date) . '</p>';
        $message .= '<p>Thanks,<br>Huracan PTY Ltd</p>';
        $message .= '</body></html>';

        $email = new Email('default');
        $email->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setEmailFormat('html')
            ->send($message);

        return 'ok';
    }

    public function cert_email($fullname, $certificate_expire_date, $certificate_cert_date, $validate, $type, $id, $from, $to, $subject)
    {
        $message = '<html><body>';
        $message .= '<p>Hello <strong>' . ucwords(strtolower($fullname)) . '</strong></p>';
        $message .= '<p><strong>Cert Name:</strong> ' . h($type) . '</p>';
        $message .= '<p>Cert Date: ' . h($certificate_cert_date) . '</p>';
        $message .= '<p>Validity (Days): ' . h($validate) . '</p>';
        $message .= '<p>Expiry Date: ' . h($certificate_expire_date) . '</p>';
        $message .= '<p>Thanks,<br>Huracan PTY Ltd</p>';
        $message .= '</body></html>';

        $email = new Email('default');
        $email->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setEmailFormat('html')
            ->send($message);

        // Save CertificationEmail status (use CertificationEmails table)
        $this->loadModel('CertificationEmails');
        try {
            $entity = $this->CertificationEmails->get($id);
            $entity->status = 'Y';
            $this->CertificationEmails->save($entity);
        } catch (\Exception $e) {
            // ignore missing record in minimal migration
        }

        return $message;
    }

    public function ldjs_email($fullname, $reportno, $validation, $revalidation, $summary, $detail, $tablename, $id, $subject, $from, $to, $rtype)
    {
        $message = '<html><body>';
        $message .= '<p>Hello <strong>' . ucwords(strtolower($fullname)) . '</strong></p>';
        $message .= '<p><strong>Report No:</strong> ' . h($reportno) . '</p>';
        $message .= '<p><strong>Report Type:</strong> ' . h($rtype) . '</p>';
        $message .= '<p>Validation: ' . h($validation) . '</p>';
        $message .= '<p>Revalidation: ' . h($revalidation) . '</p>';
        $message .= '<p>Summary: ' . h($summary) . '</p>';
        $message .= '<p>Detail: ' . h($detail) . '</p>';
        $message .= '<p>Thanks,<br>Huracan PTY Ltd</p>';
        $message .= '</body></html>';

        $email = new Email('default');
        $email->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setEmailFormat('html')
            ->send($message);

        // Update table record status = 'Y' (dynamic table name)
        try {
            $table = TableRegistry::getTableLocator()->get($tablename);
            $entity = $table->get($id);
            $entity->status = 'Y';
            $table->save($entity);
        } catch (\Exception $e) {
            // ignore errors in minimal migration
        }

        return $message;
    }

    // --------------------- grid_access & retrive_link_summary ---------------------
    public function grid_access()
    {
        $controller = $this->request->getParam('controller');
        $action = $this->request->getParam('action');
        $session = $this->request->getSession();
        // $roleId = $session->read('adminData.AdminMaster.role_master_id');
        $adminData = $session->read('adminData');
        //$roleId = $adminData->role_master_id;
        $roleId = $adminData['role_master_id'] ?? null;

        // derive url pattern used in original code
        $urlLike = $controller . '/' . $action;
        if (in_array($controller, ['Reports','Sqreports','Jrns','Audits','Jobs','Lessons','Certifications','Documents','Suggestions','Jhas'])) {
            $urlLike = 'Reports/report_hsse_list';
        }
        if ($controller === 'RoleMasters') {
            $urlLike = 'RoleMasters/list_roles';
        }

        $this->loadModel('RolePermissions');
        $this->loadModel('AdminMenus');

        $roleMenuData = $this->RolePermissions->find()
            ->contain(['AdminMenus'])
            ->where([
                'RolePermissions.role_master_id' => $roleId,
                'AdminMenus.url LIKE' => $urlLike,
                'AdminMenus.parent_id !=' => 0
            ])
            ->first();

        $rp = $roleMenuData ? $roleMenuData->toArray() : null;
        $this->set('is_add', ($roleMenuData && $roleMenuData->add == 1) ? 1 : 0);
        $this->set('is_view', ($roleMenuData && $roleMenuData->view == 1) ? 1 : 0);
        $this->set('is_edit', ($roleMenuData && $roleMenuData->edit == 1) ? 1 : 0);
        $this->set('is_block', ($roleMenuData && $roleMenuData->block == 1) ? 1 : 0);
        $this->set('is_delete', ($roleMenuData && $roleMenuData->delete == 1) ? 1 : 0);
    }

    public function retrive_link_summary($madleVal, $uniqueType, $aTP)
    {
        $linkDataHolder = [];
        $linkHolder = [];

        foreach ($uniqueType as $u => $type) {
            $condition = [$aTP . '.type' => $type];
            // $madleVal is expected to be a Table/Query or array - try to handle both
            if (is_object($madleVal) && method_exists($madleVal, 'find')) {
                $reportType = $madleVal->find()->where($condition)->all()->toArray();
            } elseif (is_array($madleVal)) {
                $reportType = array_filter($madleVal, function($r) use ($type, $aTP) {
                    return isset($r[$aTP]) && isset($r[$aTP]['type']) && $r[$aTP]['type'] === $type;
                });
            } else {
                $reportType = [];
            }

            foreach ($reportType as $t => $rt) {
                $linkReportId = is_object($rt) && isset($rt->{$aTP}) ? ($rt->{$aTP}->link_report_id ?? null) : ($rt[$aTP]['link_report_id'] ?? null);
                if (!$linkReportId) {
                    continue;
                }

                switch ($type) {
                    case 'lesson':
                        $this->loadModel('LessonMains');
                        $reportdetail = $this->LessonMains->get($linkReportId);
                        if ($reportdetail) {
                            $linkHolder[$u]['report_number'][$t][] = '<a href=' . $this->request->getAttribute('webroot') . 'Lessons/add_lsreport_view/' . base64_encode($reportdetail->id) . ' target="_blank">' . h($reportdetail->report_no) . '</a>';
                            $linkHolder[$u]['summary'][$t][] = $reportdetail->summary;
                        }
                        break;
                    case 'job':
                        $this->loadModel('JobReportMains');
                        $reportdetail = $this->JobReportMains->get($linkReportId);
                        if ($reportdetail) {
                            $linkHolder[$u]['report_number'][$t][] = '<a href=' . $this->request->getAttribute('webroot') . 'Jobs/add_jobreport_view/' . base64_encode($reportdetail->id) . ' target="_blank">' . h($reportdetail->report_no) . '</a>';
                            $linkHolder[$u]['summary'][$t][] = $reportdetail->comment ?? '';
                        }
                        break;
                    case 'audit':
                        $this->loadModel('AuditReportMains');
                        $reportdetail = $this->AuditReportMains->get($linkReportId);
                        if ($reportdetail) {
                            $linkHolder[$u]['report_number'][$t][] = '<a href=' . $this->request->getAttribute('webroot') . 'Audits/add_audit_view/' . base64_encode($reportdetail->id) . ' target="_blank">' . h($reportdetail->report_no) . '</a>';
                            $linkHolder[$u]['summary'][$t][] = $reportdetail->summary ?? '';
                        }
                        break;
                    case 'sq':
                        $this->loadModel('SqReportMains');
                        $reportdetail = $this->SqReportMains->get($linkReportId);
                        if ($reportdetail) {
                            $linkHolder[$u]['report_number'][$t][] = '<a href=' . $this->request->getAttribute('webroot') . 'SqReports/add_report_view/' . base64_encode($reportdetail->id) . ' target="_blank">' . h($reportdetail->report_no) . '</a>';
                            $linkHolder[$u]['summary'][$t][] = $reportdetail->summary ?? '';
                        }
                        break;
                    case 'jn':
                        $this->loadModel('JnReportMains');
                        $reportdetail = $this->JnReportMains->get($linkReportId);
                        if ($reportdetail) {
                            $linkHolder[$u]['report_number'][$t][] = '<a href=' . $this->request->getAttribute('webroot') . 'Jrns/add_report_view/' . base64_encode($reportdetail->id) . ' target="_blank">' . h($reportdetail->trip_number) . '</a>';
                            $linkHolder[$u]['summary'][$t][] = $reportdetail->summary ?? '';
                        }
                        break;
                    case 'hsse':
                        $this->loadModel('Reports');
                        $reportdetail = $this->Reports->get($linkReportId);
                        if ($reportdetail) {
                            $linkHolder[$u]['report_number'][$t][] = '<a href=' . $this->request->getAttribute('webroot') . 'Reports/add_report_view/' . base64_encode($reportdetail->id) . ' target="_blank">' . h($reportdetail->report_no) . '</a>';
                            $linkHolder[$u]['summary'][$t][] = $reportdetail->summary ?? '';
                        }
                        break;
                    case 'suggestion':
                        $this->loadModel('SuggestionMains');
                        $reportdetail = $this->SuggestionMains->get($linkReportId);
                        if ($reportdetail) {
                            $linkHolder[$u]['report_number'][$t][] = '<a href=' . $this->request->getAttribute('webroot') . 'Suggestions/add_suggestion_view/' . base64_encode($reportdetail->id) . ' target="_blank">' . h($reportdetail->report_no) . '</a>';
                            $linkHolder[$u]['summary'][$t][] = $reportdetail->summary ?? '';
                        }
                        break;
                    case 'jha':
                        $this->loadModel('JhaMains');
                        $reportdetail = $this->JhaMains->get($linkReportId);
                        if ($reportdetail) {
                            $linkHolder[$u]['report_number'][$t][] = '<a href=' . $this->request->getAttribute('webroot') . 'Jhas/add_jha_view/' . base64_encode($reportdetail->id) . ' target="_blank">' . h($reportdetail->report_no) . '</a>';
                            $linkHolder[$u]['summary'][$t][] = $reportdetail->summary ?? '';
                        }
                        break;
                }
            }
        }

        // flatten into linkDataHolder like original
        foreach ($linkHolder as $h => $holder) {
            if (!empty($holder['report_number'])) {
                foreach ($holder['report_number'] as $k => $val) {
                    if (isset($val[0])) {
                        $linkDataHolder['rep_no'][] = $val[0];
                    }
                }
            }
            if (!empty($holder['summary'])) {
                foreach ($holder['summary'] as $k => $val) {
                    if (isset($val[0])) {
                        $linkDataHolder['rep_summary'][] = $val[0];
                    }
                }
            }
        }

        return $linkDataHolder;
    }
}
