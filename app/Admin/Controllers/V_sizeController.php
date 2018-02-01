<?php

namespace App\Admin\Controllers;

use App\Models\V_size;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class V_sizeController extends Controller
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
            $content->description('查看logs的size(MB)，N表示今天，N-1表示前一天，以此类推');

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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(V_size::class, function (Grid $grid) {

            $grid->etl_theme();
			$grid->etl_db();
			$grid->etl_table();
			
		/*
			$ave=0;
			$grid->actions(function ($actions) {
			$actions->disableDelete();
            $actions->disableEdit();
			$n=$actions->row->N;
			$n1=$actions->row->N_minus1;
			$n2=$actions->row->N_minus2;
			$n3=$actions->row->N_minus3;
			$n4=$actions->row->N_minus4;
			$n5=$actions->row->N_minus5;
			$n6=$actions->row->N_minus6;
			//if($n==$n1) $actions->row->style('color:red');
			//dd($actions->row);
			});
			//dd($ave);
		*/
			$grid->rows(function($row){
				//dd($row);
				$n=$row->N;
				$n1=$row->N_minus1;
				$n2=$row->N_minus2;
				$n3=$row->N_minus3;
				$n4=$row->N_minus4;
				$n5=$row->N_minus5;
				$n6=$row->N_minus6;
				$ave1=($n1+$n2+$n3+$n4+$n5+$n6)*0.2/6;
				$ave2=($n1+$n2+$n3+$n4+$n5+$n6)*2/6;
				//if($n!=0&&$n1!=0&&$n2!=0&&$n3!=0&&$n4!=0&&$n5!=0&&$n6!=0)
				  if($n<=$ave1||$n>=$ave2) 
					  $row->style('color:red');
		    });
		
		/*
			$grid->N()->sortable()->display(function ($N){
				$lableColor = 'success';
				$ave=$actions->row->N_minus1;
				if ($N == $ave)
				$lableColor = 'warning';
                   return "<span class='label label-{$lableColor}'>".$N."</span>";
				});
			
			/*
			 $grid->actions(function ($actions) {
				$actions->disableDelete();
				$actions->disableEdit();
			});
			*/
			
			
			$grid->N('N(今日)')->sortable();
			$grid->N_minus1('N-1')->sortable();
			$grid->N_minus2('N-2')->sortable();
			$grid->N_minus3('N-3')->sortable();
			$grid->N_minus4('N-4')->sortable();
			$grid->N_minus5('N-5')->sortable();
			$grid->N_minus6('N-6')->sortable();
			$grid->disableExport();
			$grid->disableActions();
			$grid->disableRowSelector();
			$grid->disableCreation();
			
			$grid->filter(function ($filter) {
				 $filter->disableIdFilter();
                // 设置created_at字段的范围查询
                //$filter->like('etl_theme','etl_theme');
				$filter->like('etl_theme','etl_theme');
				$filter->like('etl_db','etl_db');
				$filter->like('etl_table','etl_table');
				$filter->between('N','当日size范围');
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
        return Admin::form(V_size::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
