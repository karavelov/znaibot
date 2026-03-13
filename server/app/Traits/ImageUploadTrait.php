<?php 

namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManagerStatic as Image;

trait ImageUploadTrait {
    // $resize=false, $width=300, $height=300
    public function uploadImage(Request $request, $inputName, $isThumb = false, $base64=null)
    {
       
       if($request->hasFile($inputName)) {

        $ddate=date('Y/m');
        $godina=date('Y');
        $mesec=date('m');
        $WRITEDIR="uploads/".$godina; if(!is_dir($WRITEDIR)) { mkdir($WRITEDIR, 0777); };
        $WRITEDIR="uploads/".$godina."/".$mesec; if(!is_dir($WRITEDIR)) { mkdir($WRITEDIR, 0777); };
        $NORMALDIR="$WRITEDIR";
        $THUMBDIR1="$WRITEDIR/t1";
        $THUMBDIR2="$WRITEDIR/t2";
        $THUMBDIR3="$WRITEDIR/t3";
        $THUMBDIR4="$WRITEDIR/t4";
        $THUMBDIR5="$WRITEDIR/t5";
        $THUMBDIR6="$WRITEDIR/t6";
        $THUMBDIR7="$WRITEDIR/t7";
        $THUMBDIR8="$WRITEDIR/t8";
        if (!is_dir("$THUMBDIR1"));{@mkdir("$THUMBDIR1", 0777);};
        if (!is_dir("$THUMBDIR2"));{@mkdir("$THUMBDIR2", 0777);};
        if (!is_dir("$THUMBDIR3"));{@mkdir("$THUMBDIR3", 0777);};
        if (!is_dir("$THUMBDIR4"));{@mkdir("$THUMBDIR4", 0777);};
        if (!is_dir("$THUMBDIR5"));{@mkdir("$THUMBDIR5", 0777);};
        if (!is_dir("$THUMBDIR6"));{@mkdir("$THUMBDIR6", 0777);};
        if (!is_dir("$THUMBDIR7"));{@mkdir("$THUMBDIR7", 0777);};
        if (!is_dir("$THUMBDIR8"));{@mkdir("$THUMBDIR8", 0777);};

        $image=$request->{$inputName};
        $image_name=rand().'_'.$image->getClientOriginalName();
        if ($isThumb) {
            $path = $image->move($THUMBDIR1,$image_name);
        } else {
            $path = $image->move($NORMALDIR,$image_name);
        }

        if($base64) {
            list($type, $data)  = explode(';', $base64);
            list(, $data)       = explode(',', $data);
            $data               = base64_decode($data);
            file_put_contents($path, $data);
        }

        // if($resize) {
        //     $img=Image::make($path)->resize($width, $height)->save($path);
        // }

        return $path;
    }}


    public function uploadMultiImage(Request $request, $inputName, $isThumb = false)
    {
       
       if($request->hasFile($inputName)) {

        $paths=[];

        $ddate=date('Y/m');
        $godina=date('Y');
        $mesec=date('m');
        $WRITEDIR="uploads/".$godina; if(!is_dir($WRITEDIR)) { mkdir($WRITEDIR, 0777); };
        $WRITEDIR="uploads/".$godina."/".$mesec; if(!is_dir($WRITEDIR)) { mkdir($WRITEDIR, 0777); };
        $NORMALDIR="$WRITEDIR";
        $THUMBDIR1="$WRITEDIR/t1";
        $THUMBDIR2="$WRITEDIR/t2";
        $THUMBDIR3="$WRITEDIR/t3";
        $THUMBDIR4="$WRITEDIR/t4";
        $THUMBDIR5="$WRITEDIR/t5";
        $THUMBDIR6="$WRITEDIR/t6";
        $THUMBDIR7="$WRITEDIR/t7";
        $THUMBDIR8="$WRITEDIR/t8";
        if (!is_dir("$THUMBDIR1"));{@mkdir("$THUMBDIR1", 0777);};
        if (!is_dir("$THUMBDIR2"));{@mkdir("$THUMBDIR2", 0777);};
        if (!is_dir("$THUMBDIR3"));{@mkdir("$THUMBDIR3", 0777);};
        if (!is_dir("$THUMBDIR4"));{@mkdir("$THUMBDIR4", 0777);};
        if (!is_dir("$THUMBDIR5"));{@mkdir("$THUMBDIR5", 0777);};
        if (!is_dir("$THUMBDIR6"));{@mkdir("$THUMBDIR6", 0777);};
        if (!is_dir("$THUMBDIR7"));{@mkdir("$THUMBDIR7", 0777);};
        if (!is_dir("$THUMBDIR8"));{@mkdir("$THUMBDIR8", 0777);};

        $images=$request->{$inputName};

        foreach($images as $image) {
            $image_name=rand().'_'.$image->getClientOriginalName();
            if ($isThumb) {
                $paths[] = $image->move($THUMBDIR1,$image_name);
            } else {
                $paths[] = $image->move($NORMALDIR,$image_name);
            }  
        }

        return $paths;
    }}





    public function updateImage(Request $request, $inputName, $isThumb = false, $oldPath = null, $base64=null) {

        if($request->hasFile($inputName)){
           if(File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }
            
        $ddate=date('Y/m');
        $godina=date('Y');
        $mesec=date('m');
        $WRITEDIR="uploads/".$godina; if(!is_dir($WRITEDIR)) { mkdir($WRITEDIR, 0777); };
        $WRITEDIR="uploads/".$godina."/".$mesec; if(!is_dir($WRITEDIR)) { mkdir($WRITEDIR, 0777); };
        $NORMALDIR="$WRITEDIR";
        $THUMBDIR1="$WRITEDIR/t1";
        $THUMBDIR2="$WRITEDIR/t2";
        $THUMBDIR3="$WRITEDIR/t3";
        $THUMBDIR4="$WRITEDIR/t4";
        $THUMBDIR5="$WRITEDIR/t5";
        $THUMBDIR6="$WRITEDIR/t6";
        $THUMBDIR7="$WRITEDIR/t7";
        $THUMBDIR8="$WRITEDIR/t8";
        if (!is_dir("$THUMBDIR1"));{@mkdir("$THUMBDIR1", 0777);};
        if (!is_dir("$THUMBDIR2"));{@mkdir("$THUMBDIR2", 0777);};
        if (!is_dir("$THUMBDIR3"));{@mkdir("$THUMBDIR3", 0777);};
        if (!is_dir("$THUMBDIR4"));{@mkdir("$THUMBDIR4", 0777);};
        if (!is_dir("$THUMBDIR5"));{@mkdir("$THUMBDIR5", 0777);};
        if (!is_dir("$THUMBDIR6"));{@mkdir("$THUMBDIR6", 0777);};
        if (!is_dir("$THUMBDIR7"));{@mkdir("$THUMBDIR7", 0777);};
        if (!is_dir("$THUMBDIR8"));{@mkdir("$THUMBDIR8", 0777);};

        $image=$request->{$inputName};
        $image_name=rand().'_'.$image->getClientOriginalName();
        if ($isThumb) {
            $path = $image->move($THUMBDIR1,$image_name);
        } else {
            $path = $image->move($NORMALDIR,$image_name);
        }



        if($base64) {
            list($type, $data)  = explode(';', $base64);
            list(, $data)       = explode(',', $data);
            $data               = base64_decode($data);
            file_put_contents($path, $data);
        }

   

        return $path;
    }
}
    


    public function deleteImage($path) {
        if(File::exists($path)){
            File::delete($path);
        }
    }





    public function updateImageAdvertisment(Request $request, $inputName, $isThumb = false, $oldPath = null, $base64 = null)
    {
        if ($request->hasFile($inputName)) {
            // Delete the old file if it exists
            if ($oldPath && File::exists(public_path($oldPath))) {
                File::delete(public_path($oldPath));
            }
    
            // Set up the directory structure based on the current year and month
            $datePath = date('Y/m');
            $baseDir = "uploads/$datePath";
    
            // Ensure the directories exist
            if (!is_dir(public_path($baseDir))) {
                mkdir(public_path($baseDir), 0777, true);
            }
    
            // Define the path for normal and thumbnail directories
            $NORMALDIR = public_path($baseDir);
            $THUMBDIR = $NORMALDIR . '/thumbnails';
    
            // Ensure the thumbnail directory exists if needed
            if ($isThumb && !is_dir($THUMBDIR)) {
                mkdir($THUMBDIR, 0777, true);
            }
    
            // Get the uploaded image and generate a unique name
            $image = $request->file($inputName);
            $image_name = uniqid('media_') . '.' . $image->getClientOriginalExtension();
    
            // Move the image to the appropriate directory
            if ($isThumb) {
                $image->move($THUMBDIR, $image_name);
                $path = "$baseDir/thumbnails/$image_name";
            } else {
                $image->move($NORMALDIR, $image_name);
                $path = "$baseDir/$image_name";
            }
    
            // Handle base64 encoded image if provided
            if ($base64) {
                list(, $data) = explode(',', $base64);
                $data = base64_decode($data);
                file_put_contents(public_path($path), $data);
            }
    
     
            return $path;
        }
    
        // Return the old path if no new file was uploaded
        return $oldPath;
    }


    public function storeImageFromUrl($url_image, $oldPath = null, $base64 = null)
    {
        if (!$url_image && !$base64) {
            return $oldPath; // If neither URL nor base64 is provided, return the old image path
        }
    
        // Delete the old image if it exists
        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }
    
        // Directory structure setup
        $godina = date('Y');
        $mesec = date('m');
        $WRITEDIR = "uploads/" . $godina;
        if (!is_dir($WRITEDIR)) {
            mkdir($WRITEDIR, 0777);
        }
        $WRITEDIR = "uploads/" . $godina . "/" . $mesec;
        if (!is_dir($WRITEDIR)) {
            mkdir($WRITEDIR, 0777);
        }
    
        $NORMALDIR = "$WRITEDIR";
    
        // Generate a unique name for the image
        $image_name = 'image_' . time() . '.jpg';
        $path = $NORMALDIR . '/' . $image_name;
    
        if ($base64) {
            // Handle base64 encoded image
            list(, $data) = explode(',', $base64);
            $data = base64_decode($data);
            file_put_contents(public_path($path), $data);
        } else {
            // Handle image URL
            try {
                $response = Http::get($url_image);
                if ($response->successful()) {
                    file_put_contents(public_path($path), $response->body());
                } else {
                    throw new \Exception('Unable to download image from URL');
                }
            } catch (\Exception $e) {
                return null; // Handle failure to download the image
            }
        }
    
        return $path; // Return the new image path
    }
    
    
    public function deleteTempUploadImage()
    {
        $uploadDir = public_path('uploads');
        $tempFilePattern = $uploadDir . '/temp_*.jpg'; // Match temp files like 'temp_12345.jpg'
    
        // Use glob to find matching temp files
        $tempFiles = glob($tempFilePattern);
    
        // Delete each found temp file
        foreach ($tempFiles as $tempFile) {
            if (File::exists($tempFile)) {
                File::delete($tempFile);
            }
        }
    }
    

}





?>
