<?php

namespace App\Admin\Controllers;

use App\Models\CourseType;
use App\Models\User;
use App\Models\Course;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class CourseController extends AdminController
{
    protected function grid()
    {
        $grid = new Grid(new Course());


            $grid->column('id', __('Id'));
            $grid->column('user_token', __('Teacher'))->display(function ($token) {
                $item = DB::table('admin_users')->where('token', '=', $token)->value('username');
                return $item;

            });

        if (Admin::user()->isRole('teacher')) {
            $token = Admin::user()->token;
            $ids = DB::table('courses')->where('user_token', '=', $token)->pluck('id');
            $grid->model()->whereIn('course_id', $ids);
        }


            $grid->column('recommended', __('Recommended'))->switch();
            $grid->column('name', __('Name'));
            $grid->column('thumbnail', __('Thumbnail'))->image('', 50, 50);

            $grid->column('description', __('Description'));
            $grid->column('type_id', __('Type id'));
            $grid->column('price', __('Price'));
            $grid->column('lesson_num', __('Lesson num'));
            $grid->column('video_length', __('Video length'));
            $grid->column('dowmloadable_res', __('Resources num'));

            $grid->column('created_at', __('Created at'));

            return $grid;
        }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));

        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));

      $show->field('description', __('Description'));
        $show->field('type_id', __('Type id'));
        $show->field('price', __('Price'));
        $show->field('lesson_num', __('Lesson num'));
        $show->field('video_length', __('Video length'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }


    //enabled when creat or edit

    protected function form()
    {


        $form = new Form(new Course());
       $form->text('name',__('Name'));

       $result=CourseType::pluck('title','id');
        $form->select('type_id', __('Category'))->options($result);

        $form->image('thumbnail',__('Thumbnail'))->uniqueName();
        $form->file('video',__('Video'))->uniqueName();
        $form->textarea('description', __('Description'));
        $form->decimal('price', __('Price'));
        $form->number('lesson_num', __('Lesson number'));
        $form->number('video_length', __('Video length'));

        $result=User::pluck('name','token');
        $form->number('dowmloadable_res', __('Resources num'));
        $form->display('created_at',__('Created at'));
        $form->display('updated_at',__('Updated at'));

        if(Admin::user()->isRole('teacher')){
            $token=Admin::user()->token;
            $userName= Admin::user()->username;
            $form->select('user_token',__('Teacher'))->options([$token=>$userName])->default($token)->readOnly();
        }else{
            $res = DB::table('admin_users')->pluck('username','token');
            $form->select('user_token',__('Teacher'))->options($res);
        }


        $form->switch('recommended', ('Recommended'))->default(0);


        return $form;
    }
}
