<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\CourseType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;

class CourseTypeController extends AdminController
{

    //show tree form of the menus

    public function index(Content $content): Content
    {
        $tree = new Tree(new CourseType);
        return $content->header('Course Type')->body($tree);
    }


    //just for view

    protected function detail($id): Show
    {
        $show = new Show(CourseType::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Category'));
        $show->field('description', __('Description'));
        $show->field('Order', __('Order'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

     //enabled when creat or edit

    protected function form()
    {

        //dd('add or edit');

        $form = new Form(new CourseType());

        $form->select('parent_id', __('Parent Category'))->options((new CourseType())::selectOptions());
        $form->text('title', __('Title'));
        $form->textarea('description', __('Description'));
        $form->number('Order', __('Order'));


        return $form;
    }
}
