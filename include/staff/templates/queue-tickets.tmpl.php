<script>
	$.busyLoadFull("show",  { 
	text: "LOADING ...",
	textColor: "#dd2c00",
	color: "#dd2c00",
	background: "rgba(0, 0, 0, 0.2)"
	});
</script>
  
<?php
// Calling convention (assumed global scope):
// $tickets - <QuerySet> with all columns and annotations necessary to
//      render the full page
// For searches, some staff members may be able to see everything
$view_all_tickets = $queue->ignoreVisibilityConstraints();
// Impose visibility constraints
// ------------------------------------------------------------
$l = $_GET['l'];
$t = $_GET['t'];
$s = $_GET['s'];
$r = $_GET['r'];
if (isset($l)||isset($t)||isset($s)||isset($r)) $_SESSION['filter']=1;
$filters=$_SESSION['filter'];
if ($_GET['a'] == 'search' || $_GET['queue'] == 'adhoc') $filters=22;
if (is_numeric($l)) $_SESSION['loc'] = $l;
if (is_numeric($t)) $_SESSION['top'] = $t;
if (is_numeric($s)) $_SESSION['sta'] = $s;
if (is_numeric($r)) $_SESSION['rec'] = $r;
$loc = $_SESSION['loc'];
$top = $_SESSION['top'];
$sta = $_SESSION['sta'];
$rec = $_SESSION['rec'];
$_SESSION['loc'] = $loc;
$_SESSION['top'] = $top;
$_SESSION['sta'] = $sta;
$_SESSION['rec'] = $rec;
$_SESSION['filter'] = $filters;
$qfl = array();
$qft = array();
$qfs = array();
$qfr = array();
if ($loc !=='0')
  $qfl =  array('dept__id' => $loc );
if ($top !=='0')
  $qft =  array('topic_id' => $top );
if ($sta && $sta !==0)
  $qfs = array('status_id' => $sta);
if ($rec <2)
  $qfr = array('isrecordable' => $rec); 
  
// Merge the filters  
  $qf = array_merge($qfl,$qft,$qfs,$qfr);
  
 $qfilter = Q::any(new Q($qf));
if (($loc && $loc !==0) || ($top && $top !==0) || ($sta && $sta !==0|| ($rec <2)))
$tickets->filter($qfilter);
$states = array('open');
$states = array_merge($states, array('closed'));
    
   
    
    
 $Location = Dept::objects()
                ->order_by('name');
   // var_dump($Location);           
                     
    foreach ($Location as $cLocation) {
        $query = Ticket::objects();   
        
        $lqfilter = Q::any(new Q($qf1));
       
        if ($sta  >0){
         $query->filter($qfilter);
         }     
         
         if ($loc  >0){
         $query->filter($qfilter);
         }   
         if ($top  >0){
         $query->filter($qfilter);
         }   
         if ($rec  <2){
         $query->filter($qfilter);
         }         
        $Q = $queue->getBasicQuery();
        $expr = SqlCase::N()->when(new SqlExpr(new Q($Q->constraints)), 1);
              
       $query->aggregate(array(
            $queue->getId() => SqlAggregate::COUNT($expr)))
            ->filter(array('dept_id' => $cLocation->getId()));
         
        if ($filters == 1){ 
           $lfiltercount[$cLocation->getId()] = $query->values()->one();
        }
    }    
 $Topic = Topic::objects()
                ->order_by('topic');
   // var_dump($Location);           
                     
   foreach ($Topic as $cTopic) {
        $query = Ticket::objects();   
        
        $tqfilter = Q::any(new Q($qf1));
       
        if ($sta  >0){
         $query->filter($qfilter);
         }     
         
         if ($loc  >0){
         $query->filter($qfilter);
         }   
         if ($rec  <2){
         $query->filter($qfilter);
         } 
        $Q = $queue->getBasicQuery();
        $expr = SqlCase::N()->when(new SqlExpr(new Q($Q->constraints)), 1);
              
       $query->aggregate(array(
            $queue->getId() => SqlAggregate::COUNT($expr)))
            ->filter(array('topic_id' => $cTopic->getId()));
         
        if ($filters == 1){ 
           $tfiltercount[$cTopic->getId()] = $query->values()->one();
        }
    }    
    
    $YRecordables = Ticket::objects()
                ->order_by('ticket_id');
   // var_dump($Recordables);           
                     
    foreach ($YRecordables as $cyRecordables) {
        $query = Ticket::objects();   
        
        $rqfilter = Q::any(new Q($qfr));
       
        if ($sta  >0){
         $query->filter($qfilter);
         }     
         
         if ($loc  >0){
         $query->filter($qfilter);
         }   
         if ($top  >0){
         $query->filter($qfilter);
         }   
         if ($rec  <2){
         $query->filter($qfilter);
         }         
        $Q = $queue->getBasicQuery();
        $expr = SqlCase::N()->when(new SqlExpr(new Q($Q->constraints)), 1);
              
       $query->aggregate(array(
            $queue->getId() => SqlAggregate::COUNT($expr)))
            ->filter(array('isrecordable' => '1'));
         
        if ($filters == 1){ 
           $ryfiltercount[$cyRecordables->getId()] = $query->values()->one();
        }
        
    }    
   
    $NRecordables = Ticket::objects()
                ->order_by('ticket_id');
   // var_dump($Recordables);           
                     
    foreach ($NRecordables as $cnRecordables) {
        $query = Ticket::objects();   
        
        $rqfilter = Q::any(new Q($qfr));
       
        if ($sta  >0){
         $query->filter($qfilter);
         }     
         
         if ($loc  >0){
         $query->filter($qfilter);
         }   
         if ($top  >0){
         $query->filter($qfilter);
         }   
         if ($rec  <2){
         $query->filter($qfilter);
         }         
        $Q = $queue->getBasicQuery();
        $expr = SqlCase::N()->when(new SqlExpr(new Q($Q->constraints)), 1);
              
       $query->aggregate(array(
            $queue->getId() => SqlAggregate::COUNT($expr)))
            ->filter(array('isrecordable' => '0'));
         
        if ($filters == 1){ 
           $rnfiltercount[$cnRecordables->getId()] = $query->values()->one();
        }
        
    }    
       
$Statuses = array();
foreach (TicketStatusList::getStatuses(
            array('states' => $states)) as $status) {
    $Statuses[] = $status;
    
    if ($status->getId() == $sta){$sselected = $status->name;};
    
    
}  
    foreach ($Statuses as $stat){
    
    $query = Ticket::objects();   
   // $sqfilter = Q::any(new Q($qft));
   
    if ($loc  >0){
     $sqfilter = Q::any(new Q($qfl));
     $query->filter($qfilter);
     }     
    
    if ($top  >0){
     $sqfilter = Q::any(new Q($qft));
     $query->filter($qfilter);
     }  
    if ($rec  <2){
        $query->filter($qfilter);
     } 
     
    $Q = $queue->getBasicQuery();
    $expr = SqlCase::N()->when(new SqlExpr(new Q($Q->constraints)), 1);
          
   $query->aggregate(array(
        $queue->getId() => SqlAggregate::COUNT($expr)))
        ->filter(array('status_id' => $stat->getId()));
     
    if ($filters == 1){ 
       $sfiltercount[$stat->getId()] = $query->values()->one();
    }      
      
    }    
    
 
                                  
// Make sure the cdata materialized view is available
TicketForm::ensureDynamicDataView();
// Identify columns of output
$columns = $queue->getColumns();
// Figure out REFRESH url — which might not be accurate after posting a
// response
list($path,) = explode('?', $_SERVER['REQUEST_URI'], 2);
$args = array();
parse_str($_SERVER['QUERY_STRING'], $args);
// Remove commands from query
unset($args['id']);
if ($args['a'] !== 'search') unset($args['a']);
$refresh_url = $path . '?' . http_build_query($args);
// Establish the selected or default sorting mechanism
if (isset($_GET['sort']) && is_numeric($_GET['sort'])) {
    $sort = $_SESSION['sort'][$queue->getId()] = array(
        'col' => (int) $_GET['sort'],
        'dir' => (int) $_GET['dir'],
    );
}
elseif (isset($_GET['sort'])
    // Drop the leading `qs-`
    && (strpos($_GET['sort'], 'qs-') === 0)
    && ($sort_id = substr($_GET['sort'], 3))
    && is_numeric($sort_id)
    && ($sort = QueueSort::lookup($sort_id))
) {
    $sort = $_SESSION['sort'][$queue->getId()] = array(
        'queuesort' => $sort,
        'dir' => (int) $_GET['dir'],
    );
}
elseif (isset($_SESSION['sort'][$queue->getId()])) {
    $sort = $_SESSION['sort'][$queue->getId()];
}
elseif ($queue_sort = $queue->getDefaultSort()) {
    $sort = $_SESSION['sort'][$queue->getId()] = array(
        'queuesort' => $queue_sort,
        'dir' => (int) $_GET['dir'] ?: 0,
    );
}
// Handle current sorting preferences
$sorted = false;
foreach ($columns as $C) {
    // Sort by this column ?
    if (isset($sort['col']) && $sort['col'] == $C->id) {
        $tickets = $C->applySort($tickets, $sort['dir']);
        $sorted = true;
    }
}
if (!$sorted && isset($sort['queuesort'])) {
    // Apply queue sort-dropdown selected preference
    $sort['queuesort']->applySort($tickets, $sort['dir']);
}
// Apply pagination
if (isset($_REQUEST['query']) and  !isset($_REQUEST['p'])) $page = 1;
If (!$page){
$page = ($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:$_SESSION['pageno'];};
$_SESSION['pageno'] = $page;
$pageNav = new Pagenate(PHP_INT_MAX, $page, PAGE_LIMIT);
$tickets = $pageNav->paginateSimple($tickets);
$count = $tickets->total();
$pageNav->setTotal($count);
$pageNav->setURL('tickets.php', $args);
 ?>


<div class="subnav">


                        <div class="float-left subnavtitle">
                        
                            <span ><a href="<?php echo $refresh_url; ?>"
                                title="<?php echo __('Refresh'); ?>"><i class="fa fa-refresh"></i> 
                                </a> &nbsp;
								Incidents
            <?php if (isset($queue->id)) { ?> 
            <span class="text-danger">(<i class="fa fa-filter"></i> <?php echo $queue->getFullName();?>)</span> <?php } ?> 
            <?php if (Dept::getNamebyId($l)){ ?><span class="text-danger">(<i class="fa fa-filter"> </i> <?php echo Dept::getNamebyId($loc) ?>)</span> <?php }?>
            <?php if (Topic::getTopicName($t)){ ?><span class="text-danger">(<i class="fa fa-filter"></i> <?php echo Topic::getTopicName($top) ?>)</span> <?php }?>
            
            <?php switch ($rec){
                  case 0:
                    $rselected = 'Recordable (No)';
                    break;
                  case 1:
                  $rselected = 'Recordable (Yes)';
                    break;
                 
                }
            ?>
          
            <?php if ($rec <2){ ?><span class="text-danger">(<i class="fa fa-filter"></i> <?php echo $rselected ?>)</span> <?php }?>
                    
            <?php if ($sselected){ ?><span class="text-danger">(<i class="fa fa-filter"></i> <?php echo $sselected; ?>)</span> <?php }?>            
                                </span>
                        
                       
                       
                        </div>
                        <div class="configureQ">
                            <i class="icon-cog"></i>
                            <div class="noclick-dropdown anchor-left">
                                <ul>
            <?php
            if ($queue->isPrivate()) { ?>
                                    <li>
                                        <a class="no-pjax" href="#"
                                          data-dialog="ajax.php/tickets/search/<?php echo
                                          urlencode($queue->getId()); ?>"><i
                                        class="icon-fixed-width icon-save"></i>
                                        <?php echo __('Edit'); ?></a>
                                    </li>
            <?php }
            else {
                if ($thisstaff->isAdmin()) { ?>
                                    <li>
                                        <a class="no-pjax"
                                        href="queues.php?id=<?php echo $queue->id; ?>"><i
                                        class="icon-fixed-width icon-pencil"></i>
                                        <?php echo __('Edit'); ?></a>
                                    </li>
            <?php }
            # Anyone has permission to create personal sub-queues
            ?>
                                    <li>
                                        <a class="no-pjax" href="#"
                                          data-dialog="ajax.php/tickets/search?parent_id=<?php
                                          echo $queue->id; ?>"><i
                                        class="icon-fixed-width icon-plus-sign"></i>
                                        <?php echo __('Add Personal Queue'); ?></a>
                                    </li>
            <?php
            }
            if ($thisstaff->isAdmin()) { ?>
                                    <li>
                                        <a class="no-pjax"
                                        href="queues.php?a=sub&amp;id=<?php echo $queue->id; ?>"><i
                                        class="icon-fixed-width icon-level-down"></i>
                                        <?php echo __('Add Sub Queue'); ?></a>
                                    </li>
                                    <li>
                                        <a class="no-pjax"
                                        href="queues.php?a=clone&amp;id=<?php echo $queue->id; ?>"><i
                                        class="icon-fixed-width icon-copy"></i>
                                        <?php echo __('Clone'); ?></a>
                                    </li>
            <?php }
            if (
                $queue->id > 0
                && (
                    ($thisstaff->isAdmin() && $queue->parent_id)
                    || $queue->isPrivate()
            )) { ?>
                                    <li class="danger">
                                        <a class="no-pjax confirm-action" href="#"
                                            data-dialog="ajax.php/queue/<?php
                                            echo $queue->id; ?>/delete"><i
                                        class="icon-fixed-width fa fa-trash"></i>
                                        <?php echo __('Delete'); ?></a>
                                    </li>
            <?php } ?>
                                </ul>
                            </div>
                        </div>
                            

                      
                        <?php
                            Ticket::agentActions($thisstaff, array('status' => $status));
                        ?>
                       
                        
                        <div class="clearfix"></div>
</div>

<div class="card-box">

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


<div class="float-right">
            <form  class="form-inline" action="tickets.php" method="get" onsubmit="javascript:
                              $.pjax({
                                url:$(this).attr('action') + '?' + $(this).serialize(),
                                container:'#pjax-container',
                                timeout: 2000
                              });
                                return false;">
                  <input type="hidden" name="a" value="search">
                 <input type="hidden" name="search-type" value=""/>
                 <div class="input-group input-group-sm">
                 <input type="hidden" name="a" value="search">
                    <input type="text" class="form-control form-control-sm rlc-search data-url="ajax.php/tickets/lookup" name="query"
                     value="<?php echo Format::htmlchars($_REQUEST['query'], true); ?>"
                   autocomplete="off" autocorrect="off" autocapitalize="off" placeholder="Search Incidents" >
                <!-- <td>&nbsp;&nbsp;<a href="" id="advanced-user-search">[advanced]</a></td> -->
                    <div class="input-group-append">
					<button type="submit"  class="input-group-text" ><i class="fa fa-search"></i>
                    </button>
                    <button type="submit"  class="input-group-text advsearch" href="#" 
					onclick="javascript:
                           
					 $.busyLoadFull('show',  { 
						text: 'LOADING ...',
						textColor: '#c82333',
						color: '#c82333',
						background: 'rgba(0, 0, 0, 0.3)'
					});
					$.dialog('ajax.php/tickets/search', 201);
					document.getElementById('popup').style.display = 'none';"
					><i class=" fa fa-search-plus help-tip" href="#advanced" ></i>
                    </button>
					</div>
                </div>
            </form>
        </div>


<div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
    
<?php
      $lselected = Dept::getNamebyId($loc);
     
      if (!$lselected ) {$lselected = 'Location';}
?>


 <div class="btn-group btn-group-sm" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-light dropdown-toggle"  <?php if ($filters == 0){ echo 'disabled';}?>
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
         title="<?php echo __('Filter Incident Location'); ?>"><i class="fa fa-filter"></i> <?php echo $lselected;?>
        </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
              
              <a href="tickets.php?t=<?php echo $_GET['t']?>&l=0&s=<?php echo $_GET['s'];?>&r=<?php echo $_GET['r']?>"class="dropdown-item no-pjax"><i class="fa fa-filter"></i> All</a>
              
              <?php
    
                if ($loc =='0'){
                $Location = Dept::objects()
                ->order_by('name');
                    
                } else {
                $Location = Dept::objects()
                ->order_by('name') 
                ->filter(array('id' => $loc));
                }   
                     
                     foreach ($Location as $cLocation) { 
                     if ($lfiltercount[$cLocation->getId()]['__count'] > 0) {?>
                
                   <a href="tickets.php?t=<?php echo $_GET['t']?>&l=<?php echo $cLocation->id ?>&s=<?php echo $_GET['s']?>&r=<?php echo $_GET['r']?>" class="dropdown-item no-pjax"><i class="fa fa-filter"></i> <?php echo $cLocation->name?>
                     <span class="badge badge-pill badge-default  pull-right"><?php echo $lfiltercount[$cLocation->getId()]['__count'] ?></span> </a>
                     <?php }}      
        ?>
            </div>
    </div>
    
<?php
      $tselected = Topic::getTopicName($t);
     
      if (!$tselected ) {$tselected = 'Type';}
?>


 <div class="btn-group btn-group-sm" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-light dropdown-toggle"  <?php if ($filters == 0){ echo 'disabled';}?>
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
         title="<?php echo __('Filter Incident Type'); ?>"><i class="fa fa-filter"></i> <?php echo $tselected;?>
        </button>
            <div class="dropdown-menu  dropdown-menu-xlg  dropdown-menu-right" aria-labelledby="btnGroupDrop1">
              
              <a href="tickets.php?t=0&l=<?php echo $_GET['l']?>&s=<?php echo $_GET['s'];?>&r=<?php echo $_GET['r']?>" class="dropdown-item no-pjax"><i class="fa fa-filter"></i> All</a>
              
              <?php
    
                if ($top =='0'){
                $Topic = Topic::objects()
                ->order_by('topic');
                    
                } else {
                $Topic = Topic::objects()
                ->order_by('topic') 
                ->filter(array('topic_id' => $top));
                }   
                 
                     
                     foreach ($Topic as $cTopic) { 
                     if ($tfiltercount[$cTopic->getId()]['__count'] > 0) {?>
                
                   <a href="tickets.php?t=<?php echo $cTopic->topic_id ?>&l=<?php echo $_GET['l']?>&s=<?php echo $_GET['s']?>&r=<?php echo $_GET['r']?>" class="dropdown-item no-pjax"><i class="fa fa-filter"></i> <?php echo $cTopic->topic?>
                     <span class="badge badge-pill badge-default  pull-right"><?php echo $tfiltercount[$cTopic->getId()]['__count'] ?></span> </a>
                     <?php }}      
        ?>
            </div>
    </div>
   <?php
          
      switch ($rec){
          case 0:
            $rselected = 'Recordable (No)';
            break;
          case 1:
          $rselected = 'Recordable (Yes)';
            break;
          case 2:
          $rselected = 'Recordable';
            break;
          
          }
?> 
    <div class="btn-group btn-group-sm" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-light dropdown-toggle" <?php if ($filters == 0){ echo 'disabled';}?>
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
         title="<?php echo __('Filter Recordables'); ?>"><i class="fa fa-filter"></i> <?php echo $rselected;?>
        </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
              
              <a href="tickets.php?r=2&t=<?php echo $_GET['t']?>&l=<?php echo $_GET['l']?>&s=<?php echo $_GET['s'];?>"class="dropdown-item no-pjax"><i class="fa fa-filter"></i> All</a>
              
                   <?php if ($ryfiltercount[$cnRecordables->getId()]['__count'] > 0) {?>
                   <a href="tickets.php?r=1&t=<?php echo $_GET['t'] ?>&l=<?php echo $_GET['l']?>&s=<?php echo $_GET['s']?>" class="dropdown-item no-pjax"><i class="fa fa-filter"></i> Yes
                     <span class="badge badge-pill badge-default  pull-right"><?php echo $ryfiltercount[$cyRecordables->getId()]['__count'] ?></span> </a>
                   <?php }?>
                   <?php if ($rnfiltercount[$cnRecordables->getId()]['__count'] > 0) {?>
                   <a href="tickets.php?r=0&t=<?php echo $_GET['t']?>&l=<?php echo $_GET['l']?>&s=<?php echo $_GET['s']?>" class="dropdown-item no-pjax"><i class="fa fa-filter"></i> No
                     <span class="badge badge-pill badge-default  pull-right"><?php  echo $rnfiltercount[$cnRecordables->getId()]['__count'] ?></span> </a>     
                   <?php }?>
            </div>
    </div>
    
    <?php 
    if (!$Statuses)
    return;
//var_dump($nextStatuses);
if (!$sselected) {$sselected = 'Status';}
?>
<div class="btn-group btn-group-sm" role="group">
        
        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-light dropdown-toggle"  <?php if ($filters == 0){ echo 'disabled';}?>
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
         title="<?php echo __('Filter Status'); ?>"><i class="fa fa-filter"></i> <?php echo $sselected; ?>
        </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
              
              <a class="dropdown-item no-pjax" href="tickets.php?l=<?php echo $_GET['l']?>&t=<?php echo $_GET['t']?>&s=0&r=<?php echo $_GET['r']?>"><i class="fa fa-filter"></i> All</a>
           <?php foreach ($Statuses as $status) { 
           
           if ($sfiltercount[$status->getId()]['__count'] >0) {
           
           ?>
           
           
       
            <a class="dropdown-item no-pjax"
                href="tickets.php?l=<?php echo $_GET['l']?>&t=<?php echo $_GET['t']?>&s=<?php echo $status->getId();?>&r=<?php echo $_GET['r']?>"><i class="fa fa-filter"></i> <?php
                        echo __($status->name);?> <span class="queue-status-count badge badge-pill badge-default pull-right"
              ><span class="faded-more"> <?php echo $sfiltercount[$status->getId()]['__count'];?></span></a>
      
    <?php
           }} ?>
        
            </div>
  </div> 
<div class="btn-group btn-group-sm" role="group">
<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-light" 
        
         title="<?php echo __('Clear Filters'); ?>" onclick="location.href = '/scp/tickets.php?queue=1&p=1&l=0&t=0&s=0&r=2';"><span><i class="fa fa-filter"></i><i class="fa fa-ban filtercancel"></i></span> 
        </button
</div>  


</div>

</div></div>
<div class="row subnavspacer">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="clear"></div>
<div>

 

    <form action="?" method="POST" name='tickets' id="tickets">
    <?php csrf_token(); ?>
     <input type="hidden" name="a" value="mass_process" >
     <input type="hidden" name="do" id="action" value="" >
<!--    <table class="list queue tickets" border="0" cellspacing="1" cellpadding="2" width="100%"> -->

    <table  id="ticketqueue" class="table table-striped table-hover table-condensed table-sm">
      <thead>
        <tr>
    <?php
    $canManageTickets = $thisstaff->canManageTickets();
    if ($canManageTickets) { ?>
            <th style="width:12px"></th>
    <?php
    }
    foreach ($columns as $C) {
        $heading = Format::htmlchars($C->getLocalHeading());
        if ($C->isSortable()) {
            $args = $_GET;
            $dir = $sort['col'] != $C->id ?: ($sort['dir'] ? 'desc' : 'asc');
            $args['dir'] = $sort['col'] != $C->id ?: (int) !$sort['dir'];
            $args['sort'] = $C->id;
            $heading = sprintf('<a href="?%s" class="%s">%s</a>',
                Http::build_query($args), $dir, $heading);
        }
        
        
     switch (Format::htmlchars($C->getLocalHeading())) {
             case 'Ticket':
            $foo = '';
            break;
        case 'Priority':
            $foo = 'data-breakpoints="xs sm"';
            break;
        case 'Status':
            $foo = 'data-breakpoints="xs"';
            break;
        case "Closed":
           $foo = 'data-breakpoints="xs sm"';
            break;  
        case "Close Date":
           $foo = 'data-breakpoints="xs sm"';
            break;    
        case "Days Open":
           $foo = 'data-breakpoints="xs sm"';
            break;            
        case "Opened":
           $foo = 'data-breakpoints="xs sm"';
            break;
        case "Last Updated":
           $foo = 'data-breakpoints="xs sm"';
            break; 
        case "Date Created":
           $foo = 'data-breakpoints="xs sm"';
            break; 
        case "Help Topic":
            $foo = 'data-breakpoints="xs"';
            break;
        case "Location":
           $foo = 'data-breakpoints="xs"';
            break;
        case "Purchase Order":
           $foo = 'data-breakpoints="xs sm"';
            break;  
        case "Subject":
           $foo = '';
            break;
        case "From":
           $foo = '';
            break;
        case "Assigned":
           $foo = 'data-breakpoints="xs"';
            break;
        default:
             $foo = '';
    }
        echo sprintf('<th width="%s" data-id="%d" %s>%s</th>',
            $C->getWidth(), $C->id,$foo, $heading);
    }
    ?>
        </tr>
      </thead>
      <tbody>
    <?php
    
    $sitecolor = array(
				"AST"=>"#52e462",
                "BRY"=>"#ff5252",
                "CAN"=>"rgb(241, 92, 128)",
                "IND"=>"#e040fb",
                "MEX"=>"#7c4dff",
                "NTC"=>"rgb(43, 144, 143)",
                "OH"=>"rgb(67, 67, 72)",
                "PAU"=>"#cddc39",
                "RTA"=>"#18ffff",
                "RVC"=>"rgb(247, 163, 92)",
                "TNN1"=>"#69f0ae",
                "TNN2"=>"rgb(124, 181, 236)",
                "TNS"=>"#eeff41",
                "YTD"=>"#c30000");
    foreach ($tickets as $T) {
        echo '<tr>';
        if ($canManageTickets) { ?>
            <td><input type="checkbox" class="ckb" name="tids[]"
                value="<?php echo $T['ticket_id']; ?>" /></td>
    <?php
        }
        
        foreach ($columns as $C) {
                 
        list($contents, $styles) = $C->render($T);
    
         if (strchr($styles, 'badge')!= false){
               
                switch ($contents){
               
                case 'Low':
                    $badgecolor = 'bg-success';
                    break;
                case 'Normal':
                    $badgecolor = 'bg-warning';
                    break;
                case 'High':
                    $badgecolor = 'bg-danger';
                    break;
                    
                default:
                {
                    
                    $badgoverride = null;
                    
                    if ($C->heading == 'Location'){
                       
                        $badgoverride = 'style="background-color: '.$sitecolor[$contents].' !important;"';
        
                    } else {
                       $badgecolor =  strtolower('bg-'.strtok(substr($styles, strpos($styles, "badge:") + 6), ';'));
                       $badgecolor = preg_replace('/\s+/', '', $badgecolor);
                   }
                }
               }
                              
               $badge='badge label-table notranslate '.$badgecolor;
                    
               $styles = str_replace('rem','',str_replace(strtok(substr($styles, strpos($styles, "rem:") + 3), ';'),'',str_replace('badge','rem',$styles)));
              
         }
           
            if ($style = $styles ? 'style="'.$styles.'"' : '') {
                echo "<td $style><div $style><span class =\"$badge\" $badgoverride>$contents</span></div></td>";
            }
            else {
                echo "<td><span class=\"$badge\" >$contents</span></td>";
            }
        $badge=null;
        $badgecolor=null;
        }
        echo '</tr>';
        
           
    }
    ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="<?php echo count($columns)+1; ?>">
            <?php if ($count && $canManageTickets) {
            echo __('Select');?>:&nbsp;
            <a id="selectAll" href="#ckb"><?php echo __('All');?></a>&nbsp;&nbsp;
            <a id="selectNone" href="#ckb"><?php echo __('None');?></a>&nbsp;&nbsp;
            <a id="selectToggle" href="#ckb"><?php echo __('Toggle');?></a>&nbsp;&nbsp;
            <?php }else{
                echo '<i>';
                echo $ferror?Format::htmlchars($ferror):__('Query returned 0 results.');
                echo '</i>';
            } ?>
          </td>
        </tr>
      </tfoot>
    </table>

    <?php
        if ($count > 0) { //if we actually had any tickets returned.
    ?> 
<div class="row">
<div class="col">
    <div class="float-left">
    <nav>
    <ul class="pagination">   
        <?php
            echo $pageNav->getPageLinks();
        ?>
    </ul>

    </nav>
    </div>
    <div class="float-left">
    
    <div class="btn btn-icon waves-effect btn-default m-b-5"> 
           <?php
            echo sprintf('<a class="export-csv no-pjax" href="?%s">%s</a>',
                    Http::build_query(array(
                            'a' => 'export', 'queue' => $_REQUEST['queue'],
                            'status' => $_REQUEST['status'])),
                    ('<i class="ti-cloud-down faded"></i>'));
            ?>
    </div>
            <i class="help-tip icon-question-sign" href="#export"></i>
</div>
    
   
    <div class="float-right">
          <span class="faded"><?php echo $pageNav->showing(); ?></span>
    </div>  
</div></div>

    <?php
        } ?>

    </form>
</div>
</div>
</div>
</div>
<script>
jQuery(function($){
	$('#ticketqueue').footable({"breakpoints": {
		"xs": 480,
		"sm": 768,
		"md": 992,
		"lg": 1280,
		"xlg": 1400
	}});
});
        
        
</script>