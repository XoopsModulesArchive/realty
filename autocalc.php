<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification © RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased © RealtyOne			 */
/* 	 Overall content based on Open-Realty © Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
?>
<html>
<head>

<title>Car Loan Calculator</title>

<meta name="description" content="">
<meta name="keywords" content="cars, car, cars for sale, car information, classifieds, motoring, motor car, vehicle, used cars, new cars, auto, automotive, automotive news, automotive reviews, wheels, motor, which car, 4x4 trader, auto supermarket, unique cars, alfa romeo, asia, aston martin, audi, bentley, bmw, chevrolet, chrysler, citroen, csv, daewoo, daihatsu, daimler, eunos, ferrari, ford, gmc, holden, honda, hsv, hyundai, isuzu, jaguar, jeep, kia, lada, lamborghini, peugeot, porsche, proton, rolls-royce, rover, saab, seat, subaru, suzuki, syncro, toyota, tvr, volkswagen, volvo, sports car, wagon, four wheel drive, engine, mechanical, mechanics, motor show, falcon, commodore, tyres, passenger, people mover">



</head>
<body bgColor=#ffffff leftMargin=0 topMargin=10 marginheight="0" marginwidth="0">
<table width="95%" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr> 
          <td colspan="2" height="21">CAR LOAN CALCULATOR</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="2"> 
            <script language="JavaScript">
//<!--
 function select_item(name, value) {
        this.name = name;
        this.value = value;
    }
    function get_selection(select_object) {
        contents = new select_item();
        for(var i=0;i<select_object.options.length;i++)
           if(select_object.options[i].selected == true) {
                contents.name = select_object.options[i].text;
                contents.value = select_object.options[i].value;
            }
        return contents;
    }
// Function to calculate Periodic Payments.
function pmt(formfield)
{
   
   var fv = formfield.balloon.value;
   //var nper = get_selection(formfield.term);
   //nper = nper.value;
   var nper_item = document.formfield.term.selectedIndex;
   var nper = document.formfield.term.options[nper_item].value;
   var rate_item = document.formfield.rate.selectedIndex;
   var rate = document.formfield.rate.options[rate_item].value;
   var pv = formfield.amount.value;
   var balper = formfield.balper.value
   //make sure the amount financed is not negative.
	if (pv < 0) {
		alert("The value of the amount financed (car price) must not be less than $0");
	 return false;}
   pv = pv.replace("$","");
   pv = pv.replace(",","");

   formfield.amount.value = pv;
   //used by program
   pv = -pv;
   
   //make sure the balper field is only b/w 0 and 100 and no negatives.
   if ((balper < 0) || (balper > 100)) {
		alert("The value for the balloon percentage must be between 0 and 100 % ");
	 return false;}
   
   //make sure that all money is positive not negative	 
	if (fv < 0) {
		alert("The value for the balloon payment must not be less than $0");
	 return false;}
    
   if ((fv == 0) || (fv == "")) {
	  fv = -(pv*balper/100);
   }
   fv = parseFloat(fv);
   nper = parseFloat(nper);
   pv = parseFloat(pv);
   rate = parseFloat(rate);

   rate = eval((rate)/(12 * 100));
   if ( rate == 0 )    // Interest rate is 0
   {
       pmt_value = - (fv + pv)/nper;
   }
   else 
   {
       x = Math.pow(1 + rate,nper);
                   pmt_value = -((rate * (fv + x * pv))/(-1 + x));
   }
   pmt_value = conv_number(pmt_value,2);		
   formfield.repayment.value = pmt_value;
}

function conv_number(expr, decplaces) 
{       // This function is from David Goodman's Javascript Bible.	
     var str = "" + Math.round(eval(expr) * Math.pow(10,decplaces));
     while (str.length <= decplaces) {
           str = "0" + str;
     }
     var decpoint = str.length - decplaces;
     return (str.substring(0,decpoint) + "." + str.substring(decpoint,str.length));
}
// -->
</script>
            <table border=0 cellpadding=5 cellspacing=0 align="center" width="100%">
              <form name="formfield" id="formfield">
                <tr> 
                  <td> 
                    <p><b>Amount to be financed:</b></p>
                  </td>
                  <td width="10"> 
                    <p>$ </p>
                  </td>
                  <td> 
                    <input   type="Text" id="amount" value="<?php echo $price; ?>" name="amount" size="15">
                  </td>
                </tr>
                <tr> 
                  <td colspan="2" bgcolor="E9E9E9"> 
                    <p><b>Interest Rate:</b></p>
                  </td>
                  <td bgcolor="E9E9E9"> 
                    <select name="rate" size="1"  >
                      <option value="6.0">6.0 % </option>
                      <option value="6.25">6.25 %</option>
                      <option value="6.5">6.5 %</option>
                      <option value="6.75">6.75 %</option>
                      <option value="7.0">7.0 %</option>
                      <option value="7.25">7.25 %</option>
                      <option value="7.5">7.5 %</option>
                      <option value="7.75">7.75 %</option>
                      <option value="8.0">8.0 %</option>
                      <option value="8.25">8.25 %</option>
                      <option value="8.5">8.5 %</option>
                      <option value="8.75">8.75 %</option>
                      <option value="9.0">9.0 %</option>
                      <option value="9.25">9.25 %</option>
                      <option value="9.5">9.5 %</option>
                      <option value="9.75">9.75 %</option>
                      <option value="10.0" selected>10.0 %</option>
                      <option value="10.25">10.25 %</option>
                      <option value="10.5">10.5 %</option>
                      <option value="10.75">10.75 %</option>
                      <option value="11.0">11.0 %</option>
                      <option value="11.25">11.25 %</option>
                      <option value="11.5">11.5 %</option>
                      <option value="11.75">11.75 %</option>
                      <option value="12.0">12.0 %</option>
                      <option value="12.25">12.25 %</option>
                      <option value="12.5">12.5 %</option>
                      <option value="12.75">12.75 %</option>
                      <option value="13.0">13.0 %</option>
                      <option value="13.25">13.25 %</option>
                      <option value="13.5">13.5 %</option>
                      <option value="13.75">13.75 %</option>
                      <option value="14.0">14.0 %</option>
                      <option value="14.25">14.25 %</option>
                      <option value="14.5">14.5 %</option>
                      <option value="14.75">14.75 %</option>
                      <option value="15.0">15.0 %</option>
                      <option value="15.25">15.25 %</option>
                      <option value="15.5">15.5 %</option>
                      <option value="15.75">15.75 %</option>
                      <option value="16.0">16.0 %</option>
                      <option value="16.25">16.25 %</option>
                      <option value="16.5">16.5 %</option>
                      <option value="16.75">16.75 %</option>
                      <option value="17.0">17.0 %</option>
                      <option value="17.25">17.25 %</option>
                      <option value="17.5">17.5 %</option>
                      <option value="17.75">17.75 %</option>
                      <option value="18.0">18.0 %</option>
                      <option value="18.25">18.25 %</option>
                      <option value="18.5">18.5 %</option>
                      <option value="18.75">18.75 %</option>
                      <option value="19.0">19.0 %</option>
                      <option value="19.25">19.25 %</option>
                      <option value="19.5">19.5 %</option>
                      <option value="19.75">19.75 %</option>
                      <option value="20.0">20.0 %</option>
                      <option value="20.25">20.25 %</option>
                      <option value="20.5">20.5 %</option>
                      <option value="20.75">20.75 %</option>
                      <option value="21.0">21.0 %</option>
                      <option value="21.25">21.25 %</option>
                      <option value="21.5">21.5 %</option>
                      <option value="21.75">21.75 %</option>
                      <option value="22.0">22.0 %</option>
                      <option value="22.25">22.25 %</option>
                      <option value="22.5">22.5 %</option>
                      <option value="22.75">22.75 %</option>
                      <option value="23.0">23.0 %</option>
                      <option value="23.25">23.25 %</option>
                      <option value="23.5">23.5 %</option>
                      <option value="23.75">23.75 %</option>
                      <option value="24.0">24.0 %</option>
                      <option value="24.25">24.25 %</option>
                      <option value="24.5">24.5 %</option>
                      <option value="24.75">24.75 %</option>
                      <option value="25.0">25.0 %</option>
                      <option value="25.25">25.25 %</option>
                      <option value="25.5">25.5 %</option>
                      <option value="25.75">25.75 %</option>
                      <option value="26.0">26.0 %</option>
                      <option value="26.25">26.25 %</option>
                      <option value="26.5">26.5 %</option>
                      <option value="26.75">26.75 %</option>
                      <option value="27.0">27.0 %</option>
                      <option value="27.25">27.25 %</option>
                      <option value="27.5">27.5 %</option>
                      <option value="27.75">27.75 %</option>
                      <option value="28.0">28.0 %</option>
                      <option value="28.25">28.25 %</option>
                      <option value="28.5">28.5 %</option>
                      <option value="28.75">28.75 %</option>
                      <option value="29.0">29.0 %</option>
                      <option value="29.25">29.25 %</option>
                      <option value="29.5">29.5 %</option>
                      <option value="29.75">29.75 %</option>
                      <option value="30.0">30.0 %</option>
                    </select>
                  </td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <p><b>Term:</b></p>
                  </td>
                  <td> 
                    <select name="term" size="1"  >
                      <option value="12">1 Year 
                      <option value="24">2 Years 
                      <option value="36">3 Years 
                      <option value="48">4 Years 
                      <option value="60" selected>5 Years 
                      <option value="72">6 Year 
                      <option value="84">7 Years 
                      <option value="96">8 Years 
                      <option value="108">9 Years 
                      <option value="120">10 Years 
                      <option value="132">11 Year 
                      <option value="144">12 Years 
                      <option value="156">13 Years 
                      <option value="168">14 Years 
                      <option value="180">15 Years 
                      <option value="192">16 Year 
                      <option value="204">17 Years 
                      <option value="216">18 Years 
                      <option value="228">19 Years 
                      <option value="240">20 Years 
                      <option value="252">21 Year 
                      <option value="264">22 Years 
                      <option value="276">23 Years 
                      <option value="288">24 Years 
                      <option value="300">25 Years 
                    </select>
                  </td>
                </tr>
                <tr> 
                  <td colspan="2" bgcolor="E9E9E9" valign="top"> 
                    <p><b>Balloon Final Payment:</b></p>
                  </td>
                  <td bgcolor="E9E9E9"> 
                    <p>$ 
                      <input   type="Text" name="balloon" size="7">
                      <br>
                      or<br>
                      % 
                      <input   type="Text" name="balper" size="5" maxlength="3" value="40">
                    </p>
                  </td>
                </tr>
                <tr> 
                  <td align="center" colspan="3"><input   type="reset" value="Clear Form" name="reset">&nbsp;&nbsp;<input   type="button" name="Submit" value="Calculate" onClick="pmt(formfield);"></td>
                </tr>
                <tr> 
                  <td colspan="2" bgcolor="E9E9E9"> 
                    <p><b>Monthly Amount Payable:</b></p>
                  </td>
                  <td bgcolor="E9E9E9"> 
                    <p>$ 
                      <input   type="Text" name="repayment" size="10">
                    </p>
                  </td>
                </tr>
              </form>
            </table>
          </td>
        </tr>
      </table>
 
</body>
</html>