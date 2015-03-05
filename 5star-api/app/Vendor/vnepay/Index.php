<form name="myForm" action="result.php" method="post" onsubmit="return validateForm()">
<center><H1><a style="color:blue;margin-left:20px" href="http://vnptepay.com.vn/">Card Charging Demo Client</a></H1></center>
<table align="center">

<tr>	
	<td>Ma the:</td>
	<td style="color:red;">*</td>
	<td>
	<input type="text" name="pin" onfocus="if(this.value=='Ma the') this.value=''" onblur="if(this.value=='') this.value='Ma the'" value="Ma the"/></td>			
</tr>
<tr>
	<td>
	Serial:</td>	
	<td><div id='seri' style="color:red;"></div></td>
	<td><input type="text" name="serial" /></td>
</tr>
<tr>
	<td>Nha cung cap:</td>	
	<td></td>
	<td><select name = "provider" onchange="change()">
	  <option value="VNP" selected>Vinaphone</option>
	  <option value="VMS">Mobifone</option>
	  <option value="VTT">Viettel</option>
	  <option value="FPT">FPT</option>
	  <option value="VTC">VTC Vcoin</option>
	  <option value="MGC">MegaCard</option>
	</select></td>		
</tr>
<tr>
	 <td>
	 <input type="hidden" name ="charge1" value="sac"/>
	<input type="submit"   value="Nap the" />
	<!--?php CardCharging($soapClient);?-->	
	</td>
</tr>
</table>
</form>

<script type="text/javascript">
function validateForm()
{
 
	var x=document.forms["myForm"]["pin"].value;
	var y=document.forms["myForm"]["serial"].value;
	var provider = document.forms["myForm"]["provider"].value;

	if(provider == 'VTT'|| provider == 'FPT'|| provider == 'VTC'||provider == 'VNP'||provider == 'VMS')
	{
	
	if (x==null || x=='' || y==null || y=='')
	  {  
	  alert("Can phai nhap du lieu cho pin va serial");  
	  
	  return false;
	  }
	}
	else if (x==null || x=='' || x=='Ma the')
	{  
	  alert("Can phai nhap du lieu cho pin");	  
	  return false;
	}
}
  function change(){
	var provider = document.forms["myForm"]["provider"].value;
	if(provider == 'VTT'|| provider == 'FPT'|| provider == 'VTC'|| provider == 'VNP'|| provider == 'VMS')
	{
		document.getElementById('seri').innerHTML="*";
	}else{
		document.getElementById('seri').innerHTML="";
	}
  }
  
  
 function setOptionText(ExSelect, theArray) {
     for (loop = 0; loop < ExSelect.options.length; loop++) {
          ExSelect.options[loop].text = theArray[loop];
     }
}
</script>