<?php
/**
 * Created by PhpStorm.
 * User: machao
 * Date: 2018/5/22
 * Time: 13:58
 */

//demo
// PHP version >= 5.3


include 'src/tree.php';


global $odata,$items;

//典型情况，我们数据库里保存了一组描述有tree结构关系的数据，读入到数组如下
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

//一些优化工作
$odata = [];
foreach ($items as $item) {
    $odata[$item['pid']][]=$item;
}
$items = array_column($items,null,'id');


//生成PHP数组的demo
class atree extends machao\tree {

    function getsubs($id):array{
        global $odata;
        $subids = array_column(isset($odata[$id])?$odata[$id]:[],'id');
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
$tree = new atree(0,'children');
$atree = $tree->gettree();
//你可以将处理好的数组进行json_encode,为web端初始tree提供数据
print_r($atree);

//所有节点 以树形缩进方式生成可选择的option
$awesome = new RecursiveTreeIterator(
    new RecursiveArrayIterator($atree),
    null, null, 1
);
$awesome->beginChildren();
foreach ($awesome as $line)
    echo$line . PHP_EOL;


