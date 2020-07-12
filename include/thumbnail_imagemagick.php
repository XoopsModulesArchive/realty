<?
// I don't work yet, someone needs to figure me out.
function make_thumb ($input_file_name, $input_file_path)
{
   // makes a thumbnail using ImageMagick
   global $config;
   
  // Specify your file details
  $current_file = "$input_file_path/$input_file_name";
  $max_width = $config[thumbnail_width];

  // Get the current info on the file
  $current_size = getimagesize($current_file);
  $current_img_width = $current_size[0];
  $current_img_height = $current_size[1];
  $image_base = explode('.', $current_file);

  // This part gets the new thumbnail name
  $image_basename = $image_base[0];
  $image_ext = $image_base[1];
  $thumb_name = $input_file_path."/thumb_".$input_file_name;
   $thumb_name2 = "thumb_".$input_file_name;

  $too_big_diff_ratio = $current_img_width/$max_width;
  $new_img_width = $max_width;
  $new_img_height = round($current_img_height/$too_big_diff_ratio);
   
   $path = $config[path_to_imagemagick];
      
  // Convert the file
  $make_magick = system("$path -geometry $new_img_width x $new_img_height $current_file $thumb_name");


   return $thumb_name2;
} // end function make_thumb
?>