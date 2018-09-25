<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>WHMCS - Reviews - Responses - Add Response </title>

  <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
  <link href="templates/blend/css/all.min.css?v=adcb9b" rel="stylesheet" />


	<script type="text/javascript">
        
          function checkForm(Form) {
                        
              return true;
          }
          
    </script>

  <?php
      if(isset($_GET['result']) && $_GET['result'] === 'success'):
  ?>
      <script type="text/javascript">	
          
		  //Don't Need this Here Let Them Close this.
		  alert('Record Added Successfully.'); 
          //window.opener.location.reload(false);
          //window.close();
          
      </script>
  <?php	
      endif;
  ?>
  
</head>
<body data-phone-cc-input="1">
     
  <div class="row">
      <div class="col-md-12" style="margin:15px;">
      
 
      <form action="addonmodules.php?module=review_responses&action=submit" method="post">
          <input type="hidden" name="mode" value="addresponse">
          <input type="hidden" name="id" value="<?php echo $id; ?>">
      
          <table class="form" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                  <tr>
                      <td class=" text-left" colspan="4"> <h1><?php echo $LANG['review_task_response_add'] ?><small> <a href="#" onClick="window.open('tasks.php?action=view&id=<?php echo $tkt['ticketid']; ?>');" ><?php echo $tkt['tid']; ?> - <?php echo $tkt['subject']; ?></a></small></h1></td>
                  </tr>	

                  <tr>
                    <td class="fieldlabel text-left"></td>
                    <td class="fieldlabel text-left"><?php echo $LANG['review_title'] ?>:</td>
                    <td class="fieldarea text-left"><a href="#" onClick="window.open('tasks.php?action=view&id=<?php echo $tkt['ticketid']; ?>');" ><?php echo $tkt['tid']; ?> - <?php echo $tkt['subject']; ?></a> - <button class="btn btn-sm btn-primary" onClick="window.open('tasks.php?action=view&id=<?php echo $tkt['ticketid']; ?>');">Goto Task</button></td>
                    <td class="fieldlabel text-left"></td>
                  </tr>
                  
                  <tr>
                    <td class="fieldlabel text-left"></td>
                    <td class="fieldlabel text-left"><?php echo $LANG['review_task_status_qry'] ?>:</td>
                    <td class="fieldarea text-left"><label class="label label-success"><?php echo $tkt['status']; ?></label></td>
                    <td class="fieldlabel text-left"></td>
                  </tr>
                  
				  <?php if(count($tkt['replies']) > 0): ?>	
						
                        <?php foreach($tkt['replies']['reply'] as $key => $value): ?>
                        
                          <tr>
                            <td class="fieldlabel text-left"></td>
                            <td class="fieldlabel text-left small"><?php echo ($value['admin']?$value['admin']:'Customer Response'); ?> - <?php echo fromMySQLDate($value['date'], 1) ?>:</td>
                            <td class="fieldarea text-left"><?php echo nl2br($value['message']); ?></td>
                            <td class="fieldlabel text-left"></td>
                          </tr>
                        
                        <?php endforeach; ?>
                    
				  <?php endif; ?>
                  
                  <tr>
                    <td class="fieldlabel text-left"></td>
                    <td class="fieldlabel text-left"><?php echo $LANG['review_task_response_add'] ?>:</td>
                    <td class="fieldarea text-left"><textarea name="message" id="replymessage" rows="14" class="form-control bottom-margin-10"><?php if($signature): echo $signature;  endif; ?></textarea></td>
                    <td class="fieldlabel text-left"></td>
                  </tr>

                  <?php /*?><tr>
                    <td class="fieldlabel text-left"></td>
                    <td class="fieldlabel text-left"><?php echo $LANG['review_task_response_add_attachment'] ?>:</td>
                    <td class="fieldarea text-left"><input type="file" name="attachments[]" class="form-control" /></td>
                    <td class="fieldlabel text-left"></td>
                  </tr><?php */?>

                 
                  <tr>
                      <td class="fieldlabel text-left"></td>
                      <td class="fieldlabel text-left"><?php echo $LANG['review_msgs_status_qry'] ?>:</td>
                      <td class="fieldarea text-left">
                      <?php if(count($tktstatuses) > 0): ?>
                      
                      <select name="status">
                        <?php foreach($tktstatuses as $key => $value): ?>
                         <?php if($value->title === 'Answered'): ?>
                          <option selected="selected" style="color:<?php echo $value->color; ?>;" value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
                         <?php else: ?>
                          <option style="color:<?php echo $value->color; ?>;" value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
                         <?php endif; ?>
                        <?php endforeach; ?>  
                      </select>
                      
                      <?php endif; ?>
                      
                      </td>
                      <td class="fieldlabel text-left"></td>
                  </tr>
                  
                  <tr>
                      <td class="text-center" colspan="4"><input class="btn btn-default" type="button" name="cancel" onClick="window.close();" value="Close">&nbsp;<input class="btn btn-primary" type="submit" name="submit" value="Submit"></td>
                  </tr>
                  
              </tbody>
          </table>    	
      
      </form>

          
      </div>
  </div>
</body>
</html>