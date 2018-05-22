<?php
/**
 * 将有tree结构意义的一组数据生成tree数据结构
 * Created by PhpStorm.
 * User: machao
 * Date: 2018/5/19 0019
 * Time: 10:50
 */


abstract class tree{
    public $level=0;
    public $root=0;
    public $tree;


    /**
     * 按给定的节点id，返回子节点id数组
     * 此方法是递归中调用，注意性能优化
     * @param $id
     * @return array
     */
    abstract function getsubs($id):array;

    /**
     * 按给定的节点id，返回该节点枝叶
     * 此方法是递归中调用，注意性能优化
     * @param $id
     * @param $subs
     * @param $level
     * @return array
     */
    abstract function node($id, $subs, $level);

    /**
     * 把子节点数组合并到当前节点下面，可在子类重写此方法实现你想要的合并方式
     * @param $node
     * @param $subNodes
     */
    public function branch(&$node, $subNodes){
        if(!empty($subNodes)){
            $node['sub'] = $subNodes;
        }
    }

    function __construct($root) {
        $this->root = $root;
    }

    final private function gern(){
        $this->tree = $this->recu($this->root);
    }

    final function gettree(){
        $this->gern();
        return $this->tree;
    }

    private function recu($id){
        $this->level++;

        $s = $this->getsubs($id);
        $node = $this->node($id,$s,$this->level);

        $sunNodes=null;
        foreach ($s as $k => $vi) {
            $t = $this->recu($vi);
            if(is_array($t)){
                $sunNodes[] = $t;
            }else{
                $sunNodes = $sunNodes??'';
                $sunNodes .= $t;
            }
        }

        $this->branch($node,$sunNodes);

        $this->level--;
        return $node??[];
    }

}

