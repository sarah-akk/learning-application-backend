<?php

namespace App\Admin\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class LessonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Lesson';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lesson());

        $grid->column('id', __('Id'));
        $grid->column('course_id', __('Course name'));
        $grid->column('name', __('Name'));
        $grid->column('thumbnail', __('Thumbnail'))->image(50,50);
        $grid->column('description', __('Description'));
        $grid->column('video', __('Video'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Lesson::findOrFail($id));


        $show->field('id', __('Id'));
        $show->field('name', __('Name'));

        $show->field('course_id', __('Course name'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('description', __('Description'));
        $show->field('video', __('Video'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Lesson());
        $result = Course::pluck('name', 'id');
        $form->text('name', __('Name'));

        if(Admin::user()->isRole('teacher')){
            $token=Admin::user()->token;
            $ids = DB::table('courses')->where('token','=',$token)->pluck('name','id');
            $form->select('course_id',__('Courses'))->options($ids);
        }else{
         $res = DB::table('courses')->pluck('name','id');
         $form->select('course_id',__('Courses'))->options($res);
        }

       //   $form->select('course_id', 'Courses')->options($result);
        $form->image('thumbnail', __('Thumbnail'))->uniqueName();
        $form->textarea('description', __('Description'));

        if($form->isEditing()){
            //access this during form eddting
           // dump($form—>video);
                $form->table('video',function ($form){
                $form->text('name');
                $form->hidden('old_url') ;
                $form->hidden('old_thumbnail') ;
                $form->image('thumbnail')->uniqueName();
                $form->file('url');

                   });
            }
       else {
           $form->table('video', function ($form) {
               $form->text('name')->rules('required');
               $form->image('thumbnail')->uniqueName()->rules('required');
               $form->file('url')->rules('required');

           });
       }
        //saving call back gets called before submitting to the datbase
        //but after clicking the submit button
        // a good place to process grabbed data or form data
        $form->saving(function (Form $form) {
            if ($form->isEditing()) {
                $path = env('APP_URL')."/uploads/";

                $video = $form->video;
                $res = $form->model()->video;
                $newVideo = [];

                foreach ($video as $k => $v) {
                    $valueVideo = [];

                    // Check if the URL is empty
                    if (empty($v['url'])) {
                        // Set the old URL if not empty, otherwise empty string
                        $valueVideo["old_url"] = !empty($res[$k]['url']) ? str_replace($path, "", $res[$k]['url']) : "";
                    } else {
                        $valueVideo["url"] = $v['url'];
                    }

                    // Check if the Thumbnail is empty
                    if (empty($v['thumbnail'])) {
                        // Set the old Thumbnail if not empty, otherwise empty string
                        $valueVideo["old_thumbnail"] = !empty($res[$k]['thumbnail']) ? str_replace($path, "", $res[$k]['thumbnail']) : "";
                    } else {
                        $valueVideo["thumbnail"] = $v['thumbnail'];
                    }

                    // Check if the Name is empty
                    if (empty($v['name'])) {
                        // Set the old Name if not empty, otherwise empty string
                        $valueVideo["name"] = !empty($res[$k]['name']) ? $res[$k]['name'] : "";
                    } else {
                        $valueVideo['name'] = $v['name'];
                    }

                    // Push the modified video data to the new array
                    $valueVideo['_remove_'] = $v['_remove_'] ;
                    array_push($newVideo, $valueVideo);
                }

                // Assign the new video array to the form's video attribute
                $form->video = $newVideo;
            }
        });

        return $form;
    }
}
