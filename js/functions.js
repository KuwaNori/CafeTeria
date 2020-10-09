document.getElementById('uploading').style.display = "none";
document.getElementById('Today').style.display ="block";
document.getElementById('Add').style.display ="none";
document.getElementById('List').style.display ="none";
document.getElementById('Select').style.display ="none";
var checks = document.getElementsByClassName('checkbox');
var popups = document.getElementsByClassName('popup');
var dels= document.getElementsByClassName("delc");
var btns= document.getElementsByClassName("btn2c");
for (var i= 0; i< dels.length; i++) {
  dels[i].style.display ="none";
}
for (var i= 0; i< popups.length; i++) {
  popups[i].style.display ="none";
}
// SPA
function ChangeMain(a){
  if(a == 'Today'){
    document.getElementById('Today').style.display ="block";
    document.getElementById('Add').style.display ="none";
    document.getElementById('List').style.display ="none";
    document.getElementById('Select').style.display ="none";
  } else if (a == 'Add') {
    document.getElementById('Today').style.display ="none";
    document.getElementById('Add').style.display ="block";
    document.getElementById('List').style.display ="none";
    document.getElementById('Select').style.display ="none";
  } else if (a == 'List') {
    document.getElementById('Today').style.display ="none";
    document.getElementById('Add').style.display ="none";
    document.getElementById('List').style.display ="block";
    document.getElementById('Select').style.display ="none";
  } else {
    document.getElementById('Today').style.display ="none";
    document.getElementById('Add').style.display ="none";
    document.getElementById('List').style.display ="none";
    document.getElementById('Select').style.display ="block";
  }
}
// delete button
function showdel(a){
  for (var i= 0; i< dels.length; i++) {
    if (a==i){
      if (dels[i].style.display == "block"){
        dels[i].style.display ="none";
      } else {
        dels[i].style.display ="block";
      }
    }
  }
}
// pop up
function pop(b){
  for (var t=0; t < popups.length; t++){
    document.getElementById("bg").className="bgd";
    if (b==t){
      popups[t].style.display ="block";
    }
  }
}
// closing pop up
function pclose(){
  document.getElementById("bg").className="bga";
  for (var i= 0; i< popups.length; i++) {
    popups[i].style.display ="none";
  }
}
// confirm information
function com(){
  if(confirm("本当に削除しますか？")){
    return true;
  } else {
    return false;
  }
}
//   display for uploading
function showup(){
  var uploading = document.getElementById('uploading');
  if (uploading.style.display=="none"){
    uploading.style.display="block";
  } else {
    uploading.style.display="none";
  }
}
// preview image
$('#choose').on('change', function (e) {
    var reader = new FileReader();
    reader.onload = function (e) {
        $("#preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});
