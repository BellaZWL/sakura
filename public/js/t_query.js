create_panel=function(data,type)
{
   $("#do_type").removeClass('panel-info');
   $("#do_type").removeClass('panel-danger');
   $("#do_type").removeClass('panel-success');
   $("#do_type").addClass(type);
   $("#do_result").html(data);
   return 0;
}
loadXMLDoc=function()
{
   var xmlhttp;
   attr=getAttr();
   if(attr["DBname"] == ""||attr["DBtype"] == ""||attr["Tname"] == ""){
      alert("填写必要参数才能运行");
      return 1;
   }
   create_panel('正在飞速运行中……','panel-info');
   if (window.XMLHttpRequest)
   {
       /* IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码*/
       xmlhttp=new XMLHttpRequest();
   }
   else
   {
       /*IE6, IE5 浏览器执行代码*/
       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function()
   {
       var type='panel-danger';
       if (xmlhttp.readyState==4 && xmlhttp.status==200)
       {
           var json=JSON.parse(xmlhttp.responseText);
           $("#sql_text").val(eval('(' + json + ')').data);
           /*提示信息*/
           result=eval('(' + json + ')').log;
           console.log(result);
           if (result.indexOf("Finished!") >= 0){
                 type='panel-success';
                         /*运行正确，保存表单*/
                 $("form").submit(function(e){
  alert("Submitted");
                         });
                     }
                     else
                     {

                         type='panel-danger';
                     }

                     create_panel(result.replace(/\\n/ig, '<br/>'),type);

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
        if($("#postTest").attr("disabled"))
         {
             alert("正在飞速运行中……");
             return 1;
         }

        attr=getAttr();
        if(attr["DBname"] == ""||attr["DBtype"] == ""||attr["Tname"] == ""){
             alert("填写必要参数才能运行");
             return 1;
          }

         create_panel('正在飞速运行中……','panel-info');
         console.log(attr);
         /*result=loadXMLDoc();
 *          if(result==1){
 *                           return 'error';
 *                                    }*/

         var xmlhttp;
         if (window.XMLHttpRequest)
         {
               /* IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码*/
               xmlhttp=new XMLHttpRequest();
          }
   else
   {
   /* IE6, IE5 浏览器执行代码*/
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function()
   {
     $("#postTest").attr('disabled',true);
     if (xmlhttp.readyState==4){
          $("#postTest").attr('disabled',false);
     }

     if (xmlhttp.readyState==4 && xmlhttp.status==200)
     {
        var json=JSON.parse(xmlhttp.responseText);
        var type='panel-danger';
        /*提示信息*/
        result=eval('(' + json + ')').data;
        console.log(result);
        if (result.indexOf("Finished!") >= 0){
            $("#postCreate").attr('disabled',false);
            type='panel-success'
        }
        else
        {
            $("#postCreate").attr('disabled',true);
            type='panel-danger';
        }
           create_panel(result.replace(/\\n/ig, '<br/>'),type);
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
                obj["is_increase"]='0';
          }
          else
          {   obj["is_increase"]='1'; }
          obj["is_on"]=$("#is_on").bootstrapSwitch('state');
          if(!obj["is_on"]){
              obj["is_on"]='0';
          }
          else{
              obj["is_on"]='1';
          }
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
         if($("#postCreate").attr("disabled"))
         {
             alert("请先通过测试");
             return 1;
         }

         create_panel('正在飞速运行中……','panel-info');
         console.log(getAttr());
         attr=getAttr();

         var xmlhttp;
         if (window.XMLHttpRequest)
         {
               /* IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码 */
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
                     var type='panel-danger'
                     /* 提示信息 */
                     result=eval('(' + json + ')').data;
                     console.log(result);
                     if (result.indexOf("Finished!") >= 0){
                         type='panel-success';
                         /* 运行正确，保存表单*/
                         $("form").submit(function(e){
  alert("Submitted");
                         });
                     }
                     else
                     {
                         type='panel-danger';
                     }

                     create_panel(result.replace(/\\n/ig, '<br/>'),type);
                }




            }
           xmlhttp.open("POST","/api/t_etl_query/do_create",true);
           xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
           console.log("DBname="+attr["DBname"]+"&Dbtype="+attr["Dbtype"]+"&Tname="+attr["Tname"]+"&IncreaseColumn="+attr["IncreaseColumn"]+"&sql_text="+attr["sql_text"]+"&theme_name="+attr["theme_name"]);
           xmlhttp.send("DBname="+attr["DBname"]+"&Dbtype="+attr["Dbtype"]+"&Tname="+attr["Tname"]+"&sql_text="+attr["sql_text"]+"&theme_name="+attr["theme_name"]+"&modified="+attr["modified"]+"&is_on="+attr["is_on"]+"&is_increase="+attr["is_increase"]+"&IncreaseColumn="+attr["IncreaseColumn"]);
     }
     /*创建QUERY*/

     /*改变状态*/
      $("#sql_text").change(function(){
          console.log($("#sql_text").val());
          $("#postCreate").attr('disabled',true);
      });

