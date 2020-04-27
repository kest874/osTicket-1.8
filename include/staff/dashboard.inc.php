<?php 

if ($staff->darkmode ==1 && !isset($_GET["r"])){?>
  <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/highcharts_dark.css" media="all">
<?php } else { ?>
	 <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/highcharts.css" media="all">
<?php } ?>
<script src="<?php echo ROOT_PATH; ?>scp/js/highcharts.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/highcharts-3d.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/highcharts-more.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/exporting.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/export-data.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/modules/pareto.js"></script>
<script
$('#loading').show();
            $.toggleOverlay(true);
</script>
	

Highcharts.theme = {
    chart: {styledMode: true,
    },
};
// Apply the theme
Highcharts.setOptions(Highcharts.theme);
</script>

<?php
$sitecolor = array(
"BRY"=>"#ff5252",
"CAN"=>"rgb(241, 92, 128)",
"IND"=>"#e040fb",
"MEX"=>"#7c4dff",
"NTC"=>"rgb(43, 144, 143)",
"OH"=>"rgb(67, 67, 72)",
"PAU"=>"#40c4ff",
"NTA"=>"#18ffff",
"RVC"=>"rgb(247, 163, 92)",
"TNN1"=>"#69f0ae",
"TNN2"=>"rgb(124, 181, 236)",
"TNS"=>"#eeff41",
"VIP"=>"#c30000",
"EXT"=>"rgb(67, 67, 72)");
?>

<div class="subnav">

    <div class="float-left subnavtitle">
                          
    <?php echo __('IT Dashboard');?>                        
    
    </div>
    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
   &nbsp;
      </div>   
   <div class="clearfix"></div> 
</div> 
 
<div class="row">

    <div class="col-lg-6">
        <div class="portlet" id="backlog-chart-container1"><!-- /primary heading -->
           
        </div>
    </div>
    <div class="col-lg-3">
        <div class="portlet" id="backlog-chart-container" ><!-- /primary heading -->
             
        </div>
    </div>
    <div class="col-lg-3">
        <div class="portlet" id="ticketsbystatus-chart-container"><!-- /primary heading -->
            
        </div>
    </div>
	    <div class="col-lg-6">
        <div class="portlet" id="backlogT-chart-container1"><!-- /primary heading -->
           
        </div>
    </div>
	    <div class="col-lg-3">
        <div class="portlet" id="backlogT-chart-container" ><!-- /primary heading -->
            
        </div>
    </div>
    <div class="col-lg-3">
        <div class="portlet" id="ticketsbystatusT-chart-container"><!-- /primary heading -->
            
        </div>
    </div>
	
	    <div class="col-lg-6">
        <div class="portlet" id="backlogS-chart-container1"><!-- /primary heading -->
           
        </div>
    </div>
	    <div class="col-lg-3">
        <div class="portlet" id="backlogS-chart-container" ><!-- /primary heading -->
            
        </div>
    </div>
    <div class="col-lg-3">
        <div class="portlet" id="ticketsbystatusS-chart-container"><!-- /primary heading -->
            
        </div>
    </div>
	</div>
	<div class="row">
	<div class="col-lg-12">
        <div class="portlet" id="avgdays-chart-container"><!-- /primary heading -->
           
        </div>
    </div>
	</div>
	<div class="row">
    <div class="col-lg-6">
        <div class="portlet" id="toptentopic-chart-container"><!-- /primary heading -->
           
        </div>
    </div>
    <div class="col-lg-3">
        <div class="portlet" id="toptenclosedtopic-container"><!-- /primary heading -->
           
        </div>
    </div>
    <div class="col-lg-3">
        <div class="portlet" id="toptenclosedpytopic-chart-container"><!-- /primary heading -->
            
            </div>
        </div>
       
               <div class="col-lg-3">
        <div class="portlet" id="toptenopenbyassociate-chart-container"><!-- /primary heading -->
           </div>
        </div>
         <div class="col-lg-3">
        <div class="portlet" id="toptenclosebyassociate-chart-container"><!-- /primary heading -->
            
                </div>
            </div>   
</div>

<div class="row">
  
     <div class="col-lg-6">
        <div class="portlet" id="statusbyagent-chart-container1"><!-- /primary heading -->
           
        </div>
    </div>

    
 <div class="col-lg-6">
        <div class="portlet" id="statusbyagent-chart-container2"><!-- /primary heading -->
           
        </div>
    </div>
    
</div>
       <div class="row">
    <div class="col-lg-6">
        <div class="portlet" id="statusbylocation-chart-container1"><!-- /primary heading -->
            
        </div>
    </div>
    <div class="col-lg-6">
        <div class="portlet" id="statusbylocation-chart-container2"><!-- /primary heading -->
            
        </div>
    </div>


 </div>
 <div class="row">
 
 
 <div class="col-lg-12">
        <div class="portlet" id="closedbytech-chart-container1"><!-- /primary heading -->
            
        </div>
    </div>
 
  <div class="col-lg-12">
        <div class="portlet" id="openedbylocation-chart-container1"><!-- /primary heading -->
            
        </div>
    </div>
   <div class="col-lg-12">
        <div class="portlet" id="closedbylocation-chart-container1"><!-- /primary heading -->
            
        </div>
    </div>

 </div>

 <div class="row">
    <div class="col-lg-12">
        <div class="portlet"><!-- /primary heading -->
            <div class="portlet-heading">
                <h3 class="portlet-title text-dark">
                    TICKETS (YEAR TO DATE)
                </h3>
                <div class="portlet-widgets">
                    
                    <span class="divider"></span>
                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet5"><i class="ion-minus-round"></i></a>
                    <span class="divider"></span>
                    <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="portlet5" class="panel-collapse collapse show">
                <div class="portlet-body">
                
                    <div class="table-responsive">
                        
                            <?php
                            $sql = "select distinct STATUS from
                                    (
                                    SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                    left join ost_user u on u.id = t.user_id 
                                    left join ost_organization o on o.id = u.org_id
                                    left join ost_ticket_status s on s.id = t.status_id
                                    where year(t.updated) = year(now()) and s.state = 'open' AND t.topic_id <> 14 AND t.topic_id <> 12
                                    ) a
                                    where LOCATION is not null order by STATUS";
                                    
                            $statuses = db_query($sql);   

                            $sql = "select distinct LOCATION, STATUS from
                                    (
                                    select STATUS, LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now()) and s.state = 'open' AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    group by STATUS, LOCATION ) a
                                    where LOCATION is not null order by LOCATION";
                                    
                            $locs = db_query($sql); 
                            
                            $sql = "SELECT distinct name as LOCATION FROM ost_organization order by LOCATION";
                                    
                            $rawlocs = db_query($sql);
                            
                            $sql = "select STATUS, LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now())and s.state = 'open' AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    group by STATUS, LOCATION ";
                            
                            $tbldatas = db_query($sql);
                            
                            $sql="select LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now()) and s.state = 'open' AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    group by LOCATION ";
                                    
                            $tbltotals = db_query($sql);

                            ?>
                            <table class="table table-hover table-condensed table-sm m-b-0"><thead>
                            <tr class="bg-graphred"><th>OPEN</th>
                            <?php
                            
                            foreach ($rawlocs as $loc) {

                                echo '<th>'.$loc["LOCATION"].'</th>'; 
                            }
                            ?>
                            <th>TOTAL</th></tr></thead>
                            <?php
                            
                            foreach ($statuses as $status) {
                                
                                $class = null;
                                switch ($status["STATUS"]){
                                    case 'Hold':
                                    $class = 'class="text-warning"';
                                    break;
                                    
                                }
                                
                                echo '<tr '.$class.'><td>'.$status["STATUS"].'</td>'; 
                                    foreach ($locs as $loc) {
                                     
                                       if ($status["STATUS"] == $loc["STATUS"]) {
                                           
                                            foreach ($tbldatas as $tbldata) {
                                                
                                                if ($status["STATUS"] == $tbldata["STATUS"] &&  $loc["LOCATION"] == $tbldata["LOCATION"]) {
                                                    
                                                if ($tbldata["COUNT"] != 0) $count = number_format($tbldata["COUNT"]);
                                                    echo '<td>'.$count.'</td>';
                                                    $total = $total + $count;
                                                    $count = null;
                                                }
                                            }
                                            
                                    }
                                
                                } echo '<td><strong><span class="text-danger">'.number_format($total).'</span></strong></td></tr>'; 
                                $total= null;
                            }   
                             ?>
                             <tr class="text-danger"><th>TOTAL</th>
                             <?php
                             $total = null;
                             foreach ($tbltotals as $tbltotal){
                                 $count = $tbltotal["COUNT"];
                                 echo '<td><strong><span class="text-danger">'.number_format($count).'</span></strong></td>';
                                 $total = $total + $count;
                                 $count = null;
                             }   
                             
                             echo '<td><strong><span class="text-danger">'.number_format($total).'</strong></span></td></tr>';
                            ?>
                            
                            <?php
                            $sql = "select distinct STATUS from
                                    (
                                    SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                    left join ost_user u on u.id = t.user_id 
                                    left join ost_organization o on o.id = u.org_id
                                    left join ost_ticket_status s on s.id = t.status_id
                                    where year(t.updated) = year(now()) and s.state = 'closed' AND t.topic_id <> 14 AND t.topic_id <> 12
                                    ) a
                                    where LOCATION is not null and status = 'Closed' or status = 'Auto-Closed'order by STATUS";
                                    
                            $statuses = db_query($sql);   

                            $sql = "select distinct LOCATION, STATUS from
                                    (
                                    select STATUS, LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now()) and s.state = 'closed' AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    group by STATUS, LOCATION ) a
                                    where LOCATION is not null order by STATUS, LOCATION";
                                    
                            $locs = db_query($sql); 
                            
                            $sql = "SELECT distinct name as LOCATION FROM ost_organization order by LOCATION";
                                    
                            $rawlocs = db_query($sql);
                            
                            $sql = "select STATUS, LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now())and s.state = 'closed' AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    where status = 'Closed' or status = 'Auto-Closed' group by STATUS, LOCATION  order by STATUS, LOCATION";
                            
                            $tbldatas = db_query($sql);
                            
                            $sql = "select STATUS,sum(COUNT) as Count from (
                                select STATUS, LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now())and s.state = 'closed' AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    where status = 'Closed' or status = 'Auto-Closed' group by STATUS, LOCATION  order by STATUS, LOCATION
                                    )t
                                    group by status";
                                    
                            $tbldatasts = db_query($sql);
                            
                            foreach ($tbldatasts as $tbldatast) {
                                if ($tbldatast["STATUS"] == "Auto-Closed") { 
                                    $totac = $tbldatast["Count"];
                                }
                                if ($tbldatast["STATUS"] == "Closed") {
                                    $totc = $tbldatast["Count"];
                                }
                            }
                            
                            $sql="select LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now()) and s.state = 'closed' AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    group by LOCATION order by STATUS, LOCATION";
                                    
                            $tbltotals = db_query($sql);
                            

                            ?>
                            <tr><td colspan=17 style="border-top-color: #1a1a1b;">&nbsp;</td></tr>
                            <tr class="bg-graphgreen"><th>CLOSED</th>
                            <?php
                             
                            foreach ($rawlocs as $loc) {

                                echo '<th></th>'; 
                            }
                            ?>
                            <th></th></tr></thead>
                            <?php
                            
                            foreach ($statuses as $status) {
                                
                                $class = null;
                                switch ($status["STATUS"]){
                                    case 'Auto-Closed':
                                    $class = 'class="text-warning"';
                                    break;
                                }
                               
                                
                                echo '<tr '.$class.'><td>'.$status["STATUS"].'</td>'; 
                                    foreach ($locs as $loc) {
                                     
                                       if ($status["STATUS"] == $loc["STATUS"]) {
                                           
                                            foreach ($tbldatas as $tbldata) {
                                                
                                                if ($status["STATUS"] == $tbldata["STATUS"] &&  $loc["LOCATION"] == $tbldata["LOCATION"]) {
                                                  
                                                        
                                                $count = number_format($tbldata["COUNT"]);
                                                ?>
                                                    <td> <?php if ($count !=0)echo $count;?> </td>
                                                                                                                                                               
                                               <?php }
                                            }
                                    }
                                
                                } 
                                if ($status["STATUS"] == "Auto-Closed") {
                                    $ctotal = $totac;
                                } else {
                                    $ctotal = $totc;                                   
                                }
                                                                
                                echo '<td><strong><span class="text-success">'.number_format($ctotal).'</strong></span></td></tr>'; 
                                
                            }   
                             ?>
                             <tr class="text-success"><th>TOTAL</th>
                             <?php
                             
                             foreach ($tbltotals as $tbltotal){
                                 $count = $tbltotal["COUNT"];
                                 echo '<td><strong><span class="text-success">'.number_format($count).'</strong></span></td>';
                                 $btotal = $btotal + $count;
                                 $count = null;
                             }   
                             
                             echo '<td><strong><span class="text-success">'.number_format($btotal).'</strong></span></td></tr>';
                            ?>
                            
                            
                            <?php
                            $sql = "select distinct STATUS from
                                    (
                                    SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                    left join ost_user u on u.id = t.user_id 
                                    left join ost_organization o on o.id = u.org_id
                                    left join ost_ticket_status s on s.id = t.status_id
                                    where year(t.updated) = year(now()) AND t.topic_id <> 14 AND t.topic_id <> 12
                                    ) a
                                    where LOCATION is not null order by STATUS";
                                    
                            $statuses = db_query($sql);   

                            $sql = "select distinct LOCATION, STATUS from
                                    (
                                    select STATUS, LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now()) AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    group by STATUS, LOCATION ) a
                                    where LOCATION is not null order by LOCATION";
                                    
                            $locs = db_query($sql); 
                            
                            $sql = "SELECT distinct name as LOCATION FROM ost_organization order by LOCATION";
                                    
                            $rawlocs = db_query($sql);
                            
                            $sql = "select STATUS, LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now()) AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    group by STATUS, LOCATION ";
                            
                            $tbldatas = db_query($sql);
                            
                            $sql="select LOCATION, sum(COUNT) as COUNT from
                                    (
                                    select STATUS, LOCATION, count(ticket_id) as COUNT from
                                        (
                                        SELECT t.ticket_id, t.updated, o.name as LOCATION, s.name as STATUS FROM ost_ticket t 
                                        left join ost_user u on u.id = t.user_id 
                                        left join ost_organization o on o.id = u.org_id
                                        left join ost_ticket_status s on s.id = t.status_id
                                        where year(t.updated) = year(now())AND t.topic_id <> 14 AND t.topic_id <> 12
                                        ) a
                                        where LOCATION is not null
                                        group by STATUS, LOCATION 
                                        union all 
                                        SELECT s.name as STATUS, o.name as LOCATION , 0 as COUNT FROM ost_organization o
                                    join
                                    ost_ticket_status s on 1=1 order by STATUS, LOCATION
                                    )d
                                    group by LOCATION ";
                                    
                            $tbltotals = db_query($sql);
                            
                            $sql="select sum(COUNT) as COUNT, LOCATION from (
                                    Select count(user_id) as COUNT, LOCATION from
                                         (
                                         SELECT distinct t.user_id, o.name as LOCATION FROM ost_ticket t 
                                         left join ost_user u on t.user_id = u.id 
                                         left join ost_organization o on u.org_id = o.id 
                                          
                                         where year(t.updated) = year(now())AND t.topic_id <> 14 AND t.topic_id <> 12 
                                         
                                         )a
                                         where LOCATION is not null
                                         group by LOCATION 
                                         
                                         union all 
                                                 SELECT 0 as COUNT, o.name as LOCATION  FROM ost_organization o
                                              order by LOCATION)b
                                group by LOCATION";
                             
                            $usertotals = db_query($sql);

                            ?>
                            <tr><td colspan=17 style="border-top-color: #1a1a1b;">&nbsp;</td></tr>
                            <tr class="bg-graphgreen"><th>ALL TICKETS</th>
                            <?php
                             
                            foreach ($rawlocs as $loc) {

                                echo '<th></th>'; 
                            }
                            ?>
                            <th></th></tr></thead>
                          
                             <tr class="text-success"><th>TOTAL</th>
                             <?php
                             $total = null;
                             foreach ($tbltotals as $tbltotal){
                                 $count = $tbltotal["COUNT"];
                                 echo '<td><strong><span class="text-success">'.number_format($count).'</strong></span></td>';
                                 $ttotal = $ttotal + $count;
                                 $count = null;
                             }   
                             echo '<td><strong><span class="text-primary">'.number_format($ttotal).'</strong></span></td></tr>';
                            ?>
                            <tr class="text-warning"><th>TOTAL USERS</th>
                             <?php
                             $total = null;
                             foreach ($usertotals as $tbltotal){
                                 $count = $tbltotal["COUNT"];
                                 echo '<td><strong><span class="text-warning">'.number_format($count).'</strong></span></td>';
                                 $total = $total + $count;
                                 $count = null;
                             }   
                             $utotal = $total;
                             echo '<td><strong><span class="text-primary">'.number_format($utotal).'</strong></span></td></tr>';
                            ?>
                            <tr class="text-secondary"><th>TICKETS PER USER</th>
                             <?php
                             $total = null;
                             foreach ($tbltotals as $tbltotal){
                                 
                                 foreach ($usertotals as $usertotal){
                                     
                                     if ($usertotal["LOCATION"] == $tbltotal["LOCATION"]){
                                     if ($usertotal["COUNT"] !=0){
                                         $tcount = $tbltotal["COUNT"] / $usertotal["COUNT"];
                                     } else {
                                         $tcount =0;
                                     }
                                     echo '<td><strong><span class="text-secondary">'.number_format($tcount).'</strong></span></td>';
                                 $total = $total + $usertotal["COUNT"];
                                 $tcount = null;
                                     }
                                 }
                                 
                                 
                             }   
                             $ttotal = $ttotal / $total;
                             echo '<td><strong><span class="text-primary">'.number_format($ttotal).'</strong></span></td></tr>';
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
<script>
		
         <?php
        $sql="select CALENDARWEEK as WEEK, 
                max(case when Status = 'OPEN' then VALUE else 0 end)as OPEN, 
                max(case when Status = 'CLOSED' then VALUE else 0 end) as CLOSED,
                max(case when Status = 'BACKLOG' then VALUE else '0' end) as BACKLOG
                from ( 
                Select * from(                        
                                SELECT   COUNT(created) AS VALUE, 'OPEN' AS Status, FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) 
                                                         - 2, 7)) AS CALENDARWEEK
                                FROM         ost_ticket
                                WHERE     FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) - 2, 7)) BETWEEN DATE_SUB(CURRENT_DATE (), 
                                                         INTERVAL 4 month) AND CURRENT_DATE ()
                                AND ost_ticket.topic_id <> 12 and topic_id <> 14 AND topic_id <> 94
                                GROUP BY FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) - 2, 7)) 
                                
                                Union all
                                
                                SELECT   COUNT(closed) AS VALUE, 'CLOSED' AS Status, FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) 
                                                         - 2, 7)) AS CALENDARWEEK
                                FROM         ost_ticket
                                WHERE     FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) - 2, 7)) BETWEEN DATE_SUB(CURRENT_DATE (), 
                                                         INTERVAL 4 month) AND CURRENT_DATE ()
                                AND ost_ticket.topic_id <> 12 and topic_id <> 14 AND topic_id <> 94
                                GROUP BY FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) - 2, 7))) data
                                
                                UNION all 
                                select sum(CAN)+sum(EXT)+sum(IND)+sum(MEX)+sum(NTC)+sum(OH)+sum(TNN1)+sum(SS)+sum(TNN2)+sum(TNS)+sum(RVC)+sum(NTA)+sum(BRY)+sum(PAU)+sum(VIP) as VALUE, 'BACKLOG' AS Status,  
                STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W') as CALENDARWEEK from ost_backlog 
                where STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W')
                BETWEEN DATE_SUB(CURRENT_DATE (), INTERVAL 4 month) AND CURRENT_DATE ()
                group by STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W')
                union
 /*current backlog*/
 select sum(count) as value, 'BACKLOG' AS Status, FROM_DAYS(TO_DAYS(now()) - MOD(TO_DAYS(now()) 
                                                         - 2, 7)) AS CALENDARWEEK from(
/* shopedge */ 
		select  sum(count) as count from(
									select org.name as location, IFNULL(s.count,0) as count from ost_organization org left join (
									select o.name ,count(a.ticket_id) as count  from ost_ticket a join ost_user u on a.user_id = u.id right join ost_organization o on u.org_id = o.id
									WHERE 
									a.status_id in (7)
									AND a.topic_id  in (13,161,15,99,78,17,18,19,100,16,101,102)
									group by o.name ) s on org.name = s.name
									
									union 
									
									select name as location, 0 as COUNT from ost_organization)i
/* shopedge */                                  
							        union
/* support */
									select  sum(count) as count from(
									select org.name as location, IFNULL(s.count,0) as count from ost_organization org left join (
									select o.name ,count(a.ticket_id) as count  from ost_ticket a join ost_user u on a.user_id = u.id right join ost_organization o on u.org_id = o.id
									WHERE 
									a.status_id in (7)
									AND a.topic_id not in (163,94,93,12,92,13,14,161,15,99,78,17,18,19,100,16,101,102)
									group by o.name ) s on org.name = s.name
									
									union 
									
									select name as location, 0 as COUNT from ost_organization)i
/* support */
									union
 /* Unassigned */                                   
									select count(topic_id) as count from ost_ticket where status_id = 1 and topic_id not in (12,94)
 /* Unassigned */       
 )c
 /*current backlog*/             
                Order by CALENDARWEEK, STATUS)dt
                group by CALENDARWEEK;";
        $results = db_query($sql); 
        
    ?> 
    
    
 $(function() {        
     Highcharts.chart('backlog-chart-container1', {
        chart: {
            type: 'areaspline',
        },
        title: {
            text: 'IT TICKETS (OPENED|CLOSED|BACKLOG)',
            style: {
                color: '#797979',
                fontSize: '14px',
                fontWeight: '600',
                }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },

        xAxis: {
            categories: [ <?php foreach ($results as $result) {echo "'".$result['WEEK']."',";}?>
                
            ],
            
        },
        yAxis: {
            title: {
                text: 'Number of Tickets'
            },
              plotLines: [{
                color: 'green', // Color value
                dashStyle: 'shortdash', // Style of the plot line. Default to solid
                value: 45, // Value of where the line will appear
                width: 2, // Width of the line
                label: {
                    text: '',
                    style: {
                    color: 'black',
                    fontWeight: 'bold'
                }
                } ,
                zIndex: 6                
              }]
                    },
        tooltip: {
            shared: true,
            valueSuffix: ' tickets'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [
        {
            type: 'column',
            name: 'CLOSED',
            data: [<?php foreach ($results as $result) { echo $result['CLOSED'].',';}?>]
        }, {
            type: 'spline',
            name: 'OPENED',
            data: [<?php foreach ($results as $result) { echo $result['OPEN'].',';}?>],
            color: '#e3c436'
            
        }, {
            name: 'BACKLOG',
            data: [<?php foreach ($results as $result) { 
			$bvalue = ( $result['BACKLOG'] == 0 ? 0:$result['BACKLOG'] );
			echo $bvalue.',';}?>],
			  color: '#dd3c37'
            
          }]

    });

});      
 

 
	
       <?php
        $sql="select CALENDARWEEK as WEEK, 
                max(case when Status = 'OPEN' then VALUE else 0 end)as OPEN, 
                max(case when Status = 'CLOSED' then VALUE else 0 end) as CLOSED,
                max(case when Status = 'BACKLOG' then VALUE else '0' end) as BACKLOG
                from ( 
                Select * from(                        
                                SELECT   COUNT(created) AS VALUE, 'OPEN' AS Status, FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) 
                                                         - 2, 7)) AS CALENDARWEEK
                                FROM         ost_ticket
                                WHERE     FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) - 2, 7)) BETWEEN DATE_SUB(CURRENT_DATE (), 
                                                         INTERVAL 4 month) AND CURRENT_DATE ()
                                AND (topic_id != '12' 
									AND topic_id != '14' 
									AND topic_id != '94' 
									AND topic_id != '13'
									AND topic_id != '14'
									AND topic_id != '15' 
									AND topic_id != '16' 
									AND topic_id != '17' 
									AND topic_id != '18' 
									AND topic_id != '19')
                                GROUP BY FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) - 2, 7)) 
                                
                                Union all
                                
                                SELECT   COUNT(closed) AS VALUE, 'CLOSED' AS Status, FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) 
                                                         - 2, 7)) AS CALENDARWEEK
                                FROM         ost_ticket
                                WHERE     FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) - 2, 7)) BETWEEN DATE_SUB(CURRENT_DATE (), 
                                                         INTERVAL 4 month) AND CURRENT_DATE ()
                               AND (topic_id != '12' 
									AND topic_id != '14' 
									AND topic_id != '94' 
									AND topic_id != '13'
									AND topic_id != '14'
									AND topic_id != '15' 
									AND topic_id != '16' 
									AND topic_id != '17' 
									AND topic_id != '18' 
									AND topic_id != '19')
                                GROUP BY FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) - 2, 7))) data
                                
                                UNION all 
                                select sum(CAN)+sum(EXT)+sum(IND)+sum(MEX)+sum(NTC)+sum(OH)+sum(TNN1)+sum(SS)+sum(TNN2)+sum(TNS)+sum(RVC)+sum(NTA)+sum(BRY)+sum(PAU)+sum(VIP) as VALUE, 'BACKLOG' AS Status,  
								STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W') as CALENDARWEEK from ost_backlog 
				
								where STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W')
				
								BETWEEN DATE_SUB(CURRENT_DATE (), INTERVAL 4 month) AND CURRENT_DATE () and Type = 'IT'
								group by STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W')
								
								union
								
								select sum(count) as VALUE,'BACKLOG' AS Status, STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W') as CALENDARWEEK from (select  sum(count) as count, WEEK, YEAR, YEARWEEK from(
								select org.name as location, IFNULL(s.count,0) as count, week(now()) as WEEK, 
								year(now()) as YEAR,yearweek(now(),3) as YEARWEEK  from ost_organization org left join (
								select o.name ,count(a.ticket_id) as count  from ost_ticket a join ost_user u on a.user_id = u.id right join ost_organization o on u.org_id = o.id
								WHERE 
								a.status_id in (7)
							     AND a.topic_id not in (163,94,93,12,92,13,14,161,15,99,78,17,18,19,100,16,101,102)
								group by o.name ) s on org.name = s.name
								
								union 
								
								
								select name as location, 0 as COUNT ,week(now()) as WEEK, 
								year(now()) as YEAR,yearweek(now(),3) as YEARWEEK  from ost_organization)i
							
								group by  WEEK, YEAR, YEARWEEK )a				
				Order by CALENDARWEEK, STATUS)dt
                group by CALENDARWEEK;";
        $results = db_query($sql); 
        
    ?> 
    
    
 $(function() {        
     Highcharts.chart('backlogT-chart-container1', {
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'SUPPORT TICKETS (OPENED|CLOSED|BACKLOG)',
            style: {
                color: '#797979',
                fontSize: '14px',
                fontWeight: '600',
                }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },

        xAxis: {
            categories: [ <?php foreach ($results as $result) {echo "'".$result['WEEK']."',";}?>
                
            ],
            
        },
        yAxis: {
            title: {
                text: 'Number of Tickets'
            },
              plotLines: [{
                color: 'green', // Color value
                dashStyle: 'shortdash', // Style of the plot line. Default to solid
                value: 35, // Value of where the line will appear
                width: 2, // Width of the line
                label: {
                    text: '',
                    style: {
                    color: 'black',
                    fontWeight: 'bold'
                }
                } ,
                zIndex: 6                
              }]
                    },
        tooltip: {
            shared: true,
            valueSuffix: ' tickets'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [
        {
            type: 'column',
            name: 'CLOSED',
            data: [<?php foreach ($results as $result) { echo $result['CLOSED'].',';}?>]
        }, {
            type: 'spline',
            name: 'OPENED',
            data: [<?php foreach ($results as $result) { echo $result['OPEN'].',';}?>],
            color: '#e3c436'
            
        }, {
            name: 'BACKLOG',
            data: [<?php foreach ($results as $result) { 
			$bvalue = ( $result['BACKLOG'] == 0 ? 0:$result['BACKLOG'] );
			echo $bvalue.',';}?>],
            color: '#dd3c37'
            
          }]

    });

});      

       <?php
        $sql="select CALENDARWEEK as WEEK, 
                max(case when Status = 'OPEN' then VALUE else 0 end)as OPEN, 
                max(case when Status = 'CLOSED' then VALUE else 0 end) as CLOSED,
                max(case when Status = 'BACKLOG' then VALUE else '0' end) as BACKLOG
                from ( 
                Select * from(                        
                                SELECT   COUNT(created) AS VALUE, 'OPEN' AS Status, FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) 
                                                         - 2, 7)) AS CALENDARWEEK
                                FROM         ost_ticket
                                WHERE     FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) - 2, 7)) BETWEEN DATE_SUB(CURRENT_DATE (), 
                                                         INTERVAL 4 month) AND CURRENT_DATE ()
                                AND (topic_id = '13' 
									OR topic_id = '15' 
									OR topic_id = '16' 
									OR topic_id = '17' 
									OR topic_id = '18' 
									OR topic_id = '19')
                                GROUP BY FROM_DAYS(TO_DAYS(created) - MOD(TO_DAYS(created) - 2, 7)) 
                                
                                Union all
                                
                                SELECT   COUNT(closed) AS VALUE, 'CLOSED' AS Status, FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) 
                                                         - 2, 7)) AS CALENDARWEEK
                                FROM         ost_ticket
                                WHERE     FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) - 2, 7)) BETWEEN DATE_SUB(CURRENT_DATE (), 
                                                         INTERVAL 4 month) AND CURRENT_DATE ()
                               AND (topic_id = '13' 
									OR topic_id = '15' 
									OR topic_id = '16' 
									OR topic_id = '17' 
									OR topic_id = '18' 
									OR topic_id = '19')
                                GROUP BY FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed) - 2, 7))) data
                                
                                UNION all 
                                select sum(CAN)+sum(EXT)+sum(IND)+sum(MEX)+sum(NTC)+sum(OH)+sum(TNN1)+sum(SS)+sum(TNN2)+sum(TNS)+sum(RVC)+sum(NTA)+sum(BRY)+sum(PAU)+sum(VIP) as VALUE, 'BACKLOG' AS Status,  
								STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W') as CALENDARWEEK from ost_backlog 
				
								where STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W')
				
								BETWEEN DATE_SUB(CURRENT_DATE (), INTERVAL 4 month) AND CURRENT_DATE () and Type = 'SE'
								group by STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W')
				
								union
											
								select sum(count) as VALUE,'BACKLOG' AS Status, STR_TO_DATE(CONCAT(YEARWEEK,' Monday'), '%x%v %W') as CALENDARWEEK from (select  sum(count) as count, WEEK, YEAR, YEARWEEK from(
								select org.name as location, IFNULL(s.count,0) as count, week(now()) as WEEK, 
								year(now()) as YEAR,yearweek(now(),3) as YEARWEEK  from ost_organization org left join (
								select o.name ,count(a.ticket_id) as count  from ost_ticket a join ost_user u on a.user_id = u.id right join ost_organization o on u.org_id = o.id
								WHERE 
								a.status_id in (7)
								AND a.topic_id  in (13,161,15,99,78,17,18,19,100,16,101,102)
								group by o.name ) s on org.name = s.name
								
								union 
												
								select name as location, 0 as COUNT ,week(now()) as WEEK, 
								year(now()) as YEAR,yearweek(now(),3) as YEARWEEK  from ost_organization)i
				
								group by  WEEK, YEAR, YEARWEEK )a				
			    Order by CALENDARWEEK, STATUS)dt
                group by CALENDARWEEK;";
        $results = db_query($sql); 
        
    ?> 
    
    
 $(function() {        
     Highcharts.chart('backlogS-chart-container1', {
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'SHOPEDGE TICKETS (OPENED|CLOSED|BACKLOG)',
            style: {
                color: '#797979',
                fontSize: '14px',
                fontWeight: '600',
                }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },

        xAxis: {
            categories: [ <?php foreach ($results as $result) {echo "'".$result['WEEK']."',";}?>
                
            ],
            
        },
        yAxis: {
            title: {
                text: 'Number of Tickets'
            },
              plotLines: [{
                color: 'green', // Color value
                dashStyle: 'shortdash', // Style of the plot line. Default to solid
                value: 5, // Value of where the line will appear
                width: 2, // Width of the line
                label: {
                    text: '',
                    style: {
                    color: 'black',
                    fontWeight: 'bold'
                }
                } ,
                zIndex: 6                
              }]
                    },
        tooltip: {
            shared: true,
            valueSuffix: ' tickets'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [
        {
            type: 'column',
            name: 'CLOSED',
            data: [<?php foreach ($results as $result) { echo $result['CLOSED'].',';}?>]
        }, {
            type: 'spline',
            name: 'OPENED',
            data: [<?php foreach ($results as $result) { echo $result['OPEN'].',';}?>],
            color: '#e3c436'
            
        }, {
            name: 'BACKLOG',
            data: [<?php foreach ($results as $result) { 
			$bvalue = ( $result['BACKLOG'] == 0 ? 0:$result['BACKLOG'] );
			echo $bvalue.',';}?>],
            color: '#dd3c37'
            
          }]

    });

});      	

///// AVG Days

<?php
        $sql1="select avg(daysopen) as DaysOpen,CALENDARWEEK from
					( select datediff(ost_ticket.closed,created) as DaysOpen,  FROM_DAYS(TO_DAYS(closed) - MOD(TO_DAYS(closed)- 2, 7)) AS CALENDARWEEK FROM ost_ticket where closed > DATE_SUB(LAST_DAY(DATE_ADD(NOW(), INTERVAL 12-MONTH(NOW()) MONTH)), INTERVAL 1 YEAR) 
					AND ost_ticket.topic_id <> 12 and topic_id <> 14 AND topic_id <> 94 and (status_id = 3 or status_id=12)) d
					group by CALENDARWEEK";
        $tresults = db_query($sql1); 
    ?>    
$(function() {        
 Highcharts.chart('avgdays-chart-container', {

    chart: {
        renderTo: 'avgdays-chart-container',
        type: 'column'
    },
    title: {
        text: 'Average Days Open',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
    },
    xAxis: {
        categories: [<?php foreach ($tresults as $tresult) {echo "'".$tresult['CALENDARWEEK']."',";}?>]
    },
    yAxis: [{
        title: {
            text: 'Average Days'
        }
    }],
    credits: false,
    series: [{
        name: 'Days',
        type: 'column',
        zIndex: 2,
        data: [<?php foreach ($tresults as $tresult) {echo $tresult['DaysOpen'].',';} ?>]
    }]
});

});        

<?php
        $sql1="select count(t.topic_id) as COUNT,topics.topic as TOPIC  from ost_ticket t
join 
(
select * from (select topic_id, topic from ost_help_topic where topic_pid = 0  and topic_id not in (12,92,93,94)
union
select ht.topic_id,concat(htp.topic,' / ', ht.topic) as topic from ost_help_topic ht join (SELECT topic_id, topic FROM osticket_sup.ost_help_topic where isactive = 1)htp on ht.topic_pid = htp.topic_id )data
) topics
on t.topic_id = topics.topic_id
where t.status_id not in (3,4,5,12) and t.topic_id not in (12,14)
group by t.topic_id  order by COUNT desc limit 10";

$tresults = db_query($sql1); 
    ?>    
$(function() {        
 Highcharts.chart('toptentopic-chart-container', {

    chart: {
        renderTo: 'toptentopic-chart-container',
        type: 'column'
    },
    title: {
        text: 'TOP 10 OPEN TOPICS',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
    },
    xAxis: {
        categories: [<?php foreach ($tresults as $tresult) {echo "'".$tresult['TOPIC']."',";}?>]
    },
    yAxis: [{
        title: {
            text: ''
        }
    }, {
        title: {
            text: ''
        },
        minPadding: 0,
        maxPadding: 0,
        max: 100,
        min: 0,
        opposite: true,
        labels: {
            format: "{value}%"
        }
    }],
    credits: false,
    series: [{
        type: 'pareto',
        name: 'Pareto',
        yAxis: 1,
        zIndex: 10,
        baseSeries: 1
    }, {
        name: 'Tickets',
        type: 'column',
        zIndex: 2,
        data: [<?php foreach ($tresults as $tresult) {echo $tresult['COUNT'].',';} ?>]
    }]
});

});        
    
//Top 10 Closed
  
<?php
        $sql1="select count(t.topic_id) as COUNT,topics.topic as TOPIC  from ost_ticket t
join 
(
select * from (select topic_id, topic from ost_help_topic where topic_pid = 0  and topic_id not in (12,92,93,94)
union
select ht.topic_id,concat(htp.topic,' / ', ht.topic) as topic from ost_help_topic ht join (SELECT topic_id, topic FROM osticket_sup.ost_help_topic where isactive = 1)htp on ht.topic_pid = htp.topic_id )data
) topics
on t.topic_id = topics.topic_id
where year(t.closed) = year(now()) and t.status_id in (2,3) and t.topic_id not in (12,14)
group by t.topic_id  order by COUNT desc limit 10";
$tresults = db_query($sql1); 
?>


$(function() {        
 Highcharts.chart('toptenclosedtopic-container', {

    chart: {
        renderTo: 'toptenclosedtopic-container',
        type: 'column'
    },
    title: {
        text: 'TOP 10 CLOSED TOPICS',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
    },
    xAxis: {
        categories: [<?php foreach ($tresults as $tresult) {echo "'".$tresult['TOPIC']."',";}?>]
    },
    yAxis: [{
        title: {
            text: ''
        }
    }, {
        title: {
            text: ''
        },
        minPadding: 0,
        maxPadding: 0,
        max: 100,
        min: 0,
        opposite: true,
        labels: {
            format: "{value}%"
        }
    }],
    credits: false,
    series: [{
        type: 'pareto',
        name: 'Pareto',
        yAxis: 1,
        zIndex: 10,
        baseSeries: 1
    }, {
        name: 'Tickets',
        type: 'column',
        zIndex: 2,
        data: [<?php foreach ($tresults as $tresult) {echo $tresult['COUNT'].',';} ?>]
    }]
});

});        
        

//Top 10 Closed prior
<?php
        $sql1="select count(t.topic_id) as COUNT,topics.topic as TOPIC  from ost_ticket t
join 
(
select * from (select topic_id, topic from ost_help_topic where topic_pid = 0  and topic_id not in (12,92,93,94)
union
select ht.topic_id,concat(htp.topic,' / ', ht.topic) as topic from ost_help_topic ht join (SELECT topic_id, topic FROM osticket_sup.ost_help_topic where isactive = 1)htp on ht.topic_pid = htp.topic_id )data
) topics
on t.topic_id = topics.topic_id
where year(t.closed) = year(now())-1 and t.status_id in (2,3) and t.topic_id not in (12,14)
group by t.topic_id  order by COUNT desc limit 10";
        $ptresults = db_query($sql1); 
?>

$(function() {
 Highcharts.chart('toptenclosedpytopic-chart-container', {

    chart: {
        renderTo: 'toptenclosedpytopic-chart-container',
        type: 'column'
    },
    title: {
        text: 'TOP 10 CLOSED PRIOR YEAR TOPICS',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
    },
    xAxis: {
        categories: [<?php foreach ($ptresults as $tresult) {echo "'".$tresult['TOPIC']."',";}?>]
    },
    yAxis: [{
        title: {
            text: ''
        }
    }, {
        title: {
            text: ''
        },
        minPadding: 0,
        maxPadding: 0,
        max: 100,
        min: 0,
        opposite: true,
        labels: {
            format: "{value}%"
        }
    }],
    credits: false,
    series: [{
        type: 'pareto',
        name: 'Pareto',
        yAxis: 1,
        zIndex: 10,
        baseSeries: 1
    }, {
        name: 'Tickets',
        type: 'column',
        zIndex: 2,
        data: [<?php foreach ($ptresults as $tresult) {echo $tresult['COUNT'].',';} ?>]
    }]
});

	
	});
//Top 10 Open by Associate
<?php
 $sql1="select * from (
	select count(ticket_id) as COUNT, ASSOCIATE, LOCATION from
	(
	SELECT t.ticket_id, t.updated, o.name as LOCATION, u.name as ASSOCIATE FROM ost_ticket t 
		left join ost_user u on u.id = t.user_id 
		left join ost_organization o on o.id = u.org_id
		left join ost_ticket_status s on s.id = t.status_id
		where year(t.updated) = year(now())AND t.topic_id <> 14 AND t.topic_id <> 12 and u.id <> 674 and s.state='open'
	)a
	group by ASSOCIATE order by COUNT DESC
    ) a limit 10";
        $tresults = db_query($sql1); 
   ?>     
$(function() {
     Highcharts.chart('toptenopenbyassociate-chart-container', {

        chart: {
            renderTo: 'toptenopenbyassociate-chart-container',
            type: 'column'
        },
        title: {
            text: 'TOP 10 OPEN BY ASSOCIATE',
                style: {
                color: '#797979',
                fontSize: '14px',
                fontWeight: '600',
                }
        },
        xAxis: {
            categories: [<?php foreach ($tresults as $tresult) {echo "'".$tresult['ASSOCIATE']."',";}?>]
        },
        yAxis: [{
            title: {
                text: ''
            }
        }, {
            title: {
                text: ''
            },
            minPadding: 0,
            maxPadding: 0,
            max: 100,
            min: 0,
            opposite: true,
            labels: {
                format: "{value}%"
            }
        }],
        credits: false,
        series: [{
            type: 'pareto',
            name: 'Pareto',
            yAxis: 1,
            zIndex: 10,
            baseSeries: 1
        }, {
            name: 'Tickets',
            type: 'column',
            zIndex: 2,
            data: [<?php foreach ($tresults as $tresult) {echo $tresult['COUNT'].',';} ?>]
        }]
    });
});

 //Top 10 Closed Associate
<?php
 $sql1="select * from (
	select count(ticket_id) as COUNT, ASSOCIATE, LOCATION from
	(
	SELECT t.ticket_id, t.updated, o.name as LOCATION, u.name as ASSOCIATE FROM ost_ticket t 
		left join ost_user u on u.id = t.user_id 
		left join ost_organization o on o.id = u.org_id
		left join ost_ticket_status s on s.id = t.status_id
		where year(t.updated) = year(now())AND t.topic_id <> 14 AND t.topic_id <> 12 and u.id <> 674 and s.state='closed'
	)a
	group by ASSOCIATE order by COUNT DESC
    ) a limit 10";
        $tresults = db_query($sql1); 
   ?>     
$(function() {
     Highcharts.chart('toptenclosebyassociate-chart-container', {

        chart: {
            renderTo: 'toptenclosebyassociate-chart-container',
            type: 'column'
        },
        title: {
            text: 'TOP 10 CLOSED BY ASSOCIATE',
                style: {
                color: '#797979',
                fontSize: '14px',
                fontWeight: '600',
                }
        },
        xAxis: {
            categories: [<?php foreach ($tresults as $tresult) {echo "'".$tresult['ASSOCIATE']."',";}?>]
        },
        yAxis: [{
            title: {
                text: ''
            }
        }, {
            title: {
                text: ''
            },
            minPadding: 0,
            maxPadding: 0,
            max: 100,
            min: 0,
            opposite: true,
            labels: {
                format: "{value}%"
            }
        }],
        credits: false,
        series: [{
            type: 'pareto',
            name: 'Pareto',
            yAxis: 1,
            zIndex: 10,
            baseSeries: 1
        }, {
            name: 'Tickets',
            type: 'column',
            zIndex: 2,
            data: [<?php foreach ($tresults as $tresult) {echo $tresult['COUNT'].',';} ?>]
        }]
    });
});
		
   

//Backlog
$(function() {

    Highcharts.chart('backlog-chart-container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'BACKLOG (<?php echo $BacklogITTotal+$BacklogSETotal+$UnassignedTickets;?>)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> <b> ({point.y})</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Backlog',
            data: [
            <?php if ($BacklogTickets["CAN"]) { ?>
            ["CAN", <?php echo $BacklogTickets["CAN"]; ?>],
            <?php } if ($BacklogTickets["BRY"]) { ?>
            ["BRY", <?php echo $BacklogTickets["BRY"]; ?>],              
            <?php } if ($BacklogTickets["EXT"]) { ?>
            ["EXT", <?php echo $BacklogTickets["EXT"]; ?>], 
            <?php } if ($BacklogTickets["IND"]) { ?>
            ["IND", <?php echo $BacklogTickets["IND"]; ?>], 
            <?php } if ($BacklogTickets["MEX"]) { ?>
            ["MEX", <?php echo $BacklogTickets["MEX"]; ?>], 
            <?php } if ($BacklogTickets["NTC"]) { ?>
            ["NTC", <?php echo $BacklogTickets["NTC"]; ?>], 
            <?php } if ($BacklogTickets["OH"]) { ?>
            ["OH", <?php echo $BacklogTickets["OH"]; ?>],
            <?php } if ($BacklogTickets["PAU"]) { ?>
            ["PAU", <?php echo $BacklogTickets["PAU"]; ?>],
            <?php } if ($BacklogTickets["NTA"]) { ?>
            ["NTA", <?php echo $BacklogTickets["NTA"]; ?>], 
            <?php } if ($BacklogTickets["RTC"]) { ?>
            ["RTC", <?php echo $BacklogTickets["RTC"]; ?>],         
             <?php } if ($BacklogTickets["RVC"]) { ?>
            ["RVC", <?php echo $BacklogTickets["RVC"]; ?>],           
            <?php } if ($BacklogTickets["SS"]) { ?>
            ["SS", <?php echo $BacklogTickets["SS"]; ?>], 
            <?php } if ($BacklogTickets["TNN1"]) { ?>   
            ["TNN1", <?php echo $BacklogTickets["TNN1"]; ?>], 
            <?php } if ($BacklogTickets["TNN2"]) { ?>
            ["TNN2", <?php echo $BacklogTickets["TNN2"]; ?>], 
            <?php } if ($BacklogTickets["TNS"]) { ?>
            ["TNS", <?php echo $BacklogTickets["TNS"]; ?>],
            <?php } if ($BacklogTickets["VIP"]) { ?>
            ["VIP", <?php echo $BacklogTickets["VIP"]; ?>],
            <?php } ?>
            ]
        }]
    });
});

//Backlog
$(function() {

    Highcharts.chart('backlogT-chart-container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'SUPPORT BACKLOG (<?php echo $BacklogITTotal;?>)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> <b> ({point.y})</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Backlog',
            data: [
            <?php if ($BacklogITCAN) { ?>
            ["CAN", <?php echo $BacklogITCAN ?>],
            <?php } if ($BacklogITBRY) { ?>
            ["BRY", <?php echo $BacklogITBRY; ?>],              
            <?php } if ($BacklogITEXT) { ?>
            ["EXT", <?php echo $BacklogITEXT; ?>], 
            <?php } if ($BacklogITIND) { ?>
            ["IND", <?php echo $BacklogITIND; ?>], 
            <?php } if ($BacklogITMEX) { ?>
            ["MEX", <?php echo $BacklogITMEX; ?>], 
            <?php } if ($BacklogITNTC) { ?>
            ["NTC", <?php echo $BacklogITNTC; ?>], 
            <?php } if ($BacklogITOH) { ?>
            ["OH", <?php echo $BacklogITOH; ?>],
            <?php } if ($BacklogITPAU) { ?>
            ["PAU", <?php echo $BacklogITPAU; ?>],
            <?php } if ($BacklogITNTA) { ?>
            ["NTA", <?php echo $BacklogITNTA; ?>], 
            <?php } if ($BacklogITRTC) { ?>
            ["RTC", <?php echo $BacklogITRTC; ?>],         
             <?php } if ($BacklogITRVC) { ?>
            ["RVC", <?php echo $BacklogITRVC; ?>],           
            <?php } if ($BacklogITSS) { ?>
            ["SS", <?php echo $BacklogITSS; ?>], 
            <?php } if ($BacklogITTNN1) { ?>   
            ["TNN1", <?php echo $BacklogITTNN1; ?>], 
            <?php } if ($BacklogITTNN2) { ?>
            ["TNN2", <?php echo $BacklogITTNN2; ?>], 
            <?php } if ($BacklogITTNS) { ?>
            ["TNS", <?php echo $BacklogITTNS; ?>],
            <?php } if ($BacklogITVIP) { ?>
            ["VIP", <?php echo $BacklogITVIP; ?>],
            <?php } ?>
            ]
        }]
    });
});
//Backlog
<?php

$sql="select count(a.ticket_id) as COUNT  from ost_ticket a 
WHERE 
a.status_id != '8' 
AND a.status_id != '9' 
AND a.status_id != '6' 
AND a.status_id != '3' 
AND a.status_id != '12' 
AND (a.topic_id = '13' 
OR a.topic_id = '15' 
OR a.topic_id = '16' 
OR a.topic_id = '17' 
OR a.topic_id = '18' 
OR a.topic_id = '19')";

 $seTotals = db_query($sql); 

$sql="select org.name as location, IFNULL(s.count,0) as COUNT from ost_organization org left join (
 select o.name ,count(a.ticket_id) as count  from ost_ticket a join ost_user u on a.user_id = u.id right join ost_organization o on u.org_id = o.id
WHERE 
a.status_id in (7)
AND a.topic_id  in (13,161,15,99,78,17,18,19,100,16,101,102)
 group by o.name ) s on org.name = s.name order by org.name";
 
  $SElocsdata = db_query($sql); 
 
 ?>
 
 
$(function() {

    Highcharts.chart('backlogS-chart-container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'SHOPEDGE BACKLOG (<?php echo $BacklogSETotal?>)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> <b> ({point.y})</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Backlog',
            data: [
			     <?php
        foreach ($SElocsdata as $SEloc) { ?>
				["<?php echo $SEloc["location"]?>", <?php echo $SEloc["COUNT"] ?>],
        <?php } ?>
           ]
        }]
    });
});		
//Tickets By Status
<?php
$sql="select s.name ,count(a.ticket_id) as COUNT  from ost_ticket a join ost_ticket_status s on a.status_id = s.id
WHERE 
a.status_id not in (1,3,12)
AND a.topic_id not in (14,163,94,93,12,92)
";

 $tTotals = db_query($sql); 
 
 ?>
$(function() {

    Highcharts.chart('ticketsbystatus-chart-container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'TICKETS (<?php foreach ($tTotals as $tTotal) {echo $tTotal['COUNT'];}?>) BY STATUS',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> <b> ({point.y})</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Backlog',
            data: [
            <?php if ($UnassignedTickets) { ?>
            ["Unassigned", <?php echo $UnassignedTickets; ?>],
            <?php } if ($HeldTickets) { ?>
            ["Held", <?php echo $HeldTickets; ?>],             
            <?php } if ($ReplyTickets) { ?>
            ["Agent Action", <?php echo $ReplyTickets; ?>],
            <?php } if ($TheirReplyTickets) { ?>
            ["Submitter Action", <?php echo $TheirReplyTickets; ?>],
            <?php } if ($ThridPartyTicketsTickets) { ?>
            ["3rd Party", <?php echo $ThridPartyTicketsTickets; ?>] 
             <?php } ?>
            ]
        }]
    });
});

//Tickets By Status


<?php

$sql="select s.name ,count(a.ticket_id) as COUNT  from ost_ticket a join ost_ticket_status s on a.status_id = s.id
WHERE 
a.status_id not in (1,3,12)
AND a.topic_id not in (163,94,93,12,92,13,14,161,15,99,78,17,18,19,100,16,101,102)
";

 $tTotals = db_query($sql); 

$sql="select s.name ,count(a.ticket_id) as COUNT  from ost_ticket a join ost_ticket_status s on a.status_id = s.id
WHERE 
a.status_id not in (1,3,12)
AND a.topic_id not in (163,94,93,12,92,13,14,161,15,99,78,17,18,19,100,16,101,102)
group by s.name";
 
  $tStatusesdata = db_query($sql); 
 
 ?>
 
 
$(function() {

    Highcharts.chart('ticketsbystatusT-chart-container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'SUPPORT TICKETS (<?php foreach ($tTotals as $tTotal) {echo $tTotal['COUNT'];}?>) BY STATUS',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> <b> ({point.y})</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Backlog',
            data: [
			     <?php
        foreach ($tStatusesdata as $tStatusdata) { ?>
				["<?php echo $tStatusdata["name"]?>", <?php echo $tStatusdata["COUNT"] ?>],
        <?php } ?>
           ]
        }]
    });
});
<?php

$sql="select s.name ,count(a.ticket_id) as COUNT  from ost_ticket a join ost_ticket_status s on a.status_id = s.id
WHERE 
a.status_id not in (3,12)
AND a.topic_id  in (13,161,15,99,78,17,18,19,100,16,101,102)
";

 $seTotals = db_query($sql); 

$sql="select s.name ,count(a.ticket_id) as COUNT  from ost_ticket a join ost_ticket_status s on a.status_id = s.id
WHERE 
a.status_id not in (3,12)
AND a.topic_id  in (13,161,15,99,78,17,18,19,100,16,101,102)
group by s.name";
 
  $SEStatusesdata = db_query($sql); 
 
 ?>
 
 
$(function() {

    Highcharts.chart('ticketsbystatusS-chart-container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'SHOPEDGE TICKETS (<?php foreach ($seTotals as $seTotal) {echo $seTotal['COUNT'];}?>) BY STATUS',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> <b> ({point.y})</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Backlog',
            data: [
			     <?php
        foreach ($SEStatusesdata as $SEStatusdata) { ?>
				["<?php echo $SEStatusdata["name"]?>", <?php echo $SEStatusdata["COUNT"] ?>],
        <?php } ?>
           ]
        }]
    });
});
    <?php
        $sql="	select distinct lastname, owner_name  from (            select sum(count) as COUNT, STATUS, OWNER_NAME,LASTNAME from
				(Select COUNT(Status) as Count, STATUS, OWNER_NAME, LASTNAME from
					(SELECT ost_ticket.number as Ticket, 
						ost_ticket_status.name as STATUS, 
						ost_ticket.Updated, 
						ost_staff.lastname as LASTNAME,
						CONCAT(ost_staff.lastname, ', ', ost_staff.firstname) as OWNER_NAME
						FROM (ost_ticket LEFT JOIN ost_ticket_status ON ost_ticket.status_id = ost_ticket_status.id)
						 LEFT JOIN ost_staff ON ost_ticket.staff_id = ost_staff.staff_id WHERE ost_ticket.topic_id != 12 and 
						 ost_ticket.topic_id != 14 and ost_ticket.status_id != 3 and ost_ticket.status_id != 12) A
				where lastname is not null Group by lastname,  Status, OWNER_NAME )b
            group by STATUS,LASTNAME) a ";
        $techs = db_query($sql); 
        
        $sql= "select distinct LOCATION from 
                (select sum(COUNT) as COUNT, STATUS, LOCATION from
                    (Select COUNT(STATUS) as COUNT,STATUS, LOCATION from
                        (SELECT ost_ticket.number as Ticket, ost_ticket_status.name as STATUS, ost_organization.name as LOCATION
                        FROM ((ost_ticket LEFT JOIN ost_ticket_status ON ost_ticket.status_id = ost_ticket_status.id)
                        LEFT JOIN (ost_user LEFT JOIN ost_organization ON ost_user.org_id = ost_organization.id) ON ost_ticket.user_id = ost_user.id)
                        LEFT JOIN ost_staff ON ost_ticket.staff_id = ost_staff.staff_id WHERE ost_ticket.topic_id != 12 and ost_ticket.topic_id != 14 and ost_ticket.status_id != 3 and ost_ticket.status_id != 12) A
                    where LOCATION is not null
                Group by LOCATION,  STATUS ) a
               group by LOCATION, STATUS) b ";
                
        $locs = db_query($sql); 
        
        $sql="SELECT distinct ost_ticket_status.name as STATUS 
			    FROM (ost_ticket LEFT JOIN ost_ticket_status ON ost_ticket.status_id = ost_ticket_status.id)
                WHERE ost_ticket.topic_id != 12 and ost_ticket.topic_id != 14 and ost_ticket.status_id != 3 and ost_ticket.status_id != 12  order by STATUS";
        
        $cstatuses = db_query($sql); 
        
        $sql="select sum(count) as COUNT, STATUS, OWNER_NAME from(
                select sum(count) as COUNT, STATUS, OWNER_NAME from
                                (Select COUNT(Status) as Count, STATUS, OWNER_NAME, LASTNAME from
                                    (SELECT ost_ticket.number as Ticket, 
                                        ost_ticket_status.name as STATUS, 
                                        ost_ticket.Updated, 
                                        ost_staff.lastname as LASTNAME,
                                        CONCAT(ost_staff.lastname, ', ', ost_staff.firstname) as OWNER_NAME
                                        FROM (ost_ticket LEFT JOIN ost_ticket_status ON ost_ticket.status_id = ost_ticket_status.id)
                                         LEFT JOIN ost_staff ON ost_ticket.staff_id = ost_staff.staff_id WHERE ost_ticket.topic_id != 12 and 
                                         ost_ticket.topic_id != 14 and ost_ticket.status_id != 3 and ost_ticket.status_id != 12) A
                                where lastname is not null Group by lastname,  Status, OWNER_NAME )b
                            group by STATUS,LASTNAME
                union all 
                select b.count as COUNT, a.name as STATUS, b.OWNER_NAME  from (select 0 as count, name  from ost_ticket_status where id != 3 and id != 12 and id != 1 and id != 2 and id != 4 and id != 5) a join 
                (select distinct CONCAT(ost_staff.lastname, ', ', ost_staff.firstname) as OWNER_NAME, 0 as count
                FROM (ost_ticket LEFT JOIN ost_ticket_status ON ost_ticket.status_id = ost_ticket_status.id)
                 LEFT JOIN ost_staff ON ost_ticket.staff_id = ost_staff.staff_id WHERE ost_ticket.topic_id != 12 and 
                 ost_ticket.topic_id != 14 and ost_ticket.status_id != 3 and ost_ticket.status_id != 12 and lastname is not null) b  on a.count = b.count)d   Group by Status, OWNER_NAME";
            
        $ctechsdata = db_query($sql);
        
        
         $sql="select distinct OWNER_NAME from (select sum(count) as COUNT, STATUS, OWNER_NAME,LASTNAME from
				(Select COUNT(Status) as Count, STATUS, OWNER_NAME, LASTNAME from
					(SELECT ost_ticket.number as Ticket, 
						ost_ticket_status.name as STATUS, 
						ost_ticket.Updated, 
						ost_staff.lastname as LASTNAME,
						CONCAT(ost_staff.lastname, ', ', ost_staff.firstname) as OWNER_NAME
						FROM (ost_ticket LEFT JOIN ost_ticket_status ON ost_ticket.status_id = ost_ticket_status.id)
						 LEFT JOIN ost_staff ON ost_ticket.staff_id = ost_staff.staff_id WHERE ost_ticket.topic_id != 12 and 
						 ost_ticket.topic_id != 14 and ost_ticket.status_id != 3 and ost_ticket.status_id != 12) A
				where lastname is not null Group by lastname,  Status, OWNER_NAME )b
            group by STATUS,LASTNAME order by lastname) OWNER";
        
        $ctechs = db_query($sql);
        
        $sql="select sum(count) as COUNT, STATUS, LOCATION from (select sum(COUNT) as COUNT, STATUS, LOCATION from
                (Select COUNT(STATUS) as COUNT,STATUS, LOCATION from
                    (SELECT ost_ticket.number as Ticket, ost_ticket_status.name as STATUS, ost_organization.name as LOCATION
                    FROM ((ost_ticket LEFT JOIN ost_ticket_status ON ost_ticket.status_id = ost_ticket_status.id)
                    LEFT JOIN (ost_user LEFT JOIN ost_organization ON ost_user.org_id = ost_organization.id) ON ost_ticket.user_id = ost_user.id)
                    LEFT JOIN ost_staff ON ost_ticket.staff_id = ost_staff.staff_id WHERE ost_ticket.topic_id != 12 and ost_ticket.topic_id != 14 and ost_ticket.status_id != 3 and ost_ticket.status_id != 12) A
                    where LOCATION is not null
                Group by LOCATION,  STATUS ) a
              group by LOCATION, STATUS
              
              union all 
                select b.count as COUNT, a.name as STATUS, b.LOCATION  from (select 0 as count, name  from ost_ticket_status where id != 3 and id != 12 and id != 1 and id != 2 and id != 4 and id != 5) a join 
                
                (SELECT  distinct ost_organization.name as LOCATION, 0 as count
                    FROM ((ost_ticket LEFT JOIN ost_ticket_status ON ost_ticket.status_id = ost_ticket_status.id)
                    LEFT JOIN (ost_user LEFT JOIN ost_organization ON ost_user.org_id = ost_organization.id) ON ost_ticket.user_id = ost_user.id)
                    LEFT JOIN ost_staff ON ost_ticket.staff_id = ost_staff.staff_id WHERE ost_ticket.topic_id != 12 and ost_ticket.topic_id != 14 and ost_ticket.status_id != 3 and ost_ticket.status_id != 12)b   
                    Group by Status, LOCATION)d where location is not null Group by Status, LOCATION";

$clocsdata = db_query($sql);
        
?>

//Status by tech

$(function () {
    
    
    Highcharts.chart('statusbyagent-chart-container1', {
        chart: {
            type: 'column'
         
        },
        title: {
            text: 'TICKETS (TECH BY STATUS)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        xAxis: {
            categories: [<?php
  foreach ($cstatuses as $cstatus) {
             
             echo "'".preg_replace('/\s+/', ' ', $cstatus["STATUS"])."',";
   }   
   ?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Tickets'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                   color: 'red'
                }
            }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
             shared: true
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: [
        
        <?php
        foreach ($ctechs as $ctech) { ?>
        
        {
            name: '<?php echo $ctech["OWNER_NAME"]?>',
            data: [<?php foreach ($ctechsdata as $techsdata) {

                if ($techsdata["OWNER_NAME"] == $ctech["OWNER_NAME"]) echo $techsdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]
    });

});

$(function () {
    
    
    Highcharts.chart('statusbyagent-chart-container2', {
        chart: {
            type: 'column'
            
        },
        title: {
            text: 'TICKETS (STATUS BY TECH)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        xAxis: {
            categories: [<?php
  foreach ($ctechs as $ctech) {
             
             echo "'".preg_replace('/\s+/', ' ', $ctech["OWNER_NAME"])."',";
   }   
   ?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Tickets'
            },
            stackLabels: {
                enabled: true,
                className: 'stackLabel'
            }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
             shared: true
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                     formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: [
        
        <?php
        foreach ($cstatuses as $cstatus) { ?>
        
        {
            name: '<?php echo $cstatus["STATUS"]?>',
            data: [<?php foreach ($ctechsdata as $techsdata) {

                if ($techsdata["STATUS"] == $cstatus["STATUS"]) echo $techsdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]
    });

});



//Status by Location
$(function () {
    
    
    Highcharts.chart('statusbylocation-chart-container1', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'TICKETS (LOCATION BY STATUS)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        xAxis: {
            categories: [<?php
  foreach ($cstatuses as $cstatus) {
             
             echo "'".preg_replace('/\s+/', ' ', $cstatus["STATUS"])."',";
   }   
   ?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Tickets'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                   color: 'red'
                }
            }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                     formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: [
        
         <?php
        foreach ($locs as $loc) { ?>
        
        {
            name: '<?php echo $loc["LOCATION"]?>',
            data: [<?php foreach ($clocsdata as $locsdata) {

                if ($locsdata["LOCATION"] == $loc["LOCATION"]) echo $locsdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]
    });

});

$(function () {
    
    
    Highcharts.chart('statusbylocation-chart-container2', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'TICKETS (STATUS BY LOCATION)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },
        credits: false,
        xAxis: {
            categories: [<?php
  foreach ($locs as $loc) {
             
             echo "'".preg_replace('/\s+/', ' ', $loc["LOCATION"])."',";
   }   
   ?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Tickets'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                   color: 'red'
                }
            }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                     formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 1) {
                        return '';
                    }
                    return val;
                },
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: [
        
         <?php
        foreach ($cstatuses as $cstatus) { ?>
        
        {
            name: '<?php echo $cstatus["STATUS"]?>',
            data: [<?php foreach ($clocsdata as $locsdata) {

                if ($locsdata["STATUS"] == $cstatus["STATUS"]) echo $locsdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]
    });

});


//location 2 year
<?php

$sql="select distinct LASTNAME,OWNER_NAME from
(
	select CALENDARWEEK,CALENDARYEAR, count(LASTNAME) as COUNT,OWNER_NAME, LASTNAME from
	(
	SELECT  month(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARYEAR, u.lastname as LASTNAME, 
    concat(u.lastname, ', ', u.firstname) AS OWNER_NAME, s.name as STATUS FROM ost_ticket t 
	left join ost_staff u on u.staff_id = t.staff_id 
	left join ost_ticket_status s on s.id = t.status_id
	where t.status_id = 3 AND t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by OWNER_NAME, CALENDARYEAR, CALENDARWEEK order by CALENDARYEAR,CALENDARWEEK
)b";

$locs = db_query($sql);

$sql="select CALENDARWEEK,CALENDARYEAR, count(LASTNAME) as COUNT,OWNER_NAME, LASTNAME from
	(
	SELECT  month(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARYEAR, u.lastname as LASTNAME, 
    concat(u.lastname, ', ', u.firstname) AS OWNER_NAME, s.name as STATUS FROM ost_ticket t 
	left join ost_staff u on u.staff_id = t.staff_id 
	left join ost_ticket_status s on s.id = t.status_id
	where t.status_id = 3 AND t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by OWNER_NAME, CALENDARYEAR, CALENDARWEEK order by CALENDARYEAR,CALENDARWEEK
";

$locsdata = db_query($sql);

$sql="select distinct cat from (select concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, count(OWNER_NAME) as COUNT,OWNER_NAME from
	(
	SELECT  month(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARYEAR, 
    concat(u.lastname, ', ', u.firstname) AS OWNER_NAME, s.name as STATUS FROM ost_ticket t 
	left join ost_staff u on u.staff_id = t.staff_id 
	left join ost_ticket_status s on s.id = t.status_id
	where t.status_id = 3 AND t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by OWNER_NAME, CALENDARYEAR, CALENDARWEEK order by CALENDARYEAR,CALENDARWEEK)a";
    
 $periods = db_query($sql);   

$sql="select * from (select cat,sum(COUNT) as COUNT, OWNER_NAME,CALENDARWEEK,CALENDARYEAR from (select concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, count(OWNER_NAME) as COUNT,OWNER_NAME, CALENDARWEEK,CALENDARYEAR from
	(
	SELECT  month(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARYEAR, 
    concat(u.lastname, ', ', u.firstname) AS OWNER_NAME, s.name as STATUS FROM ost_ticket t 
	left join ost_staff u on u.staff_id = t.staff_id 
	left join ost_ticket_status s on s.id = t.status_id
	where t.status_id = 3 AND t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by OWNER_NAME, CALENDARYEAR, CALENDARWEEK
    
    union all
    
    select distinct cat, 0 as COUNT, b.OWNER_NAME,CALENDARWEEK,CALENDARYEAR   from (select concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, count(OWNER_NAME) as COUNT,OWNER_NAME, CALENDARWEEK,CALENDARYEAR  from
	(
	SELECT  month(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARWEEK,YEAR(FROM_DAYS(TO_DAYS(t.closed) - MOD(TO_DAYS(t.closed) - 2, 7))) AS CALENDARYEAR,
    concat(u.lastname, ', ', u.firstname) AS OWNER_NAME, s.name as STATUS FROM ost_ticket t 
	left join ost_staff u on u.staff_id = t.staff_id 
	left join ost_ticket_status s on s.id = t.status_id
	where t.status_id = 3 AND t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by OWNER_NAME, CALENDARYEAR, CALENDARWEEK)a left join
    
    (SELECT distinct
    concat(u.lastname, ', ', u.firstname) AS OWNER_NAME FROM ost_ticket t 
	left join ost_staff u on u.staff_id = t.staff_id 
	left join ost_ticket_status s on s.id = t.status_id
	where t.status_id = 3 AND t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH))b on 1=1) dat
    
    group by  cat, OWNER_NAME) datb order by CALENDARYEAR, CALENDARWEEK, COUNT";

    $techsdata = db_query($sql);
    
    
    $sql="SELECT distinct
    concat(u.lastname, ', ', u.firstname) AS OWNER_NAME FROM ost_ticket t 
	left join ost_staff u on u.staff_id = t.staff_id 
	left join ost_ticket_status s on s.id = t.status_id
	where t.status_id = 3 AND t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH) order by concat(u.lastname, ', ', u.firstname)";

    $techs = db_query($sql);
    
    
?>

$(function () {
    Highcharts.chart('closedbytech-chart-container1', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'TICKETS CLOSED (TECH 1 YEARS)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },

        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: {
            title: {
                text: 'Number of Tickets'
                          },
            stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
               color: 'red'
            }
        }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
             shared: true
        },
      
        plotOptions: {
           column: {
                stacking: 'normal',
            dataLabels: {
                enabled: true,
                formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
           }
        },
        
               series: [
        
         <?php
        foreach ($techs as $tech) { ?>
        
        {
            name: '<?php echo $tech["OWNER_NAME"]?>',
            data: [<?php foreach ($techsdata as $techdata) {

                if ($techdata["OWNER_NAME"] == $tech["OWNER_NAME"]) echo $techdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]


    });
});      


//Opened by location 2 year
<?php

$sql="select distinct LOCATION from
(
	select CALENDARWEEK, CALENDARYEAR, count(LOCATION) as COUNT, LOCATION from
	(
	SELECT month(t.created) AS CALENDARWEEK,year(t.created) AS CALENDARYEAR, o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
	left join ost_user u on u.id = t.user_id 
	left join ost_organization o on o.id = u.org_id
	left join ost_ticket_status s on s.id = t.status_id
	where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and (t.created) > (CURDATE() - INTERVAL 11 MONTH)
	) a
	where LOCATION is not null
	group by LOCATION, CALENDARWEEK,CALENDARYEAR order by CALENDARYEAR,CALENDARWEEK, LOCATION
)b ";

$olocs = db_query($sql);

$sql="select * from (select cat,sum(COUNT) as COUNT, LOCATION,CALENDARWEEK,CALENDARYEAR from (
	select concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, count(STATUS) as COUNT,LOCATION, CALENDARWEEK,CALENDARYEAR from
	(
		SELECT  month(t.created) AS CALENDARWEEK,year(t.created) AS CALENDARYEAR, 
		o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
		left join ost_user u on u.id = t.user_id 
		left join ost_organization o on o.id = u.org_id
		left join ost_ticket_status s on s.id = t.status_id
		where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.created >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by LOCATION, CALENDARYEAR, CALENDARWEEK
    
    union all
    
    select distinct cat, 0 as COUNT, b.LOCATION,CALENDARWEEK,CALENDARYEAR   from (select concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, count(STATUS) as COUNT,LOCATION, CALENDARWEEK,CALENDARYEAR  from
	(
	SELECT  month(t.created) AS CALENDARWEEK,year(t.created) AS CALENDARYEAR,
    o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
    left join ost_user u on u.id = t.user_id 
	left join ost_organization o on o.id = u.org_id
	left join ost_ticket_status s on s.id = t.status_id
	where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.created >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by LOCATION, CALENDARYEAR, CALENDARWEEK)a left join
    
    (SELECT distinct
     o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
	left join ost_user u on u.id = t.user_id 
	left join ost_organization o on o.id = u.org_id
	left join ost_ticket_status s on s.id = t.status_id
	where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.created >(CURDATE() - INTERVAL 11 MONTH))b on 1=1) dat
    
    group by  cat, LOCATION) datb  Where LOCATION IS NOT NULL order by CALENDARYEAR, CALENDARWEEK
    
";

$olocsdata = db_query($sql);

$sql="select distinct concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat  from
	(
	SELECT month(t.created) AS CALENDARWEEK,year(t.created) AS CALENDARYEAR, o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
	left join ost_user u on u.id = t.user_id 
	left join ost_organization o on o.id = u.org_id
	left join ost_ticket_status s on s.id = t.status_id
	where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and (t.created) > (CURDATE() - INTERVAL 11 MONTH)
	) a
	where LOCATION is not null
	group by LOCATION, CALENDARWEEK,CALENDARYEAR order by CALENDARYEAR,CALENDARWEEK, LOCATION";
 
$periods = db_query($sql);   

?>


$(function () {
    Highcharts.chart('openedbylocation-chart-container1', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'TICKETS OPENED (LOCATION 1 YEARS)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },

        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: {
            title: {
                text: 'Number of Tickets'
                          },
            stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
               color: 'red'
            }
        }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
             shared: true
        },
        
        plotOptions: {
         column: {
                stacking: 'normal',
            dataLabels: {
                enabled: true,
                formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
        }
        },
               series: [
        
         <?php
        foreach ($olocs as $oloc) { ?>
        
        {
            name: '<?php echo $oloc["LOCATION"]?>',
            data: [<?php foreach ($olocsdata as $olocdata) {

                if ($olocdata["LOCATION"] == $oloc["LOCATION"]) echo $olocdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]


    });
});  

//closed by location 2 year

<?php

$sql="select distinct LOCATION from
(
	select CALENDARWEEK, CALENDARYEAR, count(LOCATION) as COUNT, LOCATION from
	(
	SELECT month(t.closed) AS CALENDARWEEK,year(t.closed) AS CALENDARYEAR, o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
	left join ost_user u on u.id = t.user_id 
	left join ost_organization o on o.id = u.org_id
	left join ost_ticket_status s on s.id = t.status_id
	where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and (t.closed) > (CURDATE() - INTERVAL 11 MONTH)
	) a
	where LOCATION is not null
	group by LOCATION, CALENDARWEEK,CALENDARYEAR order by CALENDARYEAR,CALENDARWEEK, LOCATION
)b ";

$olocs = db_query($sql);

$sql="select * from (select cat,sum(COUNT) as COUNT, LOCATION,CALENDARWEEK,CALENDARYEAR from (
	select concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, count(STATUS) as COUNT,LOCATION, CALENDARWEEK,CALENDARYEAR from
	(
		SELECT  month(t.closed) AS CALENDARWEEK,year(t.closed) AS CALENDARYEAR, 
		o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
		left join ost_user u on u.id = t.user_id 
		left join ost_organization o on o.id = u.org_id
		left join ost_ticket_status s on s.id = t.status_id
		where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by LOCATION, CALENDARYEAR, CALENDARWEEK
    
    union all
    
    select distinct cat, 0 as COUNT, b.LOCATION,CALENDARWEEK,CALENDARYEAR   from (select concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat, count(STATUS) as COUNT,LOCATION, CALENDARWEEK,CALENDARYEAR  from
	(
	SELECT  month(t.closed) AS CALENDARWEEK,year(t.closed) AS CALENDARYEAR,
    o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
    left join ost_user u on u.id = t.user_id 
	left join ost_organization o on o.id = u.org_id
	left join ost_ticket_status s on s.id = t.status_id
	where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH)
	) a
	group by LOCATION, CALENDARYEAR, CALENDARWEEK)a left join
    
    (SELECT distinct
     o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
	left join ost_user u on u.id = t.user_id 
	left join ost_organization o on o.id = u.org_id
	left join ost_ticket_status s on s.id = t.status_id
	where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and t.closed >(CURDATE() - INTERVAL 11 MONTH))b on 1=1) dat
    
    group by  cat, LOCATION) datb  Where LOCATION IS NOT NULL order by CALENDARYEAR, CALENDARWEEK
    
";

$olocsdata = db_query($sql);

$sql="select distinct concat(MONTHNAME(STR_TO_DATE(CALENDARWEEK, '%m')),' ',CALENDARYEAR) as cat  from
	(
	SELECT month(t.closed) AS CALENDARWEEK,year(t.closed) AS CALENDARYEAR, o.name AS LOCATION, s.name as STATUS FROM ost_ticket t 
	left join ost_user u on u.id = t.user_id 
	left join ost_organization o on o.id = u.org_id
	left join ost_ticket_status s on s.id = t.status_id
	where t.topic_id <> 14 AND t.topic_id <> 12 AND t.topic_id <> 94 and (t.closed) > (CURDATE() - INTERVAL 11 MONTH)
	) a
	where LOCATION is not null
	group by LOCATION, CALENDARWEEK,CALENDARYEAR order by CALENDARYEAR,CALENDARWEEK, LOCATION";
 
$periods = db_query($sql);   

?>


$(function () {
    Highcharts.chart('closedbylocation-chart-container1', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'TICKETS CLOSED (LOCATION 1 YEARS)',
            style: {
            color: '#797979',
            fontSize: '14px',
            fontWeight: '600',
            }
        },

        xAxis: {
            categories: [<?php
      foreach ($periods as $period) {
                 
                 echo "'".preg_replace('/\s+/', ' ', $period["cat"])."',";
       }   
       ?>]
        },
        yAxis: {
            title: {
                text: 'Number of Tickets'
            },
            stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
               color: 'red'
            }
        }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
             shared: true
        },
        
        plotOptions: {
         column: {
                stacking: 'normal',
            dataLabels: {
                enabled: true,
                formatter: function(){
                    console.log(this);
                    var val = this.y;
                    if (val < 2) {
                        return '';
                    }
                    return val;
                },
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
        }
        },
        
               series: [
        
         <?php
        foreach ($olocs as $oloc) { ?>
        
        {
            name: '<?php echo $oloc["LOCATION"]?>',
            data: [<?php foreach ($olocsdata as $olocdata) {

                if ($olocdata["LOCATION"] == $oloc["LOCATION"]) echo $olocdata["COUNT"].',';
            }?>]
        }, 
        
        <?php } ?>
        ]


    });
});  
       
    
$('#loading').hide();
            $.toggleOverlay(false);      
</script>
