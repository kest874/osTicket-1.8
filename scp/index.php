<?php
/*********************************************************************
    index.php
    
    Future site for helpdesk summary aka Dashboard.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
//Nothing for now...simply redirect to tickets page.
header("Location: tickets.php?queue=1&p=1&t=0&r=2&s=0");
die();
//require('tickets.php'); 
?>
