 <?php
if(!defined('ADMINPAGE')) { 

	$begindate = date('m-d-Y', strtotime('first day of january this year'));
	$enddate = date("m-d-Y", strtotime('today'));
	
	?>
                                
	 <li  class="has_sub">
    <a class="waves-effect waves-primary" href="javascript:void(0);" ><i class=" ti-dashboard"></i> <span class="menu-arrow"></span> Dashboard </a> 
        <ul class="list-unstyled">
            <li <?php if ($_SESSION['cv19case'] == 0) echo 'class="hidden"';?>><a href="/scp/dashboard.php?a=2" title="" id="nav1">Covid-19</a></li>
            <li><a href="/scp/dashboard.php?begindate=<?php echo $begindate?>&enddate=<?php echo $enddate?>&a=1" title="" id="nav1">Incidents</a></li>
        </ul>
    </li>
	
	<li  class="has_sub">
		<a class="waves-effect waves-primary" href="/scp/directory.php" ><i class=" ti-user"></i> </span> Associates</a> 
    </li>
    <li  class="has_sub">
		<a class="waves-effect waves-primary" href="/scp/tasks.php?tl=0&ts=0"><i class=" ti-list"></i> </span> Countermeasures <span class="task-count badge badge-pill badge-primary  pull-right"><span class="faded-more"><?php echo $OpenTasks; ?></span></a> 
       
    </li> 

    <!-- Queues -->
    <?php
    $all_queues = CustomQueue::queues()->getIterator();
    $emitLevel = function($queues, $level=0) use ($all_queues, &$emitLevel) { 
        $queues->sort(function($a) { return $a->sort; });
        foreach ($queues as $q) { 
        
        $children = $all_queues->findAll(array('parent_id' => $q->id));
     
    $icon ='ti-ticket';   
    $badgecolor='badge-primary';

    switch ($q->getName()){
        
        case "Incidents":
            $hide = 0;
            break;
        case "Covid-19":
        		$hide = 0;
        		if ($_SESSION['cv19case'] == 0&&$_SESSION['cv19temp'] == 0) $hide = 1;
            break;            
        case "Covid-19 Cases":
        		$hide = 0;
        		if ($_SESSION['cv19case'] == 0) $hide = 1;
            break;
        case "Covid-19 Temp Logs":
        		$hide = 0;
        		if ($_SESSION['cv19temp'] == 0) $hide = 1;
            break;
     } 
     
    if (count($children)) { ?> 

    <li class="has_sub <?php if ($hide == 1) echo ' hidden';?>">

    <?php }
        
    if (!count($children)) { ?>
            <li <?php if ($hide == 1) echo 'class="hidden"';?>>
    <?php } ?>
        <a href=
        <?php if (count($children)) {echo '"javascript:void(0);"';} else {
         
        echo '"tickets.php?queue='. $q->getId().'&p=1&t=0&s=0&r=2"';
            
        }

        if (count($children)) {echo 'class="waves-effect waves-primary"';}?>> 
        
        <?php if (!$level){echo '<i class="'.$icon.'"></i>';}if (count($children)) {echo '<span class="menu-arrow"></span>';}?>
        <?php
              echo Format::htmlchars($q->getName());?> <?php if (!count($children)) {?><span class="queue-count badge <?php echo $badgecolor;?> badge-pill pull-right"
              data-queue-id="<?php echo $q->getId(); ?>"><span class="faded-more">-</span><?php } ?>
            </span></a>
            
    <?php
    if (!count($children)) { ?>
            <li>
    <?php } 
    if (count($children)) { ?> 

    <ul class="list-unstyled">

    <?php }
            if (count($children)) {
                $emitLevel($children, $level+1);
           }
    if (count($children)) { ?> 

    </ul></li>

    <?php      
    }
    if (!count($children)) { ?> 

    </li>

    <?php      
    }
        }
    };

    $emitLevel($all_queues->findAll(array('parent_id' => 0)));
    ?>
        
    <!-- Queues -->


    
    <li class="">
    <a class="waves-effect waves-primary" href="/scp/reports.php" ><i class="ti-stats-up"></i>   Reports </a> 
    </li>

<?php }else{ ?>


    <!-- Admin -->
<?php  if ($thisstaff->GetId() == 1){ ?>
     <li class=" has_sub ">
        <a class="waves-effect waves-primary" href="javascript:void(0);" ><i class="ti-info-alt"></i><span class="menu-arrow"></span> System </a>
        <ul class="list-unstyled">
            <li><a href="/scp/logs.php" title="" id="nav0">System Logs</a></li>
            <li><a href="/scp/system.php" title="" id="nav1">Information</a></li>
       </ul>
</li><?php } ?>
    <li class=" has_sub ">
        <a class="waves-effect waves-primary" href="javascript:void(0);" ><i class="ti-settings"></i><span class="menu-arrow"></span> Settings </a> 
        <ul class="list-unstyled">
            <?php  if ($thisstaff->GetId() == 1){ ?>
            <li><a href="/scp/settings.php?t=pages" title="" id="nav0">Company</a></li>
            <li><a href="/scp/settings.php?t=system" title="" id="nav1">System</a></li>
            <li><a href="/scp/settings.php?t=tickets" title="" id="nav2">Tickets</a></li>
            <li><a href="/scp/settings.php?t=tasks" title="" id="nav3">Tasks</a></li>
            <li><a href="/scp/settings.php?t=agents" title="" id="nav4">Agents</a></li>
            <li><a href="/scp/settings.php?t=users" title="" id="nav5">Associates</a></li>
            <li><a href="/scp/settings.php?t=kb" title="" id="nav6">Knowledgebase</a></li>
            <?php } ?>
            <li><a href="/scp/settings.php?t=targets" title="" id="nav6">Targets</a></li>
        </ul>
    </li>
    <?php  if ($thisstaff->GetId() == 1){ ?>
    <li class=" has_sub ">
        <a class="waves-effect waves-primary" href="javascript:void(0);" ><i class="ti-pencil-alt"></i><span class="menu-arrow"></span> Manage </a> 
        <ul class="list-unstyled"><li><a href="/scp/helptopics.php" title="" id="nav0">Help Topics</a></li>
            <li><a href="/scp/filters.php" title="Ticket Filters" id="nav1">Ticket Filters</a></li>
            <li><a href="/scp/slas.php" title="" id="nav2">SLA Plans</a></li><li><a href="/scp/apikeys.php" title="" id="nav3">API Keys</a></li>
            <li><a href="/scp/pages.php" title="Pages" id="nav4">Pages</a></li><li><a href="/scp/forms.php" title="" id="nav5">Forms</a></li>
            <li><a href="/scp/lists.php" title="" id="nav6">Lists</a></li><li><a href="/scp/plugins.php" title="" id="nav7">Plugins</a></li>
         </ul>
    </li>
    <li class=" has_sub "><a class="waves-effect waves-primary" href="javascript:void(0);" ><i class="ti-email"></i><span class="menu-arrow"></span> Emails </a> 
        <ul class="list-unstyled">
            <li><a href="/scp/emails.php" title="Email Addresses" id="nav0">Emails</a></li>
            <li><a href="/scp/emailsettings.php" title="" id="nav1">Settings</a></li>
            <li><a href="/scp/banlist.php" title="Banned Emails" id="nav2">Banlist</a></li>
            <li><a href="/scp/templates.php" title="Email Templates" id="nav3">Templates</a></li>
            <li><a href="/scp/emailtest.php" title="Email Diagnostic" id="nav4">Diagnostic</a></li>
        </ul>
    </li>
<?php } ?>
   <!-- <li class=" has_sub "><a class="waves-effect waves-primary" href="javascript:void(0);" ><i class=" ti-user"></i><span class="menu-arrow"></span> Associates </a> 
        <ul class="list-unstyled">
            <li><a href="/scp/staff.php" title="" id="nav0">Associates</a></li>
            <li><a href="/scp/roles.php" title="" id="nav2">Roles</a></li>
            <li><a href="/scp/departments.php" title="" id="nav3">Teams</a></li>
            
        </ul>
    </li>-->
	<li  class="has_sub">
    <a class="waves-effect waves-primary" href="/scp/staff.php" ><i class=" ti-user"></i> </span> Associates</a> 
    </li>
    <li  class="has_sub">
    <a class="waves-effect waves-primary" href="hours.php" ><i class=" ti-time"></i> </span> Hours</a> 
    </li>
    <!-- End Admin -->
    
<?php } ?>