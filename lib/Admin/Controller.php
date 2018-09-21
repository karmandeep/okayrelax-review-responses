<?php

namespace WHMCS\Module\Addon\Review_Responses\Admin;

use WHMCS\Database\Capsule;
use WHMCS\Session;
use WHMCS\Admin;
use WHMCS\Carbon;


/**
 * Sample Admin Area Controller
 */
class Controller {

    /**
     * Index action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
    public function index($vars)
    {
		$whmcs = \App::self();
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables
		$systemurl = $whmcs->getSystemURL();
		//The person whos is currently logged in 
		$reviewer_id = Admin::getAdminID();
		require ROOTDIR . '/includes/clientfunctions.php';
		//echo getAdminId('Karmandeep');
		//getAdminID
		//admin_id
		
		//We Need Three Queries
		//ALL
		
		$all = Capsule::table('review_responses')
						->join('tbltickets', 'tbltickets.id', '=', 'review_responses.tid')
						->join('tblticketreplies', 'tblticketreplies.id', '=', 'review_responses.ticket_replies_id')
						//->where('tblticketreplies.tid', 'tbltickets.id')
						//->where('mod_servermonitoring_services.uid', $userid)
						->where('review_responses.admin_id', '!=', 0)
						->where('review_responses.admin_id', '!=', $reviewer_id)
						->select('tbltickets.tid as tid' , 'tbltickets.title as title' , 'tblticketreplies.message as message' ,
								 'review_responses.id as id' , 'review_responses.admin_id as admin_id' ,
								 'review_responses.tid as ticket_id' , 'review_responses.ticket_replies_id as ticket_response_id' , 
								 'review_responses.status as status' , 'review_responses.userid as userid' ,
								 'review_responses.reviewer_id as reviewer_id')
						->get();
		

		//Pending, Where There is No Reviewer
		$pending = Capsule::table('review_responses')
							->join('tbltickets', 'tbltickets.id', '=', 'review_responses.tid')
							->join('tblticketreplies', 'tblticketreplies.id', '=', 'review_responses.ticket_replies_id')
							//->where('tblticketreplies.tid', 'tbltickets.id')
							//->where('mod_servermonitoring_services.uid', $userid)
							->where('review_responses.admin_id', '!=', 0)
							->where('review_responses.admin_id', '!=', $reviewer_id)
							->where('review_responses.reviewer_id', 0)
							->select('tbltickets.tid as tid' , 'tbltickets.title as title' , 'tblticketreplies.message as message' ,
									 'review_responses.id as id' , 'review_responses.admin_id as admin_id' ,
									 'review_responses.tid as ticket_id' , 'review_responses.ticket_replies_id as ticket_response_id' , 
									 'review_responses.status as status' , 'review_responses.userid as userid' ,
									 'review_responses.reviewer_id as reviewer_id')
							->get();
		
		


		//Reviews of the Currently Logged in Reviewer
		$myreview = Capsule::table('review_responses')
							->join('tbltickets', 'tbltickets.id', '=', 'review_responses.tid')
							->join('tblticketreplies', 'tblticketreplies.id', '=', 'review_responses.ticket_replies_id')
							//->where('tblticketreplies.tid', 'tbltickets.id')
							//->where('mod_servermonitoring_services.uid', $userid)
							->where('review_responses.admin_id', '!=', 0)
							->where('review_responses.admin_id', '!=', $reviewer_id)
							->where('review_responses.reviewer_id', $reviewer_id)
							->select('tbltickets.tid as tid' , 'tbltickets.title as title' , 'tblticketreplies.message as message' ,
									 'review_responses.id as id' , 'review_responses.admin_id as admin_id' ,
									 'review_responses.tid as ticket_id' , 'review_responses.ticket_replies_id as ticket_response_id' , 
									 'review_responses.status as status' , 'review_responses.userid as userid' ,
									 'review_responses.reviewer_id as reviewer_id')
							->get();


		//Ticket Status Query:
		
		$status_query = Capsule::table('review_responses_ticket_status_request')
								->join('tbltickets', 'tbltickets.id', '=', 'review_responses_ticket_status_request.ticketid')
								->where('review_responses_ticket_status_request.adminid' , '!=', $reviewer_id)
								->where('review_responses_ticket_status_request.review_responses_id' , 0)
								//->where('review_responses_ticket_status_request.approved' , 0)
								->select('tbltickets.id as ticket_id' , 'tbltickets.tid as tid' , 'tbltickets.title as title' , 'tbltickets.userid as userid' ,
										 'review_responses_ticket_status_request.id as id',
										 'review_responses_ticket_status_request.adminid as adminid',
										 'review_responses_ticket_status_request.status as status',
										 'review_responses_ticket_status_request.ticketid as ticketid',
										 'review_responses_ticket_status_request.approved as approved')
								->get();

		include('listing.php');
    }

    /**
     * Show action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
    public function review($vars) {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables
		
		$today = Carbon::now()->format('Y-m-d H:i:s');

		require ROOTDIR . '/includes/ticketfunctions.php';
		
		if(isset($_GET['id']) && $_GET['id']):
		
			$id = $_GET['id'];
        	
			$current_reviwer = 0;
			//Log Who is reviwing.
			$current_reviwer_qry = Capsule::table('review_responses')->where('id' , $id)->select(['review_responses.reviewer_id as reviewer_id'])->first();
			$current_reviwer = $current_reviwer_qry->reviewer_id;
			
			//if Some one clicks and views this from the first time, they become reviewer.
			$reviewer_id = Admin::getAdminID();
			Capsule::table('review_responses')->where('id' , $id)->where('reviewer_id' , 0)->where('admin_id' , '!=', $reviewer_id)->update(['reviewer_id' => $reviewer_id , 'status' => 1 , 'updated_at' => $today]);
			

			
			//Now we get the query data
			$review = Capsule::table('review_responses')
									->join('tbltickets', 'tbltickets.id', '=', 'review_responses.tid')
									->join('tblticketreplies', 'tblticketreplies.id', '=', 'review_responses.ticket_replies_id')
									//->leftjoin('review_responses_replies', 'review_responses_replies.review_responses_id', '=', 'review_responses.id')
									->leftjoin('review_responses_ticket_status_request', 'review_responses_ticket_status_request.review_responses_id', '=', 'review_responses.id')
									->where('review_responses.id' , $id)
									->where('review_responses.reviewer_id' , $reviewer_id)
									->select('tbltickets.tid as tid' , 'tbltickets.title as title' , 'tblticketreplies.message as message' ,
											 'review_responses.id as id' , 'review_responses.admin_id as admin_id' ,
											 'review_responses.tid as ticket_id' , 'review_responses.ticket_replies_id as ticket_response_id' , 
											 'review_responses.status as status' , 'review_responses.notes as notes' ,'review_responses.userid as userid' ,
											 'review_responses.created_at as created_at' , 'review_responses.updated_at as updated_at' ,
											 'review_responses_ticket_status_request.id as ticket_status_request_id',
											 'review_responses_ticket_status_request.status as taskstatuschange',
											 'review_responses_ticket_status_request.approved as approved',
											 'review_responses.reviewer_id as reviewer_id')
									->first();
			
			
			//Now Get the messages
			if($current_reviwer === 0):
				AddtoLog($review->ticket_id , 'Response Being reviewed');
				logActivity( getAdminName($reviewer_id) . ' is reviewing response of ([Ticket ID: ' . $review->tid .'] ' . $review->title .   ') ', 0);
			endif;

			
			$messages = Capsule::table('review_responses_replies')
										->where('review_responses_replies.review_responses_id' , $review->id)
										->select('review_responses_replies.admin_id as admin_id', 
												 'review_responses_replies.reviewer_id as reviewer_id',
												 'review_responses_replies.message as message' , 
												 'review_responses_replies.msgstatus as msgstatus', 
												 'review_responses_replies.created_at as created_at',
												 'review_responses_replies.updated_at as updated_at')
										->get();
			
			
			//ticket_id
			
			//echo '<pre>';
			//print_r($review->ticket_id);
			//exit;
			$status_change = Capsule::table('review_responses_ticket_status_request')	
										->where('ticketid', $review->ticket_id)
										->where('review_responses_id', 0)
										->get();
			
			//Noe The Comments are Read.
			Capsule::table('review_responses_replies')->where('admin_id' , $review->admin_id)->where('review_responses_id' , $review->id)->update(['msgstatus' => 1]);
			
			include('review.php');

		endif;

		exit;
    }
	
	

	/**
     * Show view.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
	public function readonly($vars) {
		
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables
		
		if(isset($_GET['id']) && $_GET['id']):
		
			$id = $_GET['id'];
			//Now we get the query data
			$review = Capsule::table('review_responses')
									->join('tbltickets', 'tbltickets.id', '=', 'review_responses.tid')
									->join('tblticketreplies', 'tblticketreplies.id', '=', 'review_responses.ticket_replies_id')
									//->leftjoin('review_responses_replies', 'review_responses_replies.review_responses_id', '=', 'review_responses.id')
									->leftjoin('review_responses_ticket_status_request', 'review_responses_ticket_status_request.review_responses_id', '=', 'review_responses.id')
									->where('review_responses.id' , $id)
									->select('tbltickets.tid as tid' , 'tbltickets.title as title' , 'tblticketreplies.message as message' ,
											 'review_responses.id as id' , 'review_responses.admin_id as admin_id' ,
											 'review_responses.tid as ticket_id' , 'review_responses.ticket_replies_id as ticket_response_id' , 
											 'review_responses.status as status' , 'review_responses.notes as notes' , 'review_responses.userid as userid' ,
											 'review_responses.created_at as created_at' , 'review_responses.updated_at as updated_at' ,
											 'review_responses_ticket_status_request.status as taskstatuschange',
											 'review_responses_ticket_status_request.approved as approved',
											 'review_responses.reviewer_id as reviewer_id')
									->first();
			
			
			//Now Get the messages
			
			$messages = Capsule::table('review_responses_replies')
										->where('review_responses_replies.review_responses_id' , $review->id)
										->select('review_responses_replies.admin_id as admin_id', 
												 'review_responses_replies.reviewer_id as reviewer_id',
												 'review_responses_replies.message as message' , 
												 'review_responses_replies.msgstatus as msgstatus', 
												 'review_responses_replies.created_at as created_at',
												 'review_responses_replies.updated_at as updated_at')
										->get();


			$status_change = Capsule::table('review_responses_ticket_status_request')	
										->where('ticketid', $review->ticket_id)
										->where('review_responses_id', 0)
										->get();
										
			include('readonly.php');
			
		endif;
		exit;
		
	}

	/**
     * Show view.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
	public function submit($vars) {
		
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables
		
		require ROOTDIR . '/includes/ticketfunctions.php';

		
		 if(isset($_POST['mode'])) {
		 
		 	$mode = $_POST['mode'];
			$today = Carbon::now()->format('Y-m-d H:i:s');
		 
		 	switch($mode) {
			
				case 'review':
					$id = $_POST['id'];
					
					Capsule::table('review_responses')->where('id' , $id)->update(['status' => $_POST['status'] , 'notes' => $_POST['notes'] , 'updated_at' => $today]);
					$review = Capsule::table('review_responses')
										->join('tbltickets', 'tbltickets.id', '=', 'review_responses.tid')
										->where('review_responses.id' , $id)
										->select(['review_responses.tid as tid',
												  'tbltickets.tid as ticketnumber',
												  'tbltickets.title as title',	
												  'review_responses.reviewer_id as reviewer_id' ,
												  'review_responses.ticket_replies_id as ticket_replies_id'])
										->first();
					
					AddtoLog($review->tid , 'Response ' . $this->reviewStatus($_POST['status']));
					logActivity( getAdminName($review->reviewer_id) . ' has ' . $this->reviewStatus($_POST['status']) .   ' the response of ([Ticket ID: ' . $review->ticketnumber .'] ' . $review->title . ')', 0);
					//logActivity('');
					$ticket_status_request_id = $_POST['ticket_status_request_id'];
					//lets put the query
					if($_POST['status'] == 2) {
						// Makesure you notify the customer via email, and using the template.
						//Take help from metal trade
						sendMessage('Support Ticket Reply', $review->tid, $review->ticket_replies_id);

						//$ticket_status_request_id = $_POST['ticket_status_request_id'];
						if($ticket_status_request_id > 0) {
							header("Location: addonmodules.php?module=review_responses&action=approvestatus&id=".$ticket_status_request_id."&mode=".$mode."&review_responses_id=".$id."&result=success");						
							exit;
						}
					}
					//if status is 3 then remove the 
					//ticket_status_request_id
					if($_POST['status'] == 3) {
						if($ticket_status_request_id > 0) {
							Capsule::table('review_responses_ticket_status_request')->where('id' , $ticket_status_request_id)
																			->update(['approved' => 2,
																					  'updated_at' => $today]);
						}
					}
					//Capsule::table('review_responses_ticket_status_request')->where('review_responses_id' , $id)->update(['status' => $_POST['status'] , 'notes' => $_POST['notes'] , 'updated_at' => $today]);
					header("Location: addonmodules.php?module=review_responses&action=".$mode."&id=".$id."&result=success");
					exit;
				break;

				case 'sendmessage':
					$id = $_POST['id'];
					Capsule::table('review_responses_replies')->insert(['review_responses_id' => $id ,'admin_id' => $_POST['admin_id'] , 'reviewer_id' => $_POST['reviewer_id'], 'message' => $_POST['message'], 'msgstatus' => 0 , 'created_at' => $today]);
					if($_POST['admin_id'] > 0) {
						header("Location: addonmodules.php?module=review_responses&action=view&id=".$id."");
						exit;
					}
					header("Location: addonmodules.php?module=review_responses&action=review&id=".$id."");
					exit;
				
				break;
				
				default:
				break;
				
			}
		 
		 
		 }
		
			
	}
	
	
	/**
     * Show view.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
	public function approvestatus($vars) {
		
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables
		
		require ROOTDIR . '/includes/ticketfunctions.php';

		
		$reviewer_id = Admin::getAdminID();
		
		$today = Carbon::now()->format('Y-m-d H:i:s');
		
		if(isset($_GET['id'])):
			
			$id = $_GET['id'];
			
			$status_qry = Capsule::table('review_responses_ticket_status_request')
										->join('tbltickets', 'tbltickets.id', '=', 'review_responses_ticket_status_request.ticketid')
										->where('review_responses_ticket_status_request.id' , $id)
										->select(['review_responses_ticket_status_request.id as id',
												  'tbltickets.tid as tid' , 
												  'tbltickets.title as title',
												  'review_responses_ticket_status_request.ticketid as ticketid',
												  'review_responses_ticket_status_request.adminid as adminid',
												  'review_responses_ticket_status_request.status as status'])
										->first();
						
			//Set Ticket Status and log the entry
			//Log the ebtry into review_responses_ticket_status_log
			//and Set approved to 1
			
			//Update Status
			Capsule::table('tbltickets')->where('id' , $status_qry->ticketid)
										->update(['status' => $status_qry->status]);
										
			
			//Log the Enrtry tblticketlog
			/*Capsule::table('tblticketlog')->insert(['date' => $today,
													'tid' => $status_qry->ticketid,
													'action' => 'Status changed to ' . $status_qry->status . ' (by ' . getAdminName($status_qry->adminid) . ')']);
				
			*/
			//Log the Enrtry review_responses_ticket_status_log
			Capsule::table('review_responses_ticket_status_log')->insert(['adminid' => $status_qry->adminid,
																		  'status' => $status_qry->status,
																		  'ticketid' => $status_qry->ticketid,
																		  'created_at' => $today]);
			
			
			//Status approved to 1
			Capsule::table('review_responses_ticket_status_request')->where('id' , $status_qry->id)
																	->update(['approved' => 1,
																			  'updated_at' => $today]);
								
			AddtoLog($status_qry->ticketid , 'Status changed to ' . $status_qry->status . ' Approved');
			logActivity( getAdminName($reviewer_id) . ' has Approved status change to ' . $status_qry->status .   ' of ([Ticket ID: ' . $status_qry->tid .'] ' . $status_qry->title . ')', 0);

		endif;
		
		if(isset($_GET['mode'])):
			
			$mode = $_GET['mode'];
			$review_responses_id = $_GET['review_responses_id'];
			header("Location: addonmodules.php?module=review_responses&action=".$mode."&id=".$review_responses_id."&result=success");
			exit;
			//review
		endif;
		
		header("Location: addonmodules.php?module=review_responses#tab=4");
		exit;
		//echo '<pre>';
		//print_r($vars);
		//exit;
		
	}


	public function rejectstatus($vars) {

        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables
		
		require ROOTDIR . '/includes/ticketfunctions.php';

		
		$reviewer_id = Admin::getAdminID();
		
		$today = Carbon::now()->format('Y-m-d H:i:s');

		if(isset($_GET['id'])):
			
			$id = $_GET['id'];

			$status_qry = Capsule::table('review_responses_ticket_status_request')
										->join('tbltickets', 'tbltickets.id', '=', 'review_responses_ticket_status_request.ticketid')
										->where('review_responses_ticket_status_request.id' , $id)
										->select(['review_responses_ticket_status_request.id as id',
												  'tbltickets.tid as tid' , 
												  'tbltickets.title as title',
												  'review_responses_ticket_status_request.ticketid as ticketid',
												  'review_responses_ticket_status_request.adminid as adminid',
												  'review_responses_ticket_status_request.status as status'])
										->first();
			
			Capsule::table('review_responses_ticket_status_request')->where('id' , $status_qry->id)
															->update(['approved' => 2,
																	  'updated_at' => $today]);

			AddtoLog($status_qry->ticketid , 'Status changed to ' . $status_qry->status . ' Rejected');
			logActivity( getAdminName($reviewer_id) . ' has Rejected status change to ' . $status_qry->status .   ' of ([Ticket ID: ' . $status_qry->tid .'] ' . $status_qry->title . ')', 0);

		
		endif;

		if(isset($_GET['mode'])):
			
			$mode = $_GET['mode'];
			$review_responses_id = $_GET['review_responses_id'];
			header("Location: addonmodules.php?module=review_responses&action=".$mode."&id=".$review_responses_id."&result=success");
			exit;
			//review
		endif;


		header("Location: addonmodules.php?module=review_responses#tab=4");
		exit;

	}

	//Static Function
	/**
     * Show view.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
	private function reviewStatus( $status ) {
		
		$LANG['unpublished'] = "Un-Published";
		$LANG['underreview'] = "Under-Review";
		$LANG['accepted'] = "Accepted";
		$LANG['rejected'] = "Rejected";
		
		$status_arr = [ 0 => $LANG['unpublished'] , 1 => $LANG['underreview'], 2 => $LANG['accepted'] , 3 => $LANG['rejected'] ];
		return $status_arr[$status];
	}
	
	
	private function reviewButton( $id ) {
		
		$admin_id = Admin::getAdminID();

		$reviews = Capsule::table('review_responses')->where('id' , $id)->first();


		
		//If there is no Reviewer which means the reviewer_id is 0 then Display the Review Now Button.
		if($reviews->reviewer_id == 0) {
			return "<button onClick=\"window.open('addonmodules.php?module=review_responses&action=review&id=" . $reviews->id . "','reviewwindow','width=1200,height=600,top=10,left=10,scrollbars=yes')\" class=\"btn btn-success\"><i class=\"fa fa-comment\"></i> Review Now</button>";
		}
				
		//If the Current Logged in Admin is the Reviewer of this Task Response.
		//Show The Message Count

		$cnt = Capsule::table('review_responses_replies')->where('msgstatus' , 0)->where('admin_id' , $reviews->admin_id)->where('review_responses_id' , $reviews->id)->count();
		//review_responses_id
		$count_string = "";
		if($cnt > 0) {
			$count_string = "<i style=\"background: #e50000; border-radius: 1000px; display: inline-block; min-width: 20px;\" class=\"count\">" . $cnt . "</i>";	
		}

		if($reviews->reviewer_id == $admin_id) {
			return "<button onClick=\"window.open('addonmodules.php?module=review_responses&action=review&id=" . $reviews->id . "','reviewwindow','width=1200,height=600,top=10,left=10,scrollbars=yes')\" class=\"btn btn-warning\">" . $count_string . " <i class=\"fa fa-comment\"></i> View & Comment</button>";			
		}
		
		//If its No Body the Just display them The Things.
		if($reviews->reviewer_id != $admin_id) {
			return "<button onClick=\"window.open('addonmodules.php?module=review_responses&action=readonly&id=" . $reviews->id . "','readonlywindow','width=1200,height=600,top=10,left=10,scrollbars=yes')\" class=\"btn btn-info\"><i class=\"fa fa-eye\"></i> View</button>";			
		}
		
		return '';
		
	}
}
