function compare() {
    if(document.getElementById('in3').value > document.getElementById('in4').value) {
        alertify.alert("Error","Date debut is greater than date fin",function(){ alertify.error("Something went wrong"); })
        return false;
    }
    if((document.getElementById('in7').value == 0) || (document.getElementById('in7').value >100))  {
        alertify.alert("Error","The price must be greater than 0 and less than 100",function(){ alertify.error("Something went wrong"); })
        return false;
    }
    return true;
}