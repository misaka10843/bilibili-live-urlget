import json
import wget
import os
import time

uid = '508963009'    #定义你想获取bilibili直播流的Up主uid号（也就是https://space.bilibili.com/508963009/ 中的 508963009）



########################################
#
#检测是否有json缓存，有的话就直接删除
#
########################################

if os.path.exists("bililive.json"):

  os.remove("bililive.json")    #删除文件

else:

  print("目录下好像没有缓存直播间信息的json\n我们会wget出来一个json缓存并在使用完后删除\n")

if os.path.exists("biliup.json"):

  os.remove("biliup.json")

else:

  print("目录下好像没有缓存up主直播间号的json\n我们会wget出来一个json缓存并在使用完后删除\n")

#下面的if语句如果删除通过biliAPI从up主的uid获取up主的名字那段代码的话就可以删除这个，不然千万被删除

if os.path.exists("biliname.json"):

  os.remove("biliname.json")

else:

  print("目录下好像没有缓存up主名字的json\n我们会wget出来一个json缓存并在使用完后删除\n")



########################################
#
#通过biliAPI从up主的uid获取up主的名字（非必要，就是可以保存到本地的时候好区分）
#
########################################

upname_url = 'http://api.bilibili.com/x/space/acc/info?mid=%s' % (uid)

wget.download(upname_url, 'biliname.json') 

getname = open('biliname.json','r+',encoding='utf-8')

getname = json.load(getname)

getname = getname['data']

print ('\n我知道啦！你要获取直播流的up主的名字是：'+getname['name']+'\n我们稍后会以“up主名字+-bililive-url.txt”的方式储存此获取数据qwq\n')

os.remove("biliname.json")

########################################
#
#通过biliAPI从up主的uid获取直播间的uid
#
########################################

up_url = 'http://api.live.bilibili.com/bili/living_v2/%s' % (uid)

wget.download(up_url, 'biliup.json') 

liveid_get = open('biliup.json','r+',encoding='utf-8')

getlive = json.load(liveid_get)

getlive = getlive['data']

getlive = getlive['url']

getlive = getlive[27:]

print ('\n直播间id获取到啦！是：'+getlive+'\n那么我们开始获取直播流吧！\n')

liveid_get.close()

os.remove("biliup.json")


########################################
#
#通过biliAPI获取到直播间相关信息
#
########################################

url = 'https://api.live.bilibili.com/room/v1/Room/playUrl?cid=+%s+&qn=0&platform=web' % (getlive)

wget.download(url, 'bililive.json')         #保存到本地以供下一步使用和检测用


get = open('bililive.json','r+',encoding='utf-8')

getjson = json.load(get)

getjson = getjson['data']

getjson = getjson['durl']

choice = input ('\n已检测到链接：\n'+getjson[0]['url']+'\n那我们是否保存到本地呢？[Y/N default：Y]')

get.close()

os.remove("bililive.json")

########################################
#
#将获取的url写入文件
#
########################################
if choice != 'N' :                                              #判断用户是否需要储存到本地

    file = '%s-bililive-url.txt' % (getname['name'])            #规定文件名字

    localtime = time.asctime( time.localtime(time.time()) )     #获取系统现在的时间

    txt = localtime + '的时候获取到的直播链接为:\n' + getjson[0]['url'] + '\n-----------------------------------------------------------------------------\n'

    #上面是规范文本内容
    #下面是写入文件

    with open(file,"w",encoding='utf-8') as f:

        f.write(txt)

    f.close()
    print ('\n储存完毕啦！')
else:
    print ('\n那么我们在来最后输出一次链接qwq：\n' + getjson[0]['url'])