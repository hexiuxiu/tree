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
     * @param $i
     * @param $subs
     * @param $level
     * @return array
     */
    abstract function node($id, $subs, $level);

    /**
     * 把子节点数组合并到当前节点下面，可在子类重写此方法
     * @param $node
     * @param $subNodes
     */
    function branch(&$node, $subNodes){
        if(!empty($subNodes)){
            $node['sub'] = $subNodes;
        }
    }

    function __construct($root) {
        $this->root = $root;
    }

    function gern(){
        $this->tree = $this->recu($this->root);
    }

    function gettree(){
        $this->gern();
        return $this->tree;
    }

    function recu($id){
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

//demo
global $odata,$items;
$items = [
    [
        'id'=>0,
        'name'=>'snqu',
        'pid'=>null,
    ],
    [
        'id'=>1,
        'name'=>'sndo',
        'pid'=>0
    ],
    [
        'id'=>2,
        'name'=>'v6',
        'pid'=>0
    ],
    [
        'id'=>3,
        'name'=>'machao',
        'pid'=>1
    ],
    [
        'id'=>4,
        'name'=>'cxp',
        'pid'=>1
    ],
];


$odata = [];
foreach ($items as $item) {
    $odata[$item['pid']][]=$item;
}

$items = array_column($items,null,'id');


//生成PHP数组的demo
class atree extends tree {

    function getsubs($id):array{
        global $odata;
        $subids = array_column($odata[$id]??[],'id');
        return $subids;
    }

    function node($id,$subs,$level):array{
        global $items;
        $node = $items[$id];
        $node['size'] = count($subs);
        $node['level'] = $level;
        return $node;
    }

}
$tree = new atree(0);
print_r($tree->gettree());

//生成xml的demo
class xmltree extends tree {

    function getsubs($id):array{
        global $odata;
        $subids = array_column($odata[$id]??[],'id');
        return $subids;
    }

    function node($id,$subs,$level){
        global $items;
        $node = $items[$id];
        $node['size'] = count($subs);

        $xml='<node>';
        foreach ($node as $k=>$v){
            $xml .=  "<$k>$v</$k>";
        }
        $xml.='</node>';
        return $xml;
    }

    function branch(&$node, $subNodes){

        $node =str_replace('</node>',"<sub>$subNodes</sub></node>",$node);
    }

}
$tree = new xmltree(0);
print_r($tree->gettree());