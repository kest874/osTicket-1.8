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
//AllSubmitted Suggestions
$AllSubmittedSuggestions = Ticket::objects()
        ->filter(array('status_id' => '1'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($AllSubmittedSuggestions as $row)
                $AllSubmittedSuggestions  = $row;               
 $AllSubmittedSuggestions = $AllSubmittedSuggestions["count"];
 
//Assigned Suggestions
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
//All Active Suggestions
$AllActiveSuggestions = Ticket::objects()
        
        ->filter(array('status_id' => '8'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($AllActiveSuggestions as $row)
                $AllActiveSuggestions  = $row;               
$AllActiveSuggestions = $AllActiveSuggestions["count"];              
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
//All Parking Lot Suggestions
$AllParkedSuggestions = Ticket::objects()
        ->filter(array('status_id' => '7'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($AllParkedSuggestions as $row)
                $AllParkedSuggestions  = $row;
$AllParkedSuggestions =  $AllParkedSuggestions["count"];
                
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
                
//Implemented
$ImplmentedSuggestions = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '3'))
        ->aggregate(array('count' => SqlAggregate::COUNT('ticket_id')));
 
        foreach ($ImplmentedSuggestions as $row)
                $ImplmentedSuggestions  = $row;

//Not Implemented
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

//All YTD Implemented
$AllYearToDateImplemented = Ticket::objects()
        ->filter(array('status_id' => '3'))
        ->filter(array('closed__year' => date("Y")))
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
               
        foreach ($AllYearToDateImplemented as $row)
                $AllYearToDateImplemented = $row;
$AllYearToDateImplemented = $AllYearToDateImplemented["count"];    

//All YTD Not Implemented
$AllYearToDateNotImplemented = Ticket::objects()
        ->filter(array('status_id' => '3'))
        ->filter(array('closed__year' => date("Y")))
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
               
        foreach ($AllYearToDateNotImplemented as $row)
                $AllYearToDateNotImplemented = $row;
//$AllYearToDateNotImplemented = $AllYearToDateNotImplemented["count"];      
            
//YTD Implemented
$YearToDateNotImplemented = Ticket::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('status_id' => '6'))
        ->filter(array('closed__year' => date("Y")))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('number')));
               
        foreach ($YearToDateNotImplemented as $row)
                $YearToDateNotImplemented = $row;
//Open Tasks Count
$tasks = Task::objects()
        ->filter(array('dept_id' => $thisstaff->dept_id))
        ->filter(array('flags' => '1'))
        ->values_flat('dept_id')
        ->aggregate(array('count' => SqlAggregate::COUNT('id')));
                   
        foreach ($tasks as $row)
                $OpenTasks = $row;

 $OpenTasks = ($OpenTasks["count"]) ? $OpenTasks["count"] : 0;               


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

            
$PMImplemented = (int)$PMonthImplemented["count"];
           
$CMSubmitted = (int)$CMonthSubmitted["count"];
           
$CMImplemented = (int)$CMonthImplemented["count"];

$YTDNotImplemented = (int)$YearToDateNotImplemented["count"];


$SugYearTarget = ($ost->getConfig()->getSugPerYr());
$SugTargetPerMonth = $SugYearTarget;
$SugPreviousMonthTarget = round(($MemberCount * $SugTargetPerMonth)* (int) date('m', strtotime(date('Y-m')." -1 month")));
$SugCurrentMonthTarget = round(($MemberCount * $SugTargetPerMonth)* (int) date('m', strtotime(date('Y-m'))));
$YTDSubmitted = (int)$YearToDateSubmitted["count"];
$YTDSugAheadBehind = $YTDSubmitted - $SugYearTarget;
$YTDSugAheadBehindColor = ($YTDSugAheadBehind > -1 ? 'lightgreen':'#ff9999');
if ($SugYearTarget>$SugYearTarget * 100)
$YTDSugGoal = number_format($YTDSubmitted / $SugYearTarget * 100,2).'%';

$ImpYearTarget = ($MemberCount * $ost->getConfig()->getImpPerYr());
$ImpTargetPerMonth = $ImpYearTarget/12;
$ImpPreviousMonthTarget = round(($MemberCount * $ImpTargetPerMonth)* (int) date('m', strtotime(date('Y-m')." -1 month")));
$ImpCurrentMonthTarget = round(($MemberCount * $ImpTargetPerMonth)* (int) date('m', strtotime(date('Y-m'))));
$YTDImplemented = (int)$YearToDateImplemented["count"];
$YTDImpAheadBehind = $YTDImplemented - $ImpYearTarget;
$YTDImpAheadBehindColor = ($YTDImpAheadBehind > -1 ? 'lightgreen':'#ff9999');
if ($ImpYearTarget * 100>0)
	$YTDImpGoal = number_format($YTDImplemented / $ImpYearTarget * 100,2).'%';
?>