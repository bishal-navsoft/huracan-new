<?php
$currentController = $this->request->getParam('controller');
$currentAction = $this->request->getParam('action');
$isAdmin = ($_SESSION['adminData']['RoleMaster']['id'] ?? 0) == 1;
?>

<ul id="leftMenu">

    <!-- HSSE Report -->
    <li class="<?= $currentController === 'Reports' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);" onclick="changeMainDirection('listhsse')">HSSE Report</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <?php if($is_add ?? false): ?>
                        <li>
                            <a href="javascript:void(0);" onclick="changeMainDirection('createhsse')">Create Report</a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="javascript:void(0);" onclick="changeMainDirection('listhsse')">List</a>
                    </li>
                </ul>
            </div>
        </div>
    </li>

    <!-- SQ Report -->
    <li class="<?= $currentController === 'Sqreports' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);">SQ Report</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('sq_main')">Create Report</a></li>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('sq_list')">List</a></li>
                </ul>
            </div>
        </div>
    </li>

    <!-- Journey Management -->
    <li class="<?= $currentController === 'Jrns' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);">Journey Management</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('jn_main')">Create Report</a></li>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('jn_list')">List</a></li>
                </ul>
            </div>
        </div>
    </li>

    <!-- Audits -->
    <li class="<?= $currentController === 'Audits' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);">Audit / Inspection Report</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('audit_created')">Create Report</a></li>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('audit_list')">List</a></li>
                </ul>
            </div>
        </div>
    </li>

    <!-- Jobs -->
    <li class="<?= $currentController === 'Jobs' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);">Job Report</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('job_created')">Create Report</a></li>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('job_list')">List</a></li>
                </ul>
            </div>
        </div>
    </li>

    <!-- Lessons -->
    <li class="<?= $currentController === 'Lessons' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);">Best Practice / Lesson Learnt Report</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('lesson_created')">Create Report</a></li>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('lesson_list')">List</a></li>
                </ul>
            </div>
        </div>
    </li>

    <!-- Certifications -->
    <li class="<?= $currentController === 'Certifications' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);">Certification Report</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <?php if($isAdmin): ?>
                        <li><a href="javascript:void(0);" onclick="changeMainDirection('certification_created')">Create Report</a></li>
                        <li><a href="javascript:void(0);" onclick="changeMainDirection('certification_list')">List</a></li>
                    <?php else: ?>
                        <li><a href="javascript:void(0);" onclick="changeMainDirection('certification_list_view')">View</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </li>

    <!-- Suggestions -->
    <li class="<?= $currentController === 'Suggestions' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);">Suggestion Report</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('suggestion_created')">Create Report</a></li>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('suggestion_list')">List</a></li>
                </ul>
            </div>
        </div>
    </li>

    <!-- JHA -->
    <li class="<?= $currentController === 'Jhas' ? 'selectedtab' : '' ?>">
        <a href="javascript:void(0);">Job Hazard Analysis Report</a>
        <div class="leftmenu_box">
            <div class="subdrop">
                <ul>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('jha_created')">Create Report</a></li>
                    <li><a href="javascript:void(0);" onclick="changeMainDirection('jha_list')">List</a></li>
                </ul>
            </div>
        </div>
    </li>

</ul>
<script>
function changeMainDirection(tabVal) {
    const urls = {
        'createhsse': '<?= $this->Url->build(["controller"=>"Reports","action"=>"add_report_main"]) ?>',
        'listhsse': '<?= $this->Url->build(["controller"=>"Reports","action"=>"report_hsse_list"]) ?>',
        'sq_main': '<?= $this->Url->build(["controller"=>"Sqreports","action"=>"add_sq_report_main"]) ?>',
        'sq_list': '<?= $this->Url->build(["controller"=>"Sqreports","action"=>"report_sq_list"]) ?>',
        'jn_main': '<?= $this->Url->build(["controller"=>"Jrns","action"=>"add_jn_report_main"]) ?>',
        'jn_list': '<?= $this->Url->build(["controller"=>"Jrns","action"=>"report_jrn_list"]) ?>',
        'audit_created': '<?= $this->Url->build(["controller"=>"Audits","action"=>"audit_report_main"]) ?>',
        'audit_list': '<?= $this->Url->build(["controller"=>"Audits","action"=>"report_audit_list"]) ?>',
        'job_created': '<?= $this->Url->build(["controller"=>"Jobs","action"=>"add_job_report_main"]) ?>',
        'job_list': '<?= $this->Url->build(["controller"=>"Jobs","action"=>"report_job_list"]) ?>',
        'lesson_created': '<?= $this->Url->build(["controller"=>"Lessons","action"=>"add_lesson_report_main"]) ?>',
        'lesson_list': '<?= $this->Url->build(["controller"=>"Lessons","action"=>"lesson_main_list"]) ?>',
        'certification_created': '<?= $this->Url->build(["controller"=>"Certifications","action"=>"add_certification_main"]) ?>',
        'certification_list': '<?= $this->Url->build(["controller"=>"Certifications","action"=>"certification_main_list"]) ?>',
        'certification_list_view': '<?= $this->Url->build(["controller"=>"Certifications","action"=>"add_certificate_view"]) ?>',
        'suggestion_created': '<?= $this->Url->build(["controller"=>"Suggestions","action"=>"add_suggestion_report_main"]) ?>',
        'suggestion_list': '<?= $this->Url->build(["controller"=>"Suggestions","action"=>"suggestion_main_list"]) ?>',
        'jha_created': '<?= $this->Url->build(["controller"=>"Jhas","action"=>"add_jha_report_main"]) ?>',
        'jha_list': '<?= $this->Url->build(["controller"=>"Jhas","action"=>"jha_main_list"]) ?>'
    };

    if (urls[tabVal]) {
        window.location.href = urls[tabVal];
    } else {
        console.warn('Unknown tab:', tabVal);
    }
}
</script>