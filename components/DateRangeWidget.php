<?php
namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;
use app\assets\My97DatePickerAsset;

/**
* 	日期范围选择，基于my97datepicker
*	生成[attribute]Start 和[attribute]End 文本框
*	日期选择初始化
*/
class DateRangeWidget extends Widget
{
	
	public $attribute;
	public $format;
	public $lang;

	public $model;

	public function init(){
		parent::init();
		if($this->format===null){
			$this->format  = \Yii::$app->formatter->dateFormat ;

		}
		if($this->lang===null){
			$this->lang  = \Yii::$app->language;
		}
	}
	
	public function run(){

		My97DatePickerAsset::register($this->getView()); //引入 my97 js

		$code = "<div class=\"form-group field-".Html::getInputId($this->model,$this->attribute)."\">\n";
		$code = $code.(Html::activeLabel($this->model, $this->attribute)."\n");
		$code = $code.(Html::activeInput('text', $this->model, $this->attribute."Start", ["class"=>"form-control","size"=>"10","onfocus"=>"WdatePicker({maxDate:'#F{\$dp.\$D(\'".(Html::getInputId($this->model,$this->attribute."End"))."\')}',lang:'".strtolower($this->lang)."',dateFmt:'".$this->format."'});"])."\n");
		$code = $code."~\n";
		$code = $code.(Html::activeInput('text', $this->model, $this->attribute."End", ["class"=>"form-control","size"=>"10","onfocus"=>"WdatePicker({minDate:'#F{\$dp.\$D(\'".(Html::getInputId($this->model,$this->attribute."Start"))."\')}',lang:'".strtolower($this->lang)."',dateFmt:'".$this->format."'});"])."\n");
		$code = $code."<div class=\"help-block\"></div>";
		$code = $code."</div>\n";
		return $code;
	}
}
