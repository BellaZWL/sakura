<?php

namespace App\Admin\Controllers;

use App\Models\T_etl_theme;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class T_etl_themeController extends Controller
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

            $content->header('主题管理');
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
    public function edit($theme_id)
    {
        return Admin::content(function (Content $content) use ($theme_id) {

            $content->header('主题管理');
            $content->description('修改');

            $content->body($this->form()->edit($theme_id));
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

            $content->header('主题管理');
            $content->description('新建一个不存在的主题，如果已存在则给出提示');

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
        return Admin::grid(T_etl_theme::class, function (Grid $grid) {

            $grid->theme_id()->sortable();
			$grid->theme_name();
			$grid->description();
			$grid->disableExport();
			$grid->disableRowSelector();
			$grid->filter(function ($filter) {
			$filter->disableIdFilter();
            $filter->like('theme_name','theme_name');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(T_etl_theme::class, function (Form $form) {
			 
			$form->text('theme_name', 'Theme_name');
			$form->textarea('description','Description');

            //$form->display('theme_name', 'Theme_name');//只是显示
			//$form->display('description','Description');
            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
