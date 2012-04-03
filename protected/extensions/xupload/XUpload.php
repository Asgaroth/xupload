<?php
Yii::import('zii.widgets.jui.CJuiInputWidget');
/**
 * XUpload extension for Yii.
 *
 * jQuery file upload extension for Yii, allows your users to easily upload files to your server using jquery
 * Its a wrapper of  http://blueimp.github.com/jQuery-File-Upload/
 *
 * @author AsgarothBelem <asgaroth.belem@gmail.com>
 * @link http://blueimp.github.com/jQuery-File-Upload/
 * @link https://github.com/Asgaroth/xupload
 * @version 0.2
 *
 */
class XUpload extends CJuiInputWidget {

    /**
     * the url to the upload handler
     * @var string
     */
    public $url;

    /**
     * set to true to use multiple file upload
     * @var boolean
     */
    public $multiple = false;

    /**
     * The upload template id to display files available for upload
     * defaults to null, meaning using the built-in template
     */
    public $uploadTemplate;

    /**
     * The template id to display files available for download
     * defaults to null, meaning using the built-in template
     */
    public $downloadTemplate;

    /**
     * Publishes the required assets
     */
    public function init() {
        parent::init();
        $this -> publishAssets();
    }

    /**
     * Generates the required HTML and Javascript
     */
    public function run() {

        list($name, $id) = $this -> resolveNameID();

        $model = $this -> model;

        if ($this -> uploadTemplate === null) {
            $this -> uploadTemplate = "#template-upload";
            $this -> render("upload");
        }
        if ($this -> downloadTemplate === null) {
            $this -> downloadTemplate = "#template-download";
            $this -> render("download");
        }

        if (!isset($this -> htmlOptions['enctype'])) {
            $this -> htmlOptions['enctype'] = 'multipart/form-data';
        }

        if (!isset($this -> htmlOptions['id'])) {
            $this -> htmlOptions['id'] = get_class($model) . "-form";
        }

        $options = CJavaScript::encode($this -> options);
        Yii::app() -> clientScript -> registerScript(__CLASS__ . '#' . $this -> htmlOptions['id'], "jQuery('#{$this->htmlOptions['id']}').fileupload({$options});", CClientScript::POS_READY);
        $htmlOptions = array();
        if ($this -> multiple) {
            $htmlOptions["multiple"] = true;
           /* if($this->hasModel()){
                $this -> attribute = "[]" . $this -> attribute;
            }else{
                $this -> attribute = "[]" . $this -> name;
            }*/
        }
        $this -> render("form", compact('htmlOptions'));
    }

    /**
     * Publises and registers the required CSS and Javascript
     * @throws CHttpException if the assets folder was not found
     */
    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app() -> assetManager -> publish($assets);
        if (is_dir($assets)) {
            //@ALEXTODO make ui interface optional
            Yii::app() -> clientScript -> registerCssFile($baseUrl . '/css/jquery.fileupload-ui.css');
            //The Templates plugin is included to render the upload/download listings
            Yii::app() -> clientScript -> registerScriptFile("http://blueimp.github.com/JavaScript-Templates/tmpl.min.js", CClientScript::POS_END);
            //The Iframe Transport is required for browsers without support for XHR file uploads
            Yii::app() -> clientScript -> registerScriptFile($baseUrl . '/js/jquery.iframe-transport.js', CClientScript::POS_END);
            // The basic File Upload plugin
            Yii::app() -> clientScript -> registerScriptFile($baseUrl . '/js/jquery.fileupload.js', CClientScript::POS_END);
            // The File Upload image processing plugin
            Yii::app() -> clientScript -> registerScriptFile($baseUrl . '/js/jquery.fileupload-ip.js', CClientScript::POS_END);
            //The File Upload user interface plugin
            Yii::app() -> clientScript -> registerScriptFile($baseUrl . '/js/jquery.fileupload-ui.js', CClientScript::POS_END);
            //The localization script
            Yii::app() -> clientScript -> registerScriptFile($baseUrl . '/js/locale.js', CClientScript::POS_END);
            /**
             <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
             <!--[if gte IE 8]><script src="<?php echo Yii::app()->baseUrl; ?>/js/cors/jquery.xdr-transport.js"></script><![endif]-->
             *
             */
        } else {
            throw new CHttpException(500, __CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
