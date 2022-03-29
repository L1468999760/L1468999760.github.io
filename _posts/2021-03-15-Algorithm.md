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
- [堆排](#堆排)
- [差分](#差分)
- [概率](#概率)
- [约瑟夫环](#约瑟夫环)
- [取石子](#取石子)
- [求质数](#求质数)
- [LRU](#lru)
- [KMP算法](#kmp算法)
- [双向BFS](#双向bfs)
- [单例模式](#单例模式)
- [字符串哈希](#字符串哈希)

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

中心扩散法，时间复杂度`O(n2)`

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

Manacher算法，马拉车算法，时间复杂度`O(n)`，思路：

+ 在字符串两端和每两个字符之间填充`#`，字符串长度变为`2n+1`，始终为奇数

+ 当在位置 i 开始进行中心拓展时，可以先找到` i `关于` j` 的对称点 `2 * j - i`。那么如果点` 2 * j - i `的臂长等于 `n`，就可以知道，点`i` 的臂长至少为 `min(j + length - i, n)`。那么就可以直接跳过`i` 到` i + min(j + length - i, n)` 这部分，从 `i + min(j + length - i, n) + 1` 开始拓展。

  

最长回文**子序列**（动态规划）

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

输出路径：

~~~
public class Solution {
    /**
     * retrun the longest increasing subsequence
     * @param arr int整型一维数组 the array
     * @return int整型一维数组
     */
    public int[] LIS (int[] arr) {
        // write code here
        int len = arr.length;
        if(len==0) return arr;
        int[] dp = new int[len];
        int[] nums = new int[len];
        dp[0] = arr[0];
        nums[0] = 1; // 下标为0的数截止的最长上升子序列的长度是1
        int cur = 0;
        for(int i=1;i<len;i++){
            if(arr[i]>dp[cur]){
                cur++;
                dp[cur] = arr[i];
                nums[i] = cur+1;
            }
            else{
                int l=0,r=cur;
                while(l<r){
                    int mid = l+(r-l)/2;
                    if(dp[mid]>=arr[i]) r=mid;
                    else l=mid+1;
                }
                dp[l] = arr[i];
                nums[i] = l+1;
            }
        }
        int[] ans = new int[cur+1];
        for(int i=nums.length-1;i>=0;i--){
            if(nums[i]==cur+1){
                ans[cur] = arr[i];
                cur--;
            }
        }
        return ans;
    }
}
~~~

## 树形dp

在树中选取若干节点，其中两两节点间不能相连，求最大值。（打家劫舍Ⅲ）

![](https://latex.codecogs.com/svg.image?\begin{cases}dp[i][1] = i.val + dp[i.left][0] + dp[i.right][0], \\dp[i][0] = max(dp[i.left][0],dp[i.left][1]) + max(dp[i.right][0],dp[i.right][1]).\end{cases}\)

其中 `dp[i][0]` 表示不选取 `i` 节点，`dp[i][1]`表示选取 `i` 节点。

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

例如给定`1 2 3`，则可能的集合为`{}、{1}、{1，2}、 {1,2,3}、{1,3}、{2}、{2,3}、{3}`。 

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

例如给定`1 2 3`，输出`{1 2 3}、{1 3 2}、{2 1 3}、{2 3 1}、{3 1 2}、{3 2 1}`.

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
		if(ok==0) continue; //去重，按照任意顺序输出
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

**含有重复数字的全排列**

按照字典序输出结果。

**方法一**

count计数法，使用一个哈希表记录每个数字出现的次数，然后深度优先遍历该哈希表。

```java
class Solution {
    List<List<Integer>> ans = new ArrayList<>();
    void dfs(int[] nums,List<Integer> cur,Map<Integer,Integer> map,int len) {
        if(cur.size()==len) {
            ans.add(new ArrayList(cur));
            return;
        } else {
            for(Map.Entry<Integer,Integer> it:map.entrySet()) {
                if(it.getValue()>0) {
                    map.put(it.getKey(),map.get(it.getKey())-1);
                    cur.add(it.getKey());
                    dfs(nums,cur,map,len);
                    cur.remove(cur.size()-1);
                    map.put(it.getKey(),map.get(it.getKey())+1);
                }
            }
        }
    }
    public List<List<Integer>> permuteUnique(int[] nums) {
        int len = nums.length;
        if(len==0) return ans;
        Map<Integer,Integer> map = new HashMap<>();
        Arrays.sort(nums);
        for(int i=0;i<len;i++) {
            map.put(nums[i],map.getOrDefault(nums[i],0)+1);
        }
        dfs(nums,new ArrayList<>(),map,len);
        return ans;
    }
}
```

**方法二**

先排序，对于相同的数字，保证每次都是拿从左往右第一个未被填过的数字。

时间复杂度`O(n × n!)`  。

```java
class Solution {
    int[] vis;
    void dfs(int[] nums,List<Integer> list,int cur,List<List<Integer> > ans) {
        if(cur == nums.length) {
            ans.add(new ArrayList(list));
            return;
        } else {
            for(int i=0;i<nums.length;i++) {
                // 当前数访问过 或 当前数与上一个数相同且上一个数没有被访问过
                if(vis[i]==1||(i>0&&nums[i]==nums[i-1]&&vis[i-1]==0)) continue;
                list.add(nums[i]);
                vis[i]=1;
                dfs(nums,list,cur+1,ans);
                list.remove(list.size()-1);
                vis[i]=0;
            }
        }
    }
    public List<List<Integer>> permuteUnique(int[] nums) {
        List<List<Integer> > ans = new ArrayList<>();
        int len = nums.length;
        if(len==0) return ans;
        Arrays.sort(nums);
        vis = new int[len];
        Arrays.fill(vis,0);
        dfs(nums,new ArrayList<>(),0,ans);
        return ans;
    }
}
```

## 0-1背包

有 `N` 件物品和一个容量是 `V` 的背包。每件物品只能使用一次。

第 `i` 件物品的体积是 `vi` ，价值是 `wi` 。

求解将哪些物品装入背包，可使这些物品的总体积不超过背包容量，且总价值最大。

输出最大价值。

**一般解法**

`dp[i][j]` 表示前 `i` 件物品体积为 `j` 的最大价值，

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

第 `i` 种物品最多有 `si` 件 。

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

递归：

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

非递归：

```java
void quickSort(int[] arr,int left,int right) {
    Deque<Integer> s = new ArrayDeque<>();
    s.push(left);
    s.push(right);
    while(!s.isEmpty()) {
        int rindex = s.poll(),lindex = s.poll();
        int l = lindex,r = rindex;
        int tmp = arr[l];
        while(l<r&&arr[r]>=tmp) r--;
        arr[l] = arr[r];
        while(l<r&&arr[l]<=tmp) l++;
        arr[r] = arr[l];
        arr[l] = tmp;
        if(l-1>lindex) {
            s.push(lindex);
            s.push(l-1);
        }
        if(l+1<rindex) {
            s.push(l+1);
            s.push(rindex);
        }
    }
}
```

## 堆排

堆排序是一种不稳定的排序，平均时间复杂度和最坏时间复杂度都是`O(nlogn)`。

小顶堆的实现：

```java
void adjust(int[] nums,int i,int len){ // i为待调整节点
     int j  =2*i+1; // 左子节点（下标从0开始）
     while(j<len){
         if(j+1<len && nums[j+1]>nums[j]) j++; // 如果右孩子比左孩子大，切换到右孩子
         if(nums[j]<nums[i]) break; // 都比父节点小
         else{
             int tmp = nums[i];
             nums[i] = nums[j];
             nums[j] = tmp;
             i=j; // 向下调整
             j=2*i+1;
            }
     }
}

void HeapSort(int[] nums){
    int len = nums.length;
    for(int i=len/2-1;i>=0;i--) adjust(nums,i,len);
    for(int i=len-1;i>=1;i--){
        // 交换堆顶和最后一个元素
        int tmp = nums[0];
        nums[0] = nums[i];
        nums[i] = tmp;
                
        adjust(nums,0,i); // 去除最后一个元素
    }
}
        
public static void main(String[] args){
    int[] arr = {4,1,6,2,5,3,7};
    HeapSort(arr);
}
```

## 差分

将区间 `[l,r]` 整体增加一个值 `v`。

+ `dif[l] += v` ：对于所有下标大于等于 `l` 的位置都增加了 `v`；
+ `dif[r+1] -= v` ：对下标大于 `r` 的位置减少 `v`，抵消影响。

> 练习：1109.航班预定统计, 995.K连续位的最小翻转次数

## 概率

`rand7() → rand10()`

使用两个`rand7()`构造`1-49`的均匀分布，然后去除`1-9`的数。

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

 `0,1,···,n-1`这`n`个数字排成一个圆圈，从数字`0`开始，每次从这个圆圈里删除第`m`个数字（删除后从下一个数字开始计数）。求出这个圆圈里剩下的最后一个数字。 

**方法一：模拟**

 使用ArrayList模拟，时间复杂度 `O(mn）`，空间复杂度`O(n)`。

**方法二：递归+反推**

时间复杂度`O(n)`，空间复杂度`O(n)`。

当删除了第`m%n`个元素后，剩下一个长度为`n-1`的序列。递归地求解`f(n-1,m)`，令`x=f(n-1,m）`。长度为`n`的序列最后一个删除的元素，应当是从`m%n`开始数的第`x`个元素：

`f(n,m) = (m%n+x)%n = (m+x)%n`

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

时间复杂度`O(n）`，空间复杂度`O(1)`。

（当前`index`+`m`）%上一轮剩余数字的个数

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

## 取石子

> 奇异局势：当玩家面临奇异局势时会失败。

**巴什博弈**

+ 游戏双方轮流取石子（共`N`颗）
+ 每人每次取走若干颗石子（最少取`1`颗，最多取`K`颗）
+ 石子取光，则游戏结束
+ 最后取石子的一方获胜

```c++
if(N%(K+1)==0) return false;
else return true;
```

**尼姆博弈**

+ `K` 堆各 `N1，N2，...，Nk` 颗石子
+ 每次至少取一个，多者不限
+ 最后取走的一方获胜

**解析**

`（0，0，0，...，0）`是奇异局势，`（1，1，0，...，0）`也是奇异局势。

奇异局势时，后手只需要取相等数量的石子，先手必败。

奇异局势时，二进制每一比特位上`1`的个数是偶数，使用异或运算求解。

```c++
if(N1^N2^...^Nk==0) return false;
else return true;
```

**威佐夫博弈**

+ 两堆各a，b颗石子
+ 每次从一堆或者同时从两堆取同样多个石子，每次至少取一个，多者不限
+ 最后取走的一方获胜

**解析**

前几个奇异局势：`（0，0）、（1，2）、（3，5）、（4，7）、（6，10）`。

其中，`a[0]=b[0]=0`，`a[k]`是未在前面出现过的最小自然数，`b[k]=a[k]+k` 。

换一种表达方式：

 ![](https://latex.codecogs.com/svg.image?a[k]=\left&space;\lfloor&space;(b[k]-a[k])\frac{\sqrt{5}&plus;1}{2}\right&space;\rfloor,a[k]\leq&space;b[k])

>奇异局势的性质：
>
>① 任何自然数都包含在一个且仅有一个奇异局势中；
>
>②  任意操作都可将奇异局势变为非奇异局势 ；
>
>③  采用适当的方法，可以将非奇异局势变为奇异局势 。

## 求质数

**线性筛法**

如果一个数是质数，那么它的整数倍（大于等于2倍）就一定不是质数。例如2是质数，4、6、8不是质数。

~~~c++
#include <iostream>
#include <cstring>
#include <string>
using namespace std;

int prime[10000001]; //存储素数
bool vis[10000001]; //每个数是否是素数
int main(){
    int n,cnt = 0;
    cin>>n;
    memset(vis,false,sizeof(vis));
    memset(prime,0,sizeof(prime));
    for(int i = 2;i <= n;i++){
        if(!vis[i]){
            prime[cnt++] = i;
            for(int j = 1;j*i<=n;j++) vis[j*i] = true;
        }
    }
    return 0;
}
~~~

**欧拉筛法**

是线性筛法的改进。例如6不是质数，在判断2的时就已经筛过了，到判断3的时候不用再筛了。

```c++
for(int i = 2;i <= n;i++){
    if(!vis[i]){
        prime[cnt++] = i;
        for(int j=0;j<cnt && i*prime[j]<=n;j++){
            vis[i*prime[j]] = true;
            if(i % prime[j] == 0) break;
        }
    }
}
```

## LRU

哈希表+双向链表

~~~c++
class LRUCache {
public:
    struct DLink{
        int key,value;
        DLink* prev;
        DLink* next;
        DLink(){}
        DLink(int _key,int _value){
            this->key = _key;
            this->value = _value;
        }
    };
    map<int,DLink*> m;
    int maxsize;
    DLink* head;
    DLink* tail;

    LRUCache(int capacity) {
        head = new DLink();
        tail = new DLink();
        maxsize = capacity;
        head->next = tail;
        tail->prev = head;
    }

    int get(int key) {
        if(m.count(key)){
            DLink* node = m[key];
            //移除该节点
            node->prev->next = node->next;
            node->next->prev = node->prev;
            //插入到头部
            node->next = head->next;
            head->next->prev = node;
            head->next = node;
            node->prev = head;
            return m[key]->value;
        }
        else return -1;
    }

    void put(int key, int value) {
        if(m.count(key)){
            DLink* node = m[key];
            node->value = value;
            //移除
            node->prev->next = node->next;
            node->next->prev = node->prev;
            //插入头部
            node->next = head->next;
            head->next->prev = node;
            head->next = node;
            node->prev = head;
        }
        else{
            DLink* newNode = new DLink(key,value);
            //插入头部
            head->next->prev = newNode;
            newNode->next = head->next;
            head->next = newNode;
            newNode->prev = head;
            m.insert(pair<int,DLink*>(key,newNode));
            if(m.size()>maxsize){
                m.erase(tail->prev->key);
                DLink* curNode = tail->prev;
                //移除尾部
                curNode->prev->next = tail;
                tail->prev = curNode->prev;
            }
        }
    }
};
~~~

## KMP算法

设模式串`pattern`的长度为`n`，主串`str`的长度为`m`，时间复杂度为`O(m+n)`。

`next`数组表示模式串的子串的前缀和后缀相同的最长长度，`next[i]`表示最大的`x`，满足`pattern[0:x-1]`是`pattern[0:i-1]`的后缀。

KMP的详细过程参考https://segmentfault.com/a/1190000008575379

~~~java
public int kmp (String S, String T) { //S是模式串，T是主串
	int[] next = getNext(S);
    int i = 0; //T下标
    int j = 0; //S下标
    int len1 = T.length();
    int len2 = S.length();
    
    while(i<len1&&j<len2){
        if(j==-1||S.charAt(j)==T.charAt(i)){ //T的第一个字符不匹配或S与T对应位匹配
            i++;
            j++;
        }
        else j = next[j];
    }
	if(j==len2) return i-j; //如果匹配成功，返回主串匹配位置的首位
    else return -1;
}

int[] getNext(String S){
    int len = S.length();
    int[] next = new int[len];
    int i = 0; //S下标
    int j = -1; //T下标
    next[0] = -1;
    
    while(i<len){
        if(j==-1||S.charAt(i)==S.charAt(j)){
            i++;
            j++;
            next[i] = j;
        }
        else j = next[j];
    }
}
~~~

## 双向BFS

朴素BFS可能造成搜索空间爆炸，瓶颈在于搜索空间中的最大宽度。

**当前问题：**从源点开始搜索，直到找到目标节点，得到最短路径。

**转换问题：**同时从源点和汇点开始搜索，一旦搜索到相同的值，即可得到最短路径。

方法：

+ 创建两个队列分别用于两个方向的搜索；
+ 创建两个哈希表用于解决相同节点重复搜索和记录转换次数；
+ 为了尽可能让两个搜索方向“平均”，每次从队列中取值进行扩展时，先判断哪个队列容量较少；
+ 如果在搜索过程中搜索到对方搜索过的节点，说明找到了最短路径。

> 参考自leetcode752 [宫水三叶]题解。
>
> 相关题目：127 单词接龙、752 打开转盘锁

## 单例模式

`uniqueInstance`采用`volatile`关键字修饰，`uniqueInstance = new Singleton();`代码分三步执行：

+ 为`uniqueInstance`分配内存空间
+ 初始化`uniqueInstance`
+ 将`uniqueInstance`指向分配的内存地址

使用`volatile`可以禁止JVM的指令重排，多线程下也能正常运行。

~~~java
public class Singleton {
    private volatile static Singleton uniqueInstance;
    private Singleton() {
    }
    public static Singleton getUniqueInstance() {
        if (uniqueInstance == null) {
            synchronized (Singleton.class) {
                if (uniqueInstance == null) {
                    uniqueInstance = new Singleton();
                }
            }
        }
        return uniqueInstance;
    }
}
~~~

## 字符串哈希

使用两个不同的mod值来计算Hash，如果两个Hash值都相等才认为是同一个字符串。

原字符串为s，pre是一个大整数，可以取为233333，base数组存储pre的幂。

![](https://latex.codecogs.com/svg.image?\left\{\begin{matrix}Hash[0]&space;=&space;0\\Hash[i]&space;=&space;(Hash[i-1]*pre&space;&plus;&space;s[i]-'a'&plus;1)\%mod\end{matrix}\right.&space;)

![](https://latex.codecogs.com/svg.image?\left\{\begin{matrix}base[0]&space;=&space;1\\base[i]&space;=&space;base[i-1]*pre\end{matrix}\right.&space;)

区间Hash值：

<img src="https://latex.codecogs.com/svg.image?Hash[L...R]&space;=&space;(Hash[R]-Hash[L-1]*base[R-L&plus;1]&plus;mod)\%mod" title="https://latex.codecogs.com/svg.image?Hash[L...R] = (Hash[R]-Hash[L-1]*base[R-L+1]+mod)\%mod" />

~~~java
// 字符串str的长度为len
long[] hash = new long[len+1];
long[] base = new long[len+1];
long pre = 233333; 

base[0] = 1;
for(int i = 1; i <= len; i++)
{
	hash[i] = hash[i - 1] * pre + str[i-1] - 'a' + 1;
	base[i] = base[i - 1] * pre;
}
// L~R的hash值
hashLR = hash[R] - hash[L - 1] * base[R - L + 1]; 
~~~

> 参考：
>
> https://blog.csdn.net/qq_45778406/article/details/113920372
>
> 1044 最长重复子串