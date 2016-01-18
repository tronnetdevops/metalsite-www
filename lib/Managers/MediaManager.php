<?php
/**
 * @brief Media Manager
 *
 * ## Overview
 * This file exists as
 *
 * @package SatanBarbara
 * @subpackage Application
 * @category Managers
 * @version 0.7.2b
 * @since 0.6.5b
 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
 * @copyright Copyright (c) 2015, Sean Murray
 *
 * @abstract
 */

	abstract class MediaManager {
	    /**
	     * Class Properties
	     */

	    /**#@+
	     * @static
	     * @access protected
	     */

	    /**
		 * Path to directory where files are uploaded too. 
		 * @var string
		 */
		static protected $path;
		
		static protected $max_size = 1000000;

		static protected $whitelist_ext = array('jpg','png','gif');
		
		//Set default file type whitelist
		static protected $whitelist_type = array('image/jpeg', 'image/png','image/gif');

		/**
		 * The default directory for all uploads, if a path is not specified.
		 */
		static protected $default_bucket = '/tmp';
	
	    /**#@-*/
	
	    /**
	     * Class Methods
	     */
	
	    /**#@+
		 * @static
	     * @access public
	     */
	
		/**
		 * Set Upload Path
		 * 
		 * ## Overview
		 *
		 * @uses $_FILES
		 *
		 * @param string $path Path to uploads directory.
		 *
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.6.5b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
		 */
		static public function SetPath($path){
			DebugManager::Log("Setting Upload Path", '@');
			DebugManager::Log(self::$path);
				
			self::$path = $path .'/';
		
			return true;
		}
		
		static public function GetPath(){
			DebugManager::Log("Getting Upload Path", '@');
			DebugManager::Log(self::$path);
				
			if (self::$path){
				return self::$path;
			} else {
				return self::$default_bucket;
			}
		}
	
		/**
		 * Upload An Image
		 * 
		 * ## Overview
		 *
		 * @uses $_FILES
		 *
		 * @param string $file_field Name of file upload field in html form.
		 * @param string $new_name Name of file upload.
		 * @param bool $check_image Check if uploaded file is a valid image.
		 * @param bool $random_name Generate random filename for uploaded file
		 * @param bool $force Force file overwrites.
		 *
		 * @return array A set of error messages.
	     *
		 * @version 2015-07-05.1
		 * @since 0.6.5b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
		 */
		static public function Upload($file_field = null, $new_name = false, $check_image = false, $random_name = false, $force = false) {
			DebugManager::Log("Uploading Image!", '@', 0);

			//Config Section    
			//Set file upload path
			$path = self::GetPath();
			
			DebugManager::Log($path.$new_name, null, 0);
			
			//Set max file size in bytes
			$max_size = 1000000;
			//Set default file extension whitelist
			$whitelist_ext = array('jpg','png','gif');
			//Set default file type whitelist
			$whitelist_type = array('image/jpeg', 'image/png','image/gif');

			//The Validation
			// Create an array to hold any output
			$out = array('error'=>null);
   
			if (!$file_field) {
			$out['error'][] = "Please specify a valid form field name";           
			}

			if (!$path) {
			$out['error'][] = "Please specify a valid upload path";               
			}

			if (count($out['error'])>0) {
			return $out;
			}

			//Make sure that there is a file
			if((!empty($_FILES[$file_field])) && ($_FILES[$file_field]['error'] == 0)) {

				// Get filename
				$file_info = pathinfo($_FILES[$file_field]['name']);
				$name = $file_info['filename'];
				$ext = $file_info['extension'];
   
				//Check file has the right extension           
				if (!in_array($ext, $whitelist_ext)) {
					$out['error'][] = "Invalid file Extension";
				}
   
				//Check that the file is of the right type
				if (!in_array($_FILES[$file_field]["type"], $whitelist_type)) {
					$out['error'][] = "Invalid file Type";
				}
   
				//Check that the file is not too big
				if ($_FILES[$file_field]["size"] > $max_size) {
					$out['error'][] = "File is too big";
				}
   
				//If $check image is set as true
				if ($check_image) {
					if (!getimagesize($_FILES[$file_field]['tmp_name'])) {
						$out['error'][] = "Uploaded file is not a valid image";
					}
				}

				//Create full filename including path
				if ($new_name) {
					$name = $new_name;
				}
				
				if ($random_name) {
					// Generate random filename
					$tmp = str_replace(array('.',' '), array('',''), microtime());
           
					if (!$tmp || $tmp == '') {
					  $out['error'][] = "File must have a name";
					}     
					$newname = $tmp.'.'.$ext;                                
				} else {
					  $newname = $name.'.'.$ext;
				}
   
				//Check if file already exists on server
				if (file_exists($path.$newname)) {
					if($force){
						unlink($path.$newname);
					} else {
					    $out['error'][] = "A file with this name already exists";
					}
				}

				if (count($out['error'])>0) {
					//The file has not correctly validated
					return $out;
				} 

				if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $path.$newname)) {
					/**
					 * @todo probably can do this with image in memory instead of writing then rewriting
					 */
					DebugManager::Log("Uploaded Image!", null, 1);
				
					DebugManager::Log("Checking image is png...", null, 1);
				
					if ($ext != 'png'){
						DebugManager::Log("Nope! Going to convert it!", null, 1);
					
						$finalImage = new Imagick($path.$newname);
						$finalImage->setImageFormat("png");

						DebugManager::Log("Getting rid of old image: " . $path.$newname, null, 1);

						unlink($path.$newname);
					
						  $newname = $name.'.png';
					
						if ($finalImage->writeImage($path.$newname)){
							DebugManager::Log("Sweet, it should now be at: " . $path.$newname, null, 1);
						
						} else {
							DebugManager::Log("There were problems writing the image!", null, 1);
						}
					
					
					
					}
					//Success
					$out['filepath'] = $path;
					$out['filename'] = $newname;
					return $out;
				} else {
					$out['error'][] = "Server Error!";
				}

			} else {
				$out['error'][] = "No file uploaded";
				return $out;
			}      
		}
		
		
		
		static public function CropImage(){
			$path = self::GetPath();
			
			$uri = explode('?', $_POST['imgUrl']);
			$imgUrl = $path.basename( $uri[0] );
			// original sizes
			$imgInitW = $_POST['imgInitW'];
			$imgInitH = $_POST['imgInitH'];
			// resized sizes
			$imgW = $_POST['imgW'];
			$imgH = $_POST['imgH'];
			// offsets
			$imgY1 = $_POST['imgY1'];
			$imgX1 = $_POST['imgX1'];
			// crop box
			$cropW = $_POST['cropW'];
			$cropH = $_POST['cropH'];
			// rotation angle
			$angle = $_POST['rotation'];

			$jpeg_quality = 100;

			$output_filename = "temp/croppedImg_".rand();

			// uncomment line below to save the cropped image in the same location as the original image.
			$output_filename = dirname($imgUrl). "/croppedImg_".rand();

			$what = getimagesize($imgUrl);

			switch(strtolower($what['mime']))
			{
			    case 'image/png':
			        $img_r = imagecreatefrompng($imgUrl);
					$source_image = imagecreatefrompng($imgUrl);
					$type = '.png';
			        break;
			    case 'image/jpeg':
			        $img_r = imagecreatefromjpeg($imgUrl);
					$source_image = imagecreatefromjpeg($imgUrl);
					error_log("jpg");
					$type = '.jpeg';
			        break;
			    case 'image/gif':
			        $img_r = imagecreatefromgif($imgUrl);
					$source_image = imagecreatefromgif($imgUrl);
					$type = '.gif';
			        break;
			    default: die('image type not supported');
			}


			//Check write Access to Directory

			if(!is_writable(dirname($output_filename))){
				return array(
					  "status" => 'error',
					  "message" => 'Can`t write cropped File'
			    );	
			}else{

			    // resize the original image to size of editor
			    $resizedImage = imagecreatetruecolor($imgW, $imgH);
				imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
			    // rotate the rezized image
			    $rotated_image = imagerotate($resizedImage, -$angle, 0);
			    // find new width & height of rotated image
			    $rotated_width = imagesx($rotated_image);
			    $rotated_height = imagesy($rotated_image);
			    // diff between rotated & original sizes
			    $dx = $rotated_width - $imgW;
			    $dy = $rotated_height - $imgH;
			    // crop rotated image to fit into original rezized rectangle
				$cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
				imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
				imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
				// crop image into selected area
				$final_image = imagecreatetruecolor($cropW, $cropH);
				imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
				imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
				// finally output png image
				
				unlink($imgUrl);
				
				imagepng($final_image, $imgUrl);
				// imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
				return array(
					  "status" => 'success',
					  "url" => $uri[0].'?_='.uniqid()
			    );
			}
			
			
		}
	
	    /**#@-*/
	
	}