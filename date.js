// Mask the passed field to mm/dd/yyyy
function dateMask(f, event, nextfield) {

	var cursorKeys ="16;17;8;46;37;38;39;40;33;34;35;36;45;";
	if (cursorKeys.indexOf(event.keyCode+";") != -1) return;

	var m0 = new RegExp("^t$");
	var m1 = new RegExp("(^[2-9]$)|(^1\/$)");
	var m2 = new RegExp("^[1-2][3-9]$");
	var m3 = new RegExp("(^[0-1][0-9]$)|(^[1-9]\/$)");
	var m4 = new RegExp("(^[0-1][0-9]\/[4-9]$)|(^[0-1][0-9]\/[1-9]\/$)");
	var m5 = new RegExp("^[0-1][0-9]\/[4-9][0-9]$");
	var m6 = new RegExp("^[0-1][0-9]\/[3-9][2-9]$");
	var m7 = new RegExp("^[0-1][0-9]\/[0-3][0-9]$");
	var m8 = new RegExp("^[0-1][0-9]\/[0-3][0-9]\/[5-9][0-9]$");
	var m9 = new RegExp("^[0-1][0-9]\/[0-3][0-9]\/[03-5][0-9]$");

	if (m0.test(f.value)) {
		var time = new Date();
		var month = time.getMonth() + 1;
		if (month < 10) month = "0" + month.toString();
		f.value= month + "/" + time.getDate() + "/" + time.getFullYear();
	}

	if (m1.test(f.value)) {
		// Check for Mar-Dec date
		f.value = "0" + f.value.substring(0,1) + "/";
	} else if (m2.test(f.value)) {
		// Check for invalid month
		f.value = "1";
	} else if (m3.test(f.value)) {
		// If valid month, advance
		f.value += "/";
	} else if (m4.test(f.value)) {
		// Check for day < 10
		f.value = f.value.substring(0,3) + "0" + f.value.substring(3) + "/";
	} else if (m5.test(f.value)) {
		// Check for invalid day
		f.value = f.value.substring(0,3);
	} else if (m6.test(f.value)) {
		// Verify date < 32
		f.value = f.value.substring(0,4);
	} else if (m7.test(f.value)) {
		f.value += "/";
	} else if (m8.test(f.value)) {
		// Check for post 50-99
		f.value = f.value.substring(0,6) + "19" + f.value.substring(6);
	} else if (m9.test(f.value)) {
		// Check for post 50-99
		f.value = f.value.substring(0,6) + "20" + f.value.substring(6);
	}

	if (f.value.substring(f.value.length - 2) == "//") {
		f.value = f.value.substring(0, f.value.length - 1);
	}

	return true;
}
