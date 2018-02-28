<?php

namespace App\Admin\Controllers;

use App\Models\T_etl_query;
use App\Models\T_etl_db;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use \Curl\Curl;

class T_etl_queryController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('脚本管理');
            $content->description('可以新增，删除和修改，以及测试与拉数');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($query_id)
    {
        return Admin::content(function (Content $content) use ($query_id) {

            $content->header('脚本管理');
            $content->description('修改');

            $content->body($this->form()->edit($query_id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('脚本管理');
            $content->description('新建一个不存在的脚本，如果已存在则给出提示');

            $content->body($this->form());
        });
    }
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(T_etl_query::class, function (Grid $grid) {

			$states = [
                             'on'  => ['value' => 1, 'text' => 'on', 'color' => 'primary'],
                             'off' => ['value' => 0, 'text' => 'off', 'color' => 'default'],
                        ];
                        $grid->Tname('数据表')->sortable();
                        $grid->theme_name('抽数主题')->sortable();
                        $grid->DBname('数据库')->sortable();
                        /*$grid->is_increase('是否增量')->display(function($text) {
                             if($this->is_increase = 1){
                                 return '是';
                             }
                             else{
                                 return '否';
                             } 
                        });*/
                        $grid->is_increase('是否增量')->switch($states);
                        $grid->increaseColumn('增量字段');
                        $grid->created_time('创建时间')->sortable();
                        $grid->modified_time('修改时间')->sortable();
                        /*$grid->is_on('是否启用')->display(function($text){
                            if($this->is_on =1){
                                return '是';
                            }
                            else{
                                 return '否';
                            }
                        });*/
                        $grid->is_on('是否启用')->switch($states);
                        $grid->disableExport();
			$grid->disableRowSelector();
                        $grid->actions(function ($actions) {
                            $id = $actions->row->query_id;
                            $actions->append("<a href='#' onclick='onClickRun({$id})' class='fa fa-play'></a>");
                        });

                        $grid->filter(function ($filter) {
			    $filter->disableIdFilter();
                            $filter->like('Tname','表名');
                        });

                        $script = <<<SCRIPT
                        
                        onClickRun=function(queryid){
                                    alert(queryid);
                                    var xmlhttp;
                                    if (window.XMLHttpRequest)
                                    {
                                          // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
                                          xmlhttp=new XMLHttpRequest();
                                     }
                                     else
                                     {
                                          // IE6, IE5 浏览器执行代码
                                          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                                     }
                                     xmlhttp.onreadystatechange=function()
                                     {
                                          if (xmlhttp.readyState==4 && xmlhttp.status==200)
                                          {
                                                var json=JSON.parse(xmlhttp.responseText);
                                            
                                                //提示信息
                                                result=eval('(' + json + ')').data;
                                                console.log(result);
                                                alert(result);
                                                //alert(result.replace(/\\n/ig, '<br/>'));
                                           }

                                       }
                                      xmlhttp.open("POST","/api/t_etl_query/test_run",true);
                                      xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                                      xmlhttp.send("queryid="+queryid);
                                }
  
SCRIPT;
                        Admin::script($script);

        });


    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(T_etl_query::class, function (Form $form) {
		    $form->disableReset();
                    $form->disableSubmit();
                    	if (request()->route()->getActionMethod() == 'edit') {
                            $form->text('theme_name','抽数主题')->attribute('id','theme_name');
                            $form->text('DBname','数据库')->attribute('id','DBname');
                            $form->text('Dbtype','数据类型')->attribute('id','Dbtype');
                        }
                        else {
                            $form->select('theme_name','抽数主题')->options('/api/t_etl_theme')->attribute('id','theme_name');
                            $form->select('DBname','数据库')->options('/api/t_etl_db')->load('Dbtype','/api/t_etl_db/dbtype')->attribute('id','DBname');
                            $form->select('Dbtype','数据类型')->attribute('id','Dbtype');
                        }
                        $form->text('Tname','数据表')->attribute('id','Tname');
                        $form->switch('is_increase', '增量更新？')->attribute('id','is_increase'); 
                        $form->text('increaseColumn','增量字段')->attribute('id','increaseColumn');   
                        $form->switch('is_on', '立即启用？')->attribute('id','is_on');
                        $form->textarea('sql_text','SQL')->help("SQL中包含过滤条件，建议不要修改，mongo数据默认输出所有字段，SQL只需填写过滤条件")->attribute('id','sql_text')->rows(18);
                        //$form->hidden('modified');
			$form->html('
<div class="row container-fluid"><div class="col-md-4">
  <a class="btn btn-danger center-block" onclick="loadXMLDoc() " >
    <i class="fa fa-add"></i>&nbsp;&nbsp;生成SQL
  </a>
</div>
<div class="col-md-4"> 
  <a class="btn btn-warning center-block" onclick="postTest() " id="postTest" >
    <i class="fa fa-add"></i>&nbsp;&nbsp;生成测试</a>
</div>
<div class="col-md-4">
  <a class="btn btn-success center-block" onclick="postCreate() " id="postCreate" disabled >&nbsp;&nbsp;保存配置
  </a>
</div></div><br/><br/>');
$form->html('<div class="row center-block container-fluid"  ><div class="panel panel-info" id="do_type"><div class="panel-heading">提示</div><div class="panel-body"><p id="do_result"></p></div></div></div>');

                        $script = <<<SCRIPT
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
                                  // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
                                  xmlhttp=new XMLHttpRequest();
                              }
                              else
                              {
                                  // IE6, IE5 浏览器执行代码
                                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                              } 
                              xmlhttp.onreadystatechange=function()
                              {
                                  var type='panel-danger';
                                  if (xmlhttp.readyState==4 && xmlhttp.status==200)
                                  {
                                      var json=JSON.parse(xmlhttp.responseText);
                                      $("#sql_text").val(eval('(' + json + ')').data);
                                      //提示信息
                                      result=eval('(' + json + ')').log;
                                      console.log(result);
                                      if (result.indexOf("Finished!") >= 0){
                                            type='panel-success';
                                                    //运行正确，保存表单
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
                                    if(result==1){
                                            return 'error';
                                    }*/
                                    
                                    var xmlhttp;
                                    if (window.XMLHttpRequest)
                                    {
                                          // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
                                          xmlhttp=new XMLHttpRequest();
                                     }
                              else
                              {
                              // IE6, IE5 浏览器执行代码
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
                                   //提示信息
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
                                          // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
                                          xmlhttp=new XMLHttpRequest();
                                     }
                                     else
                                     {
                                          // IE6, IE5 浏览器执行代码
                                          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                                     } 
                                     xmlhttp.onreadystatechange=function()
                                     {
                                          if (xmlhttp.readyState==4 && xmlhttp.status==200)
                                          {
                                                var json=JSON.parse(xmlhttp.responseText);
						var type='panel-danger'
                                                //提示信息
                                                result=eval('(' + json + ')').data;
                                                console.log(result);
                                                if (result.indexOf("Finished!") >= 0){
                                                    type='panel-success';
                                                    //运行正确，保存表单
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

SCRIPT;
                        Admin::script($script);  
            
        });
    }
}
