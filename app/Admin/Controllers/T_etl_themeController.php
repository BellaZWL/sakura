<?php

namespace App\Admin\Controllers;

use App\Models\T_etl_theme;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use \Curl\Curl;

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

            $content->body($this->form2()->edit($theme_id));
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

                       // $grid->theme_id()->sortable();
			$grid->theme_name('抽数主题')->sortable();
			$grid->description('描述');
			$grid->disableExport();
			$grid->disableRowSelector();
			$grid->filter(function ($filter) {
			$filter->disableIdFilter();
            $filter->like('theme_name','抽数主题');
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
			 
			$form->text('theme_name', 'Theme_name')->help("抽数主题只能新建不能删除，请谨慎操作。");
			$form->textarea('description','Description');
                        $form->saving(function (Form $form) {
                            $theme=dump($form->theme_name);
                        });
                        $form->disableReset();
  
                      $form->saving(function (Form $form) {
                            $test=dump($form->theme_name);
                            /*$curl= new Curl();
			    $url=$curl->get('http://127.0.0.1:5000/api/create_theme?theme=bids2');
                            $curl->get('http://127.0.0.1:5000/api/create_theme?theme='+$test);
                            return curl_getinfo($url);
                            */
                            $url = "http://127.0.0.1:5000/api/create_theme?theme=$test";
                            $res = curl_init();
                            curl_setopt($res,CURLOPT_URL,$url);
                            $result = curl_exec($res);
                            curl_close($res);
                            //return response()->json($result);
                            return 'ok';
                         });
 
                        //$form->display('theme_name', 'Theme_name');//只是显示
			//$form->display('description','Description');
            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
    protected function form2()
    {
        return Admin::form(T_etl_theme::class, function (Form $form) {

                        $form->display('theme_name', 'Theme_name');
                        $form->textarea('description','Description');
                        $form->disableReset();
          });
      }
}
