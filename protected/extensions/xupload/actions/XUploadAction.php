<?php
Yii::import("xupload.models.XUploadForm");

/**
 * XUploadAction
 * =============
 * Basic upload functionality for an action used by the xupload extension.
 *
 * ###Prologue
 * A file uploader is commonly a web application must have.
 * There are pretty enough extensions in the yii project to achieve this
 * (other use flash, other are jquery...). I was suggested to use the xupload.
 * After checking the project code, i noticed the action code
 * that handles the request from the user.
 *
 * ###"Do not repeat yourself (DRY)"
 * Sticking with this motto, and due to the fact I did not want every controller
 * that needs something to be uploaded to have that code plain in it, I created
 * this CAction class.
 * 99% of the code belongs to the xupload author.
 *
 * ###Needless
 * Many of you may think that this CAction is needless, this is somehow
 * true.
 * You could create a controller like <code>XuploadController.php</code> have all
 * the upload actions in there and pass this <code>controller/action<code> to the
 * xupload <code>url</code> property.
 * Even this case you are one step beyond, just create the <code>XuploadController</code>
 * and use this action as in any other controller:
 * ~~~
 * [php]
 * class MyController extends CController
 * {
 *     public function actions()
 *     {
 *         return array(
 *             'upload'=>array(
 *                 //Assuming action exist in the extension folder
 *                 'class'=>'ext.XUploadAction',
 *             ),
 *         );
 *     }
 * }
 * ###Ending
 * This code is not complicated neither is big enough. There are only 2 properties:
 * - <code>parent_id</code>: Parent folder where the file will be uploaded.
 * - <code>path</code>: Full path of the main uploading folder.
 * Check code and <code>EXUploadAction::init()</code> for more informatin.
 *
 * ###Resources
 * - [xupload](http://www.yiiframework.com/extension/xupload)
 * - [while(true)](http://dmtrs.devio.us/blog/)
 *
 * @version 0.2
 * @author Dimitrios Mengidis, [Asgaroth](http://www.yiiframework.com/user/1883/)
 */
class XUploadAction extends CAction
{
        /**
         * The query string variable name where the subfolder name will be taken from.
         *
         * Defaults to false meaning the subfolder to be used will be the result of date("mdY").
         *
         * @see XUploadAction::init().
         * @var string
         * @since 0.2
         */
        public $subfolderVar = false;

        /**
         * Full path of the main uploading folder.
         * @see XUploadAction::init()
         * @var string
         * @since 0.1
         */
        public $path;
        
        /**
         * The resolved subfolder to upload the file to
         * @var string
         * @since 0.2
         */
        private $_subfolder;
        
        
        /**
         * Initialize the propeties of this action, if they are not set.
         *
         * @since 0.1
         */
        public function init()
        {
                if(!isset($this->path)){
                        $this->path = realpath(Yii::app()->getBasePath()."/../uploads");
                }
                
                if(!is_dir($this->path)){
                		@mkdir($this->path);
						@chmod($this->path, 0777);
                        //throw new CHttpException(500, "{$this->path} does not exists.");
                }else if(!is_writable($this->path)){
						@chmod($this->path, 0777);
                        //throw new CHttpException(500, "{$this->path} is not writable.");
                }
                
                if($this->subfolderVar !== false){
                        $this->_subfolder = Yii::app()->request->getQuery($this->subfolderVar, date("mdY"));
                }else{
                        $this->_subfolder = date("mdY");
                }
        }
        
        /**
         * The main action that handles the file upload request.
         * @since 0.1
         * @author Asgaroth
         */
        public function run()
        {
                $this->init();
                $model = new XUploadForm;
                $model->file = CUploadedFile::getInstance($model, 'file');
                $model->mime_type = $model->file->getType();
                $model->size = $model->file->getSize();
                $model->name = $model->file->getName();

                if ($model->validate()) {
                        $path = $this->path."/".$this->_subfolder."/";
                        if(!is_dir($path)){
                                mkdir($path);
                        }
                        $model->file->saveAs($path.$model->name);
                        echo json_encode(array("name" => $model->name,"type" => $model->mime_type,"size"=> $model->getReadableFileSize()));
                } else {
                        echo CVarDumper::dumpAsString($model->getErrors());
                        Yii::log("XUploadAction: ".CVarDumper::dumpAsString($model->getErrors()), CLogger::LEVEL_ERROR, "application.extensions.xupload.actions.XUploadAction");
                        throw new CHttpException(500, "Could not upload file");
                }
        }
}
