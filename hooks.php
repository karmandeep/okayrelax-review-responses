<?php

use WHMCS\Database\Capsule;

use WHMCS\Admin;


/**
 * WHMCS SDK Sample Addon Module Hooks File
 *
 * Hooks allow you to tie into events that occur within the WHMCS application.
 *
 * This allows you to execute your own code in addition to, or sometimes even
 * instead of that which WHMCS executes by default.
 *
 * @see https://developers.whmcs.com/hooks/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

// Require any libraries needed for the module to function.
// require_once __DIR__ . '/path/to/library/loader.php';
//
// Also, perform any initialization required by the service's library.

/**
 * Register a hook with WHMCS.
 *
 * This sample demonstrates triggering a service call when a change is made to
 * a client profile within WHMCS.
 *
 * For more information, please refer to https://developers.whmcs.com/hooks/
 *
 * add_hook(string $hookPointName, int $priority, string|array|Closure $function)
 */

//TicketUserReply
//If required

/*
add_hook('EmailPreSend', 1000, function($vars) {
    
	if(isset($_GET['action'])):
		$action = $_GET['action'];
		if($action === 'viewticket'):
			if(isset($_POST['postreply'])) {
				
				$id = $_GET['id'];
				$vars['ticketid'] = $id;
				
				//IF we remove this then, It sends email.
				
				
				
				exit;
				header('Location: tasks.php?action=view&id=' . $id);
				exit;
			}
		endif;
	endif;
});*/

//require ROOTDIR . '/includes/ticketfunctions.php';


add_hook('TicketAdminReply', 1001, function($vars) {	

		// Perform hook code here...
		$admin_id = Admin::getAdminID();
			
		// Role ID...
		$role_id_qry = Capsule::table('tbladmins')->where('id' , $admin_id)->select(['tbladmins.roleid as roleid'])->first();
		//Role ID;
		$role_id = $role_id_qry->roleid;
		//$role_id = Admin::getAdminRoleID();
		
		
		if($role_id == 3):
			
			$qry = Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->select(['tbltickets.userid as userid' , 'tbltickets.tid as tid'])->first();
			$userid = $qry->userid;
			$tid = $qry->tid; 
			//$right_now = Carbon\Carbon::now()->timestamp; 
			$today = Carbon\Carbon::now()->format('Y-m-d H:i:s');
			
			//Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => 'In Progress']);
			
			if($userid > 0):
				//Startus 0 Means unpublished
				//Lets Insert the review_responses
				 $review_responses_id = Capsule::table('review_responses')->insertGetId( ['admin_id' => $admin_id, 
															  'tid' => $vars['ticketid'],
															  'ticket_replies_id' => $vars['replyid'],
															  'userid' => $userid, // This is The Custoer
															  'reviewer_id' => 0, //This Guy is Going to be the Reviewer
															  'status' => 0,
															  'created_at' => $today] );
		
				$status = 'Open';
				$status_qry = Capsule::table('review_responses_ticket_status_log')->where('ticketid' , $vars['ticketid'])->orderBy('id', 'desc')->first();
				
				if(count($status_qry)) {
					//Get the Status
					$status = $status_qry->status;
				}
		
				if($vars['status'] === 'In Progress'):
					$status = 'In Progress';
				endif;
				
				Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
		
				//Lets Roll
		
				Capsule::table('review_responses_ticket_status_log')->insert(['adminid' => $admin_id, 
																			  'ticketid' => $vars['ticketid'], 
																			  'status' => $status,
																			  'created_at' => $today]);
		
					if($vars['status'] !== 'In Progress') {
					
						if($vars['status'] !== 'Open'):				
		
							$check_pending = Capsule::table('review_responses_ticket_status_request')->where('adminid' , $admin_id)
																									 ->where('ticketid' , $vars['ticketid'])
																									 ->where('approved' , 0)
																									 ->where('review_responses_id' , $review_responses_id)
																									 ->select('id')
																									 ->first();
							if(!$check_pending->id) {
		
								Capsule::table('review_responses_ticket_status_request')->insert(['adminid' => $admin_id, 
																								  'status' => $vars['status'],
																								  'ticketid' => $vars['ticketid'], 
																								  'review_responses_id' => $review_responses_id,
																								  'created_at' => $today]);

								//logActivity( getAdminName($admin_id) . ' Replied to <a href="tasks.php?action=viewticket&id=' . $vars['ticketid'] . '">Ticket #ID ' . $vars['ticketid'] . '</a> status changed to ' . $vars['status'] . ' is Under review. ', 0);
								
								AddtoLog($vars['ticketid'] , 'Responsed and is sent for review'); 
								logActivity( getAdminName($admin_id) . ' replied to ([Ticket ID: '.$tid.'] ' . $vars['subject'] . ') and changed status to ' . $vars['status'] . ', is sent for review. ', 0);

							}
														
						endif;
						
					} //END IF IN PROGRESS
		
			endif;			

		endif; //END Role Id 4
});


add_hook('ClientAreaPageViewTicket', 1, function($vars) {
    // Perform hook code here...
	//tid=453506&c=p62PzuVO
	if(isset($_GET['tid']) && $_GET['c']):
		
		$tid = $_GET['tid'];
		$c = $_GET['c'];
		$ticketQry = Capsule::table('tbltickets')->where('tid' , $tid)->where('c' , $c)->select(['tbltickets.id as id'])->first();
		
		$id = $ticketQry->id;
		
		$ticketReplesQry = Capsule::table('tblticketreplies')
											->join('review_responses', 'review_responses.ticket_replies_id', '=', 'tblticketreplies.id' )
											//->where('review_responses.status' , 2)
											//->where('review_responses.admin_id' , '!=', 0)
											->where('tblticketreplies.tid' , $id)
											->select(['tblticketreplies.id as id' ,
													  'review_responses.status as status' ,													  
													   ])->get();
		
	endif;

	foreach($ticketReplesQry as $key => $value):
		
		$descreplies[$value->id] = $value->status;
	
	endforeach;
	
	$extraTemplateVariables['repliesstatus'] = $descreplies;
	
    return $extraTemplateVariables;

});





add_hook('TicketStatusChange', 1, function($vars) {
	
	//echo '<pre>';
	//print_r($_POST);
	//exit;
	$role_id_qry = Capsule::table('tbladmins')->where('id' , $vars['adminid'])->select(['tbladmins.roleid as roleid'])->first();
	//Role ID;
	$role_id = $role_id_qry->roleid;
	//$role_id = Admin::getAdminRoleID();


	
	if($role_id == 3):
	
		if($vars['adminid']):
			//Get the Last Status Set for the Ticket
			$status = 'Open';
			
			$status_qry = Capsule::table('review_responses_ticket_status_log')->where('ticketid' , $vars['ticketid'])->orderBy('id', 'desc')->first();
			
			if(count($status_qry)) {
				//Get the Status
				$status = $status_qry->status;
			}
			
			//If the Ticket Status is Open or In-Progress Don't Do anything but 
			//if they Change to Something Else Change it to
			//The revert it back to what ever to what the previous status was. (This is a Not Possible)
		
			if($vars['status'] === 'In Progress'):
				$status = 'In Progress';
			endif;
		
			Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
			
			/*switch($vars['status']) {
				
				case 'Open':
					Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
				break;
				
				case 'Answered':
					Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
				break;
		
				case 'Customer-Reply':
					Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
				break;
		
				case 'On Hold':
					Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
				break;
				
				case 'In Progress':
					$status = 'In Progress';
					Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
				break;
				
				case 'Closed':
					Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
				break;
				
			}*/
		
		
			$today = Carbon\Carbon::now()->format('Y-m-d H:i:s');
			
			//Lets Record the StstusChange...
			Capsule::table('review_responses_ticket_status_log')->insert(['adminid' => $vars['adminid'], 
																		  'ticketid' => $vars['ticketid'], 
																		  'status' => $status,
																		  'created_at' => $today]);
																		  
			if(!isset($_POST['postreply'])):
	
				if($vars['status'] !== 'In Progress') {
																					  

					if($vars['status'] !== 'Open'):
					//Check If there is already a Pending status like Closed
				
					$check_pending = Capsule::table('review_responses_ticket_status_request')->where('adminid' , $vars['adminid'])
																							 ->where('ticketid' , $vars['ticketid'])
																							 ->where('approved' , 0)
																							 ->where('review_responses_id' , 0)
																							 ->select('id')
																							 ->first();
						
						if(!$check_pending->id) {
							
							Capsule::table('review_responses_ticket_status_request')->insert(['adminid' => $vars['adminid'], 
																							  'ticketid' => $vars['ticketid'], 
																							  'status' => $vars['status'],
																							  'created_at' => $today]);
						

							$sststatus = Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->first();
							
							AddtoLog($vars['ticketid'] , 'Status changed to ' . $vars['status'] . ' is sent for review'); 

							logActivity( getAdminName($vars['adminid']) . ' changed status of ([Ticket ID: ' . $sststatus->tid . '] ' . $sststatus->title . ') to ' . $vars['status'] . ' is sent for review. ', 0);

						}
					
					endif; //if($vars['status'] !== 'Open'):
					
				} //END IF IN PROGRESS
			
			
			endif; //End if(!isset($_POST['postreply'])):
				
		endif;
		
	endif; //END Role Id 4
	
});

add_hook('TicketClose', 1, function($vars) {

	
	// Perform hook code here...
	$admin_id = Admin::getAdminID();
	
	//echo 1234;
	// Role ID...
	$role_id_qry = Capsule::table('tbladmins')->where('id' , $admin_id)->select(['tbladmins.roleid as roleid'])->first();
	//Role ID;
	$role_id = $role_id_qry->roleid;
	//$role_id = Admin::getAdminRoleID();
	
	if($role_id == 3):
		
		if($admin_id) {
			
			$status = 'In Progress';
			
			$status_qry = Capsule::table('review_responses_ticket_status_log')->where('ticketid' , $vars['ticketid'])->orderBy('id', 'desc')->first();
			
			if(count($status_qry)) {
				$status = $status_qry->status;
			}
		
			Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->update(['status' => $status]);
			
			$today = Carbon\Carbon::now()->format('Y-m-d H:i:s');
			
			//Lets Record the StstusChange...
			Capsule::table('review_responses_ticket_status_log')->insert(['adminid' => $admin_id, 
																		  'ticketid' => $vars['ticketid'], 
																		  'status' => $status,
																		  'created_at' => $today]);
		
			//Check If there is already a Pending status like Closed
			if(!isset($_POST['postreply'])):
			
				$check_pending = Capsule::table('review_responses_ticket_status_request')->where('status' , 'Closed')
																						 ->where('adminid' , $admin_id)
																						 ->where('approved' , 0)			 
																						 ->where('ticketid' , $vars['ticketid'])
																						 ->where('review_responses_id' , 0)
																						 ->select('id')
																						 ->first();
				//print_r($check_pending);
				//echo 123456;
				if(!$check_pending->id) {
					
					Capsule::table('review_responses_ticket_status_request')->insert(['adminid' => $admin_id, 
																					  'ticketid' => $vars['ticketid'], 
																					  'status' => 'Closed',
																					  'created_at' => $today]);

					$sststatus = Capsule::table('tbltickets')->where('id' , $vars['ticketid'])->first();
					
					AddtoLog($vars['ticketid'] , 'Status changed to Closed is sent for review'); 

					logActivity( getAdminName($admin_id) . ' Closed ([Ticket ID: ' . $sststatus->tid . '] ' . $sststatus->title . ') is sent for review. ', 0);
																					  
					//logActivity( getAdminName($admin_id) . ' Closed Ticket #ID' . $vars['ticketid'] . ' is sent for review. ', 0);

				}
				
			endif;

		}

	endif; //END Role Id 4
	
});



add_hook('AdminAreaPage', 1, function($vars) {
	
	$whmcs = \App::self();
	if(basename($whmcs->getPhpSelf()) === 'supporttickets.php') {
    	
		if(count($_GET)) {
			header('Location: tasks.php?' . http_build_query($_GET));
		} else {
			header('Location: tasks.php');			
		}
		exit;
	}
	
});

add_hook('AdminAreaHeaderOutput', 1, function($vars) {


	//First Check WHose Logged in
	
	// Perform hook code here...
	$admin_id = Admin::getAdminID();
	
	//echo 1234;
	// Role ID...
	$role_id_qry = Capsule::table('tbladmins')->where('id' , $admin_id)->select(['tbladmins.roleid as roleid'])->first();
	//Role ID;
	$role_id = $role_id_qry->roleid;
	
	$return = '';	
	//Get how many new replies
	//if you are a virtual assistant
	//Then this should display how many of their responses are
	//in review
	
	
	
	//If you are not an Virtual Assistant then it should display how many
	//of new responses are there for review.
	
	
	//$extraTemplateVariables['review_replies'] = 'This is a Test';
	
    //return $extraTemplateVariables;
	//0 Unpubliched
	//1 Under Review
	//2 Accepted
	//3 Rejected
	//If this is a VA
	if($role_id === 3){
		//echo 'VA';	
		//This is VA
		$data = Capsule::table('review_responses')->where('admin_id' , $admin_id)->orderBy('status' , 'asc')->get();
		
		$grouped_array = [];
		
		if(count($data)):
			foreach($data as $key => $value):
				$grouped_array[$value->status][] = $value;
			endforeach;
		endif;
		
		$messages_cnt = Capsule::table('review_responses_replies')
							->join('review_responses', 'review_responses.id', '=', 'review_responses_replies.review_responses_id' )
							->where('review_responses.admin_id' , $admin_id)
							->where('review_responses_replies.msgstatus' , 0)
							->where('review_responses_replies.admin_id' , 0)
							->count();
		
		
		
		$return = '<div class="margin-10">';
		$return .= 'You Have: <a href="addonmodules.php?module=my_referral#tab=1"><strong>Open</strong> (' . count($grouped_array[0]) .')</a> 
		| <a href="addonmodules.php?module=my_referral#tab=2"><strong>Under-Review</strong> (' . count($grouped_array[1]) .')</a> 
		| <a href="addonmodules.php?module=my_referral#tab=3"><strong>Accepted</strong> (' . count($grouped_array[2]) .')</a> 
		| <a href="addonmodules.php?module=my_referral#tab=4"><strong>Rejected</strong> (' . count($grouped_array[3]) .')</a> 
		Responses and have <a href="addonmodules.php?module=my_referral#tab=0"> <strong>Unread Messeges</strong> ('. $messages_cnt .') </a>';
		$return .= '</div>';
		
	} else {
		//echo 'Reviewer';
		//This are Others
		$data = Capsule::table('review_responses')->where('reviewer_id' , $admin_id)->get();

		$grouped_array = [];
		
		if(count($data)):
			foreach($data as $key => $value):
				$grouped_array[$value->status][] = $value;
			endforeach;
		endif;

		$messages_cnt = Capsule::table('review_responses_replies')
							->join('review_responses', 'review_responses.id', '=', 'review_responses_replies.review_responses_id' )
							->where('review_responses.reviewer_id' , $admin_id)
							->where('review_responses_replies.msgstatus' , 0)
							->where('review_responses_replies.reviewer_id' , 0)
							->count();
							
		$open = Capsule::table('review_responses')->where('reviewer_id' , 0)->where('status' , 0)->count();
		
		$return = '<div class="margin-10">';
		$return .= 'You Have: <a href="addonmodules.php?module=review_responses#tab=2"><strong>Open</strong> (' . $open .')</a> 
		| <a href="addonmodules.php?module=review_responses#tab=4"><strong>Under-Review</strong> (' . count($grouped_array[1]) .')</a> 
		| <a href="addonmodules.php?module=review_responses#tab=5"><strong>Accepted</strong> (' . count($grouped_array[2]) .')</a> 
		| <a href="addonmodules.php?module=review_responses#tab=6"><strong>Rejected</strong> (' . count($grouped_array[3]) .')</a> 
		Responses and have <a href="addonmodules.php?module=review_responses#tab=3"> <strong>Unread Messeges</strong> ('. $messages_cnt .') </a>';
		$return .= '</div>';


	}

	//echo $vars['filename'] ;
	if($vars['filename'] === 'supportcenter' || $vars['filename'] === 'tasks' || $vars['filename'] === 'index'):
		 return '<div class="alert alert-warning global-admin-warning" style="margin:0;" >' . $return . '</div>';
	endif;
	
    //return '<div class="alert alert-warning global-admin-warning" style="margin:0;" >' . $return . '</div>';
});
//When the Ticket Deleted Remove the Associated Reviews / Replies / Status request as well as Logs to Save the Database. 

add_hook('TicketDelete', 1, function($vars) {
    // Perform hook code here...
	
	$ticketId = $vars['ticketId'];
	
	$get_review_responses = Capsule::table("review_responses")->where("review_responses.tid", $ticketId)->first();
	
	Capsule::table("review_responses")
		//->join('review_responses_replies', 'review_responses.id', '=', 'review_responses_replies.review_responses_id' )
        ->where("review_responses.tid", $ticketId)
        ->delete();

	Capsule::table("review_responses_replies")
		//->join('review_responses_replies', 'review_responses.id', '=', 'review_responses_replies.review_responses_id' )
        ->where("review_responses_replies.review_responses_id", $get_review_responses->id)
        ->delete();


	Capsule::table("review_responses_ticket_status_log")
		//->join('review_responses_replies', 'review_responses.id', '=', 'review_responses_replies.review_responses_id' )
        ->where("review_responses_ticket_status_log.ticketid", $ticketId)
        ->delete();

	Capsule::table("review_responses_ticket_status_request")
		//->join('review_responses_replies', 'review_responses.id', '=', 'review_responses_replies.review_responses_id' )
        ->where("review_responses_ticket_status_request.ticketid", $ticketId)
        ->delete();
	
});

add_hook('TicketDeleteReply', 1, function($vars) {
    // Perform hook code here...
});

add_hook('AdminAreaHeadOutput', 1, function($vars) {

    return <<<HTML
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    
	<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>-->
    
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>


    <script type="text/javascript">
    
    	$(document).ready(function() {
			
			$('#example0').DataTable({
        		"order": [[ 4, "desc" ]],
				"pageLength": 50
    		});
    		$('#example').DataTable({
        		"order": [[ 2, "desc" ]],
				"pageLength": 50
    		});
    		$('#example1').DataTable({
        		"order": [[ 2, "desc" ]],
				"pageLength": 50
    		});
    		$('#example2').DataTable({
				"pageLength": 50	
			});
    		$('#example3').DataTable({
        		"order": [[ 2, "desc" ]],
				"pageLength": 50
    		});
			
    		$('#example4').DataTable({
        		"order": [[ 4, "desc" ]],
				"pageLength": 50
    		});
			
			$('#example5').DataTable({
        		"order": [[ 2, "desc" ]],
				"pageLength": 50
    		});

		 
		 
		} );
		
		
		

    </script>
    
HTML;

});