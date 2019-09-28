<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library Front Upload Files
 *
 * @package     anofie
 * @author      classiebit
**/

class File_uploads {

	var $CI_LIB;

    function __construct()
    {
        $this->CI_LIB =& get_instance();
    }
    
    /**
	* Reset Files
	*
	* @param	string	$path	path to files
	* @param	array	$data	file names
	* @return	integer 		1
	*/
    public function reset_files($path = '', $data = array())
    {
        if(empty($data))
            return 1;
        
	    foreach($data as $val)
	    {
	        if(file_exists($path.$val))
	            @unlink($path.$val);
	    }

	    return 1;
	}

    public function reset_file($path = '', $data = '')
    {
        if(file_exists($path.$data))
            @unlink($path.$data);
        
        return 1;
    }

    public function remove_files($path = '', $data = array())
    {
        if(empty($data)) 
            return 1;
        
        foreach($data as $val)
        {
            // remove original
            if(file_exists($path.$val))
                @unlink($path.$val);

            // remove resized
            $ext      = strtolower(pathinfo($val, PATHINFO_EXTENSION));
            $val      = strtolower(pathinfo($val, PATHINFO_FILENAME)).'_thumb.'.$ext;

            if(file_exists($path.$val))
                @unlink($path.$val);
            
        }

        return 1;
    }

    public function remove_file($path = '', $data = '')
    {   
        // remove original
        if(file_exists($path.$data))
            @unlink($path.$data);

        // remove resized
        $ext      = strtolower(pathinfo($data, PATHINFO_EXTENSION));
        $data     = strtolower(pathinfo($data, PATHINFO_FILENAME)).'_thumb.'.$ext;

        if(file_exists($path.$data))
            @unlink($path.$data);
        
        return 1;
    }

    public function remove_dir($path = '')
    {
        delete_files($path, true);
        @rmdir($path);

        return true;
    }





	
    

	/**
     * Upload Files 
     * 
	*/
	public function upload_files($data = array())
    {
        $this->CI_LIB->load->library(array('upload', 'image_lib'));
        
        $config                         = array();
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG';
        $config['max_size']             = '0';
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = './upload/'.$data['folder'].'/';
        
        if (!is_dir($config['upload_path']))
            @mkdir($config['upload_path'], 0777, TRUE);
        
        $files                          = array();
        $files                          = $_FILES[$data['input_file']];

        $filenames                      = array();

        foreach($files['name'] as $key => $file) 
        {
            $_FILES['files']['name']      = $files['name'][$key];
            $_FILES['files']['type']      = $files['type'][$key];
            $_FILES['files']['tmp_name']  = $files['tmp_name'][$key];
            $_FILES['files']['error']     = $files['error'][$key];
            $_FILES['files']['size']      = $files['size'][$key];
            
            $extension                    = strtolower(pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION));
            $filename                     = time().rand(1,988);
            
            // file name for further use
            $filenames[$key]              = $filename.'.'.$extension;
            
            // original file for resizing
            $config['file_name']          = $filename.'_original'.'.'.$extension;
            
            $this->CI_LIB->upload->initialize($config);

            if (! $this->CI_LIB->upload->do_upload('files')) 
            {            
                // remove all uploaded files in case of error
                $this->reset_files($config['upload_path'], $filenames);
                return array('error' => $this->CI_LIB->upload->display_errors());
            }

            // resize original image
            $resize                         = array();
            $resize['image_library']        = 'gd2';
            $resize['source_image']         = $config['upload_path'].$config['file_name'];
            $resize['new_image']            = $config['upload_path'].$filenames[$key];
            $resize['maintain_ratio']       = TRUE;
            $resize['overwrite']            = TRUE;
            $resize['width']                = 1280;
            $resize['height']               = 1024;
            $resize['quality']              = 60;
            $resize['file_permissions']     = 0644;
            
            $this->CI_LIB->image_lib->initialize($resize);  
            
            if (! $this->CI_LIB->image_lib->resize()) 
            {
                $this->reset_files($config['upload_path'], $filenames);
                return array('error' => $this->CI_LIB->image_lib->display_errors());
            }

            $this->CI_LIB->image_lib->clear();

            // cropped thumbnail
            $thumb                          = array();
            $thumb['image_library']         = 'gd2';
            $thumb['source_image']          = $config['upload_path'].$config['file_name'];

            // for changing file name
            $f_name                         = array();
            $f_name                         = explode(".",$filenames[$key]);
            $thumb['new_image']             = $config['upload_path'].$f_name[0].'_thumb.'.$f_name[1];

            $thumb['maintain_ratio']        = TRUE;
            $thumb['width']                 = 350;
            $thumb['height']                = 350;
            $thumn['quality']               = 60;
            $thumb['file_permissions']      = 0644;
            
            $this->CI_LIB->image_lib->initialize($thumb);  
            
            if (! $this->CI_LIB->image_lib->resize()) 
            {
                $this->reset_files($config['upload_path'], $filenames);
                return array('error' => $this->CI_LIB->image_lib->display_errors());
            }

            $this->CI_LIB->image_lib->clear();        

            // remove the original image
            @unlink($config['upload_path'].$config['file_name']);
        }
        
        return $filenames;
        
    }

    
    /**
    * Upload File
    *
    * @param    array   $data   files attributes -
    * @return   array   file name
    */

    public function upload_file($data = array(), $width = 1024, $height = 768, $t_width = 350, $t_height = 350, $og_width = 300, $og_height = 200)
    {
        $this->CI_LIB->load->library(array('upload', 'image_lib'));
        
        $config                         = array();
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG';
        $config['max_size']             = 0;
        $config['max_width']            = 0;
        $config['max_height']           = 0;

        if(isset($data['strict']))
        {
            $config['allowed_types']        = $data['allowed_types'];
            $config['min_width']            = $width;
            $config['min_height']           = $height;
        }
        
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = './upload/'.$data['folder'].'/';
        
        if (!is_dir($config['upload_path']))
            @mkdir($config['upload_path'], 0777, TRUE);
        

        $filename                       = isset($data['filename']) ? $data['filename'] : time().rand(1,988);
        $extension                      = strtolower(pathinfo($_FILES[$data['input_file']]['name'], PATHINFO_EXTENSION));
        
        // original file for resizing
        $config['file_name']            = $filename.'_large'.'.'.$extension;

        // file name for further use
        $filename                       = $filename.'.'.$extension;
        
        $this->CI_LIB->upload->initialize($config);

        if (! $this->CI_LIB->upload->do_upload($data['input_file'])) 
        {            
            // remove all uploaded files in case of error
            $this->reset_file($config['upload_path'], $filename);
            return array('error' => $this->CI_LIB->upload->display_errors());
        }

        if($data['folder'] == 'pages/images') 
            return $filename;
        
        // resize original image
        $resize                         = array();
        $resize['image_library']        = 'gd2';
        $resize['source_image']         = $config['upload_path'].$config['file_name'];
        $resize['new_image']            = $config['upload_path'].$filename;

        $resize['maintain_ratio']       = TRUE;
        if(isset($data['strict']))
            $resize['maintain_ratio']   = FALSE;


        $resize['overwrite']            = TRUE;
        $resize['width']                = $width;
        $resize['height']               = $height;
        $resize['quality']              = 60;
        $resize['file_permissions']     = 0644;
        
        $this->CI_LIB->image_lib->initialize($resize);  
        
        if (! $this->CI_LIB->image_lib->resize()) 
        {
            $this->reset_file($config['upload_path'], $filename);
            return array('error' => $this->CI_LIB->image_lib->display_errors());
        }

        $this->CI_LIB->image_lib->clear();

        if(!empty($data['no_thumbnail']))
        {
            return $filename;
        }

        // cropped thumbnail
        $thumb                          = array();
        $thumb['image_library']         = 'gd2';
        $thumb['source_image']          = $config['upload_path'].$config['file_name'];

        // for changing file name
        $f_name                         = array();
        $f_name                         = explode(".",$filename);
        $thumb['new_image']             = $config['upload_path'].$f_name[0].'_thumb.'.$f_name[1];

        $thumb['maintain_ratio']        = TRUE;
        $resize['overwrite']            = TRUE;
        $thumb['width']                 = $t_width;
        $thumb['height']                = $t_width;
        $thumn['quality']               = 60;
        $thumb['file_permissions']      = 0644;
        
        $this->CI_LIB->image_lib->initialize($thumb);  
        
        if (! $this->CI_LIB->image_lib->resize()) 
        {
            $this->reset_file($config['upload_path'], $filename);
            return array('error' => $this->CI_LIB->image_lib->display_errors());
        }

        $this->CI_LIB->image_lib->clear();        

        if(isset($data['og_image']))
        {
            // cropped thumbnail
            $thumb                          = array();
            $thumb['image_library']         = 'gd2';
            $thumb['source_image']          = $config['upload_path'].$config['file_name'];

            // for changing file name
            $f_name                         = array();
            $f_name                         = explode(".",$filename);
            $thumb['new_image']             = $config['upload_path'].$f_name[0].'_og.'.$f_name[1];

            $thumb['maintain_ratio']        = FALSE;
            $resize['overwrite']            = TRUE;
            $thumb['width']                 = $og_width;
            $thumb['height']                = $og_height;
            $thumn['quality']               = 60;
            $thumb['file_permissions']      = 0644;
            
            $this->CI_LIB->image_lib->initialize($thumb);  
            
            if (! $this->CI_LIB->image_lib->resize()) 
            {
                $this->reset_file($config['upload_path'], $filename);
                return array('error' => $this->CI_LIB->image_lib->display_errors());
            }

            $this->CI_LIB->image_lib->clear();            
        }

        // remove the original image
        @unlink($config['upload_path'].$config['file_name']);
        return $filename;
    } 


    /**
    * Upload File Custom
    *
    */
    public function upload_file_custom($data = array())
    {
        $this->CI_LIB->load->library(array('upload', 'image_lib'));
        
        $config                         = array();
        $config['allowed_types']        = $data['format'];
        $config['max_size']             = '0';
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = './upload/'.$data['folder'].'/';
        
        if (!is_dir($config['upload_path']))
            @mkdir($config['upload_path'], 0777, TRUE);
        
        $filename                       = $data['filename'];
        $extension                      = strtolower(pathinfo($_FILES[$data['input_file']]['name'], PATHINFO_EXTENSION));

        $config['file_name']            = $filename.'.'.$extension;
        
        $this->CI_LIB->upload->initialize($config);

        if (! $this->CI_LIB->upload->do_upload($data['input_file'])) 
        {            
            return array('error' => $this->CI_LIB->upload->display_errors());
        }

        return $filename;
    } 


    /**
    * Upload File in custom_url
    *
    * @param    array   $data   files attributes 
    * @return   array   file name
    */

    public function upload_file_custom_url($data = array())
    {
        $this->CI_LIB->load->library(array('upload'));
        
        $config                         = array();
        $config['allowed_types']        = 'php';
        $config['max_size']             = '0';
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = $data['folder'].'/';

        
        if (!is_dir($config['upload_path'])) 
            @mkdir($config['upload_path'], 0777, TRUE);

        $filename                       = $data['input_file'];
        $extension                      = $data['extension'];
        $filename                       = $filename.$extension;
        $config['file_name']            = $filename;
        
        $this->CI_LIB->upload->initialize($config);

        if (! $this->CI_LIB->upload->do_upload($data['input_file'])) 
        {            
            return array('error' => $this->CI_LIB->upload->display_errors());
        }

        return $filename;
    }

}

/* End Front Upload Files Library */