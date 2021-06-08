---
layout: post
title: 部署Springboot应用到CloudFoundry
date: 2021-06-08
categories: blog
tags: [CloudFoundry]
description: 
---



IBM CloudFoundry上有256MB免费空间，可以部署一个Springboot应用。

### 环境准备

IBM Cloud官网：[cloud.ibm.com]()

在官网上注册一个账号。

左侧导航菜单 -> Cloud Foundry -> 公共，可以看到相关信息。

下载CloudFoundry命令行接口： https://github.com/IBM-Cloud/ibm-cloud-cli-release/releases/

### 应用配置

将Springboot应用程序打包成war包。

在打包之前，要做以下配置：

在target目录下新建一个manifest.yml，填入

```
applications:
- name: webapptest
  random-route: true
  path: ./project.war
  memory: 128M
  instances: 1
```

自己设定一个name，该name会出现在之后应用的url中，path填入将要打包的路径，分配内存。

通常都是打包成jar包，这里需要打包成war包才能部署上去。以idea为例，在pom.xml文件中把packaging方式改为war，点击右侧Maven Projects -> package，打包完war包会出现在target目录下。

### 推送

将命令行切到当前项目路径下（或者直接打开idea下的Terminal）

```
ibmcloud login
```

输入注册邮箱和密码，首次登陆需要选择区域，可以在IBM Cloud官网的个人账户中找到。

安装Cloud Foundry工具

```
ibmcloud cf install
```

设置交互目标

```
ibmcloud target --cf
```

推送

```
ibmcloud cf push
```

push后面加上war（本例中我使用的是project.war）

### 查看

登录官网，左侧导航菜单 -> Cloud Foundry -> 公共，可以看到部署了一个应用在运行，点开可以看到实例的信息，点击访问应用程序URL即可访问应用程序。

本例中我的Springboot应用程序的URL是 https://webapptest-patient-lynx-xg.mybluemix.net/

（需要翻墙访问）

