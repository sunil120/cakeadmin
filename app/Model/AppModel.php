<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
        public function uploadfile($filedata, $folder){
            $basepath = WWW_ROOT.'uploads/';
            if(!is_dir($basepath)) {
                mkdir($basepath,0777);
                mkdir($basepath.'/filetemplates',0777);
                mkdir($basepath.'/importedfiles',0777);
                mkdir($basepath.'/rejectioncodes',0777);
        	}
            $filename = basename($filedata['name']);
            $filepath = $basepath.$folder.'/'.$filename;        
            if(file_exists($filepath)){
                $filename = time().$filename;
                $filepath = $basepath.$folder.'/'.$filename;
            }
            if(move_uploaded_file($filedata['tmp_name'], $filepath)){            
                return $folder."/".$filename;
            } else {
                return false;
            }
        }
        
        public function importfile($filepath){
            $allowedtypes = array('xls','xlsx','csv');
            $basepath = WWW_ROOT.'uploads/';
            $filefullpath = $basepath.$filepath;
            $fileinfo = pathinfo($filefullpath); 
            $extension = $fileinfo['extension'];
            $filedata = array();
            if(in_array(strtolower($extension), $allowedtypes)){
                switch (strtolower($extension)){
                    case 'xlsx':
                        App::import('Vendor', 'PHPExcel/IOFactory');
                        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                        $objPHPExcel = $objReader->load($filefullpath);                    
                        //We are uploading only one shit
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {                        
                            $filedata = $worksheet->toArray();
                            break;
                        }
                        break;
                    case 'xls':
                        App::import('Vendor', 'php-excel-reader/excel_reader2');            
                        $reader=new Spreadsheet_Excel_Reader();
                        $reader->setUTFEncoder('iconv');
                        $reader->setOutputEncoding('UTF-8');
                        $reader->read($filefullpath);
                        $sheets = $reader->boundsheets;
                        $alldata = $reader->sheets;
                        $filedata = array_values($alldata[0]['cells']);                    
                        break;
                    case 'csv':
                        $handle1 = fopen($filefullpath, "r");                    
                        while (($line = fgetcsv($handle1)) !== FALSE) {
                            $filedata[] = $line;
                        }
                        fclose($handle1);
                        break;
                }
                return $filedata;
            } else {
                return false;
            }        
        }
        
         
    /*
     * Before insert
     * add date_created() on insert
     */
    public function beforeSave($options = array()) {  
        if(empty($this->id)) {
            if($this->hasField('date_created')) {
               $this->data[$this->alias]['date_created'] =  date('Y-m-d H:i:s');
            }
        }
    }
}


