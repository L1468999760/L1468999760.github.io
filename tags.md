---
layout: page
title: "搜索"
description: "文章搜索"  
header-img: "img/semantic.jpg"
---

{% for tag in site.tags %}
   <a href="#{{ tag[0] }}" title="{{ tag[0] }}" rel="{{ tag[1].size }}"><b>{{ tag[0] }}</b></a>
{% endfor %}



<ul class="listing">
{% for tag in site.tags %}
  <li class="listing-seperator" id="{{ tag[0] }}">{{ tag[0] }}</li>
{% for post in tag[1] %}
  <ul>
      <li class="listing-item">
  <time datetime="{{ post.date | date:"%Y-%m-%d" }}">{{ post.date | date:"%Y-%m-%d" }}</time>
  <a href="{{ post.url }}" title="{{ post.title }}">{{ post.title }}</a>
  </li>
    </ul>
{% endfor %}
{% endfor %}
</ul>