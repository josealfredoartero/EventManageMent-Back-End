<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store($image)
    {
        // the base64 of the image sent is added to the variable
        $file = $image['img'];
         // the characters are removed to save it as an image
        $imageInfo = explode(";base64,", $file);  
        $file = str_replace(' ', '+', $imageInfo[1]);
        // original image name
        $name = $image['name'];
        // numbers to add to the image name to make it unique
        $milliseconds = round(microtime(true) * 1000);
        // the numbers created with the original name are joined
        $new_name = $milliseconds."_".$name;
        //  the image is saved in the project
        Storage::disk()->put("imgEvent/$new_name", base64_decode($file));
        $image_url = "imgEvent/$new_name";
        // return the url of the image saved
        return $image_url;
    }

    public function ImagesAll($images, $id)
    {
        foreach ($images as $image){
            // the base64 of the image sent is added to the variable
            $file = $image["img"];
            // the characters are removed to save it as an image
            $imageInfo = explode(";base64,", $file);    
            $file = str_replace(' ', '+', $imageInfo[1]);
            // original image name
            $name = $image["name"];
            // numbers to add to the image name to make it unique
            $milliseconds = round(microtime(true) * 1000); 
            // the numbers created with the original name are joined
            $new_name = $milliseconds."_".$name;
            //  the image is saved in the project
            Storage::disk('images')->put("imgPublication/$new_name", base64_decode($file));
            $img_link = url('/')."/imgPublication/$new_name"; 

                // We proceed to store each image belonging to the new publication
                $imagen = new Image();
                $imagen->url = $img_link;
                $imagen->id_publicaciones = $id;
                $res = $imagen->save();
        }

        return $res;
    }

    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // query all image of the publication 
        $images = Image::where('id_publication', $id)->get();
        $res = null;
        // delete image from proyect Storage
        foreach ($images as $image){
            $res = Storage::disk("images")->delete("/imgPublication/".$image->name);
        }
        // return repsonse
        return $res;
    }

    public function deleteImg($id){
        // find image to delete
        $image = Image::find($id);
        // Storage::disk("images")->delete("/imgPublication/".$image->name);
        // delete image from proyect Storage
        Storage::disk("images")->delete($image->url);
        // delete image from database
        $res = $image->delete();
        // return true or false from the accion
        return $res;
    }

    public function deleteEvent($url)
    {
        // delete imgage from proyect Storage
        $res = Storage::disk("images")->delete($url);

        // return true or false from the action
        return $res;
    }
}
