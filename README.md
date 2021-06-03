# bilibili-live-urlget
bilibili直播流获取（可以说就是下载up的直播流视频qwq），使用的是python制作
共有**网站版（php）**和**主机版（python）**
主机版是直接运行在**系统**上，网站版是运行于**服务器**中，然后依靠**PHP**输出给用户

## 目录
- [bilibili-live-urlget](#bilibili-live-urlget)
  * [文件目录](#文件目录)
  * [前置](#前置)
    + [python](#python)
    + [php](#php)
  * [运行](#运行)
    + [python](#python-1)
    + [PHP](#php)
  * [反馈](#反馈)
  * [体验一下](#体验一下)

## 文件目录
```
|
|-main.py   (主机版)
|-mian.php  (网站版)
|-web       (网页版2.0)
   |-demo.css
   |-bililive.php
```

## 前置

### python
使用前建议先输入以下指令来安装前置
```python
pip install wget
```
### php
网站版无须任何前置，只需要您优化界面就可以了

## 运行
### python
一些原理和一些需要注意的地方可以看py文件里面的注解
### PHP
虽然无须任何特殊运行方式，直接访问即可，但是为了您能正常优化界面，还请您先看一下python的代码（因为php代码就是python代码移植过去，所以就没有写太多注解）

## 反馈
您可以提交issues反馈

## 体验一下
网站版体验通道：[这里](http://sakura.pysio.online/test/bililive.php)

## readme统计
![](https://count.getloli.com/get/@misaka10843.github.readme)
