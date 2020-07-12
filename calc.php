

<?php
	include('../../mainfile.php');
	include("include/common.php");
	global $conn, $config, $lang;
include("$config[template_path]/user_top.html");	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head lang="en-us" dir="LTR">
	<title>Amortization Calculator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript">
		function getAmortization(a,n,p)
		{
			if (document.getElementById('terminY').checked)
			{
				n = n * 12;
			}

			var i=0;
			var sATline="";
			var oAmortizationTable=document.getElementById("amortizationtable");
			oAmortizationTable.style.visibility="visible";

			/* Calculate amortization and write table to text area **/
			var payment = getPayment(a,n,p);
			oAmortizationTable.value = "Your monthly payment for a " + n + " month loan at " + p + "% would be " + (Math.round(payment*100)/100);
		}

	function getPayment(a,n,p) {
		/* Calculates the monthly payment from annual percentage
		   rate, term of loan in months and loan amount. **/
		var acc=0;
		var base = 1 + p/1200;
		for (i=1;i<=n;i++)
			{ acc += Math.pow(base,-i); }
		return a/acc;
	}
	function CheckYears()
	{
		document.getElementById('terminM').checked = "";
	}
	function CheckMonths()
	{
		document.getElementById('terminY').checked = "";
	}
	</script>
</head>

<body>
		<table cellpadding=2 style="border:2px outset;background-color:silver;font-size:smaller;">
			<tr>	<td>	Loan Amount</td>
				<td>	<input id=amount name=amount type=text value="<?php echo $price; ?>" size=10></td>
			</tr>
			<tr> <td> Down Payment</td>
				<td> <input id=downpayment name=downpayment type=text size=10></td>
			</tr>
			<tr>	<td>	Interest Rate (APR)</td>
				<td>	<input id=apr name=apr type=text value=6.0 size=10><td>
			</tr>
			<tr>	<td>	Term </td>
				<td>
				<INPUT TYPE=RADIO NAME="terminM" id="terminM" CHECKED onclick="CheckMonths()">Months<BR>
				<INPUT TYPE=RADIO NAME="terminY" id="terminY" onclick="CheckYears()">Years<BR>
				<input id=term name=term type=text value=120 size=10><td>
			</tr>
			<tr>	<td align=right colspan=2>
					<button onclick="getAmortization(document.getElementById('amount').value-document.getElementById('downpayment').value, document.getElementById('term').value,document.getElementById('apr').value)" style="height: 25px; width: 100px;">Calculate</button>
				</td>
			</tr>
		</table>
	<p>
		<textarea id="amortizationtable" rows="16" cols="40"  style="visibility:hidden;background-color:silver"></textarea>
	</p>
</body>

</html>
