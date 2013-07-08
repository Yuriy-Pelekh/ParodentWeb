var xmlhttp

function changeState(id, checked, table){
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null){
        alert ("Your browser does not support XMLHTTP!");
        return;
    }
    var url="checked.php";
    url=url+"?id="+id+"&checked="+checked+"&table="+table;
    url=url+"&sid="+Math.random();
//    xmlhttp.onreadystatechange=stateChanged2;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
}

//function stateChanged2(){
//    if (xmlhttp.readyState==4){
//        document.getElementById("txtList").innerHTML=xmlhttp.responseText;
//    }
//}

function GetXmlHttpObject(){
    if (window.XMLHttpRequest){
// code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    }
    if (window.ActiveXObject){
// code for IE6, IE5
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
    return null;
}