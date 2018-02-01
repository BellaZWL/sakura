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
			$grid->DB('DB_name')->sortable();
			$grid->Dbtype();
			$grid->server('server_ip');
			$grid->port();
			$grid->UserName();
			$grid->Password();
			$grid->precommand();
			$grid->disableExport();
			$grid->disableRowSelector();
			$grid->filter(function ($filter) {
				$filter->disableIdFilter();
                $filter->like('DB','DB_name');
				$filter->like('Dbtype','Dbtype');
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
            $form->text('DB', 'DB');
			$form->text('DBname','DBname');
			$form->text('Dbtype','Dbtype');
			$form->text('server','server_ip');
			$form->text('port','port');
			$form->text('UserName','UserName');
			$form->text('Password','Password');
			$form->text('precommand','precommand');
			
            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
