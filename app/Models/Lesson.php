<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $casts = [
        'video'=>'json',
    ];

    public function setVideoAttribute($value){
        $newVideo = [];
        foreach ($value as $k=>$v){
            $valueVideo = [];
            if(!empty($v['old_url'])){
                $valueVideo['url'] = $v['old_url'];
            }
            else
            {
                $valueVideo['url']=$v['url'];
            }

            if(!empty($v['old_thumbnail'])){
                $valueVideo['thumbnail'] = $v['old_thumbnail'];
            }
            else
            {
                $valueVideo['thumbnail']=$v['thumbnail'];
            }
            $valueVideo['name']=$v['name'];
            array_push($newVideo,$valueVideo);
        }

        $this->attributes['video'] = json_encode(array_values($newVideo));
    }

    public function getVideoAttribute($value)
    {
        $result = json_decode($value, true);
        if (!empty($result)) {
            foreach ($result as $key => $video) { // Change variable name here
                $result[$key]['url'] = env('APP_URL') . "/uploads/" . $video['url']; // Use $video instead of $value
                $result[$key]['thumbnail'] = env('APP_URL') . "/uploads/" . $video['thumbnail']; // Use $video instead of $value
            }
        }
        return $result;
    }

    public function getThumbnailAttribute($value){
        return env( 'APP_URL' ) . "/uploads/". $value;
        }

}
