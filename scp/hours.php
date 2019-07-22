<?php
require 'admin.inc.php';


$nav->setTabActive('hours');

$ost->addExtraHeader('<meta name="tip-namespace" content="hours.hours" />',
    "$('#content').data('tipNamespace', 'hours.hours');");

require(STAFFINC_DIR.'header.inc.php');
require_once(STAFFINC_DIR.'hours.inc.php');
include(STAFFINC_DIR.'footer.inc.php');
?>
