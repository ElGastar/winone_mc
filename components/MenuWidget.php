<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuWidget
 *
 * @author т
 */
namespace app\components;
use yii\base\Widget;
use app\models\Category;
class MenuWidget extends Widget{
    public $tpl;
    public $data;
    public $tree;
    public $menuHtml;
    public function init(){
        parent::init();
        if($this->tpl===NULL){
            $this->tpl=menu;
        }
        return $this->tpl.='.php';
    }

    public function run() {
        $this->data= Category::find()->indexBy('id')->asArray()->all();
        $this->tree=$this->getTree();
        $this->menuHtml= $this->getMenuHtml($this->tree);
        return $this->menuHtml;
    }
    
    protected function getTree() {
        $tree=[];
 
        foreach ($this->data as $id=>&$node){
            if(!$node['parent_id'])
                $tree[$id]=&$node;
            else 
          $this->data[$node['parent_id']]['childs'][$node['id']]=&$node;
           
        }
        return $tree;
    }
    protected function getMenuHtml($tree) {
        $str='';
        foreach ($tree as $category){
            $str .= $this->catToTemplate($category);
          
        }
        return $str;
    }
    
    protected function catToTemplate($category) {
        ob_start();
        include __DIR__.'/menu_tpl/'.$this->tpl;
        return ob_get_clean() ;
        
    }
}
