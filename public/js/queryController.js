loadXMLDoc=function()
{
var xmlhttp;
attr=getAttr();
if(attr["DBname"] == ""||attr["DBtype"] == ""||attr["Tname"] == ""){
   alert("填写必要参数才能运行");
   return 1;
}

if (window.XMLHttpRequest)
{
/*IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码*/
xmlhttp=new XMLHttpRequest();
}
else
{
/*IE6, IE5 浏览器执行代码*/
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function()
{
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
  {
     var json=JSON.parse(xmlhttp.responseText);
     document.getElementById("sql_text").innerHTML=eval('(' + json + ')').data;
      }
 }
 xmlhttp.open("POST","/api/t_etl_query/get_sql",true);
 xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
 attr=getAttr();
 if(!attr["is_increase"]){
     IncreaseColumn='';
 }
 console.log("DBname="+attr["DBname"]+"&Dbtype="+attr["Dbtype"]+"&Tname="+attr["Tname"]+"&IncreaseColumn="+attr["IncreaseColumn"]+"&modified="+attr["modified"]);
 xmlhttp.send("DBname="+attr["DBname"]+"&Dbtype="+attr["Dbtype"]+"&Tname="+attr["Tname"]+"&IncreaseColumn="+attr["IncreaseColumn"]);
 }
 /*发送测试信息*/
 postTest=function(){
      console.log(getAttr());
      result=loadXMLDoc();
      if(result==1){
              return 'error';
      }
      Attr=getAttr();
      var xmlhttp;
      if (window.XMLHttpRequest)
      {
            /* IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码*/
            xmlhttp=new XMLHttpRequest();
       }
       else
{
/* IE6, IE5 浏览器执行代码 */
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function()
{
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
  {
     var json=JSON.parse(xmlhttp.responseText);
     console.log(eval('(' + json + ')').data);
      }
 }
 xmlhttp.open("POST","/api/t_etl_query/do_test",true);
 xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
 console.log("DBname="+attr["DBname"]+"&Dbtype="+attr["Dbtype"]+"&Tname="+attr["Tname"]+"&IncreaseColumn="+attr["IncreaseColumn"]+"&sql_text="+attr["sql_text"]);
 xmlhttp.send("DBname="+attr["DBname"]+"&Dbtype="+attr["Dbtype"]+"&Tname="+attr["Tname"]+"&IncreaseColumn="+attr["IncreaseColumn"]+"&sql_text="+attr["sql_text"]);
 return 'sucess';
}
 getAttr =function(){
       var obj=new Array();
       obj["theme_name"]=$("#theme_name").val();
       obj["DBname"]=$("#DBname").val();
       obj["Dbtype"]=$("#Dbtype").val();
       obj["Tname"]=$("#Tname").val();
       obj["IncreaseColumn"]=$("#increaseColumn").val();
       obj["is_increase"]=$("#is_increase").bootstrapSwitch('state');
       if(!obj["is_increase"]){
             obj["IncreaseColumn"]='';
       }
       obj["is_on"]=$("#is_on").bootstrapSwitch('state');
       obj["sql_text"]=$("#sql_text").val();
       var url=window.location.href;
       if(url.search("edit")!=-1){
              obj["modified"]='1';
       }
       else{
              obj["modified"]='0';
       }
       return  obj;
 }
  /*创建query*/
  postCreate = function(){
      console.log(getAttr());
      /*测试query生成
 *       var result=postTest();
 *             alert(result);
 *                   if(result != 'success'){
 *                              alert("测试失败"+result);
 *                                         return ;
 *                                               }*/
      attr=getAttr();
      var xmlhttp;
      if (window.XMLHttpRequest)
      {
            /* IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码 */
            xmlhttp=new XMLHttpRequest();
       }
       else
       {
            /* IE6, IE5 浏览器执行代码  */
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
       }
       xmlhttp.onreadystatechange=function()
       {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                 var json=JSON.parse(xmlhttp.responseText);
                 console.log(eval('(' + json + ')').data);
             }
         }
        xmlhttp.open("POST","/api/t_etl_query/do_create",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        console.log("DBname="+attr["DBname"]+"&Dbtype="+attr["Dbtype"]+"&Tname="+attr["Tname"]+"&IncreaseColumn="+attr["IncreaseColumn"]+"&sql_text="+attr["sql_text"]+"&theme_name="+attr["theme_name"]);
        xmlhttp.send("DBname="+attr["DBname"]+"&Dbtype="+attr["Dbtype"]+"&Tname="+attr["Tname"]+"&sql_text="+attr["sql_text"]+"&theme_name="+attr["theme_name"]+"&modified="+attr["modified"]);
  }

