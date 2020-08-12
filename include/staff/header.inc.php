<!DOCTYPE HTML>
<?php
require(INCLUDE_DIR.'class.dashboard.php');

header("Content-Type: text/html; charset=UTF-8");
$title = ($ost && ($title=$ost->getPageTitle()))
    ? $title : ('osTicket :: '.__('Staff Control Panel'));
if (!isset($_SERVER['HTTP_X_PJAX'])) { ?>

<html<?php
if (($lang = Internationalization::getCurrentLanguage())
        && ($info = Internationalization::getLanguageInfo($lang))
        && (@$info['direction'] == 'rtl'))
    echo ' dir="rtl" class="rtl"';
if ($lang) {
    echo ' lang="' . Internationalization::rfc1766($lang) . '"';
}
?>>
<script>
            var resizefunc = [];
</script>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="icon" href="<?php echo ROOT_PATH ?>images/favicon.ico" type="image/x-icon" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="x-pjax-version" content="<?php echo GIT_VERSION; ?>">
    <title><?php echo Format::htmlchars($title); ?></title>
    <!--[if IE]>
    <style type="text/css">
        .tip_shadow { display:block !important; }
    </style>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/bootstrap.min.css" media="all">
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-1.11.2.min.js"></script>
    
    <script src="<?php echo ROOT_PATH; ?>scp/js/tether.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>scp/js/modernizr.min.js"></script>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/morris.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/footable.bootstrap.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/icons.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/styles.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/scp.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/notify-metro.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>css/thread.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/redactor.css" media="screen">
    
    <link type="text/css" href="<?php echo ROOT_PATH; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" media="screen" />
    
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/dropdown.css">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/loadingbar.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/flags.css">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/multi-select.css">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/select2.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/rtl.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/helptopic.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>scp/css/loadingoverlay.min.css"/>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>scp/js/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>scp/js/loadingoverlay.min.js"></script>
    <link type="text/css" rel="stylesheet" href="./css/translatable.css"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/accordian.css" media="all">
    
    <?php
    
    if($ost && ($headers=$ost->getExtraHeaders())) {
        echo "\n\t".implode("\n\t", $headers)."\n";
    }
    ?>
</head>
<body class="fixed-left">
 						<script>
							$.busyLoadFull("show",  { 
							text: "LOADING ...",
							textColor: "#dd2c00",
							color: "#dd2c00",
							background: "rgba(0, 0, 0, 0.2)"
							});
						</script>

    <div id="pjax-container" class="<?php if ($_POST) echo 'no-pjax'; ?>">
<?php } else {
    header('X-PJAX-Version: ' . GIT_VERSION);
    if ($pjax = $ost->getExtraPjax()) { ?>
    <script type="text/javascript">
    <?php foreach (array_filter($pjax) as $s) echo $s.";"; ?>
    </script>
    <?php }
    foreach ($ost->getExtraHeaders() as $h) {
        if (strpos($h, '<script ') !== false)
            echo $h;
    } ?>
    <title><?php echo ($ost && ($title=$ost->getPageTitle()))?$title:'osTicket :: '.__('Admin'); ?></title>
    
    <?php
} # endif X_PJAX 
?>

 <?php
    if($ost->getError())
        echo sprintf('<div id="error_bar">%s</div>', $ost->getError());
    elseif($ost->getWarning())
        echo sprintf('<div id="warning_bar">%s</div>', $ost->getWarning());
    elseif($ost->getNotice())
        echo sprintf('<div id="notice_bar">%s</div>', $ost->getNotice());
    ?>

 <script type="text/javascript"> 
 $(document).ready(function(){ 
 
 <?php //if($errors['err']) {echo "$.Notification.notify('warning','top right', 'Warning', '".$errors['err']."');";
 //}else
if($msg) {echo "$.Notification.notify('success','top right', '', '".$msg."');";}
        //}elseif($warn) {echo "$.Notification.notify('warning','top right', 'Overdue', '".$warn."');";}
        foreach (Messages::getMessages() as $M) { 
            
                echo "$.Notification.notify('success','top right', '', '".(string) $M."');";
 } ?>
 
 });
</script> 
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar" >

                <!-- LOGO -->
                <div class="topbar-left" <?php  if(defined('ADMINPAGE')) echo "style=' background-color:#f8632e;'"?>>
                     <div class="text-center">
                        <a href="#" class=" open-left waves-light waves-effect logo"><i class="mdi mdi-menu"></i> <span>NASG</span></a>
                    </div>
                 
                </div>
                <!-- Button mobile view to collapse sidebar menu -->
                <nav class="navbar-custom" <?php  if(defined('ADMINPAGE')) echo "style=' background-color: #f8632e;'"?>>
                    <ul class="hide-phone list-inline float-left mb-0 mr-0">
                        <li class="list-inline-item notification-list hide-phone  mr-0">
                            <span class="nav-link"> Safety Incident System <?php  if(defined('ADMINPAGE')) echo " (Administration)"?></span>
                        </li>
                    </ul>

                    <ul class="list-inline float-right mb-0 mr-2">

                        <li class="list-inline-item notification-list hide-phone  mr-0">
                            <a class="nav-link waves-light waves-effect" href="#" id="btn-fullscreen">
                                <i class="mdi mdi-crop-free noti-icon"></i>
                            </a>
                        </li>

                        <li class="list-inline-item notification-list mr-0 hidden">
                        
                            <a class="nav-link right-bar-toggle waves-light waves-effect" href="#">
                                
                                <span class="mdi mdi-dots-horizontal noti-icon"></span>
                   
                            </a>
                        </li>
                        
                        <li class="list-inline-item notification-list mr-0 translation-link">
                            <a href="/" class="nav-link waves-light waves-effect english" id="english" data-lang="English" style="display:none;"><span class="flag flag-us" title="English" alt="English" class="notranslate" ></span></a>
                            <a href="/" class="nav-link waves-light waves-effect spanish" id="spanish" data-lang="Spanish"><span class="flag flag-mx" title="Spanish" alt="Spanish" class="notranslate" ></span></a>
                            <div id="google_translate_element"  style="display: none"></div>
                        </li>
                        
                        <li class="list-inline-item dropdown notification-list  mr-0">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                 <i class="fa fa-user-o" style="font-size: 16px;"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                               <!-- item-->
                               <?php
                                if($thisstaff->isAdmin() && !defined('ADMINPAGE')) { ?>
                               <a  class="dropdown-item notify-item" href="<?php echo ROOT_PATH ?>scp/admin.php"><i class="mdi mdi-settings"></i> <?php echo __('Admin Panel'); ?></a>
                                <?php }else{ 
                                 if ($thisstaff->isAdmin()) {?>
                                
                               <a  class="dropdown-item notify-item" href="<?php echo ROOT_PATH ?>scp/index.php"><i class="mdi mdi-account-box-outline"></i><?php echo __('Agent Panel'); ?></a>
                                 <?php }} ?>
                                <!-- item-->
                                <a  class="dropdown-item notify-item" href="<?php echo ROOT_PATH ?>scp/profile.php"> <i class="mdi mdi-account-star-variant"></i> <?php echo __('Profile'); ?></a>
                                
                                <!-- item-->    
                                <a  class="dropdown-item notify-item" href="<?php echo ROOT_PATH ?>scp/logout.php?auth=<?php echo $ost->getLinkToken(); ?>"> <i class="mdi mdi-logout"></i> Log Out</a>
                                
                            </div>
                        </li>

    
</ul>
<script type="text/javascript">
  function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false, multilanguagePage: true}, 'google_translate_element'); }
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script><!-- Flag click handler -->
<script type="text/javascript">
    $('.translation-link a').click(function() {
      var lang = $(this).data('lang');
      var $frame = $('.goog-te-menu-frame:first');
      switch (lang){
          case "English": 
            
            document.getElementById('spanish').style.display = "inherit";
            document.getElementById('english').style.display = "none";
            break;
           case "Spanish": 
           
            document.getElementById('spanish').style.display = "none";
            document.getElementById('english').style.display = "inherit";
            break;
          
      }

      if (!$frame.size()) {
        alert("Error: Could not find Google translate frame.");
        return false;
      }
      $('.goog-te-menu-frame:first').contents().find('.goog-te-menu2-item span.text').each(function(){ if( $(this).html() == lang ) $(this).click(); });
      return false;
      
      
  
    });
</script>                   
                    <ul class="list-inline menu-left mb-0">
                                             <li class="float-left">
                            
                            </button>
                        </li>
                        <li class="hide-phone app-search">
                            
                        </li>
                    </ul>
     
                </nav>
                
            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
<?php  

		$local = $_SESSION['loc'];
		
		if (!$local) {$_SESSION['loc']= $thisstaff->dept_id;}
		
 		$_SESSION['scv19'] = 0;    

 		if ($thisstaff->staff_id == 1 || $thisstaff->staff_id == 6 || $thisstaff->staff_id == 29 || $thisstaff->staff_id == 4 
		|| $thisstaff->staff_id == 61 || $thisstaff->staff_id == 10 || $thisstaff->staff_id == 22 || $thisstaff->staff_id == 51
		|| $thisstaff->staff_id == 70 || $thisstaff->staff_id == 30 || $thisstaff->staff_id == 15 || $thisstaff->staff_id == 71
		|| $thisstaff->staff_id == 72 || $thisstaff->staff_id == 59 || $thisstaff->staff_id == 7 || $thisstaff->staff_id == 41
		|| $thisstaff->staff_id == 8 || $thisstaff->staff_id == 73 || $thisstaff->staff_id == 68 || $thisstaff->staff_id == 58
		|| $thisstaff->staff_id == 69|| $thisstaff->staff_id == 75 || $thisstaff->staff_id == 76 || $thisstaff->staff_id == 77
		|| $thisstaff->staff_id == 23
		) $_SESSION['scv19'] = 1; 
?>
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <!--- Divider -->
					
                    <div id="sidebar-menu">
					
                        <ul>
                        <?php include STAFFINC_DIR . "sidebar.inc.php"; ?>
                        
                        </ul>

                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
						<script>
							$.busyLoadFull("show",  { 
							text: "LOADING ...",
							textColor: "#dd2c00",
							color: "#dd2c00",
							background: "rgba(0, 0, 0, 0.2)"
							});
						</script>
                 
<script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/moment.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/footable.js"></script>                
               