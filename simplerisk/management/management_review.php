<?php
        /* This Source Code Form is subject to the terms of the Mozilla Public
         * License, v. 2.0. If a copy of the MPL was not distributed with this
         * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

        // Include required functions file
        require_once(realpath(__DIR__ . '/../includes/functions.php'));
        require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
	require_once(realpath(__DIR__ . '/../includes/display.php'));
	require_once(realpath(__DIR__ . '/../includes/alerts.php'));

        // Include Zend Escaper for HTML Output Encoding
        require_once(realpath(__DIR__ . '/../includes/Component_ZendEscaper/Escaper.php'));
        $escaper = new Zend\Escaper\Escaper('utf-8');

        // Add various security headers
        header("X-Frame-Options: DENY");
        header("X-XSS-Protection: 1; mode=block");

        // If we want to enable the Content Security Policy (CSP) - This may break Chrome
        if (CSP_ENABLED == "true")
        {
                // Add the Content-Security-Policy header
		header("Content-Security-Policy: default-src 'self' 'unsafe-inline';");
        }

        // Session handler is database
        if (USE_DATABASE_FOR_SESSIONS == "true")
        {
		session_set_save_handler('sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc');
        }

        // Start the session
	session_set_cookie_params(0, '/', '', isset($_SERVER["HTTPS"]), true);
        session_start('SimpleRisk');

        // Include the language file
        require_once(language_file());

        require_once(realpath(__DIR__ . '/../includes/csrf-magic/csrf-magic.php'));

        // Check for session timeout or renegotiation
        session_check();

        // Check if access is authorized
        if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
        {
                header("Location: ../index.php");
                exit(0);
        }

        // Record the page the workflow started from as a session variable
	$_SESSION["workflow_start"] = $_SERVER['SCRIPT_NAME'];

        // If reviewed is passed via GET
        if (isset($_GET['reviewed']))
        {
                // If it's true
                if ($_GET['reviewed'] == true)
                {
			// Display an alert
			set_alert(true, "good", "Management review submitted successfully!");
                }       
        }

        // If mitigated was passed back to the page as a GET parameter
        if (isset($_GET['mitigated']))
        {
                // If its true
                if ($_GET['mitigated'] == true)
                {
                        // Display an alert
                        set_alert(true, "good", "Mitigation submitted successfully!");
                }
        }
?>

<!doctype html>
<html>
  
  <head>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/sorttable.js"></script>
    <title>SimpleRisk: Enterprise Risk Management Simplified</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-responsive.css"> 
  </head>
  
  <body>
    <title>SimpleRisk: Enterprise Risk Management Simplified</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-responsive.css">
    <link rel="stylesheet" href="../css/divshot-util.css">
    <link rel="stylesheet" href="../css/divshot-canvas.css">
    <link rel="stylesheet" href="../css/display.css">

<?php
	view_top_menu("RiskManagement");

	// Get any alert messages
	get_alert();
?>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <?php view_risk_management_menu("PerformManagementReviews"); ?>
        </div>
        <div class="span9">
          <div class="row-fluid">
            <div class="span12">
              <p><?php echo $escaper->escapeHtml($lang['ManagementReviewHelp']); ?>.</p>
              <?php get_risk_table(2); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>

</html>
