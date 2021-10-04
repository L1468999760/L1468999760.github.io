---
layout: page
title: "文章归档"
description: ""
header-img: "img/blue.jpg"
---

<ul class="listing">
{% for post in site.posts %}
  {% capture y %}{{post.date | date:"%Y"}}{% endcapture %}
  {% if year != y %}
    {% assign year = y %}
    <li class="listing-seperator"><font size="6">{{ y }}</font></li>
  {% endif %}
  <ul>
    <li class="listing-item">
    	<time datetime="{{ post.date | date:"%Y-%m-%d" }}">{{ post.date | date:"%Y-%m-%d" }}</time>
    	<a href="{{ post.url }}" title="{{ post.title }}">{{ post.title }}</a>
    </li>
  </ul>
{% endfor %}
</ul>

