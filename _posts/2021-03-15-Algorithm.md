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
- [树形dp](#树形dp)
- [求所有集合的子集](#求所有集合的子集)
- [全排列](#全排列)
- [0-1背包](#0-1背包)
- [多重背包](#多重背包)
- [完全背包](#完全背包)
- [快速幂](#快速幂)
- [快排](#快排)
- [差分](#差分)
- [概率](#概率)
- [约瑟夫环](#约瑟夫环)

## Kruskal（并查集）

~~~
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

~~~
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

~~~
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

~~~
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

~~~
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

## 树形dp

在树中选取若干节点，其中两两节点间不能相连，求最大值。（打家劫舍Ⅲ）

![](https://latex.codecogs.com/svg.image?\begin{cases}dp[i][1] = i.val + dp[i.left][0] + dp[i.right][0], \\dp[i][0] = max(dp[i.left][0],dp[i.left][1]) + max(dp[i.right][0],dp[i.right][1]).\end{cases}\)

其中 dp[i][0] 表示不选取 i 节点，dp[i][1]表示选取 i 节点。

~~~
class Solution {
    int[] dfs(TreeNode root){
        if(root==null) return new int[]{0,0};
        int[] tmp = new int[2];
        var l = dfs(root.left);
        var r = dfs(root.right);
        tmp[0] = Math.max(l[0],l[1]) + Math.max(r[0],r[1]);
        tmp[1] = root.val + l[0] + r[0];
        return tmp;
    }
    public int rob(TreeNode root) {
       var res = dfs(root);
       return Math.max(res[0],res[1]);
    }
}
~~~

## 求所有集合的子集

例如给定1 2 3，则可能的集合为{}、{1}、{1，2}、 {1,2,3}、{1,3}、{2}、{2,3}、{3}。 

dfs法

~~~
class Solution{
public:
    vector<vector<int>> res;
    void dfs(int pos,vector<int> nums,vector<int> cur){
        if(pos==nums.size()){
            res.push_back(cur);
            return;
        }
        //选取
        cur.push_back(nums[pos]);
        dfs(pos+1,nums,cur);
        cur.pop_back();
        //不选取
        dfs(pos+1,nums,cur);
    }
    vector<vector<int>> subsetGet(vector<int>& nums){
        vector<int> cur;
        dfs(0,nums,cur);
        return res;
    }
}
~~~

二进制法

~~~
class Solution {
public:
    vector<vector<int>> subsetGet(vector<int>& nums) {
        int len=nums.size();
        if(len==0) return res;
        
        vector<vector<int>> ans; //开辟二维数组
        int all_set = 1 << nums.size(); //所有的可能数 +1

        for(int i = 0; i < all_set; i++){
            vector<int> item; //开辟一维数组
            int cur=0;
            for(int j = 0; j < nums.size(); j++){
                if(i & (1 << j)){ //某位置元素是否存在的条件
                    item.push_back(nums[j]);
                }
            }
            ans.push_back(item);
        }
        return ans;

    }
};
~~~

## 全排列

例如给定1 2 3，输出{1 2 3}、{1 3 2}、{2 1 3}、{2 3 1}、{3 1 2}、{3 2 1}.

~~~
class Solution {
public:
    vector<vector<int>> ans;
    void swap(int from,int to,vector<int> cur)
    {
        if(from==to) ans.push_back(cur);
        else
        {
            for(int i=from;i<=to;i++)
            {
                int ok=1;
		for(int j=from;j<i;j++){
		   if(cur[j]==cur[i]){
		      ok=0;
		      break;
		   }
		}
		if(ok==0) continue; //去重
                int tmp=cur[i];
                cur[i]=cur[from];
                cur[from]=tmp;
                swap(from+1,to,cur);
                cur[from]=cur[i];
                cur[i]=tmp;
            }
        }
    }
    vector<vector<int>> permute(vector<int>& nums) {
        int len=nums.size();
        if(!len) return ans;
        //sort(nums.begin(),nums.end());
        swap(0,len-1,nums);
        return ans;
    }
};
~~~

## 0-1背包

有 N 件物品和一个容量是 V 的背包。每件物品只能使用一次。

第 i 件物品的体积是 vi ，价值是 wi 。

求解将哪些物品装入背包，可使这些物品的总体积不超过背包容量，且总价值最大。

输出最大价值。

**一般解法**

dp[i][j] 表示前 i 件物品体积为 j 的最大价值，

<img src="https://latex.codecogs.com/svg.image?dp[i][j]=max(dp[i-1][j],dp[i-1][j-v[i]]&plus;w[i])" title="dp[i][j]=max(dp[i-1][j],dp[i-1][j-v[i]]+w[i])" />

~~~java
class Main{
    public static void main(String[] args){
        Scanner scan = new Scanner(System.in);
        int N,V;
        int v,w;
        int[][] dp = new int[1001][1001];
        N=scan.nextInt();
        V=scan.nextInt();
        for(int i=1;i<=N;i++){
            v=scan.nextInt();
            w=scan.nextInt();
            for(int j=V;j>=0;j--){
                if(j<v) dp[i][j]=dp[i-1][j];
                else dp[i][j]=Math.max(dp[i-1][j],dp[i-1][j-v]+w);
            }
        }
        System.out.println(dp[N][V]);
    }
}
~~~

**一维优化**

<img src="https://latex.codecogs.com/svg.image?dp[j]=max(dp[j],dp[j-v]&plus;w),v\leq&space;j\leq&space;V" title="dp[j]=max(dp[j],dp[j-v]+w),v\leq j\leq V" />

容量要逆序枚举。

~~~java
for(int i=1;i<=N;i++){
    v=scan.nextInt();
    w=scan.nextInt();
    for(int j=V;j>=v;j--){
        dp[j]=Math.max(dp[j],dp[j-v]+w);
    }
}
~~~

## 多重背包

第 i 种物品最多有 si 件 。

**一般解法**

<img src="https://latex.codecogs.com/svg.image?dp[i][j]=max(dp[i][j],dp[i-1][j-v[i]*k]&plus;w[i]*k),0\leq&space;k\leq&space;s_i" title="dp[i][j]=max(dp[i][j],dp[i-1][j-v[i]*k]+w[i]*k),0\leq k\leq s_i" />

**二进制优化**

如果同一种物品的数量有很多，算法的复杂度过高。

将相同种类的多个物品重组，转化为0-1背包问题。

例如一种物品有11件，可以分解为11=1+2+4+4。

一种物品11件 → 四种物品各1件。

~~~java
class Main{
    public static void main(String[] args){
        Scanner scan = new Scanner(System.in);
        int N,V;
        int v,w,s;
        int cnt = 0;//分组的组别
        int[] volume = new int[12001];
        int[] worth = new int[12001];
        int[] dp = new int[2001];
        N=scan.nextInt();
        V=scan.nextInt();
        for(int i=1;i<=N;i++){
            v=scan.nextInt();
            w=scan.nextInt();
            s=scan.nextInt();
            int k = 1;//组别里的个数
            while(k<=s){
            	cnt++;//从1开始
            	volume[cnt]=v*k;//整体体积
            	worth[cnt]=w*k;//整体价值
            	s-=k;
            	k*=2;
            }
            //剩余的形成一组
            if(s>0){
            	cnt++;
            	volume[cnt]=v*s;
            	worth[cnt]=w*s;
            }
        }
        for(int i=1;i<=cnt;i++){
        	for(int j=V;j>=volume[i];j--){
        		dp[j]=Math.max(dp[j],dp[j-volume[i]]+worth[i]);
        	}
        }
        System.out.println(dp[V]);
    }
}
~~~

**单调队列优化**

以后补上。

## 完全背包

每种物品有无限件可用。

**一般解法**

<img src="https://latex.codecogs.com/svg.image?dp[i][j]=max(dp[i][j],dp[i-1][j-v[i]*k]&plus;w[i]*k),0\leq&space;k*v[i]\leq&space;j" title="dp[i][j]=max(dp[i][j],dp[i-1][j-v[i]*k]+w[i]*k),0\leq k*v[i]\leq j" />

**一维优化**

<img src="https://latex.codecogs.com/svg.image?dp[j]&space;=&space;max(dp[j],dp[j-v]&plus;w),v\leq&space;j\leq&space;V" title="dp[j] = max(dp[j],dp[j-v]+w),v\leq j\leq V" />

容量要顺序枚举。

> 更多种背包问题见[《背包九讲》](https://github.com/tianyicui/pack/blob/master/V2.pdf)

## 快速幂

<img src="https://latex.codecogs.com/svg.image?(a^n)\quad&space;mod&space;\quad&space;m" title="(a^n)\quad mod \quad m" />

~~~java
int pow_mod(int a,int n,int m){
    long ans = 1L;
    while(n>0){
        if((n&1)==1){
            ans*=a;
            ans%=m;
        }
        a*=a;
        a%=m;
        n>>=1;
    }
    return (int)ans;
}
~~~

## 快排

~~~java
void quicksort(int[] arr,int left,int right){
    if(left >= right) return;
    int tmp = arr[left],l = left,r = right;
    while(l<r){
        while(l<r && tmp<=arr[r]) r--;
        arr[l] = arr[r];
        while(l<r && tmp>=arr[l]) l++;
        arr[r] = arr[l];
    }
    arr[l] = tmp;
    quicksort(arr,left,l-1);
    quicksort(arr,l+1,right);
}
~~~

## 差分

将区间 [l,r] 整体增加一个值 v。

+ dif[l] += v ：对于所有下标大于等于 l 的位置都增加了 v；
+ dif[r+1] -= v ：对下标大于 r 的位置减少 v，抵消影响。

> 练习：1109.航班预定统计, 995.K连续位的最小翻转次数

## 概率

rand7() → rand10()

使用两个rand7()构造1-49的均匀分布，然后去除1-9的数。

~~~java
class Solution extends SolBase {
    public int rand10() {
        int a = (rand7()-1)*7,b = rand7();
        if(a+b<10) return rand10();
        return (a+b)%10+1;
    }
}
~~~

## 约瑟夫环

 0,1,···,n-1这n个数字排成一个圆圈，从数字0开始，每次从这个圆圈里删除第m个数字（删除后从下一个数字开始计数）。求出这个圆圈里剩下的最后一个数字。 
 
**方法一：模拟**
 
 使用ArrayList模拟，时间复杂度 O(mn），空间复杂度O(n)。
 
**方法二：递归+反推**

时间复杂度O(n)，空间复杂度O(n)。

当删除了第m%n个元素后，剩下一个长度为n-1的序列。递归地求解f(n-1,m)，令x=f(n-1,m）。长度为n的序列最后一个删除的元素，应当是从m%n开始数的第x个元素：

f(n,m) = (m%n+x)%n = (m+x)%n

```java
class Solution {
    public int lastRemaining(int n, int m) {
        return f(n, m);
    }

    public int f(int n, int m) {
        if (n == 1) {
            return 0;
        }
        int x = f(n - 1, m);
        return (m + x) % n;
    }
}
```

**方法三：迭代+反推**

时间复杂度O(n），空间复杂度O(1)。

（当前index+m）%上一轮剩余数字的个数

```java
class Solution {
    public int lastRemaining(int n, int m) {
        int ans = 0;
        // 最后一轮剩下2个人，所以从2开始反推
        for (int i = 2; i <= n; i++) {
            ans = (ans + m) % i;
        }
        return ans;
    }
}
```
