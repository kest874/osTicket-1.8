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