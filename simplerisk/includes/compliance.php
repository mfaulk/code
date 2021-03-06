<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required configuration files
require_once(realpath(__DIR__ . '/config.php'));
require_once(realpath(__DIR__ . '/cvss.php'));
require_once(realpath(__DIR__ . '/services.php'));
require_once(realpath(__DIR__ . '/alerts.php'));

// Include the language file
require_once(language_file());

// Include Zend Escaper for HTML Output Encoding
require_once(realpath(__DIR__ . '/Component_ZendEscaper/Escaper.php'));
$escaper = new Zend\Escaper\Escaper('utf-8');

/******************************************************
 * FUNCTION: DISPLAY FRAMEWORK CONTROLS IN COMPLIANCE *
 ******************************************************/
function display_framework_controls_in_compliance()
{
    global $lang, $escaper;
    
    $tableID = "framework-controls";
    
    echo "
        <table width=\"100%\" id=\"{$tableID}\" >
            <thead class='hide'>
                <tr >
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <br>
        <script>
            var pageLength = 10;
            var form = $('#{$tableID}').parents('form');
            var datatableInstance = $('#{$tableID}').DataTable({
                scrollX: true,
                bFilter: false,
                bLengthChange: false,
                processing: true,
                serverSide: true,
                bSort: true,
                pagingType: \"full_numbers\",
                dom : \"flrtip\",
                pageLength: pageLength,
                dom : \"flrti<'#view-all.view-all'>p\",
                ajax: {
                    url: BASE_URL + '/api/compliance/define_tests',
                    data: function(d){
                        d.control_framework = \$(\"#filter_by_control_framework\").val();
                    },
                    complete: function(response){
                    }
                }
            });
            
            // Add paginate options
            datatableInstance.on('draw', function(e, settings){
                $('.paginate_button.first').html('<i class=\"fa fa-chevron-left\"></i><i class=\"fa fa-chevron-left\"></i>');
                $('.paginate_button.previous').html('<i class=\"fa fa-chevron-left\"></i>');

                $('.paginate_button.last').html('<i class=\"fa fa-chevron-right\"></i><i class=\"fa fa-chevron-right\"></i>');
                $('.paginate_button.next').html('<i class=\"fa fa-chevron-right\"></i>');
            })
            
            // Add all text to View All button on bottom
            $('.view-all').html(\"".$escaper->escapeHtml($lang['ALL'])."\");

            // View All
            $(\".view-all\").click(function(){
                var oSettings =  datatableInstance.settings();
                oSettings[0]._iDisplayLength = -1;
                datatableInstance.draw()
                $(this).addClass(\"current\");
            })
            
            // Page event
            $(\"body\").on(\"click\", \"span > .paginate_button\", function(){
                var index = $(this).attr('aria-controls').replace(\"DataTables_Table_\", \"\");

                var oSettings =  datatableInstance.settings();
                if(oSettings[0]._iDisplayLength == -1){
                    $(this).parents(\".dataTables_wrapper\").find('.view-all').removeClass('current');
                    oSettings[0]._iDisplayLength = pageLength;
                    datatableInstance.draw()
                }
            })
            
            // View All
            $(\"#filter_by_control_framework\").change(function(){
                \$(\"#{$tableID}\").DataTable().draw();
            })

            $('#filter_by_control_framework').multiselect({
                allSelectedText: '".$escaper->escapeHtml($lang['ALL'])."',
                includeSelectAllOption: true
            });
            
            $(\"body\").on(\"click\", \".add-test\", function(){
                $(\"#test-new-form\")[0].reset();
                $(\"[name=framework_control_id]\", $(\"#test-new-form\")).val($(this).data('control-id'))
            })
            
            $(\"body\").on(\"click\", \".delete-row\", function(){
                var testId = $(this).data('id');
                $(\"#test--delete [name=test_id]\").val(testId);
            })
            
            $(\"body\").on(\"click\", \".edit-test\", function(){
                var testId = $(this).data('id');
                \$.ajax({
                    type: \"GET\",
                    url: BASE_URL + \"/api/compliance/test?id=\" + testId,
                    success: function(result){
                        var data = result['data'];
                        var form = \$('#test-edit-form');
                        \$('[name=test_id]', form).val(data['id']);
                        \$('[name=tester]', form).val(data['tester']);
                        \$('[name=test_frequency]', form).val(data['test_frequency']);
                        \$('[name=last_date]', form).val(data['last_date']);
                        \$('[name=next_date]', form).val(data['next_date']);
                        \$('[name=name]', form).val(data['name']);
                        \$('[name=objective]', form).val(data['objective']);
                        \$('[name=test_steps]', form).val(data['test_steps']);
                        \$('[name=approximate_time]', form).val(data['approximate_time']);
                        \$('[name=expected_results]', form).val(data['expected_results']);
                        \$(\".datepicker\" , form).datepicker({dateFormat: \"yy-mm-dd\"});
                    }
                })
                
            })
            
        </script>
    ";
}

/*****************************************
 * FUNCTION: ADD FRAMEWORK CONTROLS TEST *
 *****************************************/
function add_framework_control_test($tester, $test_frequency, $name, $objective, $test_steps, $approximate_time, $expected_results, $framework_control_id, $last_date="0000-00-00", $next_date=false){
    if($next_date === false) $next_date = date('Y-m-d');

    // Open the database connection
    $db = db_open();

    // Get the risk levels
    $stmt = $db->prepare("INSERT INTO `framework_control_tests` (`tester`, `test_frequency`, `last_date`, `next_date`, `name`, `objective`, `test_steps`, `approximate_time`, `expected_results`, `framework_control_id`, `created_at`) VALUES (:tester, :test_frequency, :last_date, :next_date, :name, :objective, :test_steps, :approximate_time, :expected_results, :framework_control_id, :created_at)");
    
    $stmt->bindParam(":tester", $tester, PDO::PARAM_INT);
    $stmt->bindParam(":test_frequency", $test_frequency, PDO::PARAM_INT);
    $stmt->bindParam(":last_date", $last_date, PDO::PARAM_STR);
    $stmt->bindParam(":next_date", $next_date, PDO::PARAM_STR);
    $stmt->bindParam(":name", $name, PDO::PARAM_STR, 100);
    $stmt->bindParam(":objective", $objective, PDO::PARAM_STR, 1000);
    $stmt->bindParam(":test_steps", $test_steps, PDO::PARAM_STR, 1000);
    $stmt->bindParam(":approximate_time", $approximate_time, PDO::PARAM_INT);
    $stmt->bindParam(":expected_results", $expected_results, PDO::PARAM_STR, 1000);
    $stmt->bindParam(":framework_control_id", $framework_control_id, PDO::PARAM_INT);
    $stmt->bindParam(":created_at", date("Y-m-d"));
    $stmt->execute();

    $test_id = $db->lastInsertId();

    $message = "A new test named \"{$name}\" was created by username \"" . $_SESSION['user'] . "\".";
    write_log($test_id + 1000, $_SESSION['uid'], $message, "test");

    // Close the database connection
    db_close($db);
    
    return $test_id;
}

/********************************************
 * FUNCTION: UPDATE FRAMEWORK CONTROLS TEST *
 ********************************************/
function update_framework_control_test($test_id, $tester=false, $test_frequency=false, $name=false, $objective=false, $test_steps=false, $approximate_time=false, $expected_results=false, $last_date=false, $next_date=false, $framework_control_id=false){
    
    // Get test by test ID
    $test = get_framework_control_test_by_id($test_id);
    if($tester === false) $tester = $test['tester'];
    if($test_frequency === false) $test_frequency = $test['test_frequency'];
    if($name === false) $name = $test['name'];
    if($objective === false) $objective = $test['objective'];
    if($test_steps === false) $test_steps = $test['test_steps'];
    if($approximate_time === false) $approximate_time = $test['approximate_time'];
    if($expected_results === false) $expected_results = $test['expected_results'];
    if($last_date === false) $last_date = $test['last_date'];
    if($next_date === false) $next_date = $test['next_date'];
    if($framework_control_id === false) $framework_control_id = $test['framework_control_id'];

    // Open the database connection
    $db = db_open();

    // Get the risk levels
    $stmt = $db->prepare("UPDATE `framework_control_tests` SET `tester`=:tester, `test_frequency`=:test_frequency, `last_date`=:last_date, `next_date`=:next_date, `name`=:name, `objective`=:objective, `test_steps`=:test_steps, `approximate_time`=:approximate_time, `expected_results`=:expected_results, `framework_control_id`=:framework_control_id WHERE id=:test_id; ");
    
    $stmt->bindParam(":test_id", $test_id, PDO::PARAM_INT);
    $stmt->bindParam(":tester", $tester, PDO::PARAM_INT);
    $stmt->bindParam(":test_frequency", $test_frequency, PDO::PARAM_INT);
    $stmt->bindParam(":last_date", $last_date, PDO::PARAM_STR);
    $stmt->bindParam(":next_date", $next_date, PDO::PARAM_STR);
    $stmt->bindParam(":name", $name, PDO::PARAM_STR, 100);
    $stmt->bindParam(":objective", $objective, PDO::PARAM_STR, 1000);
    $stmt->bindParam(":test_steps", $test_steps, PDO::PARAM_STR, 1000);
    $stmt->bindParam(":approximate_time", $approximate_time, PDO::PARAM_INT);
    $stmt->bindParam(":expected_results", $expected_results, PDO::PARAM_STR, 1000);
    $stmt->bindParam(":framework_control_id", $framework_control_id, PDO::PARAM_INT);
    $stmt->execute();

    // Close the database connection
    db_close($db);

    $message = "A test was updated for test ID \"{$test_id}\" by username \"" . $_SESSION['user'] . "\".";
    write_log($test_id + 1000, $_SESSION['uid'], $message, "test");
    
    return $test_id;
    
}

/******************************************************
 * FUNCTION: DELETE FRAMEWORK CONTROL TEST BY TEST ID *
 ******************************************************/
function delete_framework_control_test($test_id){
    // Open the database connection
    $db = db_open();

    $stmt = $db->prepare("DELETE FROM `framework_control_tests` WHERE id=:id; ");
    $stmt->bindParam(":id", $test_id, PDO::PARAM_INT);
    $stmt->execute();

    // Close the database connection
    db_close($db);

    $message = "A test was deleted for test ID \"{$test_id}\" by username \"" . $_SESSION['user'] . "\".";
    write_log($test_id + 1000, $_SESSION['uid'], $message, "test");
    
    return $array;
}

/***********************************************************
 * FUNCTION: GET FRAMEWORK CONTROL TEST LIST BY CONTROL ID *
 ***********************************************************/
function get_framework_control_tests_by_control_id($framework_control_id){
    // Open the database connection
    $db = db_open();

    $stmt = $db->prepare("SELECT t1.*, t2.name tester_name
        FROM `framework_control_tests` t1
            LEFT JOIN `user` t2 ON t1.tester = t2.value
        WHERE t1.framework_control_id=:framework_control_id");
    $stmt->bindParam(":framework_control_id", $framework_control_id, PDO::PARAM_INT);
    $stmt->execute();

    // Store the list in the array
    $array = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    db_close($db);

    return $array;
}

/***************************************************
 * FUNCTION: GET FRAMEWORK CONTROL TEST BY TEST ID *
 ***************************************************/
function get_framework_control_test_by_id($test_id){
    // Open the database connection
    $db = db_open();

    $stmt = $db->prepare("SELECT t1.*, t2.name tester_name
        FROM `framework_control_tests` t1
            LEFT JOIN `user` t2 ON t1.tester = t2.value
        WHERE t1.id=:test_id");
    $stmt->bindParam(":test_id", $test_id, PDO::PARAM_INT);
    $stmt->execute();

    // Store the test in the array
    $test = $stmt->fetch(PDO::FETCH_ASSOC);

    // Close the database connection
    db_close($db);

    return $test;
} 

/*********************************************************
 * FUNCTION: GET FRAMEWORK CONTROL TEST AUDIT BY TEST ID *
 *********************************************************/
function get_framework_control_test_audit_by_id($test_audit_id){
    // Open the database connection
    $db = db_open();


    $stmt = $db->prepare("
        SELECT t1.*, t2.name tester_name, t3.short_name control_name, GROUP_CONCAT(DISTINCT t4.name) framework_name, t5.id result_id, t5.test_result, t5.summary, t5.test_date, t5.submitted_by, t5.submission_date
        FROM `framework_control_test_audits` t1
            LEFT JOIN `user` t2 ON t1.tester = t2.value
            LEFT JOIN `framework_controls` t3 ON t1.framework_control_id = t3.id
            LEFT JOIN `frameworks` t4 ON t3.framework_ids=t4.value OR t3.framework_ids like concat('%,', t4.value) OR t3.framework_ids like concat(t4.value, ',%') OR t3.framework_ids like concat('%,', t4.value, ',%')
            LEFT JOIN `framework_control_test_results` t5 ON t1.id=t5.test_audit_id
        WHERE t1.id=:test_audit_id
    ");
    $stmt->bindParam(":test_audit_id", $test_audit_id, PDO::PARAM_INT);
    $stmt->execute();

    // Store the test in the array
    $test = $stmt->fetch(PDO::FETCH_ASSOC);

    // Close the database connection
    db_close($db);
    
    if($test['framework_name']){
        $framework_names = explode(",", $test['framework_name']);
        foreach($framework_names as &$framework_name)
        {
            $framework_name = try_decrypt(trim($framework_name));
        }
        $test['framework_name'] = implode(", ", $framework_names);
    }

    return $test;
} 

/*************************************
 * FUNCTION: DISPLAY INITIATE AUDITS *
 *************************************/
function display_initiate_audits()
{
    global $lang, $escaper;
    
    echo "
        <div id='filter-container'>
            <div class='row-fluid'>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['FilterByText']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                <input type='text' id='filter_by_text' class='form-control'>
                </div>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['DesiredFrequency']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                    <input type='text' id='filter_by_frequency' class='form-control'>
                </div>
                <div class='span2 hide' align='right'>
                    <strong>".$escaper->escapeHtml($lang['Status']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2 hide'>";
                    create_multiple_dropdown("test_status", "all", "filter_by_status", NULL, false);
                echo "</div>
            </div>
            <div class='row-fluid'>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['Framework']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                    <select id='filter_by_framework' class='' multiple=''>\n";
                        $options = getAvailableControlFrameworkList();
                        is_array($options) || $options = array();
                        foreach($options as $option){
                            echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                        }
                    echo "</select>
                </div>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['Control']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                    <input type='text' id='filter_by_control' class='form-control'>
                </div>
                
            </div>
        </div>
    ";
    
    echo "
        <script>
            // Redraw Past Audit table
            function redraw(){
                $('#initiate_audit_treegrid').treegrid('reload');
            } 
            
            // timer identifier
            var typingTimer;                
            // time in ms (1 second)
            var doneTypingInterval = 1000;  

            $('#filter_by_framework').multiselect({
                allSelectedText: '".$escaper->escapeHtml($lang['ALL'])."',
                includeSelectAllOption: true,
                onDropdownHide: function(){
                    redraw();
                }
            });
            
            $('#filter_by_status').multiselect({
                allSelectedText: '".$escaper->escapeHtml($lang['ALL'])."',
                includeSelectAllOption: true,
                onDropdownHide: function(){
                    redraw();
                }
            });
            
            // Search filter event
            $('#filter_by_text').keyup(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redraw, doneTypingInterval);
            });

            // Search filter event
            $('#filter_by_control').keyup(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redraw, doneTypingInterval);
            });

            // Search filter event
            $('#filter_by_frequency').keyup(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redraw, doneTypingInterval);
            });

        </script>
    ";
    echo "<table id=\"initiate_audit_treegrid\" class='easyui-treegrid' 
            data-options=\"
                iconCls: 'icon-ok',
                animate: true,
                collapsible: false,
                fitColumns: true,
                url: '".$_SESSION['base_url']."/api/compliance/initiate_audits',
                onBeforeLoad: function(row, param){
                    param.filter_by_text = $('#filter_by_text').val();
                    param.filter_by_status = $('#filter_by_status').val();
                    param.filter_by_frequency = $('#filter_by_frequency').val();
                    param.filter_by_framework = $('#filter_by_framework').val();
                    param.filter_by_control = $('#filter_by_control').val();
                },
                method: 'get',
                idField: 'id',
                treeField: 'name',
                scrollbarSize: 0
            \">";
    echo "<thead>";
    echo "<th data-options=\"field:'name'\" style='width: 220px'>".$escaper->escapeHtml($lang['Name'])."</th>";
    echo "<th data-options=\"field:'desired_frequency'\" >".$escaper->escapeHtml($lang['DesiredFrequency'])."</th>";
    echo "<th data-options=\"field:'last_audit_date'\" >".$escaper->escapeHtml($lang['LastAuditDate'])."</th>";
    echo "<th data-options=\"field:'next_audit_date'\" >".$escaper->escapeHtml($lang['NextAuditDate'])."</th>";
    echo "<th data-options=\"field:'status'\" >".$escaper->escapeHtml($lang['Status'])."</th>";
    echo "<th data-options=\"field:'action'\" >&nbsp;</th>";
    echo "</thead>\n";
    echo "</table>";
    
}

/***********************************
 * FUNCTION: DISPLAY ACTIVE AUDITS *
 ***********************************/
function display_active_audits(){
    global $lang, $escaper;
    
    $tableID = "active-audits";
    
    echo "
        <div id='filter-container'>
            <div class='row-fluid'>
                <div class='span1' align='right'>
                    <strong>".$escaper->escapeHtml($lang['Framework']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span3'>
                    <select id='filter_by_framework' multiple=''>
                        <option selected value='-1'>".$escaper->escapeHtml($lang['Unassigned'])."</option>\n";
                        $options = getAvailableControlFrameworkList();
                        is_array($options) || $options = array();
                        foreach($options as $option){
                            echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                        }
                    echo "</select>
                </div>
                <div class='span1' align='right'>
                    <strong>".$escaper->escapeHtml($lang['Status']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span3'>";
                    create_multiple_dropdown("test_status", "all", "filter_by_status", NULL, true, $escaper->escapeHtml($lang['Unassigned']), "0");
                echo "</div>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['FilterByText']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                    <input type='text' id='filter_by_text' class='form-control'>
                </div>
            </div>
        </div>

        <table id=\"{$tableID}\" width=\"100%\" class=\"risk-datatable table table-bordered table-striped table-condensed\">
            <thead >
                <tr >
                    <th valign=\"top\">".$escaper->escapeHtml($lang['TestName'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['TestFrequency'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['Tester'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['Objective'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['ControlName'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['FrameworkName'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['Status'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['LastAuditDate'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['NextAuditDate'])."</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <br>
        <script>
            var pageLength = 10;
            var form = $('#{$tableID}').parents('form');
            var datatableInstance = $('#{$tableID}').DataTable({
                bFilter: false,
                bLengthChange: false,
                processing: true,
                serverSide: true,
                bSort: true,
                pagingType: \"full_numbers\",
                dom : \"flrtip\",
                pageLength: pageLength,
                dom : \"flrti<'#view-all.view-all'>p\",
                createdRow: function(row, data, index){
                    var background = $('.background-class', $(row)).data('background');
                    $(row).find('td').addClass(background)
                },
                ajax: {
                    url: BASE_URL + '/api/compliance/active_audits',
                    data: function(d){
                        d.filter_text = \$(\"#filter_by_text\").val();
                        d.filter_framework  = \$(\"#filter_by_framework\").val();
                        d.filter_status  = \$(\"#filter_by_status\").val();
                    },
                    complete: function(response){
                    }
                }
            });
            
            // Add paginate options
            datatableInstance.on('draw', function(e, settings){
                $('.paginate_button.first').html('<i class=\"fa fa-chevron-left\"></i><i class=\"fa fa-chevron-left\"></i>');
                $('.paginate_button.previous').html('<i class=\"fa fa-chevron-left\"></i>');

                $('.paginate_button.last').html('<i class=\"fa fa-chevron-right\"></i><i class=\"fa fa-chevron-right\"></i>');
                $('.paginate_button.next').html('<i class=\"fa fa-chevron-right\"></i>');
            })
            
            // Add all text to View All button on bottom
            $('.view-all').html(\"".$escaper->escapeHtml($lang['ALL'])."\");

            // View All
            $(\".view-all\").click(function(){
                var oSettings =  datatableInstance.settings();
                oSettings[0]._iDisplayLength = -1;
                datatableInstance.draw()
                $(this).addClass(\"current\");
            })
            
            // Page event
            $(\"body\").on(\"click\", \"span > .paginate_button\", function(){
                var index = $(this).attr('aria-controls').replace(\"DataTables_Table_\", \"\");

                var oSettings =  datatableInstance.settings();
                if(oSettings[0]._iDisplayLength == -1){
                    $(this).parents(\".dataTables_wrapper\").find('.view-all').removeClass('current');
                    oSettings[0]._iDisplayLength = pageLength;
                    datatableInstance.draw()
                }
            })
            
            // Redraw Past Audit table
            function redrawActiveAudits(){
                $(\"#{$tableID}\").DataTable().draw();
            } 
            
            // timer identifier
            var typingTimer;                
            // time in ms (1 second)
            var doneTypingInterval = 1000;  

            $('#filter_by_framework').multiselect({
                allSelectedText: '".$escaper->escapeHtml($lang['ALL'])."',
                includeSelectAllOption: true
            });
            
            $('#filter_by_status').multiselect({
                allSelectedText: '".$escaper->escapeHtml($lang['ALL'])."',
                includeSelectAllOption: true
            });
            
            // Search filter event
            $('#filter_by_text').keyup(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redrawActiveAudits, doneTypingInterval);
            });

            $('#filter_by_framework').change(function(){
                redrawActiveAudits();
            });
            
            $('#filter_by_status').change(function(){
                redrawActiveAudits();
            });
            
        </script>
    ";
    
}

/************************************
 * INITIATE FRAMEWORK CONTROL TESTS *
 ************************************/
function initiate_framework_control_tests($type, $id){
    // Open the database connection
    $db = db_open();

    switch($type){
        case "framework":
            $framework = get_framework($id);
            $name = $framework['name'];
            
            $framework_filter_pattern1 = $id;
            $framework_filter_pattern2 = "%,".$id;
            $framework_filter_pattern3 = $id.",%";
            $framework_filter_pattern4 = "%,".$id.",%";
            
            $sql = "
                INSERT INTO `framework_control_test_audits`(test_id, tester, test_frequency, last_date, next_date, name, objective, test_steps, approximate_time, expected_results, framework_control_id, desired_frequency, status, created_at)
                SELECT 
                    t1.id as test_id, t1.tester, t1.test_frequency, t1.last_date, t1.next_date, t1.name, t1.objective, t1.test_steps, t1.approximate_time, t1.expected_results, t1.framework_control_id, t1.desired_frequency, 0 as status, '".date("Y-m-d H:i:s")."' as created_at
                FROM framework_control_tests t1
                    INNER JOIN framework_controls t2 ON t1.framework_control_id=t2.id
                WHERE
                    t2.framework_ids LIKE '{$framework_filter_pattern1}' OR t2.framewORk_ids LIKE '{$framework_filter_pattern2}' OR t2.framework_ids LIKE '{$framework_filter_pattern3}' OR t2.framework_ids LIKE '{$framework_filter_pattern4}';
            ";
            // Create temp table from framework_control_test
            $stmt = $db->prepare($sql);
            $stmt->execute();

//            $message = "A framework was initiated for framework ID \"{$id}\" by username \"" . $_SESSION['user'] . "\".";
//            write_log($id + 1000, $_SESSION['uid'], $message, "framework");
            
            // Get child test ID list of a framework
            $sql = "
                SELECT 
                    t1.id as test_id, t1.name
                FROM framework_control_tests t1
                    INNER JOIN framework_controls t2 ON t1.framework_control_id=t2.id
                WHERE
                    t2.framework_ids LIKE '{$framework_filter_pattern1}' OR t2.framewORk_ids LIKE '{$framework_filter_pattern2}' OR t2.framework_ids LIKE '{$framework_filter_pattern3}' OR t2.framework_ids LIKE '{$framework_filter_pattern4}';
            ";
            // Create temp table from framework_control_test
            $stmt = $db->prepare($sql);
            $stmt->execute();
            // Store tests in the array
            $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($tests as $test){
                $message = "An active audit for \"{$test["name"]}\" was initiated by username \"" . $_SESSION['user'] . "\".";
                write_log($test['test_id'] + 1000, $_SESSION['uid'], $message, "test");
            }
        break;
        case "control":
            $control = get_framework_control($id);
            $name = $control['short_name'];

            $sql = "
                INSERT INTO `framework_control_test_audits`(test_id, tester, test_frequency, last_date, next_date, name, objective, test_steps, approximate_time, expected_results, framework_control_id, desired_frequency, status, created_at)
                SELECT 
                    t1.id as test_id, t1.tester, t1.test_frequency, t1.last_date, t1.next_date, t1.name, t1.objective, t1.test_steps, t1.approximate_time, t1.expected_results, t1.framework_control_id, t1.desired_frequency, 0 as status, '".date("Y-m-d H:i:s")."' as created_at
                FROM framework_control_tests t1
                    INNER JOIN framework_controls t2 ON t1.framework_control_id=t2.id
                WHERE
                    t2.id=:control_id;
            ";
            
            // Create temp table from framework_control_test
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":control_id", $id, PDO::PARAM_INT);
            $stmt->execute();

//            $message = "A control was initiated for control ID \"{$id}\" by username \"" . $_SESSION['user'] . "\".";
//            write_log($id + 1000, $_SESSION['uid'], $message, "control");

            // Get child test ID list of a control
            $sql = "
                SELECT 
                    t1.id as test_id, t1.name
                FROM framework_control_tests t1
                    INNER JOIN framework_controls t2 ON t1.framework_control_id=t2.id
                WHERE
                    t2.id=:control_id;
            ";
            // Create temp table from framework_control_test
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":control_id", $id, PDO::PARAM_INT);
            $stmt->execute();
            // Store tests in the array
            $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($tests as $test){
                $message = "An active audit for \"{$test["name"]}\" was initiated by username \"" . $_SESSION['user'] . "\".";
                write_log($test['test_id'] + 1000, $_SESSION['uid'], $message, "test");
            }
        break;
        case "test":
            $test = get_framework_control_test_by_id($id);
            $name = $test['name'];

            $sql = "
                INSERT INTO `framework_control_test_audits`(test_id, tester, test_frequency, last_date, next_date, name, objective, test_steps, approximate_time, expected_results, framework_control_id, desired_frequency, status, created_at)
                SELECT 
                    t1.id as test_id, t1.tester, t1.test_frequency, t1.last_date, t1.next_date, t1.name, t1.objective, t1.test_steps, t1.approximate_time, t1.expected_results, t1.framework_control_id, t1.desired_frequency, 0 as status, '".date("Y-m-d H:i:s")."' as created_at
                FROM framework_control_tests t1
                WHERE
                    t1.id=:test_id;
            ";
            
            // Create temp table from framework_control_test
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":test_id", $id, PDO::PARAM_INT);
            
            $stmt->execute();

            $message = "An active audit for \"{$test["name"]}\" was initiated by username \"" . $_SESSION['user'] . "\".";
            write_log($id + 1000, $_SESSION['uid'], $message, "test");
        break;
    }

    // Close the database connection
    db_close($db);
    
    return $name;
}

/***********************************************
 * FUNCTION: GET FRAMEWORK CONTROL TEST AUDITS *
 ***********************************************/
function get_framework_control_test_audits($active, $columnName=false, $columnDir=false, $filters=false){
    global $escaper;

    // Open the database connection
    $db = db_open();
    
    $sql = "
        SELECT t1.id, t1.test_id, t1.test_frequency, t1.last_date, t1.next_date, t1.name, t1.objective, t1.test_steps, t1.approximate_time, t1.expected_results, t1.framework_control_id, t1.desired_frequency, t1.status, t1.created_at, 
        t2.name tester_name, t3.short_name control_name, GROUP_CONCAT(DISTINCT t4.name) framework_name, t5.test_result, t5.summary, t5.submitted_by, t5.submission_date, ifnull(t6.name, '--') audit_status_name
        FROM `framework_control_test_audits` t1
            LEFT JOIN `user` t2 ON t1.tester = t2.value
            LEFT JOIN `framework_controls` t3 ON t1.framework_control_id = t3.id
            LEFT JOIN `frameworks` t4 ON (t3.framework_ids=t4.value OR t3.framework_ids like concat('%,', t4.value) OR t3.framework_ids like concat(t4.value, ',%') OR t3.framework_ids like concat('%,', t4.value, ',%')) AND t4.status=1
            LEFT JOIN `framework_control_test_results` t5 ON t1.id=t5.test_audit_id
            LEFT JOIN `test_status` t6 ON t1.status=t6.value
    ";

    $wheres = array();

    $closed_audit_status = get_setting("closed_audit_status");

	// Active audits
    if($active)
    {
        $wheres[] = " t1.status<>'".$closed_audit_status."' ";
    }
    // Past audits
    else
    {
        $wheres[] = " t1.status='".$closed_audit_status."' ";
    }
    if($filters !== false){
        if(!empty($filters['filter_control'])){
            $wheres[] = " t3.short_name like :filter_control ";
            $filters['filter_control'] = "%{$filters['filter_control']}%";
        }
        if(!empty($filters['filter_test_result'])){
            $wheres[] = " t5.test_result like :filter_test_result ";
            $filters['filter_test_result'] = "%{$filters['filter_test_result']}%";
        }
        if(isset($filters['filter_framework'])){
            if($filters['filter_framework']){
                $framework_wheres = [];

                foreach($filters['filter_framework'] as $val){
                    if(!$val)
                        continue;
                    $val = (int)$val;
                    // If unassigned option.
                    if($val == -1)
                    {
                        $framework_wheres[] = "(t3.framework_ids is NULL OR t3.framework_ids='')";
                    }
                    else
                    {
                        $framework_filter_pattern1 = $val;
                        $framework_filter_pattern2 = "%,".$val;
                        $framework_filter_pattern3 = $val.",%";
                        $framework_filter_pattern4 = "%,".$val.",%";

                        $framework_wheres[] = "(t3.framework_ids like '{$framework_filter_pattern1}' or t3.framework_ids like '{$framework_filter_pattern2}' or t3.framework_ids like '{$framework_filter_pattern3}' or t3.framework_ids like '{$framework_filter_pattern4}')";
                    }
                }
                
                $wheres[] = "(". implode(" OR ", $framework_wheres) . ")";
            }
            else
            {
                $wheres[] = " 0 ";
            }
        
        }
        if(isset($filters['filter_status'])){
            if($filters['filter_status'])
            {
                foreach($filters['filter_status'] as &$val){
                    $val = (int)$val;
                }
                unset($val);
                
                $wheres[] = " t1.status IN (".implode(",", $filters['filter_status']).") ";
            }
            else
            {
                $wheres[] = " 0 ";
            }
        }
        if(!empty($filters['filter_start_audit_date'])){
            $wheres[] = " t1.last_date>=:filter_start_audit_date ";
        }
        if(!empty($filters['filter_end_audit_date'])){
            $wheres[] = " t1.last_date<=:filter_end_audit_date ";
        }
    }

    $sql .= " WHERE ".implode(" AND ", $wheres);

    $sql .= " GROUP BY t1.id ";
    
    if($columnName == "test_name"){
        $sql .= " ORDER BY t1.name {$columnDir} ";
    }
    elseif($columnName == "test_frequency"){
        $sql .= " ORDER BY t1.test_frequency {$columnDir} ";
    }
    elseif($columnName == "tester"){
        $sql .= " ORDER BY t2.name {$columnDir} ";
    }
    elseif($columnName == "objective"){
        $sql .= " ORDER BY t1.objective {$columnDir} ";
    }
    elseif($columnName == "control_name"){
        $sql .= " ORDER BY t3.short_name {$columnDir} ";
    }
    elseif($columnName == "framework_name"){
        $sql .= " ORDER BY GROUP_CONCAT(DISTINCT t4.name) {$columnDir} ";
    }
    elseif($columnName == "status"){
        $sql .= " ORDER BY t6.name {$columnDir} ";
    }
    elseif($columnName == "last_date"){
        $sql .= " ORDER BY t1.last_date {$columnDir} ";
    }
    elseif($columnName == "next_date"){
        $sql .= " ORDER BY t1.next_date {$columnDir} ";
    }
    elseif($columnName == "test_result"){
        $sql .= " ORDER BY t5.test_result {$columnDir} ";
    }
    else{
        $sql .= " ORDER BY t1.created_at ";
    }

    $stmt = $db->prepare($sql);
    if($filters !== false){
        if(!empty($filters['filter_control'])){
            $stmt->bindParam(":filter_control", $filters['filter_control'], PDO::PARAM_STR, 200);
        }
        if(!empty($filters['filter_test_result'])){
            $stmt->bindParam(":filter_test_result", $filters['filter_test_result'], PDO::PARAM_STR, 200);
        }
        if(!empty($filters['filter_start_audit_date'])){
            $stmt->bindParam(":filter_start_audit_date", $filters['filter_start_audit_date'], PDO::PARAM_STR, 10);
        }
        if(!empty($filters['filter_end_audit_date'])){
            $stmt->bindParam(":filter_end_audit_date", $filters['filter_end_audit_date'], PDO::PARAM_STR, 10);
        }
    }

    $stmt->execute();

    // Store tests in the array
    $test_audits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    db_close($db);
    
    $filtered_test_audits = array();
    
    foreach($test_audits as &$test_audit){
        
        $framework_names = explode(",", $test_audit['framework_name']);
        $decrypted_framework_names = [];
        foreach($framework_names as $framework_name){
            $decrypted_framework_names[] = try_decrypt(trim($framework_name));
        }
        
        $test_audit['framework_name'] = implode(", ", $decrypted_framework_names);

        // Filter by search text
        if(
            empty($filters['filter_text']) 
            || (stripos($test_audit['name'], $filters['filter_text']) !== false) 
            || (stripos($test_audit['last_date'], $filters['filter_text']) !== false) 
            || (stripos($test_audit['control_name'], $filters['filter_text']) !== false) 
            || (stripos($test_audit['framework_name'], $filters['filter_text']) !== false) 
            || (stripos($test_audit['audit_status_name'], $filters['filter_text']) !== false) 
            || (stripos($test_audit['test_result'], $filters['filter_text']) !== false) 
            || (stripos($test_audit['objective'], $filters['filter_text']) !== false) 
        )
        {
            $filtered_test_audits[] = $test_audit;
        }
    }

    return $filtered_test_audits;
}

/*******************************
 * FUNCTION: SAVE TEST COMMENT *
 *******************************/
function save_test_comment($test_audit_id, $comment){
    $user    =  $_SESSION['uid'];
    
    // Open the database connection
    $db = db_open();
    
    $sql = "
        INSERT INTO `framework_control_test_comments`(`test_audit_id`, `user`, `comment`) VALUES(:test_audit_id, :user, :comment);
    ";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":test_audit_id", $test_audit_id, PDO::PARAM_STR);
    $stmt->bindParam(":comment", $comment, PDO::PARAM_INT);
    $stmt->bindParam(":user", $user, PDO::PARAM_INT);
    
    // Insert a test result
    $stmt->execute();
    
    // Close the database connection
    db_close($db);
}

/**********************************
 * FUNCTION: GET COMPLIANCE FILES *
 **********************************/
function get_compliance_files($ref_id, $ref_type){
    // Open the database connection
    $db = db_open();

    $stmt = $db->prepare("SELECT t1.* FROM `compliance_files` t1 WHERE t1.`ref_id`=:ref_id and t1.`ref_type`=:ref_type;");
    $stmt->bindParam(":ref_id", $ref_id, PDO::PARAM_INT);
    $stmt->bindParam(":ref_type", $ref_type, PDO::PARAM_STR, 20);
    $stmt->execute();

    $files = $stmt->fetchAll();

    // Close the database connection
    db_close($db);
    
    return $files;
}

/*******************************************
 * FUNCTION: DISPLAY TESTING IN COMPLIANCE *
 *******************************************/
function display_testing()
{
    global $lang, $escaper;
    
    $test_audit_id = (int)$_GET['id'];
    
    $test_audit = get_framework_control_test_audit_by_id($test_audit_id);
    
    // If test date is not set, set today as default
    if(!$test_audit['test_date'] || $test_audit['test_date']=="0000-00-00")
    {
        $test_audit['test_date'] = date("Y-m-d");
    }
    
    echo "
        <form class='well' method='POST' enctype='multipart/form-data'>
            <h4>".$escaper->escapeHtml($test_audit['name'])."</h4>
            <table width='100%'>
                <tr>
                    <td width='50%' valign='top'>
                        <table width='100%'>
                            <tr>
                                <td valign='top'>".$escaper->escapeHtml($lang['AuditStatus']).":&nbsp;&nbsp;</td>
                                <td>";
                                    create_dropdown("test_status", $test_audit['status'], "status", true, false, false, "", "--");
                                    
                                echo "
                                </td>
                            </tr>
                            <tr>
                                <td valign='top'>".$escaper->escapeHtml($lang['TestResult']).":&nbsp;&nbsp;</td>
                                <td>
                                    <select name='test_result'>
                                        <option value=''>--</option>
                                        <option ". ($test_audit['test_result'] == 'Pass' ? "selected" : "") ." value='Pass'>Pass</option>
                                        <option ". ($test_audit['test_result'] == 'Inconclusive' ? "selected" : "") ." value='Inconclusive'>Inconclusive</option>
                                        <option ". ($test_audit['test_result'] == 'Fail' ? "selected" : "") ." value='Fail'>Fail</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td valign='top'>".$escaper->escapeHtml($lang['Tester']).":&nbsp;&nbsp;</td>
                                <td>";
                                    create_dropdown("user", $test_audit['tester'], "tester", false, false, false);
                                echo "
                                </td>
                            </tr>
                            <tr>
                                <td valign='top'>".$escaper->escapeHtml($lang['TestDate']).":&nbsp;&nbsp;</td>
                                <td>
                                    <input name='test_date' value='{$test_audit['test_date']}' required class='datepicker form-control' type='text'>
                                </td>
                            </tr>
                        </table>                    
                    </td>
                    <td>
                        <table width='100%'>
                            <tr>
                                <td valign='top'>".$escaper->escapeHtml($lang['Summary']).":&nbsp;&nbsp;</td>
                                <td>
                                    <textarea name='summary' class='form-control' style='width:100%'>".$escaper->escapeHtml($test_audit['summary'])."</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td valign='top'>".$escaper->escapeHtml($lang['Attachment']).":&nbsp;&nbsp;</td>
                                <td>
                                     <div class=\"file-uploader\">
                                        <label for=\"file-upload\" class=\"btn\">Choose File</label>
                                        <span class=\"file-count-html\"> <span class=\"file-count\">".count(get_compliance_files($test_audit_id, "test_audit"))."</span> File Added</span>
                                        <p><font size=\"2\"><strong>Max ". round(get_setting('max_upload_size')/1024/1024) ." Mb</strong></font></p>
                                        <ul class=\"exist-files\">
                                            ";
                                            display_compliance_files($test_audit_id, "test_audit");
                                        echo "
                                        </ul>
                                        <ul class=\"file-list\">
                                            
                                        </ul>
                                        <input type=\"file\" id=\"file-upload\" name=\"file[]\" class=\"hidden-file-upload active\" />
                                    </div>
                                </td>
                            </tr>
                        </table>                    
                    </td>
                </tr>
                <tr>
                    <td align='right' colspan='2'><button name='submit_test_result'  type='submit'>".$escaper->escapeHtml($lang['Submit'])."</button></td>
                </tr>
            </table>

        </form>
    ";
    
    display_test_audit_comment($test_audit_id);
    echo "
        <script>
            $(\".datepicker\").datepicker({dateFormat: \"yy-mm-dd\"});
        </script>
    ";
}

/**************************************
 * FUNCTION: DISPLAY COMPLIANCE FILES *
 **************************************/
function display_compliance_files($ref_id, $ref_type){
    global $lang, $escaper;
    
    $files = get_compliance_files($ref_id, $ref_type);
    
    $html = "";
    
    foreach($files as $file){
        $html .= "
            <li>            
                <div class=\"file-name\"><a href=\"".$_SESSION['base_url']."/compliance/download.php?id=".$escaper->escapeHtml($file['unique_name'])."\">".$escaper->escapeHtml($file['name'])."</a></div>
                <a href=\"#\" class=\"remove-file\" data-id=\"file-upload-0\"><i class=\"fa fa-remove\"></i></a>
                <input name=\"unique_names[]\" value=\"{$file['unique_name']}\" type=\"hidden\">
            </li>            
        ";
    }
    
    echo $html;
    
    return count($files);
}

/**************************************
 * FUNCTION: DISPLAY TEST AUDIT TRAIL *
 **************************************/
function display_test_audit_trail($test_audit_id)
{
    global $escaper, $lang;
    
    echo "
        <div class=\"row-fluid comments--wrapper\" >
            <div class=\"well\" >
                <h4 class=\"collapsible--toggle\"><span><i class=\"fa fa-caret-right\"></i>".$escaper->escapeHtml($lang['AuditTrail'])."</span></h4>
                <div class=\"collapsible\" style='display:none'>
                    <div class=\"row-fluid\">
                        <div class=\"span12 audit-trail\">";
                            get_audit_trail($test_audit_id+1000, 36500, 'test_audit');
                        echo "</div>
                    </div>
                </div>
            </div>
        </div>
    ";
}

/****************************************
 * FUNCTION: DISPLAY TEST AUDIT COMMENT *
 ****************************************/
function display_test_audit_comment($test_audit_id)
{
    global $escaper, $lang;
    
    $test_audit_id = (int)$test_audit_id;
    
    echo "
        <div class=\"row-fluid comments--wrapper\">

            <div class=\"well\">
                <h4 class=\"collapsible--toggle clearfix\">
                    <span><i class=\"fa  fa-caret-right\"></i>".$escaper->escapeHtml($lang['Comments'])."</span>
                    <a href=\"#\" class=\"add-comments pull-right\"><i class=\"fa fa-plus\"></i></a>
                </h4>

                <div class=\"collapsible\" style='display:none'>
                    <div class=\"row-fluid\">
                        <div class=\"span12\">

                            <form id=\"comment\" class=\"comment-form\" name=\"add_comment\" method=\"post\" action=\"/management/comment.php?id={$test_audit_id}\">
                                <input type='hidden' name='id' value='{$test_audit_id}'>
                                <textarea style=\"width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;\" name=\"comment\" cols=\"50\" rows=\"3\" id=\"comment-text\" class=\"comment-text\"></textarea>
                                <div class=\"form-actions text-right\" id=\"comment-div\">
                                    <input class=\"btn\" id=\"rest-btn\" value=\"".$escaper->escapeHtml($lang['Reset'])."\" type=\"reset\" />
                                    <button id=\"comment-submit\" type=\"submit\" name=\"submit\" class=\"comment-submit btn btn-primary\" >".$escaper->escapeHtml($lang['Submit'])."</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class=\"row-fluid\">
                        <div class=\"span12\">
                            <div class=\"comments--list clearfix\">
                                ".get_testing_comment_list($test_audit_id)."
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ";
    
    echo "
        <script>

            \$('body').on('click', '.collapsible--toggle span', function(event) {
                event.preventDefault();
                var container = \$(this).parents('.comments--wrapper');
                \$(this).parents('.collapsible--toggle').next('.collapsible').slideToggle('400');
                \$(this).find('i').toggleClass('fa-caret-right fa-caret-down');
                if($('.collapsible', container).is(':visible') && $('.add-comments', container).hasClass('rotate')){
                    $('.add-comments', container).click()
                }
            });

            $('body').on('click', '.add-comments', function(event) {
                event.preventDefault();
                var container = \$(this).parents('.comments--wrapper');
                if(!$('.collapsible', container).is(':visible')){
                    $(this).parents('.collapsible--toggle').next('.collapsible').slideDown('400');
                    $(this).parent().find('span i').removeClass('fa-caret-right');
                    $(this).parent().find('span i').addClass('fa-caret-down');
                }
                $(this).toggleClass('rotate');
                $('.comment-form', container).fadeToggle('100');
            });
            
            $('body').on('click', '.comment-submit', function(e){
                e.preventDefault();
                var container = $('.comments--wrapper');
                
                if(!$('.comment-text', container).val()){
                    $('.comment-text', container).focus();
                    return;
                }
                
                var risk_id = $('.large-text', container).html();
                
                var getForm = \$(this).parents('form', container);
                var form = new FormData($(getForm)[0]);

                $.ajax({
                    type: 'POST',
                    url: BASE_URL + '/api/compliance/save_audit_comment',
                    data: form,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        $('.comments--list', container).html(data.data);
                        $('.comment-text', container).val('')
                        $('.comment-text', container).focus()
                    }
                })
            })
        
        </script>
    ";
      
    return true;
}

/******************************************
 * FUNCTION: DISPLAY TESTING COMMENT LIST *
 ******************************************/
function get_testing_comment_list($test_audit_id)
{
    global $escaper;

    // Open the database connection
    $db = db_open();

    // Get the comments
    $stmt = $db->prepare("SELECT a.date, a.comment, b.name FROM framework_control_test_comments a LEFT JOIN user b ON a.user = b.value WHERE a.test_audit_id=:test_audit_id ORDER BY a.date DESC");

    $stmt->bindParam(":test_audit_id", $test_audit_id, PDO::PARAM_INT);

    $stmt->execute();

    // Store the list in the array
    $comments = $stmt->fetchAll();

    // Close the database connection
    db_close($db);

    $returnHTML = "";
    foreach ($comments as $comment)
    {
//        $text = try_decrypt($comment['comment']);
        $text = $comment['comment'];
        $date = date(DATETIME, strtotime($comment['date']));
        $user = $comment['name'];
        
        if($text != null){
            $returnHTML .= "<p class=\"comment-block\">\n";
            $returnHTML .= "<b>" . $escaper->escapeHtml($date) ." by ". $escaper->escapeHtml($user) ."</b><br />\n";
            $returnHTML .= $escaper->escapeHtml($text);
            $returnHTML .= "</p>\n";
        }
    }

    return $returnHTML;
    
}

/********************************
 * FUNCTION: INSERT TEST RESULT *
 ********************************/
function save_test_result($test_audit_id, $status, $test_result, $tester, $test_date, $summary)
{
    $test_audit = get_framework_control_test_audit_by_id($test_audit_id);
    
    $submitted_by = $_SESSION['uid'];
    $submission_date = date("Y-m-d H:i:s");
    
    // Open the database connection
    $db = db_open();
    
    // Check submitted result is existing
    if(!$test_audit['result_id']){
        $sql = "INSERT INTO framework_control_test_results(`test_audit_id`, `test_result`, `summary`, `test_date`, `submitted_by`, `submission_date`) VALUES(:test_audit_id, :test_result, :summary, :test_date, :submitted_by, :submission_date);";
    }
    else{
        $sql = "UPDATE framework_control_test_results SET `test_result`=:test_result, `summary`=:summary, `test_date`=:test_date, `submitted_by`=:submitted_by, `submission_date`=:submission_date WHERE `test_audit_id`=:test_audit_id;";
    }
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":test_audit_id", $test_audit_id, PDO::PARAM_INT);
    $stmt->bindParam(":test_result", $test_result, PDO::PARAM_STR);
    $stmt->bindParam(":test_date", $test_date, PDO::PARAM_STR);
    $stmt->bindParam(":summary", $summary, PDO::PARAM_STR);
    $stmt->bindParam(":submitted_by", $submitted_by, PDO::PARAM_STR);
    $stmt->bindParam(":submission_date", $submission_date, PDO::PARAM_STR);
    $stmt->execute();
    
    // Update tester in test audit table
    $sql = "UPDATE framework_control_test_audits SET `tester`=:tester WHERE `id`=:test_audit_id;";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":tester", $tester, PDO::PARAM_INT);
    $stmt->bindParam(":test_audit_id", $test_audit_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Close the database connection
    db_close($db);
    
    // Update status in test_audit table
    update_test_audit_status($test_audit_id, $status);
    
    $closed_audit_status = get_setting("closed_audit_status");
    
	// Check audit was closed
    if($status == $closed_audit_status)
    {
        // update last audit date and next audti date in test_audit table
        update_last_and_next_auditdate($test_audit_id, $test_date);
    }
    
    return true;
}

/*********************************************************
 * FUNCTION: UPDATE TESTAUDIT, CONTROL, FRAMEWORK STATUS *
 *********************************************************/
function update_test_audit_status($test_audit_id, $status)
{
    // Open the database connection
    $db = db_open();
    
    // Update test audit status
    $sql = "UPDATE framework_control_test_audits SET `status`=:status WHERE id=:test_audit_id;";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":status", $status, PDO::PARAM_INT);
    $stmt->bindParam(":test_audit_id", $test_audit_id, PDO::PARAM_INT);
    $stmt->execute();
    
    //  Update Framework and Control status....
    
    // Close the database connection
    db_close($db);
}

/***********************************************************
 * FUNCTION: UPDATE LAST DATE AND NEXT DATE IN AUDIT TABLE *
 ***********************************************************/
function update_last_and_next_auditdate($test_audit_id, $last_date)
{
    // Get test by ID
    $test_audit = get_framework_control_test_audit_by_id($test_audit_id);
    $next_date = date("Y-m-d", strtotime($last_date) + $test_audit['test_frequency']*24*60*60);

    // Open the database connection
    $db = db_open();
    
    $sql = "UPDATE `framework_control_test_audits` SET `last_date`=:last_date, `next_date`=:next_date WHERE id=:test_audit_id;";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":test_audit_id", $test_audit_id, PDO::PARAM_INT);
    $stmt->bindParam(":last_date", $last_date, PDO::PARAM_INT);
    $stmt->bindParam(":next_date", $next_date, PDO::PARAM_INT);
    
    // Update test status
    $stmt->execute();
    
    // Close the database connection
    db_close($db);
}

/************************************
 * FUNCTION: DELETE COMPLIANCE FILE *
 ************************************/
function delete_compliance_file($file_id){
    // Open the database connection
    $db = db_open();

    // Delete a compliance file by file ID
    $stmt = $db->prepare("DELETE FROM `compliance_files` WHERE id=:file_id; ");
    $stmt->bindParam(":file_id", $file_id, PDO::PARAM_INT);
    $stmt->execute();

    // Close the database connection
    db_close($db);
}

/********************************
 * FUNCTION: SUBMIT TEST RESULT *
 ********************************/
function submit_test_result()
{
    global $escaper, $lang;
    
    $test_audit_id  = (int)$_GET['id'];
    $status         = (int)$_POST['status'];
    $test_result    = $_POST['test_result'];
    $tester         = (int)$_POST['tester'];
    $test_date      = $_POST['test_date'];
    $summary        = $_POST['summary'];
    
    if(!$test_audit_id || !$tester || !$test_date)
    {
        set_alert(true, "bad", $lang['InvalidParams']);
        return false;
    }
    else
    {
        // Check if user already attached files 
        $unique_names = isset($_POST['unique_names']) ? $_POST['unique_names'] : [];
        
        // Get existing files
        $files = get_compliance_files($test_audit_id, "test_audit");
        
        // Delete files
        foreach($files as $file){
            // Check if file is deleted
            if(!in_array($file['unique_name'], $unique_names)){
                delete_compliance_file($file['id']);
            }
        }
    
        // If submitted files are existing, save files
        if(!empty($_FILES['file'])){
            $files = $_FILES['file'];
            $result = upload_compliance_files($test_audit_id, "test_audit", $files);
            if($result !== true){
                $errors = $result;
            }
        }
        
        // Check if error was happen in uploading files
        if(!empty($errors)){
            $errors = array_unique($errors);
            set_alert(true, "bad", implode(", ", $errors));
            return false;
        }else{
            // Save a test result
            save_test_result($test_audit_id, $status, $test_result, $tester, $test_date, $summary);
            
            $closed_audit_status = get_setting("closed_audit_status");
            
            $test_audit = get_framework_control_test_audit_by_id($test_audit_id);
            $test_audit_name = empty($test_audit["name"]) ? "" : $test_audit["name"];

            // The audit was closed
            if($status == $closed_audit_status)
            {
                $message = "An audit named \"{$test_audit_name}\" was modified and closed by username \"" . $_SESSION['user'] . "\".";
                write_log($test_audit_id + 1000, $_SESSION['uid'], $message, "test_audit");
            }
            // The audit status is not close
            else
            {
                $message = "An audit named \"{$test_audit_name}\" was modified by username \"" . $_SESSION['user'] . "\".";
                write_log($test_audit_id + 1000, $_SESSION['uid'], $message, "test_audit");
            }
            
            set_alert(true, "good", $escaper->escapeHtml($lang['SavedSuccess']));
            return true;
        }
    }
    
}

/*************************************
 * FUNCTION: UPLOAD COMPLIANCE FILES *
 *************************************/
function upload_compliance_files($test_audit_id, $ref_type, $files)
{

    $user = $_SESSION['uid'];
    
    // Open the database connection
    $db = db_open();
    
    // Get the list of allowed file types
    $stmt = $db->prepare("SELECT `name` FROM `file_types`");
    $stmt->execute();

    // Get the result
    $result = $stmt->fetchAll();

    // Create an array of allowed types
    foreach ($result as $key => $row)
    {
        $allowed_types[] = $row['name'];
    }
    
    $errors = array();

    foreach($files['name'] as $key => $name){
        if(!$name)
            continue;
            
        $file = array(
            'name' => $files['name'][$key],
            'type' => $files['type'][$key],
            'tmp_name' => $files['tmp_name'][$key],
            'size' => $files['size'][$key],
            'error' => $files['error'][$key],
        );
        
        // If the file type is appropriate
        if (in_array($file['type'], $allowed_types))
        {
            // Get the maximum upload file size
            $max_upload_size = get_setting("max_upload_size");

            // If the file size is less than max size
            if ($file['size'] < $max_upload_size)
            {
                // If there was no error with the upload
                if ($file['error'] == 0)
                {
                    // Read the file
                    $content = fopen($file['tmp_name'], 'rb');

                    // Create a unique file name
                    $unique_name = generate_token(30);

                    // Store the file in the database
                    $stmt = $db->prepare("INSERT compliance_files (ref_id, ref_type, name, unique_name, type, size, user, content) VALUES (:ref_id, :ref_type, :name, :unique_name, :type, :size, :user, :content)");
                    $stmt->bindParam(":ref_id", $test_audit_id, PDO::PARAM_INT);
                    $stmt->bindParam(":ref_type", $ref_type, PDO::PARAM_STR);
                    $stmt->bindParam(":name", $file['name'], PDO::PARAM_STR, 30);
                    $stmt->bindParam(":unique_name", $unique_name, PDO::PARAM_STR, 30);
                    $stmt->bindParam(":type", $file['type'], PDO::PARAM_STR, 30);
                    $stmt->bindParam(":size", $file['size'], PDO::PARAM_INT);
                    $stmt->bindParam(":user", $user, PDO::PARAM_INT);
                    $stmt->bindParam(":content", $content, PDO::PARAM_LOB);
                    $stmt->execute();
                    
                }
                // Otherwise
                else
                {
                    switch ($file['error'])
                    {
                        case 1:
                            $errors[] = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                            break;
                        case 2:
                            $errors[] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                            break;
                        case 3:
                            $errors[] = "The uploaded file was only partially uploaded.";
                            break;
                        case 4:
//                            $errors[] = "No file was uploaded.";
                            break;
                        case 6:
                            $errors[] = "Missing a temporary folder.";
                            break;
                        case 7:
                            $errors[] = "Failed to write file to disk.";
                            break;
                        case 8:
                            $errors[] = "A PHP extension stopped the file upload.";
                            break;
                        default:
                            $errors[] = "There was an error with the file upload.";
                    }
                }
            }
            else $errors[] = "The uploaded file was too big to store in the database.  A SimpleRisk administrator can modify the maximum file upload size under \"File Upload Settings\" under the \"Configure\" menu.  You may also need to modify the 'upload_max_filesize' and 'post_max_size' values in your php.ini file.";
        }
        else $errors[] = "The file type of the uploaded file (" . $file['type'] . ") is not supported.  A SimpleRisk administrator can add it under \"File Upload Settings\" under the \"Configure\" menu.";
    }

    // Close the database connection
    db_close($db);
    
    if($errors){
        return $errors;
    }else{
        return true;
    }
}


/**************************************
 * FUNCTION: DOWNLOAD COMPLIANCE FILE *
 **************************************/
function download_compliance_file($unique_name)
{
    global $escaper;

    // Open the database connection
    $db = db_open();

    // Get the file from the database
    $stmt = $db->prepare("SELECT * FROM compliance_files WHERE BINARY unique_name=:unique_name");
    $stmt->bindParam(":unique_name", $unique_name, PDO::PARAM_STR, 30);
    $stmt->execute();

    // Store the results in an array
    $array = $stmt->fetch();

    // Close the database connection
    db_close($db);

    // If the array is empty
    if (empty($array))
    {
        // Do nothing
        exit;
    }
    else
    {
        header("Content-length: " . $array['size']);
        header("Content-type: " . $array['type']);
        header("Content-Disposition: attachment; filename=" . $escaper->escapeUrl($array['name']));
        echo $array['content'];
        exit;
    }
}

/*********************************
 * FUNCTION: DISPLAY PAST AUDITS *
 *********************************/
function display_past_audits(){
    global $lang, $escaper;
    
    $tableID = "past-audits";
    
    echo "
        <div id='filter-container'>
            <div class='row-fluid'>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['FilterByText']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                <input type='text' id='filter_by_text' class='form-control'>
                </div>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['Control']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                    <input type='text' id='filter_by_control' class='form-control'>
                </div>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['TestResult']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                    <input type='text' id='filter_by_test_result' class='form-control'>
                </div>
            </div>
            <div class='row-fluid'>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['Framework']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span2'>
                    <select id='filter_by_framework' class='' multiple=''>
                        <option selected value='-1'>".$escaper->escapeHtml($lang['Unassigned'])."</option>\n";
                        $options = getAvailableControlFrameworkList();
                        is_array($options) || $options = array();
                        foreach($options as $option){
                            echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                        }
                    echo "</select>
                </div>
                
            </div>
            <div class='row-fluid'>
                <div class='span2' align='right'>
                    <strong>".$escaper->escapeHtml($lang['AuditDate']).":&nbsp;&nbsp;&nbsp;</strong>
                </div>
                <div class='span10'>
                    <input type='text' id='start_audit_date' class='form-control' placeholder='".$escaper->escapeHtml($lang['StartDate'])."'>
                    &nbsp;&nbsp;~&nbsp;&nbsp;
                    <input type='text' id='end_audit_date' class='form-control' placeholder='".$escaper->escapeHtml($lang['EndDate'])."'>
                </div>
            </div>
        </div>

        <table id=\"{$tableID}\" width=\"100%\" class=\"risk-datatable table table-bordered table-striped table-condensed\">
            <thead >
                <tr >
                    <th valign=\"top\">".$escaper->escapeHtml($lang['TestName'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['AuditDate'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['ControlName'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['FrameworkName'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['Status'])."</th>
                    <th valign=\"top\">".$escaper->escapeHtml($lang['TestResult'])."</th>
                    <th valign=\"top\"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <br>
        <script>
            var pageLength = 10;
            var form = $('#{$tableID}').parents('form');
            var datatableInstance = $('#{$tableID}').DataTable({
                bFilter: false,
                bLengthChange: false,
                processing: true,
                serverSide: true,
                bSort: true,
                pagingType: \"full_numbers\",
                dom : \"flrtip\",
                pageLength: pageLength,
                dom : \"flrti<'#view-all.view-all'>p\",
                createdRow: function(row, data, index){
                    var background = $('.background-class', $(row)).data('background');
                    $(row).find('td').addClass(background)
                },
                ajax: {
                    url: BASE_URL + '/api/compliance/past_audits',
                    data: function(d){
                        d.filter_text = \$(\"#filter_by_text\").val();
                        d.filter_control    = \$(\"#filter_by_control\").val();
                        d.filter_test_result = \$(\"#filter_by_test_result\").val();
                        d.filter_framework  = \$(\"#filter_by_framework\").val();
                        d.filter_start_audit_date   = \$(\"#start_audit_date\").val();
                        d.filter_end_audit_date     = \$(\"#end_audit_date\").val();
                    },
                    complete: function(response){
                    }
                }
            });
            
            // Add paginate options
            datatableInstance.on('draw', function(e, settings){
                $('.paginate_button.first').html('<i class=\"fa fa-chevron-left\"></i><i class=\"fa fa-chevron-left\"></i>');
                $('.paginate_button.previous').html('<i class=\"fa fa-chevron-left\"></i>');

                $('.paginate_button.last').html('<i class=\"fa fa-chevron-right\"></i><i class=\"fa fa-chevron-right\"></i>');
                $('.paginate_button.next').html('<i class=\"fa fa-chevron-right\"></i>');
            })
            
            // Add all text to View All button on bottom
            $('.view-all').html(\"".$escaper->escapeHtml($lang['ALL'])."\");

            // View All
            $(\".view-all\").click(function(){
                var oSettings =  datatableInstance.settings();
                oSettings[0]._iDisplayLength = -1;
                datatableInstance.draw()
                $(this).addClass(\"current\");
            })
            
            // Page event
            $(\"body\").on(\"click\", \"span > .paginate_button\", function(){
                var index = $(this).attr('aria-controls').replace(\"DataTables_Table_\", \"\");

                var oSettings =  datatableInstance.settings();
                if(oSettings[0]._iDisplayLength == -1){
                    $(this).parents(\".dataTables_wrapper\").find('.view-all').removeClass('current');
                    oSettings[0]._iDisplayLength = pageLength;
                    datatableInstance.draw()
                }
            })
            
            $('body').on('click', '.reopen', function(){
                var id = $(this).data('id');
                \$.ajax({
                    type: \"POST\",
                    url: BASE_URL + \"/api/compliance/reopen_audit\",
                    data:{
                        id: id
                    },
                    success: function(result){
                        $('#{$tableID}').DataTable().draw();
                    }
                })
            })
            
            // Redraw Past Audit table
            function redrawPastAudits(){
                $(\"#{$tableID}\").DataTable().draw();
            } 
            
            // timer identifier
            var typingTimer;                
            // time in ms (1 second)
            var doneTypingInterval = 1000;  

            $('#filter_by_framework').multiselect({
                allSelectedText: '".$escaper->escapeHtml($lang['ALL'])."',
                includeSelectAllOption: true
            });
            
            $('#start_audit_date, #end_audit_date').datepicker({dateFormat: 'yy-mm-dd'})

            // Search filter event
            $('#filter_by_text').keyup(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redrawPastAudits, doneTypingInterval);
            });
            $('#filter_by_control').keyup(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redrawPastAudits, doneTypingInterval);
            });
            $('#filter_by_test_result').keyup(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redrawPastAudits, doneTypingInterval);
            });
            $('#filter_by_framework').change(function(){
                redrawPastAudits();
            });
            $('#start_audit_date').change(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redrawPastAudits, doneTypingInterval);
            });
            $('#end_audit_date').change(function(){
                clearTimeout(typingTimer);
                typingTimer = setTimeout(redrawPastAudits, doneTypingInterval);
            });
            
        </script>
    ";
}

/************************************
 * FUNCTION: DISPLAY TEST IN DETAIL *
 ************************************/
function display_detail_test()
{
    global $lang, $escaper;
    
    $test_audit_id = (int)$_GET['id'];
    
    // Get test audit information
    $test_audit = get_framework_control_test_audit_by_id($test_audit_id);
    
    // Get attachement files
    $files = get_compliance_files($test_audit_id, "test_audit");
    
    echo "
        <div class='well' >
            <table width='100%' id='test_detail_information'>
                <tr>
                    <td width='50%' valign='top'>
                        <table width='100%'>
                            <tr>
                                <td valign='top' class='text-right' width='200px'><strong>".$escaper->escapeHtml($lang['TestName']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['name'])."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['Tester']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['tester_name'])."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['TestFrequency']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".(int)$test_audit['test_frequency']. " " .$escaper->escapeHtml($test_audit['test_frequency'] > 1 ? $lang['days'] : $lang['Day'])."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['Objective']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['objective'] ? $test_audit['objective'] : "--")."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['TestSteps']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['test_steps'] ? $test_audit['test_steps'] : "--")."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['ApproximateTime']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".(int)$test_audit['approximate_time']. " " .$escaper->escapeHtml($test_audit['approximate_time'] > 1 ? $lang['minutes'] : $lang['minute'])."
                                </td>
                            </tr>
                        </table>                    
                    </td>
                    <td valign='top'>
                        <table width='100%'>
                            <tr>
                                <td valign='top' class='text-right' width='200px'><strong>".$escaper->escapeHtml($lang['ExpectedResults']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['expected_results'] ? $test_audit['expected_results'] : "--")."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['FrameworkName']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['framework_name'])."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['ControlName']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['control_name'])."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['DesiredFrequency']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".(int)$test_audit['desired_frequency']. " " .$escaper->escapeHtml($test_audit['test_frequency'] > 1 ? $lang['days'] : $lang['Day'])."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['CreatedDate']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$test_audit['created_at']."
                                </td>
                            </tr>                            
                        </table>                    
                    </td>
                </tr>
            </table>
            <!-- Test Result -->
            <table width='100%' id='test_result_information' style='margin-top: 15px'>
                <tr>
                    <td width='50%' valign='top'>
                        <table width='100%'>
                            <tr>
                                <td valign='top' class='text-right' width='200px'><strong>".$escaper->escapeHtml($lang['TestResult']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['test_result'] ? $test_audit['test_result'] : "--")."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right'><strong>".$escaper->escapeHtml($lang['TestDate']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$test_audit['last_date']."
                                </td>
                            </tr>

                        </table>                    
                    </td>
                    <td valign='top'>
                        <table width='100%'>
                            <tr>
                                <td valign='top' class='text-right' width='200px'><strong>".$escaper->escapeHtml($lang['Summary']).":&nbsp;&nbsp;</strong></td>
                                <td>
                                    ".$escaper->escapeHtml($test_audit['summary'] ? $test_audit['summary'] : "--")."
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' class='text-right' width='200px'><strong>".$escaper->escapeHtml($lang['AttachmentFiles']).":&nbsp;&nbsp;</strong></td>
                                <td>
                            ";
                                if($files){
                                    foreach($files as $file){
                                        echo  "
                                            <p>            
                                                <a href=\"".$_SESSION['base_url']."/compliance/download.php?id=".$file['unique_name']."\" >".$escaper->escapeHtml($file['name'])."</a>
                                            </p>
                                        ";
                                    }
                                }
                                else
                                {
                                    echo "<p>No files</p>";
                                }
                            echo "
                                </td>
                            </tr>
                        </table>                    
                    </td>
                </tr>
            </table>

        </div>
    ";
    
    // Display test audit comment
    display_test_audit_comment($test_audit_id);
    
    // Display test audit trail
    display_test_audit_trail($test_audit_id);
}

/*******************************
 * FUNCTION: REOPEN TEST AUDIT *
 *******************************/
function reopen_test_audit($test_audit_id)
{
    // Set test audit status to undefined
    update_test_audit_status($test_audit_id, 0);
    
    $message = "A closed test audit was for ID \"{$test_audit_id}\" reopened by username \"" . $_SESSION['user'] . "\".";
    write_log($test_audit_id + 1000, $_SESSION['uid'], $message, "test_audit");

    return true;
}

/******************************************************
 * FUNCTION: GET FRAMEWORKS FROM INITIATE AUDITS PAGE *
 ******************************************************/
function get_initiate_frameworks_by_filter($filter_by_text, $filter_by_status, $filter_by_frequency, $filter_by_framework, $filter_by_control)
{
    $controls = get_initiate_controls_by_filter($filter_by_text, $filter_by_status, $filter_by_frequency, $filter_by_framework, $filter_by_control);

    $filtered_framework_ids = [];
//    $filtered_control_ids = [];
    foreach($controls as $control){
        $filtered_framework_ids = array_merge($filtered_framework_ids, explode(",", $control['framework_ids']));
//        $filtered_control_ids[] = $control['id'];
    }
    $filtered_framework_id_set = implode(",", array_unique($filtered_framework_ids));
//    $filtered_control_ids = implode(",", $filtered_control_ids);

    // Open the database connection
    $db = db_open();

    $sql = "
        SELECT t1.*
        FROM 
            `frameworks` t1 
            INNER JOIN `framework_controls` t2 on FIND_IN_SET(t1.value, t2.framework_ids)
        WHERE
            FIND_IN_SET(t1.value, :filtered_framework_id_set) 
    ";
    
    $where = ["t1.status=1"];
    
    if($filter_by_frequency){
        $where[] = "t1.desired_frequency like :filter_by_frequency";
    }

    if($filter_by_framework){
        $where[] = "FIND_IN_SET(t1.value, :filter_by_framework)";
    }else{
        $where[] = "0";
    }

    if($filter_by_control){
        $where[] = "t2.short_name like :filter_by_control";
    }

    if($where){
        $sql .= " OR ". "(". implode(" AND ", $where). ")";
    }
    
    $sql .= " GROUP BY t1.value ";

    // Get the risk levels
    $stmt = $db->prepare($sql);

    $stmt->bindParam(":filtered_framework_id_set", $filtered_framework_id_set, PDO::PARAM_STR);
    
    if($filter_by_frequency){
        $filter_by_frequency = "%{$filter_by_frequency}%";
        $stmt->bindParam(":filter_by_frequency", $filter_by_frequency, PDO::PARAM_STR);
    }

    if($filter_by_framework){
        $framework_ids = implode(",", $filter_by_framework);
        $stmt->bindParam(":filter_by_framework", $framework_ids, PDO::PARAM_STR);
    }

    if($filter_by_control){
        $filter_by_control = "%{$filter_by_control}%";
        $stmt->bindParam(":filter_by_control", $filter_by_control, PDO::PARAM_STR);
    }

    $stmt->execute();
    
    // Store the list in the array
    $frameworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $filtered_frameworks = [];
    foreach($frameworks as $framework){
        $framework['name'] = try_decrypt($framework['name']);
        if(!$filter_by_text || stripos($framework['name'], $filter_by_text) !== false || in_array($framework['value'], $filtered_framework_ids)){
            $filtered_frameworks[] = $framework;
        }
    }
    
    // Close the database connection
    db_close($db);

    return $filtered_frameworks;
}

/****************************************************
 * FUNCTION: GET CONTROLS FROM INITIATE AUDITS PAGE *
 ****************************************************/
function get_initiate_controls_by_filter($filter_by_text, $filter_by_status, $filter_by_frequency, $filter_by_framework, $filter_by_control, $framework_id=null)
{
    $tests = get_initiate_tests_by_filter($filter_by_text, $filter_by_status, $filter_by_frequency, $filter_by_framework, $filter_by_control, $framework_id);
    $filtered_control_ids = [];
//    $filtered_test_ids = [];
    foreach($tests as $test){
        $filtered_control_ids[$test['framework_control_id']] = $test['framework_control_id'];
//        $filtered_test_ids[] = $test['id'];
    }
    $filtered_control_ids = implode(",", $filtered_control_ids);
//    $filtered_test_ids = implode(",", $filtered_test_ids);
    
    // Open the database connection
    $db = db_open();
    
    $sql = "
        SELECT t1.*, GROUP_CONCAT(DISTINCT t2.value SEPARATOR ',') framework_ids
        FROM 
            `framework_controls` t1 
            INNER JOIN `frameworks` t2 on FIND_IN_SET(t2.value, t1.framework_ids) and t2.status=1
            /*INNER JOIN `framework_control_tests` t3 on t1.id=t3.framework_control_id and FIND_IN_SET(t3.id, '{\$filtered_test_ids}')*/
        WHERE
            FIND_IN_SET(t1.id, :filtered_control_ids) 
    ";
    if($filter_by_framework){
        $sql .= " AND FIND_IN_SET(t2.value, :filter_by_framework) ";
    }else{
        $sql .= " AND 0 ";
    }
    
    $where = ["t2.status=1"];
    if($filter_by_text){
        $where[] = "(t1.short_name like :filter_by_text)";
    }
    if($filter_by_frequency){
        $where[] = "(t1.desired_frequency like :filter_by_frequency)";
    }
    if($filter_by_framework){
        $where[] = "FIND_IN_SET(t2.value, :filter_by_framework)";
    }else{
        $where[] = "0";
    }
    if($filter_by_control){
        $where[] = "t1.short_name like :filter_by_control";
    }
    if($framework_id){
        $where[] = "t2.value = :framework_id";
    }

    if($where){
        $sql .= " OR ". "(". implode(" AND ", $where). ")";
    }
    
    $sql .= " GROUP BY t1.id ";
    $sql .= " ORDER BY t1.short_name ";

    // Get the risk levels
    $stmt = $db->prepare($sql);

    $stmt->bindParam(":filtered_control_ids", $filtered_control_ids, PDO::PARAM_STR);
    
    if($filter_by_text){
        $filter_by_text = "%{$filter_by_text}%";
        $stmt->bindParam(":filter_by_text", $filter_by_text, PDO::PARAM_STR);
    }
    if($filter_by_frequency){
        $filter_by_frequency = "%{$filter_by_frequency}%";
        $stmt->bindParam(":filter_by_frequency", $filter_by_frequency, PDO::PARAM_STR);
    }
    if($filter_by_framework){
        $framework_ids = implode(",", $filter_by_framework);
        $stmt->bindParam(":filter_by_framework", $framework_ids, PDO::PARAM_STR);
    }
    if($filter_by_control){
        $filter_by_control = "%{$filter_by_control}%";
        $stmt->bindParam(":filter_by_control", $filter_by_control, PDO::PARAM_STR);
    }
    if($framework_id){
        $stmt->bindParam(":framework_id", $framework_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    
    // Store the list in the array
    $controls = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Close the database connection
    db_close($db);
    
    return $controls;
}

/*************************************************
 * FUNCTION: GET TESTS FROM INITIATE AUDITS PAGE *
 *************************************************/
function get_initiate_tests_by_filter($filter_by_text, $filter_by_status, $filter_by_frequency, $filter_by_framework, $filter_by_control, $framework_id=null, $control_id=null)
{
    // Open the database connection
    $db = db_open();

    $sql = "
        SELECT t1.*
        FROM `framework_control_tests` t1
            INNER JOIN `framework_controls` t2 on t1.framework_control_id=t2.id
            INNER JOIN `frameworks` t3 on FIND_IN_SET(t3.value, t2.framework_ids) AND t3.status=1
        WHERE
            t3.status=1
    ";
    
    $where = [];
    
    if($filter_by_text){
        $where[] = "(t1.name like :filter_by_text OR t1.desired_frequency like :filter_by_text OR t1.last_date like :filter_by_text OR t1.next_date like :filter_by_text)";
    }
    if($filter_by_frequency){
        $where[] = "t1.desired_frequency like :filter_by_frequency";
    }
    if($filter_by_status){
        
    }
    if($filter_by_framework){
        $where[] = "FIND_IN_SET(t3.value, :filter_by_framework)";
    }else{
        $where[] = "0";
    }
    if($filter_by_control){
        $where[] = "t2.short_name like :filter_by_control";
    }
    if($framework_id){
        $where[] = "t3.value = :framework_id";
    }
    if($control_id){
        $where[] = "t2.id = :control_id";
    }

    if($where){
        $sql .= " AND ". implode(" AND ", $where);
    }
    
    $sql .= " GROUP BY t1.id ";
    $sql .= " ORDER BY t1.name ";

    $stmt = $db->prepare($sql);
    
    if($filter_by_text){
        $filter_by_text = "%{$filter_by_text}%";
        $stmt->bindParam(":filter_by_text", $filter_by_text, PDO::PARAM_STR);
    }
    if($filter_by_frequency){
        $filter_by_frequency = "%{$filter_by_frequency}%";
        $stmt->bindParam(":filter_by_frequency", $filter_by_frequency, PDO::PARAM_STR);
    }
    if($filter_by_status){
        
    }
    if($filter_by_framework){
        $framework_ids = implode(",", $filter_by_framework);
        $stmt->bindParam(":filter_by_framework", $framework_ids, PDO::PARAM_STR);
    }
    if($filter_by_control){
        $filter_by_control = "%{$filter_by_control}%";
        $stmt->bindParam(":filter_by_control", $filter_by_control, PDO::PARAM_STR);
    }
    if($framework_id){
        $stmt->bindParam(":framework_id", $framework_id, PDO::PARAM_INT);
    }
    if($control_id){
        $stmt->bindParam(":control_id", $control_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    // Store the list in the array
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    db_close($db);
    
    return $tests;
}

?>
