<?php 

$AllTeams = $_GET['a'];

?>


<div class="subnav">

    <div class="float-left subnavtitle">
    
        <?php if ($AllTeams == 1) { ?>
            <?php echo __('AI Teams Dashboard');?>  - <span style="color: red; font-weight: bold;">All Teams</span> 
        <?php } else { ?>
            <?php echo __('AI Team Dashboard');?>  - <span style="color: red; font-weight: bold;"><?php echo $Dept; ?></span>  
        <?php } ?>
    
    </div>
    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
   &nbsp;
      </div>   
   <div class="clearfix"></div> 
</div> 
<script src="<?php echo ROOT_PATH; ?>scp/js/morris.min.js"></script>
<script src="<?php echo ROOT_PATH; ?>scp/js/raphael-min.js"></script>
<?php if ($AllTeams == 1) { ?>

<div class="card-box">
<div>
<div class="row">
    <div class="col-lg-3">
        <div class="card-box">
            <h4 class="text-dark  header-title m-t-0 m-b-10">Suggestions By Status</h4>
        
            <div class="widget-chart text-center">
                <div id="AllSuggestionsByStatus" style="height: 275px;"></div>
        
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card-box">
            <h4 class="text-dark  header-title m-t-0 m-b-10">YTD Closed</h4>
        
            <div class="widget-chart text-center">
                <div id="AllYTDClosed" style="height: 275px;"></div>
        
            </div>
        </div>
    </div>

</div></div>

<script>

Morris.Bar({
  element: 'AllSuggestionsByStatus',
  data: [
    { y: 'Submitted', a: <?php echo $AllSubmittedSuggestions; ?>},
    { y: 'Active', a: <?php echo $AllActiveSuggestions; ?>},
    { y: 'Parking Lot', a: <?php echo $AllParkedSuggestions; ?>},
    ],
  xkey: 'y',
  ykeys: ['a'],
  labels: [''],
  hideHover: 'auto',
  barRatio: 0.4,
   resize: true,
  xLabelAngle: 60,
  barColors: function (row, series, type) {
    
       switch (row.x){
            case 0:
                return "#f1b53d";
                break;
            case 1:
                return "lightgreen";
                break;
            case 2:
                return "#ff9999";
                break;
            case 3:
                return "#7266ba";
                break;
            case 4:
                return "#E67E22";
                break;
            case 5:
               return "#9B59B6";
                break;
            case 6:
                return "#546E7A";
                break;
            case 7:
                return "#1ABC9C";
                break;
            case 8:
                return "#C0392B";
                break;
            case 9:
                return "#27AE60";
                break;        
        }
    }
  }).on('click', function(i){
   
  switch (i) {
    case 0:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=1";
        break;
    case 1:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=8";
        break;
    case 2:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=7";
        break;
    
  }    
});



Morris.Bar({
  element: 'AllYTDClosed',
  data: [
    { y: 'Implemented', a: <?php echo $AllYearToDateImplemented; ?>},
    { y: 'Not Implmented', a: <?php echo $AllYearToDateNotImplemented; ?>},
   
    ],
  xkey: 'y',
  ykeys: ['a'],
  labels: [''],
  hideHover: 'auto',
  barRatio: 0.4,
   resize: true,
  xLabelAngle: 60,
  barColors: function (row, series, type) {
    
       switch (row.x){
            case 0:
                return "lightgreen";
                break;
            case 1:
                return "#ff9999";
                break;
            case 2:
                return "#ff9999";
                break;
            case 3:
                return "#7266ba";
                break;
            case 4:
                return "#E67E22";
                break;
            case 5:
               return "#9B59B6";
                break;
            case 6:
                return "#546E7A";
                break;
            case 7:
                return "#1ABC9C";
                break;
            case 8:
                return "#C0392B";
                break;
            case 9:
                return "#27AE60";
                break;        
        }
    }
  }).on('click', function(i){
   
  switch (i) {
    case 0:
        window.location.href = "tickets.php?queue=89&l=0&t=0&s=3";
        break;
    case 1:
        window.location.href = "tickets.php?queue=89&l=0&t=0&s=6";
        break;
   
  }    
});


$('svg').height(700);
</script>

<?php } else { 

//*************************************************** My Team ***************************************************?>


<div class="card-box">
<div>
<table class="table table-sm table-responsive">
    <tr>
        <td>
            <table class="table table-sm table-responsive">
            <tr>
                <td width="200"><span style="color: red; font-weight: bold;">Members: </span></td>
                <td width="200"><?php echo $MemberCount ?></td>
            </tr>
            <tr>
                <td><span style="color: red; font-weight: bold;">Mentor: </span> </td>
                <td><?php echo $Manager ?> </td>
            </tr>
            <tr>
                <td><span style="color: red; font-weight: bold;">Team Leader: </span> </td>
                <td><?php echo $Teamleader ?></td>
            </tr>


            </table>
        </td>

        <td>
            <table class="table table-sm table-responsive">
                <tr >
                    <td rowspan="<?php echo $MemberCount/6;?>"><span style="color: red; font-weight: bold;">Members: </span> </td>
          
                       <?php
                    $DeptMembers = Staff::objects()
                            ->filter(array('dept_id' => $thisstaff->dept_id))
                            ->filter(array('isactive' => '1'));
                           
                            $count=0;
                            foreach ($DeptMembers as $row){
                                if ($count==6){$count=0; echo '</tr><tr>';}
                                
                                $name = $row->lastname.', '.$row->firstname;
                                if ($name != $Manager && $name != $Teamleader) {
                                ?>
                                <td ><?php echo $name;?></td>
                                <?php
                                $count++;
                                
                                }
                            }

                                    
                       ?>
                    </tr>
            </table> 
        </td>
    </tr>
</table>
       
</div>
</div>   



<div class="row">
                            <div class="col-lg-6 col-xl-3">
                                <div class="widget-bg-color-icon card-box">
                                    <div class="bg-icon bg-icon-danger pull-left">
                                        <i class="mdi mdi-ticket-confirmation text-danger"></i>
                                    </div>
                                    <div class="text-right">
                                       <a href="tickets.php?queue=241&p=1"><h3 class="text-dark"><b class="counter"><?php echo $OwnedSuggestions;?></b></h3></a>
                                        <p class="text-muted mb-0">Open</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-xl-3">
                                <div class="widget-bg-color-icon card-box">
                                    <div class="bg-icon bg-icon-primary pull-left">
                                        <i class="mdi mdi-ticket-account text-success"></i>
                                    </div>
                                    <div class="text-right">
                                        <a href="tickets.php?queue=3&p=1"><h3 class="text-dark"><b class="counter"><?php echo $AssignedSuggestions;?></b></h3></a>
                                        <p class="text-muted mb-0">Assigned</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-3">
                            <div class="widget-bg-color-icon card-box">
                                <div class="bg-icon bg-icon-primary pull-left">
                                    <i class="ti-info-alt text-success"></i>
                                </div>
                                <div class="text-right">
                                    <a href="tickets.php?queue=14&p=1"><h3 class="text-dark"><b class="counter"><?php echo $YTDImplemented+$YTDNotImplemented ;?></b></h3></a>
                                    <p class="text-muted mb-0">YTD Closed</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            </div>
                                <div class="col-lg-6 col-xl-3">
                            <div class="widget-bg-color-icon card-box">
                                <div class="bg-icon bg-icon-primary pull-left">
                                    <i class="ti-info-alt text-success"></i>
                                </div>
                                <div class="text-right">
                                    <a href="tickets.php?queue=14&p=1"><h3 class="text-dark"><b class="counter"><?php echo round($SugTargetPerMonth/$MemberCount,1) ;?></b></h3></a>
                                    <p class="text-muted mb-0">Suggestion Target (Month/Associate)</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card-box">
            <h4 class="text-dark  header-title m-t-0 m-b-10">Owned Suggestions By Status</h4>
        
            <div class="widget-chart text-center">
                <div id="OwnedSuggestionsByStatus" style="height: 275px;"></div>
        
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card-box">
            <h4 class="text-dark  header-title m-t-0 m-b-10">Assigned Suggestions By Status</h4>
        
            <div class="widget-chart text-center">
                <div id="AssignedSuggestionsByStatus" style="height: 275px;"></div>
        
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card-box">
            <h4 class="text-dark  header-title m-t-0 m-b-10">YTD Closed</h4>
        
            <div class="widget-chart text-center">
                <div id="YTDClosed" style="height: 275px;"></div>
        
            </div>
        </div>
    </div>

                             <div class="col-lg-6 col-xl-3">
                            <div class="widget-bg-color-icon card-box">
                                <div class="bg-icon bg-icon-primary pull-left">
                                    <i class="ti-info-alt text-success"></i>
                                </div>
                                <div class="text-right">
                                    <a href="tickets.php?queue=14&p=1"><h3 class="text-dark"><b class="counter"><?php echo round($ImpTargetPerMonth/$MemberCount,1) ;?></b></h3></a>
                                    <p class="text-muted mb-0">Implementation Target (Month/Associate)</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            </div>
</div>
<div class="row">
<div class="col-lg-12">
   <div class="card-box">     
         <table class="table table-sm table-responsive">
    
        <tr style="font-weight: bold;">
            <td width="125px">Month</td>
            <td width="100px">Suggestions</td> 
            <td width="90px">Target</td>
            <td width="100px">Ahead/Behind</td>
            <td width="100px">Goal</td>
            <td width="100px">Implementations</td>
            <td width="90px">Target</td>
            <td width="100px">Ahead/Behind</td>
            <td width="100px">Goal</td>
            <td></td>
            
        </tr>
        <!--<tr>
            <td style="font-weight: bold;">Last Month</td>
            <td><?php echo $PMSubmitted;?></td>
            <td><?php echo $SugPreviousMonthTarget;?></td>
            <td style="background-color:<?php echo $PMSugAheadBehindColor ?>"><?php echo $PMSugAheadBehind;?></td>
            <td><?php echo $PMSugGoal;?></td>
            <td><?php echo $PMImplemented;?></td>
            <td><?php echo $ImpPreviousMonthTarget;?></td>
            <td style="background-color:<?php echo $PMImpAheadBehindColor ?>"><?php echo $PMImpAheadBehind;?></td>
            <td><?php echo $PMImpGoal;?></td>
            <td></td>
        <tr>
            <td style="font-weight: bold;">Month to Date</td>
            <td><?php echo $CMSubmitted;?></td>
            <td><?php echo $SugCurrentMonthTarget;?></td>
            <td style="background-color:<?php echo $CMSugAheadBehindColor ?>"><?php echo $CMSugAheadBehind;?></td>
            <td><?php echo $CMSugGoal;?></td>
            <td><?php echo $CMImplemented;?></td>
            <td><?php echo $ImpCurrentMonthTarget;?></td>
            <td style="background-color:<?php echo $CMImpAheadBehindColor ?>"><?php echo $CMImpAheadBehind;?></td>
            <td><?php echo $CMImpGoal;?></td>
            <td></td>
        </tr>-->
        <tr>
            <td style="font-weight: bold;">Year To Date</td>
            <td><?php echo $YTDSubmitted;?></td>
            <td><?php echo $SugYearTarget;?></td>
            <td style="background-color:<?php echo $YTDSugAheadBehindColor ?>"><?php echo $YTDSugAheadBehind;?></td>
            <td><?php echo $YTDSugGoal;?></td>
            <td><?php echo $YTDImplemented;?></td>
            <td><?php echo $ImpYearTarget;?></td>
            <td style="background-color:<?php echo $YTDImpAheadBehindColor ?>"><?php echo $YTDImpAheadBehind;?></td>
            <td><?php echo $YTDImpGoal;?></td>
            <td></td>
        </tr>
        
  
</table>
        
       </div>                       
    </div>                     
</div>
    
    
    
<script>

Morris.Bar({
  element: 'OwnedSuggestionsByStatus',
  data: [
    { y: 'Submitted', a: <?php echo $SubmittedSuggestions; ?>},
    { y: 'Active', a: <?php echo $ActiveSuggestions; ?>},
    { y: 'Parking Lot', a: <?php echo $ParkedSuggestions; ?>},
    ],
  xkey: 'y',
  ykeys: ['a'],
  labels: [''],
  hideHover: 'auto',
  barRatio: 0.4,
   resize: true,
  xLabelAngle: 60,
  barColors: function (row, series, type) {
    
       switch (row.x){
            case 0:
                return "#f1b53d";
                break;
            case 1:
                return "lightgreen";
                break;
            case 2:
                return "#ff9999";
                break;
            case 3:
                return "#7266ba";
                break;
            case 4:
                return "#E67E22";
                break;
            case 5:
               return "#9B59B6";
                break;
            case 6:
                return "#546E7A";
                break;
            case 7:
                return "#1ABC9C";
                break;
            case 8:
                return "#C0392B";
                break;
            case 9:
                return "#27AE60";
                break;        
        }
    }
  }).on('click', function(i){
   
  switch (i) {
    case 0:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=1";
        break;
    case 1:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=8";
        break;
    case 2:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=7";
        break;
    
  }    
});


Morris.Bar({
  element: 'AssignedSuggestionsByStatus',
  data: [
    { y: 'Submitted', a: <?php echo $AssignedSubmittedSuggestions; ?>},
    { y: 'Active', a: <?php echo $AssignedActiveSuggestions; ?>},
    { y: 'Parking Lot', a: <?php echo $AssignedParkedSuggestions; ?>},
    ],
  xkey: 'y',
  ykeys: ['a'],
  labels: [''],
  hideHover: 'auto',
  barRatio: 0.4,
   resize: true,
  xLabelAngle: 60,
  barColors: function (row, series, type) {
    
       switch (row.x){
            case 0:
                return "#f1b53d";
                break;
            case 1:
                return "lightgreen";
                break;
            case 2:
                return "#ff9999";
                break;
            case 3:
                return "#7266ba";
                break;
            case 4:
                return "#E67E22";
                break;
            case 5:
               return "#9B59B6";
                break;
            case 6:
                return "#546E7A";
                break;
            case 7:
                return "#1ABC9C";
                break;
            case 8:
                return "#C0392B";
                break;
            case 9:
                return "#27AE60";
                break;        
        }
    }
  }).on('click', function(i){
   
  switch (i) {
    case 0:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=1";
        break;
    case 1:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=8";
        break;
    case 2:
        window.location.href = "tickets.php?queue=88&p=1&l=0&t=0&s=7";
        break;
    
  }    
});
Morris.Bar({
  element: 'YTDClosed',
  data: [
    { y: 'Implemented', a: <?php echo $YTDImplemented; ?>},
    { y: 'Not Implmented', a: <?php echo $YTDNotImplemented; ?>},
   
    ],
  xkey: 'y',
  ykeys: ['a'],
  labels: [''],
  hideHover: 'auto',
  barRatio: 0.4,
   resize: true,
  xLabelAngle: 60,
  barColors: function (row, series, type) {
    
       switch (row.x){
            case 0:
                return "lightgreen";
                break;
            case 1:
                return "#ff9999";
                break;
            case 2:
                return "#ff9999";
                break;
            case 3:
                return "#7266ba";
                break;
            case 4:
                return "#E67E22";
                break;
            case 5:
               return "#9B59B6";
                break;
            case 6:
                return "#546E7A";
                break;
            case 7:
                return "#1ABC9C";
                break;
            case 8:
                return "#C0392B";
                break;
            case 9:
                return "#27AE60";
                break;        
        }
    }
  }).on('click', function(i){
   
  switch (i) {
    case 0:
        window.location.href = "tickets.php?queue=89&l=0&t=0&s=3";
        break;
    case 1:
        window.location.href = "tickets.php?queue=89&l=0&t=0&s=6";
        break;
   
  }    
});


$('svg').height(700);
</script>
<?php } ?>


