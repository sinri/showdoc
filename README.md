# ShowDoc 乐其魔改版
  
  许可证维持原状，商标（如果存在）为原所有人所有。代码变更详见GitHub Compare。

# 魔改日志

  我们需要更多的牺牲品 —— 某大魔王

## 数据库

### TABLE `user` 用户表

    [uid] => INT 用户ID
    [username] => VC 用户名
    [groupid] => INT 组名
    [name] => 不明
    [avatar] => 不明
    [avatar_small] => 不明
    [email] => 不明
    [password] => 某种方式记录的暗文密码
    [cookie_token] => 不明
    [cookie_token_expire] => 似乎是INT，不明
    [reg_time] => INT 注册时间
    [last_login_time] => INT，基本都是0

### TABLE `item` 项目表

    [item_id] => INT 项目ID 
    [item_name] => VC 项目名
    [item_description] => VC 项目描述
    [uid] => INT 创建者UID
    [username] => VC 创建者UN
    [password] => 不明
    [addtime] => INT 创建时间
    [last_update_time] => INT 更新时间
    [item_domain] => 不明
    [item_type] => INT 不明 似乎是单页还是多页(1大概是多页)

### TABLE `page` 文章表

    [page_id] => INT 文章ID
    [author_uid] => INT 作者UID
    [author_username] => VC 作者UN
    [item_id] => INT 项目ID
    [cat_id] => INT 分类ID
    [page_title] => VC 文章标题
    [page_content] => VC Markdown
    [s_number] => INT 不明，默认99，似乎是顺序相关内容
    [addtime] => INT 添加时间
    [page_comments] => 不明

### TABLE `catalog` 目录表

    [cat_id] => INT 目录ID
    [cat_name] => VC 目录名
    [item_id] => INT 项目ID
    [s_number] => INT 似乎是顺序ID
    [addtime] => INT 创建时间
    [parent_cat_id] => INT 上一层目录的ID，顶层为0
    [level] => INT 项目本身为1，所以新建的从2开始顺延

### TABLE `Item_Member` 项目用户表

    [item_member_id] => INT 登记ID
    [item_id] => INT 项目ID
    [uid] => INT 用户ID
    [username] => VC 用户名
    [addtime] => INT 添加时间
    [member_group_id] => INT 似乎是存是否只读，1应该是非只读

### TABLE `User_Token` 用户指纹表，因为没啥用就算了

----
  
## 中文版教程：http://www.showdoc.cc/help

### What is ShowDoc ?

Whenever we take over a module or project which has been developed by other people, we always feel crazy watching at those codes without notes. Where is the document?! Where is the document?! **Show me the doc !!**

A programmer often hopes the others to write technical documents, with the hope of not writing them on his/her own. Because writing a technical document needs a lot of time to handle the format and layout, and the person who writes it has to think of all kinds of non-technical details such as which catalog to put in the newly-established word document

All kinds of the word documents are kept by different persons in a team dispersedly. The person who needs other documents gets the documents by shouting out. He/She gives a shout asking for the documents and then receives them from other people by IMs or the e-mail. This kind of communication is not bad, but the efficiency is not high.

ShowDoc is a tool greatly applicable for an IT team to share documents online. It can promote communication efficiency among members of the team.

### What can it be used for?

- #### API Document （ [Demo](https://www.showdoc.cc/demo-en)）
 
 With the development of mobile Internet, BaaS (Backend as a Service) becomes more and more popular. The Server end provides API, and the APP end or Webpage frontend can invoke data conveniently. Using ShowDoc can compile exquisite API documents in a very fast and convenient way.

- #### Data Dictionary （ [Demo](https://www.showdoc.cc/demo-en)）
 
 A good Data Dictionary can easily exhibit database structure to other people, such as definition of each field and the like.

- #### Explanation Document （ [Demo](https://www.showdoc.cc/help-en)）
 
 You can absolutely use ShowDoc to compile the explanation documents for some tools, as well as to compile some technical specifications explanation documents for the team to look up.
 
### What functions does it have?

- #### Sharing and Exporting

 Responsive webpage design can share the project documents to computer or mobile devices for reading. It can also export the project into word document for browsing offline.
 
- #### Permission Management

 - Public Project and Private Project
 
   Projects on ShowDoc are divided into two categories including Public Project and Private Project. Public Project can be visited by any user no matter he/she logs in or not, while inputting password for verification is needed for visiting the Private Project. The password is set by project creator. 
   
  - Project Transfer
  
   The project creator can transfer the project to other users of the website freely.
   
  - Project Members
  
   You can easily add or delete project members in the project of ShowDoc. Members of the project can edit the project, but they can not transfer or delete the project (only creator of the project has the permission).
   
- #### Edit Function
  - Markdown Edit
  
   ShowDoc adopts Markdown Editor, and it is excellent both in editing and reading experience. If you know nothing about Markdown, please search “Learning and Introduction of Markdown” on the search engine.
   
  - Template Insert
  
   On the editing page of ShowDoc, a click on the button which is on the top of the Editor can easily insert API interface template and data dictionary template. After inserting the template, altering data is the only thing that need to do and it reduces a lot of work in editing.
   
  - History Version
  
   ShowDoc provides a function of History Version on the page, and you can easily restore the page to the former version.
   

### Deploy It to Your Own Server
 - ShowDoc Deploy Manual
  
     Please refer to:[https://www.showdoc.cc/help-en?page_id=16975](https://www.showdoc.cc/help-en?page_id=16975)
  

### Copyright 

 ShowDoc is issued complying with Apache2 Open Source License, and it is for free use. 
 
 Copyright © 2016 by star7th 
 
 
 E-mail: xing7th#gmail.com (change # into @) 
 
 All rights reserved. 
 
