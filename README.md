# tree
PHP 将有tree结构意义的一组数据生成tree数据结构

**典型使用场景**：

  在web开发中，前端需要实现tree树，如：
  
  https://github.com/jonmiles/bootstrap-treeview
  
  http://element.eleme.io/#/zh-CN/component/tree
  
  https://github.com/mar10/fancytree/wiki
  
  这些tree树在初始化的时候，通常需要tree描述的json数组。
  抽象类快捷灵活高效地生成tree结构数组的组装，你无需关心递归的一些东西，对于新手非常友好。

#### 使用：
这是一个抽象类，需要实现2个抽象方法`getsubs()` `node()`，可能还需要重写`branch()`方法.
自己实现这些方法是非常容易的。

做好准备工作后，调用`gettree()`,即可实现你想的结构。
demo有详细的使用说明。
