<!DOCTYPE HTML>
<?php
require(INCLUDE_DIR.'class.dashboard.php');
//require_once('staff.inc.php');
$staff=Staff::lookup($thisstaff->getId());
header("Content-Type: text/html; charset=UTF-8");
header("Content-Security-Policy: frame-ancestors ".$cfg->getAllowIframes().";");

$title = ($ost && ($title=$ost->getPageTitle()))
    ? $title : ('osTicket :: '.__('Staff Control Panel'));

$_SESSION['dm'] = $staff->darkmode;
$dm = $_SESSION['dm'];

if (!isset($_SERVER['HTTP_X_PJAX'])) { ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html<?php
if (($lang = Internationalization::getCurrentLanguage())
        && ($info = Internationalization::getLanguageInfo($lang))
        && (@$info['direction'] == 'rtl'))
    echo ' dir="rtl" class="rtl"';
if ($lang) {
    echo ' lang="' . Internationalization::rfc1766($lang) . '"';
}

// Dropped IE Support Warning
if (osTicket::is_ie())
    $ost->setWarning(__('osTicket no longer supports Internet Explorer.'));
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
   <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-3.4.0.min.js"></script>
    
    <script src="<?php echo ROOT_PATH; ?>scp/js/tether.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>scp/js/modernizr.min.js"></script>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/morris.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/footable.bootstrap.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/icons.css" media="all">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/select2.min.css">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/redactor.css" media="screen">
    <?php if ($dm ==1){?>
    	<link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/styles_dark.css" media="all">
  	<?php } else { ?>
  		<link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/styles.css" media="all">
 		 <?php } 
 		 if ($dm ==1){?>
    	<link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/scp_dark.css" media="all">
  	<?php } else { ?>
  		<link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/scp.css" media="all">  	
  	<?php } ?>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/notify-metro.css" media="all">
    
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>css/thread.css" media="all">
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/bootstrap-datepicker.min.css" media="all">
    
    
    <link type="text/css" href="<?php echo ROOT_PATH; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" media="screen" />
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/font-awesome.min.css">

    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/typeahead.css" media="screen">
    <link type="text/css" href="<?php echo ROOT_PATH; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css"
         rel="stylesheet" media="screen" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>css/jquery-ui-timepicker-addon.css" media="all">
    
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/font-awesome-ie7.min.css">
    <![endif]-->

    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/dropdown.css">
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/loadingbar.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/flags.css">
    
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/rtl.css"/>

    <?php if ($dm ==1){?>
    	<link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/helptopic_dark.css"/>
    <?php } else { ?>
    	<link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/helptopic.css"/>
    <?php } ?>	
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>scp/css/loadingoverlay.min.css"/>
    
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery.easyui.min.js"></script>
   <-- <script type="text/javascript" src="<?php echo ROOT_PATH; ?>scp/js/loadingoverlay.min.js"></script>-->
    <link type="text/css" rel="stylesheet" href="./css/translatable.css"/>
    
    <?php if ($dm ==1){?>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/typeahead_dark.css" media="screen">
  <?php } else { ?>	
    	<link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/typeahead.css" media="screen">
    <?php } 
        

    if($ost && ($headers=$ost->getExtraHeaders())) {
        echo "\n\t".implode("\n\t", $headers)."\n";
    }
    ?>
</head>
<body class="fixed-left">


 

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
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                     <div class="text-center">
                        <a href="#" class=" open-left waves-light waves-effect logo"><i class="mdi mdi-menu"></i> <span>NASG</span></a>
                    </div>
                 
                </div>
                <!-- Button mobile view to collapse sidebar menu -->
                <nav class="navbar-custom">
                    <ul class="hide-phone list-inline float-left mb-0 mr-0">
                        <li class="list-inline-item notification-list hide-phone  mr-0">
                            <span class="nav-link">IT Support System</span>
                        </li>
                    </ul>

                    <ul class="list-inline float-right mb-0 mr-2">

                        <li class="list-inline-item notification-list hide-phone  mr-0">
                            <a class="nav-link waves-light waves-effect" href="#" id="btn-fullscreen">
                                <i class="mdi mdi-crop-free noti-icon"></i>
                            </a>
                        </li>
                        
                        <li class="list-inline-item notification-list mr-0">
                            <a class="nav-link waves-light waves-effect darkmode" href="#" id="btn-darkscreen">
                                <?php if ($dm == 1) { ?>
                                	
                                	<i class="sun noti-icon"></i>
                                
                                <?php
                                } else { ?>
                                	
                                	<i class="moon noti-icon"></i> 
                            
                            		<?php } ?>
                            
                            </a>
                        </li>
                        
                        <script>
                        	
                        	 $('.darkmode').on('click', function(){
											        $.ajax({
											          url: "ajax.php/staff/<?php echo $thisstaff->getId();?>/darkmode",
											          type: "GET"
											        });
											        location.reload(true);
											    });
                        </script>

                        <li class="list-inline-item notification-list mr-0 hidden">
                        
                            <a class="nav-link right-bar-toggle waves-light waves-effect" href="#">
                                
                                <span class="mdi mdi-dots-horizontal noti-icon"></span>
                   
                            </a>
                        </li>
                        

                        
                        <li class="list-inline-item notification-list mr-0 translation-link">
                            <a href="#" class="nav-link waves-light waves-effect english" id="english" data-lang="English" style="display:none;"><span class="flag flag-us" title="English" alt="English" class="notranslate" ></span></a>
                            <a href="#" class="nav-link waves-light waves-effect spanish" id="spanish" data-lang="Spanish"><span class="flag flag-mx" title="Spanish" alt="Spanish" class="notranslate" ></span></a>
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
                               <a  class="no-pjax dropdown-item notify-item" href="<?php echo ROOT_PATH ?>scp/admin.php"><i class="mdi mdi-settings"></i> <?php echo __('Admin Panel'); ?></a>
                                <?php }else{ 
                                 if ($thisstaff->isAdmin()) {?>
                                
                               <a  class="no-pjax dropdown-item notify-item" href="<?php echo ROOT_PATH ?>scp/index.php"><i class="mdi mdi-account-box-outline"></i><?php echo __('Agent Panel'); ?></a>
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
            
            <style>
              .sei {
                 
                  background: url("./images/icons/ise.png") no-repeat;
                      padding:0px 4px 0px 12px;
                      position: relative;
                      top: 4px;
              }
              .sei:hover {
                  background: url("./images/icons/iseh.png") no-repeat;
              }
          </style>                   
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
					
					
					
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

    <title><?php echo ($ost && ($title=$ost->getPageTitle()))?$title:'osTicket :: '.__('Staff Control Panel'); ?></title>
    
    <?php
} # endif X_PJAX 
?>
					<?php if ($UnassignedTickets > 0 ) { ?>
					
				     <div class="alert alert-secondary m-b-15 m-t--15" role="alert">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>There are currently <span class="badge badge-primary"><?php echo $UnassignedTickets; ?></span>  unassigned ticket<?php if ($UnassignedTickets > 1) echo "s";?>. 
                            <div class="float-right"></span> <a href="tickets.php?queue=3&l=0&t=0&s=1&st=0"><i class="mdi mdi-ticket-account" aria-hidden="true"></i></a></div>        
                         </div>
					
					<?php }
					
					if ($BacklogTotal == 45) { ?>
					
					<div class="alert alert-warning m-b-30" role="alert">
                                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Backlog is currently <span class="badge badge-warning"><?php echo $BacklogTotal; ?></span>  at the target of <span class="badge badge-success">45</span>.
                                        <div class="float-right">
                                             </span> <a href="tickets.php?queue=245&p=1&l=0&t=0&s=0&st=0"><i class="fa fa-laptop"></i></a> &nbsp;
                                             </span> <a href="tickets.php?queue=246&p=1&l=0&t=0&s=0&st=0"><i class="sei"></i></a>
                                        </div> 
                         </div>
					
					<?php }
					
					if ($BacklogTotal >= 40 && $BacklogTotal <= 44 || $BacklogTotal >= 46 && $BacklogTotal <=49 ) { ?>
					
					<div class="alert alert-warning m-b-30" role="alert">
                                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Backlog is currently <span class="badge badge-warning"><?php echo $BacklogTotal; ?></span>  within 5 of the established target of <span class="badge badge-success">45</span>.
                                                                 <div class="float-right">
                                             </span> <a href="tickets.php?queue=245&p=1&l=0&t=0&s=0&st=0"><i class="fa fa-laptop"></i></a> &nbsp;
                                             </span> <a href="tickets.php?queue=246&p=1&l=0&s=0&p=1&l=0&t=0&s=0&st=0"><i class="sei"></i></a>
                                        </div> 
                         </div>
					
					<?php }
					
					if ($BacklogTotal >= 50) { ?>
					
				     <div class="alert alert-danger m-b-30" role="alert">
                                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Backlog is currently <span class="badge badge-danger"><?php echo $BacklogTotal; ?></span> which is greater than 5 above the established target of <span class="badge badge-success">45</span>.
                                        <div class="float-right">
                                             </span> <a href="tickets.php?queue=245&p=1&l=0&t=0&s=0&st=0"><i class="fa fa-laptop"></i></a> &nbsp;
                                             </span> <a href="tickets.php?queue=246&p=1&l=0&t=0&s=0&st=0"><i class="sei"></i></a>
                                        </div> 
                         </div>
					
					<?php }

					if ($BacklogTotal < 40) { ?>
					
					<div class="alert alert-success m-b-30" role="alert">
                                        <i class="fa fa-check-square" aria-hidden="true"></i> Backlog is currently <span class="badge badge-success"><?php echo $BacklogTotal; ?></span> is greater than 5 below the established target of <span class="badge badge-success">45</span>.
                                        <div class="float-right">
                                             </span> <a href="tickets.php?queue=245&p=1&l=0&t=0&s=0&st=0"><i class="fa fa-laptop"></i></a> &nbsp;
                                             </span> <a href="tickets.php?queue=246&p=1&l=0&t=0&s=0&st=0"><i class="sei"></i></a>
                                        </div> 
                         </div>
			
					<?php } ?>
<script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/moment.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/footable.js"></script>