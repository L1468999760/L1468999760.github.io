---
layout: post
title: 算法模板
date: 2021-03-15
categories: blog
tags: [算法]
description: 常用算法模板。
---
  
  目录
- [Kruskal（并查集）](#kruskal并查集)
- [Prim](#prim)
- [拓扑排序](#拓扑排序)
- [判断回文子串](#判断回文子串)
- [最长上升子序列（LIS）](#最长上升子序列lis)

## Kruskal（并查集）

~~~c
#define MAXV 1000 //最大点数  
#define MAXE 1000 //最大边数  
#include <iostream>  
#include <algorithm>  
using namespace std;  

struct Edge //定义边 
{
	int s;
	int t;
	int d;
 } E[MAXE];
bool cmp(Edge& a,Edge& b)
{
	return a.d<b.d;
}
int find(int x)
{
	while(x!=fa[x]) //路径压缩 
	{
		fa[x]=fa[fa[x]];
		x=fa[x];
	}
	return x;
}
void Union(int x,int y)
{
    fa[x]=y;
}
int main()
{
	int countE,countV,fa[MAXV];
	int r[MAXV]={0}; //r用来统计频率 
	int count=0; //统计加入的边数
	int ans=0;  
	/*  
	输入,存储边  
	*/  
	sort(E,E+countE,cmp);
	for(int i=0;i<countV;i++) fa[i]=i; //初始化并查集
	for(int i=0;i<countE;i++)
	{
		int root_s=find(E[i].s),root_t=find(E[i].t);
		if(root_s!=root_t)
		{
            	//Union(root_s,root_t);
			if(r[root_s]<r[root_t])
			{
				r[root_t]++;
				fa[root_s]=root_t;
			}
			else
			{
				r[root_s]++;
				fa[root_t]=root_s;
			}
			count++;
			ans+=E[i].d;
		}
		if(count==countV-1) break;
	 }
	 cout<<ans<<endl;
	 return 0; 
}
~~~

## Prim

~~~c
#define MAXV 1000 //最大点数  
#define MAXE 1000 //最大边数  
#define MAXL 0x3fffffff  
#include <iostream>  
#include <iomanip>  
#include <algorithm>  
using namespace std;  

int main()
{
	int d[MAXV],vis[MAXV]={0},G[MAXV][MAXV];
	int ans=0;  
	/*  
	输入,存储边 
	*/  
	fill(d,d+MAXV,MAXL);

	for(int i=0;i<MAXV;i++)
	{
		int t=-1;
		for(int j=0;j<MAXV;j++) //寻找加入的点 
		{
			if(!vis[j]&&(t==-1||d[t]>d[j])) t=j; 
		}
		if(i) ans+=d[t];
		vis[t]=1;
		for(int j=0;j<MAXV;j++) //更新距离 
		{
			if(!vis[j]&&d[j]>G[t][j]) d[j]=G[t][j];
		}
	 }
	 cout<<ans<<endl;
	 return 0; 
}
~~~

## 拓扑排序

~~~c
#define MAXV 1000  
#include <iostream>  
#include <vector>  
#include <queue>  
using namespace std;  

int main()
{
	int count=0; //统计标记的点数 
	int indgree[MAXV]={0}; //统计每个点的入度和 
	vector<int> vec[MAXV]; //存储边 （点与点的后继）
	vector<int> d; //记录路径 
	queue<int> q; //存储入度为0的点
	//priority_queue<int> q;
	/*  
	存储边、入度
	*/  
	while(!q.empty())
	{
		int cur=q.front();
		d.push_back(cur);
		count++;
		q.pop();
		for(int i=0;i<vec[cur].size();i++)
		{
			indgree[vec[cur][i]]--;
			if(indgree[vec[cur][i]]==0) q.push(indgree[vec[cur][i]]);
		}

	 }	 
	 if(count==MAXV) cout<<"Yes"<<endl;
	 else cout<<"No"<<endl;
	return 0;
}
~~~

## 判断回文子串

中心扩散法

~~~c
#define MAXL 10005  
#include <iostream>  
#include <cstring>  
using namespace std;  

int main()
{
	string s;
	cin>>s;
	int len=s.length();
	int dp[MAXL][MAXL]={0};
	for(int i=0;i<len;i++)
	{
		//每个点向两边扩散 
		for(int j=i,k=i;j>=0&&k<len&&s[j]==s[k];--j,++k)
		dp[j][k]=1;
		for(int j=i,k=i+1;j>=0&&k<len&&s[j]==s[k];--j,++k)
		dp[j][k]=1;
	 } 
	 return 0; 
}
~~~

最长回文子串（动态规划）

<a href="https://www.codecogs.com/eqnedit.php?latex=dp[i][i]=1&space;\newline&space;dp[i][i&plus;1]=\left\{\begin{matrix}&space;0&space;&,s[i]&space;\neq&space;s[i&plus;1],&space;\\&space;2&space;&&space;,s[i]&space;=&space;s[i&plus;1]&space;.\end{matrix}\right.\\&space;dp[i][j]=\left\{\begin{matrix}&space;dp[i&plus;1][j-1]&plus;2&space;&,s[i]&space;=&space;s[j],&space;\\&space;max(dp[i&plus;1][j],dp[i][j-1])&space;&&space;,s[i]&space;=&space;s[j]&space;.\end{matrix}\right." target="_blank"><img src="https://latex.codecogs.com/gif.latex?dp[i][i]=1&space;\newline&space;dp[i][i&plus;1]=\left\{\begin{matrix}&space;0&space;&,s[i]&space;\neq&space;s[i&plus;1],&space;\\&space;2&space;&&space;,s[i]&space;=&space;s[i&plus;1]&space;.\end{matrix}\right.\\&space;dp[i][j]=\left\{\begin{matrix}&space;dp[i&plus;1][j-1]&plus;2&space;&,s[i]&space;=&space;s[j],&space;\\&space;max(dp[i&plus;1][j],dp[i][j-1])&space;&&space;,s[i]&space;=&space;s[j]&space;.\end{matrix}\right." title="dp[i][i]=1 \newline dp[i][i+1]=\left\{\begin{matrix} 0 &,s[i] \neq s[i+1], \\ 2 & ,s[i] = s[i+1] .\end{matrix}\right.\\ dp[i][j]=\left\{\begin{matrix} dp[i+1][j-1]+2 &,s[i] = s[j], \\ max(dp[i+1][j],dp[i][j-1]) & ,s[i] = s[j] .\end{matrix}\right." /></a>

## 最长上升子序列（LIS）

~~~c
#include <iostream>  
#include <cmath>  
#include <vector>  
using namespace std;  
//暴力求解 O(n^2) 
int maxIncSub(vector<int>& vec)
{
	int ans=0;
	vector<int> dp(vec.size(),1);
	for(int i=1;i<vec.size();i++)
	{
		for(int j=i-1;j>=0;j--)
		{
			if(vec[i]>vec[j]) dp[i]=max(dp[i],dp[j]+1);
		}
		ans=max(ans,dp[i]);
	}
	return ans;
}
//二分求解 O(nlogn) 
int LIS(vector<int>& vec)
{
	int cur=0;
	vector<int> dp(vec.size(),0); //记录当前长度最后一个数字的数值,升序 
	dp[cur]=vec[0];
	for(int i=1;i<vec.size();i++)
	{
		if(vec[i]>dp[cur])
		{
			cur++;
			dp[cur]=vec[i];
		}
		else //替换掉dp中第一个大于等于vec[i]的数值，用二分查找该下标 
		{
			int l=0,r=cur,mid;
			while(l<r)
			{
				mid=l+(r-l)/2;
				if(dp[mid]>=vec[i]) r=mid;
				else l=mid+1;
			}
			dp[l]=vec[i];
		}
	 } 
	 return cur+1;
	
}
int main()
{
    
    return 0;
}
~~~

