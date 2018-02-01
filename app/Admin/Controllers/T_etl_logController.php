<?php

namespace App\Admin\Controllers;

use App\Models\T_etl_log;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Tab;

class T_etl_logController extends Controller
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

            $content->header('logs查看');
            $content->description('显示每条query7日内的数据，可以按size或者rows排序');

            $content->body($this->grid());
        });
    }
	
    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    
	public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('logs查看');
            $content->description('编辑');

            $content->body($this->form()->edit($id));
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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(T_etl_log::class, function (Grid $grid) {
			//$grid->model()
			//$grid->model()->select('etl_theme','etl_db','etl_table','snapshot_date','row_count','size');
			//$grid->model()->whereBetween('snapshot_date',[CURRENT_DATE-6,CURRENT_DATE]);
			//$grid->model()->where('snapshot_date','<','CURRENT_DATE+1');
			//$grid->model()->groupBy('etl_theme','etl_db','etl_table','snapshot_date','row_count','size');
			//$grid->model()->orderBy('snapshot_date');
			
			$grid->etl_theme();
			$grid->etl_db();
			$grid->etl_table();
			$grid->snapshot_date();
			$grid->row_count();
			$grid->size();
			
			//$grid->disableCreateButton();
			$grid->disableRowSelector();
			$grid->disableActions();
			
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(T_etl_log::class, function (Form $form) {

            $form->display('id', 'ID');
			$form->display('etl_theme');
		    $form->display("etl_db");
			$form->display("etl_table");
			$form->display("snapshot_date");
			$form->display("row_count");
			$form->display("size");
            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
