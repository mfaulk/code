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
    if (csp_enabled())
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

    if (!isset($_SESSION))
    {
        session_name('SimpleRisk');
        session_start();
    }

    // Include the language file
    require_once(language_file());

    require_once(realpath(__DIR__ . '/../includes/csrf-magic/csrf-magic.php'));

    // Check for session timeout or renegotiation
    session_check();

    // Check if access is authorized
    if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
    {
        set_unauthenticated_redirect();
        header("Location: ../index.php");
        exit(0);
    }

    // Check if access is authorized
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != "1")
    {
        header("Location: ../index.php");
        exit(0);
    }

    if (isset($_POST['update_settings']))
    {
        $error = false;
        
	// Update the setting to enable pop-up windows for text boxes
        $enable_popup = (isset($_POST['enable_popup'])) ? 1 : 0;
	$current_enable_popup = get_setting("enable_popup");
	if ($enable_popup != $current_enable_popup)
	{
        	update_setting("enable_popup", $enable_popup);
	}

        // Update the setting to show all risks for plan projects
        $plan_projects_show_all = (isset($_POST['plan_projects_show_all'])) ? 1 : 0;
        $current_plan_projects_show_all = get_setting("plan_projects_show_all");
        if ($plan_projects_show_all != $current_plan_projects_show_all)
        {
                update_setting("plan_projects_show_all", $plan_projects_show_all);
        }

	// Update the default language setting
	$default_language = get_name_by_value("languages", (int)$_POST['languages']);
	$current_default_language = get_setting("default_language");
	if ($default_language != $current_default_language)
	{
		update_setting("default_language", $default_language);
	}

	// Update the default timezone setting
	$default_timezone = $_POST['default_timezone'];
	$current_default_timezone = get_setting("default_timezone");
	if ($default_timezone != $current_default_timezone)
	{
		update_setting("default_timezone", $default_timezone);
	}

	// Update the default risk score setting
        $default_risk_score = $_POST['default_risk_score'];
	$current_default_risk_score = get_setting("default_risk_score");
	if ($default_risk_score != $current_default_risk_score)
	{
		// If the default risk score is between 0 and 10
        	if (($default_risk_score >= 0) && ($default_risk_score <= 10))
        	{
			update_setting("default_risk_score", $default_risk_score);
        	}
	}
        
	// Update the default closed audit status setting
	$default_closed_audit_status = (int)$_POST['closed_audit_status'];
	$current_default_closed_audit_status = get_setting("closed_audit_status");
	if ($default_closed_audit_status != $current_default_closed_audit_status)
	{
		// If the default closed audit status is empty
		if (empty($default_closed_audit_status))
		{
			set_alert(true, "bad", $escaper->escapeHtml($lang['ClosedAuditStatusIsRequired']));
			$error = true;
		}
		else
		{
            		update_setting("closed_audit_status", $default_closed_audit_status);
		}
        }

	// Update the default currency setting
	$default_currency = $_POST['default_currency'];
	$current_default_currency = get_setting("currency");
	if ($default_currency != $current_default_currency)
	{
		// If the default currency is not empty
        	if ($default_currency != "")
        	{
        		// If the default currency value is less than or equal to six characters long
                	if (strlen($default_currency) <= 6)
                	{
                		// Update the currency
                        	update_setting("currency", $default_currency);
                	}
		}
        }

	// Update the default asset valuation setting
	$default_asset_valuation = (int)$_POST['default_asset_valuation'];
	$current_default_asset_valuation = get_setting("default_asset_valuation");
	if ($default_asset_valuation != $current_default_asset_valuation)
	{
        	// If the default asset valuation is numeric
        	if (is_numeric($default_asset_valuation))
        	{
                	// If the default asset valuation is between 1 and 10
                	if ($default_asset_valuation >= 1 && $default_asset_valuation <= 10)
                	{
                		// Update the default asset valuation
				update_setting("default_asset_valuation", $default_asset_valuation);
                	}
		}
        }
        
	// Update the session activity timeout setting
	$session_activity_timeout = (int)$_POST['session_activity_timeout'];
	$current_session_activity_timeout = get_setting("session_activity_timeout");
	if ($session_activity_timeout != $current_session_activity_timeout)
	{
		update_setting("session_activity_timeout", $session_activity_timeout);
	}

	// Update the session renegotiation period setting
	$session_renegotiation_period = (int)$_POST['session_renegotiation_period'];
	$current_session_renegotiation_period = get_setting("session_renegotiation_period");
	if ($session_renegotiation_period != $current_session_renegotiation_period)
	{
		update_setting("session_renegotiation_period", $session_renegotiation_period);
	}

	// Update the content security policy
	$content_security_policy = isset($_POST['content_security_policy']) ? 1 : 0;
	$current_content_security_policy = get_setting("content_security_policy");
	if ($content_security_policy != $current_content_security_policy)
	{
		update_setting("content_security_policy", $content_security_policy);
	}

	// Update the debug logging
	$debug_logging = isset($_POST['debug_logging']) ? 1 : 0;
	$current_debug_logging = get_setting("debug_logging");
	if ($debug_logging != $current_debug_logging)
	{
		update_setting("debug_logging", $debug_logging);
	}

	// Update the debug log file
	$debug_log_file = $_POST['debug_log_file'];
	$current_debug_log_file = get_setting("debug_log_file");
	if ($debug_log_file != $current_debug_log_file)
	{
		update_setting("debug_log_file", $debug_log_file);
	}

        // If all setting values were saved successfully
        if (!$error)
	{
        	// Display an alert
        	set_alert(true, "good", "The settings were updated successfully.");
        }
    }
?>

<!doctype html>
<html>

  <head>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <title>SimpleRisk: Enterprise Risk Management Simplified</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-responsive.css">

    <link rel="stylesheet" href="../css/divshot-util.css">
    <link rel="stylesheet" href="../css/divshot-canvas.css">
    <link rel="stylesheet" href="../css/display.css">

    <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/theme.css">
  </head>

  <body>

<?php
    view_top_menu("Configure");

    // Get any alert messages
    get_alert();
?>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <?php view_configure_menu("Settings"); ?>
        </div>
        <div class="span9">
          <div class="row-fluid">
            <div class="span12">
              <div class="hero-unit">
                <form name="settings" method="post" action="">
		    <h3><?php echo $escaper->escapeHtml($lang['Settings']); ?></h3>
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['UserInterface']); ?></strong></u></td>
                            </tr>
                            <tr>
                              <td colspan="2"><input <?php if(get_setting('enable_popup') == 1){ echo "checked"; } ?> name="enable_popup" class="hidden-checkbox" size="2" value="90" id="enable_popup" type="checkbox">  <label for="enable_popup"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['EnablePopupWindowsForTextBoxes']); ?></label></td>
                            </tr>
                            <tr>
                              <td colspan="2"><input <?php if(get_setting('plan_projects_show_all') == 1){ echo "checked"; } ?> name="plan_projects_show_all" class="hidden-checkbox" size="2" value="90" id="plan_projects_show_all" type="checkbox">  <label for="plan_projects_show_all"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['ShowAllRisksForPlanProjects']); ?></label></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                    </table>
		    <br />
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['DefaultValues']); ?></strong></u></td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DefaultLanguage']); ?>:</td>
			      <td><?php create_dropdown("languages", get_value_by_name("languages", get_setting("default_language")), null, false); ?></td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DefaultTimezone']); ?>:</td>
                              <td>
					<?php
						echo "<select id=\"default_timezone\" name=\"default_timezone\" class=\"form-field\" style=\"width:auto;\">\n";

						// Get the list of timezones
						$timezones = timezone_list();

						// Get the defeault timezone
						$default_timezone = get_setting("default_timezone");

						// For each timezone
						foreach($timezones as $key => $value)
						{
							echo "<option value=\"" . $key . "\"" . ($key == $default_timezone ? " selected" : "") . ">" . $value . "</option>\n";
						}

						echo "</select>\n";
					?>
			      </td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DefaultRiskScore']); ?>:</td>
                              <td><input value="<?php echo (get_setting('default_risk_score') ? get_setting('default_risk_score') : 10); ?>" name="default_risk_score" id="default_risk_score" type="number" min="0" step="0.1" max="10" /></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DefaultClosedAuditStatus']) ?>:</td>
                              <td><?php create_dropdown("test_status", get_setting("closed_audit_status"), "closed_audit_status", false, false, false, "required"); ?></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DefaultCurrencySymbol']) ?>:</td>
                              <td><input type="text" name="default_currency" maxlength="3" value="<?php echo get_setting("currency"); ?>" /></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DefaultAssetValuation']) ?>:</td>
                              <td>
                                <?php
                                  // Get the default asset valuation
                                  $default = get_default_asset_valuation();

                                  // Create the asset valuation dropdown
                                  create_asset_valuation_dropdown("default_asset_valuation", $default);
                                ?>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                    </table>
		    <br />
		    <table border="1" width="600" cellpadding="10px">
		    <tbody>
		    <tr>
		      <td>
		        <table border="0" width="100%">
		          <tbody>
			    <tr>
			      <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['UserSessions']); ?></strong></u></td>
			    </tr>
			    <tr>
			      <td width="300px"><?php echo $escaper->escapeHtml($lang['SessionActivityTimeout']) . " (" . $escaper->escapeHtml($lang["seconds"]) . ")"; ?>:</td>
			      <td><input name="session_activity_timeout" id="session_activity_timeout" type="number" min="0" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("session_activity_timeout")); ?>" /></td>
			    </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['SessionRenegotiationPeriod']) . " (" . $escaper->escapeHtml($lang["seconds"]) . ")"; ?>:</td>
                              <td><input name="session_renegotiation_period" id="session_renegotiation_period" type="number" min="0" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("session_renegotiation_period")); ?>" /></td>
                            </tr>
			  </tbody>
			</table>
		      </td>
		    </tr>
		    </tbody>
		    </table>
                    <br>
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['Security']); ?></strong></u></td>
                            </tr>
                            <tr>
                              <td colspan="2"><input <?php if(get_setting('content_security_policy') == 1){ echo "checked"; } ?> name="content_security_policy" class="hidden-checkbox" size="2" value="90" id="content_security_policy" type="checkbox">  <label for="content_security_policy"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['EnableCSP']); ?></label></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                    </table>
                    <br>
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['Debugging']); ?></strong></u></td>
                            </tr>
                            <tr>
                              <td colspan="2"><input <?php if(get_setting('debug_logging') == 1){ echo "checked"; } ?> name="debug_logging" class="hidden-checkbox" size="2" value="90" id="debug_logging" type="checkbox">  <label for="debug_logging"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['EnableDebugLogging']); ?></label></td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DebugLogFile']); ?>:</td>
                              <td><input name="debug_log_file" id="debug_log_file" type="text" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("debug_log_file")); ?>" /></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                    </table>
                    <br>
                    <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_settings" />

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>

</html>
