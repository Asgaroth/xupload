<?php
Yii::import( "xupload.models.XUploadForm" );

/**
 * XUploadAction
 * =============
 * Basic upload functionality for an action used by the xupload extension.
 *
 * XUploadAction is used together with XUpload and XUploadForm to provide file upload funcionality to any application
 *
 * You must configure properties of XUploadAction to customize the folders of the uploaded files.
 *
 * Using XUploadAction involves the following steps:
 *
 * 1. Override CController::actions() and register an action of class XUploadAction with ID 'upload', and configure its
 * properties:
 * ~~~
 * [php]
 * class MyController extends CController
 * {
 *     public function actions()
 *     {
 *         return array(
 *             'upload'=>array(
 *                 'class'=>'xupload.actions.XUploadAction',
 *                 'path' =>Yii::app() -> getBasePath() . "/../uploads",
 *                 'publicPath' => Yii::app() -> getBaseUrl() . "/uploads",
 *                 'subfolderVar' => "parent_id",
 *             ),
 *         );
 *     }
 * }
 *
 * 2. In the form model, declare an attribute to store the uploaded file data, and declare the attribute to be validated
 * by the 'file' validator.
 * 3. In the controller view, insert a XUpload widget.
 *
 * ###Resources
 * - [xupload](http://www.yiiframework.com/extension/xupload)
 *
 * @version 0.3
 * @author Asgaroth (http://www.yiiframework.com/user/1883/)
 */
class XUploadAction extends CAction {
    /**
     * The query string variable name where the subfolder name will be taken from.
     * If false, no subfolder will be used.
     * Defaults to null meaning the subfolder to be used will be the result of date("mdY").
     *
     * @see XUploadAction::init().
     * @var string
     * @since 0.2
     */
    public $subfolderVar;

    /**
     * Path of the main uploading folder.
     * @see XUploadAction::init()
     * @var string
     * @since 0.1
     */
    public $path;

    /**
     * Public path of the main uploading folder.
     * @see XUploadAction::init()
     * @var string
     * @since 0.1
     */
    public $publicPath;

    /**
     * The resolved subfolder to upload the file to
     * @var string
     * @since 0.2
     */
    private $_subfolder = "";

    /**
     * Initialize the propeties of pthis action, if they are not set.
     *
     * @since 0.1
     */
    public function init( ) {
        if( !isset( $this->path ) ) {
            $this->path = realpath( Yii::app( )->getBasePath( )."/../uploads" );
        }

        if( !is_dir( $this->path ) ) {
            mkdir( $this->path, 0777, true );
            chmod ( $this->path , 0777 );
            //throw new CHttpException(500, "{$this->path} does not exists.");
        } else if( !is_writable( $this->path ) ) {
            chmod( $this->path, 0777 );
            //throw new CHttpException(500, "{$this->path} is not writable.");
        }

        if( $this->subfolderVar !== null ) {
            $this->_subfolder = Yii::app( )->request->getQuery( $this->subfolderVar, date( "mdY" ) );
        } else if( $this->subfolderVar !== false ) {
            $this->_subfolder = date( "mdY" );
        }
    }

    /**
     * The main action that handles the file upload request.
     * @since 0.1
     * @author Asgaroth
     */
    public function run( ) {
        header( 'Vary: Accept' );
        if( isset( $_SERVER['HTTP_ACCEPT'] ) && (strpos( $_SERVER['HTTP_ACCEPT'], 'application/json' ) !== false) ) {
            header( 'Content-type: application/json' );
        } else {
            header( 'Content-type: text/plain' );
        }

        if( isset( $_GET["_method"] ) ) {
            if( $_GET["_method"] == "delete" ) {
                $success = is_file( $_GET["file"] ) && $_GET["file"][0] !== '.' && unlink( $_GET["file"] );
                echo json_encode( $success );
            }
        } else {
            $this->init( );
            $model = new XUploadForm;
            $model->file = CUploadedFile::getInstance( $model, 'file' );
            if( $model->file !== null ) {
                $model->mime_type = $model->file->getType( );
                $model->size = $model->file->getSize( );
                $model->name = $model->file->getName( );
                if( $model->validate( ) ) {
                    $path = ($this->_subfolder != "") ? "{$this->path}/{$this->_subfolder}/" : "{$this->path}/";
                    $publicPath = ($this->_subfolder != "") ? "{$this->publicPath}/{$this->_subfolder}/" : "{$this->publicPath}/";
                    if( !is_dir( $path ) ) {
                        mkdir( $path, 0777, true );
                        chmod ( $path , 0777 );
                    }
                    $model->file->saveAs( $path.$model->name );
                    chmod( $path.$model->name, 0777 );
                    echo json_encode( array( array(
                            "name" => $model->name,
                            "type" => $model->mime_type,
                            "size" => $model->size,
                            "url" => $publicPath.$model->name,
                            "thumbnail_url" => $publicPath.$model->name,
                            "delete_url" => $this->getController( )->createUrl( "upload", array(
                                "_method" => "delete",
                                "file" => $path.$model->name
                            ) ),
                            "delete_type" => "POST"
                        ) ) );
                } else {
                    echo json_encode( array( array( "error" => $model->getErrors( 'file' ), ) ) );
                    Yii::log( "XUploadAction: ".CVarDumper::dumpAsString( $model->getErrors( ) ), CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction" );
                }
            } else {
                throw new CHttpException( 500, "Could not upload file" );
            }
        }
    }

}
