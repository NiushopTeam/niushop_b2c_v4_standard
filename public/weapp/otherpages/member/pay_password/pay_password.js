(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["otherpages/member/pay_password/pay_password"],{"0104":function(e,t,s){"use strict";var n=s("174f"),i=s.n(n);i.a},"174f":function(e,t,s){},"250b":function(e,t,s){"use strict";s.r(t);var n=s("98d0"),i=s.n(n);for(var o in n)"default"!==o&&function(e){s.d(t,e,(function(){return n[e]}))}(o);t["default"]=i.a},"56d9":function(e,t,s){"use strict";var n={mypOne:function(){return s.e("components/myp-one/myp-one").then(s.bind(null,"82bc"))},nsShowToast:function(){return Promise.all([s.e("common/vendor"),s.e("components/ns-show-toast/ns-show-toast")]).then(s.bind(null,"f505"))}},i=function(){var e=this,t=e.$createElement,s=(e._self._c,e._f("mobile")(e.memberInfo.mobile));e.$mp.data=Object.assign({},{$root:{f0:s}})},o=[];s.d(t,"b",(function(){return i})),s.d(t,"c",(function(){return o})),s.d(t,"a",(function(){return n}))},"98d0":function(e,t,s){"use strict";(function(e){Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n=i(s("4c22"));function i(e){return e&&e.__esModule?e:{default:e}}var o=function(){s.e("components/myp-one/myp-one").then(function(){return resolve(s("82bc"))}.bind(null,s)).catch(s.oe)},a=function(){Promise.all([s.e("common/vendor"),s.e("components/ns-show-toast/ns-show-toast")]).then(function(){return resolve(s("f505"))}.bind(null,s)).catch(s.oe)},c={components:{mypOne:o,nsShowToast:a},data:function(){return{isClick:!1,step:1,key:"",code:"",password:"",repassword:"",isSub:!1,back:"",memberInfo:{},dynacodeData:{seconds:120,timer:null,codeText:"获取验证码",isSend:!1}}},mixins:[n.default],methods:{input:function(e){0==this.step?4==e.length?(this.isClick=!0,this.code=e):this.isClick=!1:1==this.step?6==e.length?(this.isClick=!0,this.password=e):this.isClick=!1:6==e.length?(this.isClick=!0,this.repassword=e):this.isClick=!1},confirm:function(){var e=this;if(this.isClick)if(0==this.step)this.$api.sendRequest({url:"/api/member/verifypaypwdcode",data:{code:this.code,key:this.key},success:function(t){0==t.code?(e.$refs.input.clear(),e.isClick=!1,e.step=1):e.$util.showToast({title:t.message})}});else if(1==this.step)this.$refs.input.clear(),this.isClick=!1,this.step=2;else if(this.password==this.repassword){if(this.isSub)return;this.isSub=!0,this.$api.sendRequest({url:"/api/member/modifypaypassword",data:{key:this.key,code:this.code,password:this.password},success:function(t){t.code>=0?(e.$util.showToast({title:"修改成功"}),setTimeout((function(){e.back?e.$util.redirectTo(e.back,{},"redirectTo"):e.$util.redirectTo("/pages/member/index/index",{},"reLaunch")}),2e3)):(e.initInfo(),e.$util.showToast({title:t.message}))}})}else this.$util.showToast({title:"两次输入的密码不一致",success:function(t){e.initInfo()}})},initInfo:function(){this.isClick=!1,this.step=1,this.password="",this.repassword="",this.oldpassword="",this.isSub=!1,this.$refs.input.clear()},getMemberInfo:function(){var t=this;this.$api.sendRequest({url:"/api/member/info",success:function(s){0==s.code&&(t.memberInfo=s.data,""==t.memberInfo.mobile?e.showModal({title:"提示",content:"设置支付密码需要先绑定手机号,是否立即绑定?",success:function(e){e.confirm?t.$util.redirectTo("/pages/member/info/info",{action:"mobile",back:t.back},"redirectTo"):t.back?t.$util.redirectTo(t.back):t.$util.redirectTo("/pages/member/index/index",{},"redirectTo")}}):(t.step=0,t.sendMobileCode()))}})},sendMobileCode:function(){var e=this;120==this.dynacodeData.seconds&&(this.dynacodeData.isSend||(this.dynacodeData.isSend=!0,this.$api.sendRequest({url:"/api/member/paypwdcode",success:function(t){e.dynacodeData.isSend=!1,t.code>=0?(e.key=t.data.key,120==e.dynacodeData.seconds&&null==e.dynacodeData.timer&&(e.dynacodeData.timer=setInterval((function(){e.dynacodeData.seconds--,e.dynacodeData.codeText=e.dynacodeData.seconds+"s后可重新获取"}),1e3))):e.$util.showToast({title:t.message})},fail:function(){e.$util.showToast({title:"request:fail"}),e.dynacodeData.isSend=!1}})))}},onLoad:function(t){this.$langConfig.refresh(),t.back&&(this.back=t.back),e.getStorageSync("token")?this.getMemberInfo():this.$util.redirectTo("/pages/login/login/login")},filters:{mobile:function(e){return e.substring(0,3)+"****"+e.substring(7)}},watch:{"dynacodeData.seconds":{handler:function(e,t){0==e&&(clearInterval(this.dynacodeData.timer),this.dynacodeData={seconds:120,timer:null,codeText:"获取动态码",isSend:!1})},immediate:!0,deep:!0}}};t.default=c}).call(this,s("543d")["default"])},bf89:function(e,t,s){"use strict";s.r(t);var n=s("56d9"),i=s("250b");for(var o in i)"default"!==o&&function(e){s.d(t,e,(function(){return i[e]}))}(o);s("0104");var a,c=s("f0c5"),r=Object(c["a"])(i["default"],n["b"],n["c"],!1,null,null,null,!1,n["a"],a);t["default"]=r.exports},c514:function(e,t,s){"use strict";(function(e){s("6b76");n(s("66fd"));var t=n(s("bf89"));function n(e){return e&&e.__esModule?e:{default:e}}e(t.default)}).call(this,s("543d")["createPage"])}},[["c514","common/runtime","common/vendor"]]]);