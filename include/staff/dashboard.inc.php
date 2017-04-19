<?php

//Team Members Count
$DeptMembers = Staff::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('isactive' => '1'))
        ->aggregate(array('count' => SqlAggregate::COUNT('staff_id')));
 
        foreach ($DeptMembers as $row)
                $DeptMembers  = $row;

//Department Information
$Dept= Dept::objects()
        ->filter(array('id' => $thisstaff->dept_id));
        foreach ($Dept as $row)
                $Dept  = $row;
    
$Manager = $Dept->manager;
$Teamleader = $Dept->teamleader;

//Submitted Suggestions
$SubmittedSuggestions = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '1'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($SubmittedSuggestions as $row)
                $SubmittedSuggestions  = $row;
                
//Submitted Suggestions
$AssignedSubmittedSuggestions = Ticket::objects()
        ->filter(array('team_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '1'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($AssignedSubmittedSuggestions as $row)
                $AssignedSubmittedSuggestions  = $row;                
                
//Active Suggestions
$ActiveSuggestions = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '8'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($ActiveSuggestions as $row)
                $ActiveSuggestions  = $row;
                
//Active Suggestions
$AssignedActiveSuggestions = Ticket::objects()
        ->filter(array('team_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '8'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($AssignedActiveSuggestions as $row)
                $AssignedActiveSuggestions  = $row;
                
//Parking Lot Suggestions
$ParkedSuggestions = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '7'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($ParkedSuggestions as $row)
                $ParkedSuggestions  = $row;
                
//Parking Lot Suggestions
$AssignedParkedSuggestions = Ticket::objects()
        ->filter(array('team_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '7'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($AssignedParkedSuggestions as $row)
                $AssignedParkedSuggestions  = $row;

//Open Owned Suggestions
$OwnedSuggestions = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id__ne' => '3'))
        ->filter(array('status_id__ne' => '6'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($OwnedSuggestions as $row)
                $OwnedSuggestions  = $row;
                
//Open Assigned Suggestions
$AssignedSuggestions = Ticket::objects()
        ->filter(array('team_id' => $thisstaff->dept_id))
        ->filter(array('status_id__ne' => '3'))
        ->filter(array('status_id__ne' => '6'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($AssignedSuggestions as $row)
                $AssignedSuggestions  = $row;
                
//Implmented
$ImplmentedSuggestions = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '3'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($ImplmentedSuggestions as $row)
                $ImplmentedSuggestions  = $row;

//Not Implmented
$NotImplmentedSuggestions = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '6'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($NotImplmentedSuggestions as $row)
                $NotImplmentedSuggestions  = $row;

//Previous Month Submitted
$PMonthSubmitted= Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('created__year' => date("Y")))
        ->filter(array('created__month' => date("m")-1))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
                 
        foreach ($PMonthSubmitted as $row)
                $PMonthSubmitted = $row;

                //Current Month Submitted
$CMonthSubmitted= Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('created__year' => date("Y")))
        ->filter(array('created__month' => date("m")))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
            
        foreach ($CMonthSubmitted as $row)
                $CMonthSubmitted = $row;

//YTD Submitted
$YearToDateSubmitted = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('created__year' => date("Y")))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
               
        foreach ($YearToDateSubmitted as $row)
                $YearToDateSubmitted = $row;

//Previous Month Implemented
$PMonthImplemented= Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '3'))
        ->filter(array('closed__year' => date("Y")))
        ->filter(array('closed__month' => date("m")-1))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
           
        foreach ($PMonthImplemented as $row)
                $PMonthImplemented = $row;

//Current Month Implemented
$CMonthImplemented= Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '3'))
        ->filter(array('closed__year' => date("Y")))
        ->filter(array('closed__month' => date("m")))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
               
        foreach ($CMonthImplemented as $row)
                $CMonthImplemented = $row;
                
//YTD Implemented
$YearToDateImplemented = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '3'))
        ->filter(array('closed__year' => date("Y")))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
               
        foreach ($YearToDateImplemented as $row)
                $YearToDateImplemented = $row;

//Open Tasks Count
$tasks = Task::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('flags' => '1'))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('id')));
                   
        foreach ($tasks as $row)
                $OpenTasks = $row;

                


$MemberCount = $DeptMembers["count"];
$OwnedSuggestions = $OwnedSuggestions["count"];
$AssignedSuggestions = $AssignedSuggestions["count"];
$SubmittedSuggestions = $SubmittedSuggestions["count"];
$AssignedSubmittedSuggestions = $AssignedSubmittedSuggestions["count"];
$ActiveSuggestions = $ActiveSuggestions["count"];
$AssignedActiveSuggestions = $AssignedActiveSuggestions["count"];
$ParkedSuggestions = $ParkedSuggestions["count"];
$AssignedParkedSuggestions = $AssignedParkedSuggestions["count"];
$ImplmentedSuggestions = $ImplmentedSuggestions["count"];
$NotImplmentedSuggestions = $NotImplmentedSuggestions["count"];

$PMSubmitted = (int)$PMonthSubmitted["count"];
$PMTargetSuggestions =  round(($MemberCount * 17) /12 * (int) date('m', strtotime(date('Y-m')." -1 month")));
$PMSugAheadBehind = $PMSubmitted - $PMTargetSuggestions;
$PMSugAheadBehindColor = ($PMSugAheadBehind > -1 ? 'lightgreen':'#ff9999');
$PMSugGoal = number_format($PMSubmitted / $PMTargetSuggestions * 100,2).'%';
            
$PMImplemented = (int)$PMonthImplemented["count"];
$PMTargetImplemented = $MemberCount * 12/12 * (int) date('m', strtotime(date('Y-m')." -1 month"));
$PMImpAheadBehind = $PMImplemented - $PMTargetImplemented;
$PMImpAheadBehindColor = ($PMImpAheadBehind > -1 ? 'lightgreen':'#ff9999');
$PMImpGoal = number_format($PMImplemented / $PMTargetImplemented * 100,2).'%';
            
$CMSubmitted = (int)$CMonthSubmitted["count"];
$CMTargetSuggestions =  round(($MemberCount * 17) /12 * (int) date('m', strtotime(date('Y-m'))));
$CMSugAheadBehind = $CMSubmitted - $CMTargetSuggestions;
$CMSugAheadBehindColor = ($CMSugAheadBehind > -1 ? 'lightgreen':'#ff9999');
$CMSugGoal = number_format($CMSubmitted / $CMTargetSuggestions * 100,2).'%';
            
$CMImplemented = (int)$CMonthImplemented["count"];
$CMTargetImplemented = $MemberCount * 12/12 * (int) date('m', strtotime(date('Y-m')));
$CMImpAheadBehind = $CMImplemented - $CMTargetImplemented;
$CMImpAheadBehindColor = ($CMImpAheadBehind > -1 ? 'lightgreen':'#ff9999');
$CMImpGoal = number_format($CMImplemented / $CMTargetImplemented * 100,2).'%';

$YTDSubmitted = (int)$YearToDateSubmitted["count"];
$YTDTargetSuggestions =  round(($MemberCount * 17));
$YTDSugAheadBehind = $YTDSubmitted - $YTDTargetSuggestions;
$YTDSugAheadBehindColor = ($YTDSugAheadBehind > -1 ? 'lightgreen':'#ff9999');
$YTDSugGoal = number_format($YTDSubmitted / $YTDTargetSuggestions * 100,2).'%';

$YTDImplemented = (int)$YearToDateImplemented["count"];
$YTDTargetImplemented = $MemberCount * 12;
$YTDImpAheadBehind = $YTDImplemented - $YTDTargetImplemented;
$YTDImpAheadBehindColor = ($YTDImpAheadBehind > -1 ? 'lightgreen':'#ff9999');
$YTDImpGoal = number_format($YTDImplemented / $YTDTargetImplemented * 100,2).'%';

$OpenTasks = ($OpenTasks["count"]) ? $OpenTasks["count"] : 0

?>

<style>
#dashboard .ui-icon{
    background-image: url("images/ui-icons_777777_256x240.png");
}

</style>

<script>
  $( function() {
    var icons = {
         header: "ui-icon-plus",    // custom icon class
         activeHeader: "ui-icon-minus" // custom icon class
     };
    $("#dashboard").show();
    $( "#dashboard" ).accordion({
       active: false,
       autoHeight: true,
       navigation: true,
       collapsible: true,
       icons: icons
       
    });

  } );
  </script>


<div id="dashboard" style="display:none">
<h3>AI Team Dashboard</h3>
    <table width="100%" style="font-size: smaller">
    
        <tr style="font-weight: bold;">
            <td colspan="2"><h2> <span style="color: red; font-weight: bold;"><?php echo $Dept; ?></span> Members: (<span style="color: red; font-weight: bold;"><?php echo $MemberCount ?></span>) </h2></td>
            <td colspan = "4"><h2>Suggestions</h2></td>
            <td colspan = "1"><h2>Tasks</h2></td>
            <td colspan = "2"><h2>Targets <small>(1 associate)</small></h2></td>               
        </tr>

<tr><td colspan = "2"><table>
   
   <tr>
        <td><span style="color: red; font-weight: bold;">Mentor: </span> <?php echo $Manager ?></td>
   </tr>
    <tr>
        <td><span style="color: red; font-weight: bold;">Team Leader: </span> <?php echo $Teamleader ?></td>
   </tr>

           <tr >
                <td><a href="https://suggestions.nasg.net/scp/directory.php?q=&did=<?php echo $thisstaff->dept_id; ?>&submit=Filter"  style="color: red; font-weight: bold;"> Click to view members</a></td>
           </tr>

   </table>
    </td>
    <td colspan = "4"  valign="top">
        <table>
            <tr style="font-weight: bold;">
                <td rowspan="2" width="50px" style="color: red;"  align="middle"> Owned<br>Open<br>(<?php echo $OwnedSuggestions ?>)</td>
                <td width="80px" valign="top">Submitted</td>
                <td width="50px" valign="top">Active</td>
                <td width="70px" valign="top">Parking Lot</td>
                <td rowspan="2" width="50px" style="color: green;">Closed</td>
                <td width="80px" valign="top">Implemented</td>
                              
            </tr>        
            <tr  valign="top">
                
                <td valign="top"><?php echo $SubmittedSuggestions ?></td>
                <td><?php echo $ActiveSuggestions ?></td>
                <td><?php echo $ParkedSuggestions ?></td>
                <td><?php echo $ImplmentedSuggestions ?></td>
                
            </tr>
            
            <tr style="font-weight: bold;">
            
                <td rowspan="2" width="50px" style="color: red;" align="middle">Assigned<br>Open<br>(<?php echo $AssignedSuggestions?>)</td>
                <td width="80px" valign="top">Submitted</td>
                <td width="50px" valign="top">Active</td>
                <td width="70px" valign="top">Parking Lot</td>
                <td rowspan="2" width="50px" style="color: green;">&nbsp;</td>
                <td width="80px" valign="top" style="text-decoration: line-through;">Implemented</td>
                
                
            </tr>        
            <tr>
                <td><?php echo $AssignedSubmittedSuggestions ?></td>
                <td><?php echo $AssignedActiveSuggestions ?></td>
                <td><?php echo $AssignedParkedSuggestions ?></td>
                <td><?php echo $NotImplmentedSuggestions ?></td>
                
            </tr>
        </table>
        
        <td colspan = "1"  valign="top">    
            <table>
            <tr>
                <td style="font-weight: bold;" width = "100px">Open Tasks</td>
            </tr>
            <tr>
                <td><?php echo $OpenTasks ?></td>
            </tr>
            
            </table>
    </td>  
    <td colspan = "2"  valign="top">    
        <table>
        <tr><td style="font-weight: bold; color: red;" width = "30px">1.4</td><td style="font-weight: bold;">Suggestions per month</td></tr>
        <tr><td style="font-weight: bold; color: red;" width = "30px">16.8</td><td style="font-weight: bold;">Suggestions per year</td></tr>
        <tr><td style="font-weight: bold; color: red;" width = "30px">1</td><td style="font-weight: bold;">Implmentations per month</td></tr>
        <tr><td style="font-weight: bold; color: red;" width = "30px">12</td><td style="font-weight: bold;">Implmentations per year</td></tr>
        </table>
    </td>    
    </tr>
        <tr><td>	&nbsp;</td></tr>
        <tr style="font-weight: bold;">
        <td colspan="9">
        <h2>Statistics:</h2></td>
        </tr>
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
        <tr>
            <td style="font-weight: bold;">Last Month</td>
            <td><?php echo $PMSubmitted;?></td>
            <td><?php echo $PMTargetSuggestions;?></td>
            <td style="background-color:<?php echo $PMSugAheadBehindColor ?>"><?php echo $PMSugAheadBehind;?></td>
            <td><?php echo $PMSugGoal;?></td>
            <td><?php echo $PMImplemented;?></td>
            <td><?php echo $PMTargetImplemented;?></td>
            <td style="background-color:<?php echo $PMImpAheadBehindColor ?>"><?php echo $PMImpAheadBehind;?></td>
            <td><?php echo $PMImpGoal;?></td>
            <td></td>
        <tr>
            <td style="font-weight: bold;">Month to Date</td>
            <td><?php echo $CMSubmitted;?></td>
            <td><?php echo $CMTargetSuggestions;?></td>
            <td style="background-color:<?php echo $CMSugAheadBehindColor ?>"><?php echo $CMSugAheadBehind;?></td>
            <td><?php echo $CMSugGoal;?></td>
            <td><?php echo $CMImplemented;?></td>
            <td><?php echo $CMTargetImplemented;?></td>
            <td style="background-color:<?php echo $CMImpAheadBehindColor ?>"><?php echo $CMImpAheadBehind;?></td>
            <td><?php echo $CMImpGoal;?></td>
            <td></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Year To Date</td>
            <td><?php echo $YTDSubmitted;?></td>
            <td><?php echo $YTDTargetSuggestions;?></td>
            <td style="background-color:<?php echo $YTDSugAheadBehindColor ?>"><?php echo $YTDSugAheadBehind;?></td>
            <td><?php echo $YTDSugGoal;?></td>
            <td><?php echo $YTDImplemented;?></td>
            <td><?php echo $YTDTargetImplemented;?></td>
            <td style="background-color:<?php echo $YTDImpAheadBehindColor ?>"><?php echo $YTDImpAheadBehind;?></td>
            <td><?php echo $YTDImpGoal;?></td>
            <td></td>
        </tr>
        <tr>
        <td>
        </td>
        </tr>
  
</table>
</div>