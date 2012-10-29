# XUpload extension for Yii Framework

## Yii extension page
[Extension page](http://www.yiiframework.com/extension/xupload/)



## Demo
[jQuery file upload](http://blueimp.github.com/jQuery-File-Upload/ "jQuery File Upload") extension for Yii, allows your users to easily upload files to your server:

[GitTip!](https://www.gittip.com/Asgaroth/ "GitTip")

* [XUpload Workflow example](http://www.yiiframework.com/wiki/348/xupload-workflow/)
* [Additional form data with XUpload](http://www.yiiframework.com/wiki/395/additional-form-data-with-xupload/)


## Setup instructions

1. Download the files and extract them to you project desired folder
2. Add an application alias in you application configuration that points to the extracted folder
~~~
[php]
...
'import'=>array(
  'application.models.*',
	'application.components.*',
),
'aliases' => array(
	//assuming you extracted the files to the extensions folder
	'xupload' => 'ext.xupload'
),

'modules'=>array(...
~~~

3. Create a model, declare an attribute to store the uploaded file data,  and declare the attribute to be validated by the 'file' validator. _Or use XUploadForm_
4. Create a controller to handle form based file uploads. _Or use XUploadAction_
5. Add the Widget to you view
~~~
[php]
<?php
$this->widget('xupload.XUpload', array(
					'url' => Yii::app()->createUrl("site/upload"),
                    'model' => $model,
                    'attribute' => 'file',
                    'multiple' => true,
));
?>
~~~

> Info: Ensure that the apache server has write permissions in the folder you are uploading the files, XUpload will try to create the upload folder if it doesn't exist already.

### Using XUploadAction and XUploadForm:

- XUploadAcion adds basic upload functionality to any controller.
- XUploadForm its a simple form model to store uploaded file data

1. Override CController::actions() and register an action of class XUploadAction with ID 'upload', and configure its properties:
~~~
[php]
class SiteController extends CController
{
    public function actions()
    {
        return array(
            'upload'=>array(
                'class'=>'xupload.actions.XUploadAction',
                'path' =>Yii::app() -> getBasePath() . "/../uploads",
                'publicPath' => Yii::app() -> getBaseUrl() . "/uploads",
            ),
        );
    }
}
~~~
2. Create an initial action that will render the form using the XUploadModel:

~~~
[php]
public function actionIndex() {
		Yii::import("xupload.models.XUploadForm");
		$model = new XUploadForm;
		$this -> render('index', array('model' => $model, ));
	}
~~~

## Additional Documentation

Here is a wiki describing [a more complex workflow](http://www.yiiframework.com/wiki/348/xupload-workflow/ "A more complex workflow") using this widget.

And a wiki explaining how to send [additional data](http://www.yiiframework.com/wiki/395/additional-form-data-with-xupload/ "additional data") with your file

 **Note:** _See the attached project for more examples_

##Resources
 * [Forum Discussion](http://www.yiiframework.com/forum/index.php?/topic/19277-extension-xupload/page__gopid__94404#entry94404 "Forum Discussion") : for any questions or community support
 * [Project page](https://github.com/Asgaroth/xupload "XUpload in GitHub") : to fork and contribute
 * [Demo](http://blueimp.github.com/jQuery-File-Upload/ "jQuery file upload plugin")
 * [jQuery Plugin Wiki](https://github.com/blueimp/jQuery-File-Upload/wiki "jQuery Plugin Wiki")
 * [jQuery File Upload Plugin](https://github.com/blueimp/jQuery-File-Upload "jQuery File Upload")

##Changelog

#### V 0.4 Mon Jul  2 16:25:53 COT 2012
- Added image preview assets
- Added image processing assets
- Added the ability to specify upload, download, and form views

#### V 0.3a Sun Mon Apr  2 21:43:38 COT 2012
- Fixed missing dependencies

#### V 0.3 Sun Apr  1 18:35:23 COT 2012
- Updated to the new jquery plugin version

#### V 0.2 Sun May  8 19:28:43 COT 2011

- Added Multiple file uploading functionality
- Integrated [XUploadAction](http://www.yiiframework.com/wiki/182/a-simple-action-for-xupload-extension/ "XUploadAction") with a few changes (thanks to [tydeas_dr](http://www.yiiframework.com/user/4786/ "tydeas_dr"))
- Moved XUploadForm to the extension folder


#### v 0.1 Mon Mar 21 14:51:13 COT 2011

- First release

## License
Released under the [MIT license](http://creativecommons.org/licenses/MIT/).






