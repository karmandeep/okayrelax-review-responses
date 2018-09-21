<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>WHMCS - Reviews - Responses - Set Status </title>

  <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
  <link href="templates/blend/css/all.min.css?v=adcb9b" rel="stylesheet" />

   
  <script type="text/javascript">
  
  	<?php if($review->status == 1): ?>
	   window.opener.location.reload(false);	
  	<?php endif; ?>
	<?php if($messages[(count($messages) - 1)]->msgstatus == 0): ?>
	   window.opener.location.reload(false);	
  	<?php endif; ?>
	
      function checkForm(Form) {
          
         /* var ipAddressRegEx = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;

          if(Form.ipaddress.value == '') {
              alert("Please Enter IP Address.");
              Form.ipaddress.focus();
              return false;
          } else if(!ipAddressRegEx.test(Form.ipaddress.value)) {
              alert("IP Address Invalid.");
              Form.ipaddress.focus();
              return false;
          } else {
              return true;
          }*/
          
          return true;
      }
  
  	  
	  
	  
  </script>

  <?php
      if(isset($_GET['result']) && $_GET['result'] === 'success'):
  ?>
      <script type="text/javascript">	
          
		  //Don't Need this Here Let Them Close this.
		  //alert('Record Updated Successfully.'); 
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
     
          <form name="review" action="addonmodules.php?module=review_responses&action=submit" method="post">
              <input type="hidden" name="mode" value="setstatus">
              <input type="hidden" name="id" value="<?php echo $id; ?>">
          
              <table class="form" width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                      <tr>
                          <td class=" text-left" colspan="4"> <h1><?php echo $LANG['review_heading'] ?></h1> </td>
                      </tr>	
                      

                      <tr>
                          <td class="fieldlabel text-left"></td>
                          <td class="fieldlabel text-left"><?php echo $LANG['review_msgs_status_qry'] ?>:</td>
                          <td class="fieldarea text-left">
                          <?php if(count($tktstatuses) > 0): ?>
                          
                          <select name="status">
                          	<?php foreach($tktstatuses as $key => $value): ?>
                             <?php if($tktstatus->status === $value->title): ?>
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
                          <td class="text-center" colspan="4"><input class="btn btn-default" type="button" name="cancel" onClick="window.close();" value="Close">&nbsp;<input class="btn btn-primary" type="submit" name="submit" value="Change"></td>
                      </tr>
                  </tbody>
              </table>    	
          
          </form>


          
      </div>
  </div>
</body>
</html>