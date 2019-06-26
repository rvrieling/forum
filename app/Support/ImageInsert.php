<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;

class ImageInsert
{
    protected $image;
    protected $store_path;
    public $imageName;

    public function __construct($image, $store_path)
    {
        $this->image      = $image;
        $this->store_path = $store_path;

        $this->store();
    }

    private function store()
    {
        $height          = 1080;
        $width           = 1920;
        $canvas          = Image::canvas($width, $height);
        $imageName       = $this->store_path . '-' . rand(1, 1000000) . str_random(20) . Carbon::now()->timestamp;
        $this->imageName = $imageName;
        $img             = Image::make($this->image)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($img, 'center');
        $canvas->save(storage_path('app/public/images/' . $this->store_path . '/' . $imageName . '.png'));

        return $this;
    }
}
