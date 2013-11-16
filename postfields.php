<?php 
function saveSrchRow() {
global $srchrow;
$srchrow['salutation']	=isset($_POST['salutation'])?$_POST['salutation']:'';
$srchrow['birth_date']	=isset($_POST['birth_date'])?$_POST['birth_date']:'';
$srchrow['postal_code']	=isset($_POST['postal_code'])?$_POST['postal_code']:'';
$srchrow['last_name'] 	=isset($_POST['last_name'])?$_POST['last_name']:'';
$srchrow['first_name']	=isset($_POST['first_name'])?$_POST['first_name']:'';
$srchrow['middle_name']	=isset($_POST['middle_name'])?$_POST['middle_name']:'';
$srchrow['home_phone']	=isset($_POST['home_phone'])?$_POST['home_phone']:'';
$srchrow['work_phone']	=isset($_POST['work_phone'])?$_POST['work_phone']:'';
$srchrow['contact_email']=isset($_POST['contact_email'])?$_POST['contact_email']:'';
$srchrow['address_1']	=isset($_POST['address_1'])?$_POST['address_1']:'';
$srchrow['address_2']	=isset($_POST['address_2'])?$_POST['address_2']:'';
$srchrow['address_3']	=isset($_POST['address_3'])?$_POST['address_3']:'';
$srchrow['city']      	=isset($_POST['city'])?$_POST['address_3']:'';
$srchrow['state']     	=isset($_POST['state'])?$_POST['state']:'';
$srchrow['country']   	=isset($_POST['country'])?$_POST['country']:'';
$srchrow['home_phone']	=isset($_POST['home_phone'])?$_POST['birth_date']:'';
$srchrow['work_phone']	=isset($_POST['work_phone'])?$_POST['work_phone']:'';
$srchrow['other_phone1']=isset($_POST['other_phone1'])?$_POST['other_phone1']:'';
$srchrow['other_phone2']=isset($_POST['other_phone2'])?$_POST['other_phone2']:'';
$srchrow['other_phone3']=isset($_POST['other_phone3'])?$_POST['other_phone3']:'';
$srchrow['other_phone_type1']	=isset($_POST['other_phone_type1'])?$_POST['other_phone_type1']:'';
$srchrow['other_phone_type2']	=isset($_POST['other_phone_type2'])?$_POST['other_phone_type2']:'';
$srchrow['other_phone_type3']	=isset($_POST['other_phone_type3'])?$_POST['other_phone_type3']:'';
$srchrow['comments']			=isset($_POST['comments'])?$_POST['comments']:'';
}
function initSrchRow() {
global $srchrow;
$srchrow['salutation']='';					
$srchrow['birth_date']='';
$srchrow['postal_code']='';
$srchrow['last_name'] ='';
$srchrow['first_name']='';
$srchrow['middle_name']='';
$srchrow['home_phone']='';
$srchrow['work_phone']='';
$srchrow['contact_email']='';
$srchrow['address_1'] ='';
$srchrow['address_2'] ='';
$srchrow['address_3'] ='';
$srchrow['city']      ='';
$srchrow['state']     ='';
$srchrow['country']   ='';
$srchrow['home_phone']='';
$srchrow['work_phone']='';
$srchrow['other_phone1']='';
$srchrow['other_phone2']='';
$srchrow['other_phone3']='';
$srchrow['other_phone_type1']='';
$srchrow['other_phone_type2']='';
$srchrow['other_phone_type3']='';
$srchrow['comments']='';
}
?>
