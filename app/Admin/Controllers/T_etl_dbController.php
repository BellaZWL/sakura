<?php

namespace App\Admin\Controllers;

use App\Models\T_etl_db;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class T_etl_dbController extends Controller
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

            $content->header('数据源配置管理');
            $content->description('可以新增，删除和修改');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($DBid)
    {
        return Admin::content(function (Content $content) use ($DBid) {

            $content->header('数据源配置管理');
            $content->description('修改数据源配置');

            $content->body($this->form()->edit($DBid));
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

            $content->header('数据源配置管理');
            $content->description('新增数据源配置,插入数据库时DB和DBname插入值一样');

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
        return Admin::grid(T_etl_db::class, function (Grid $grid) {

            //$grid->DBid('DB_ID')->sortable();
			$grid->DB('数据库')->sortable();
                        $grid->DBname('数据库原名')->sortable();
			$grid->Dbtype('数据库类型')->sortable();
			$grid->server('服务器');
			$grid->port('端口');
			$grid->UserName('用户名');
			$grid->precommand('字符编码');
			$grid->disableExport();
			$grid->disableRowSelector();
			$grid->filter(function ($filter) {
				$filter->disableIdFilter();
                                $filter->like('DB','数据库');
				$filter->like('Dbtype','数据库类型');
            });
           // $grid->created_at();
           //$grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(T_etl_db::class, function (Form $form) {

            //$form->display('DBid', '数据源ID');
                        //$form->text('DB', 'DB');
                        
                        $form->hidden('DB');
                        $form->html('<input type="text" name="DB" id="DB" hidden>');
			$form->text('DBname','数据库名')->attribute('id','DBname');	
                        $form->select('Dbtype','数据库类型')->options(["postgresql"=>"postgresql","mongodb"=>"mongodb","mysql"=>"mysql"]);
			$form->text('server','服务器');
			$form->text('port','端口');
			$form->text('UserName','用户名');
			$form->text('Password','密码');
			$form->text('precommand','字符编码');
                        $script = <<<SCRIPT
                            $("#DBname").change(function(){
  	                        console.log($("#DBname").val());
                                $("#DB").val($("#DBname").val());
});
SCRIPT;
                        Admin::script($script);
			
            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
