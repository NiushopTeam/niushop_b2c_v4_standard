(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["otherpages/notice/detail/detail"],{3213:function(t,e,n){"use strict";(function(t){n("6b76");o(n("66fd"));var e=o(n("4660"));function o(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])},4660:function(t,e,n){"use strict";n.r(e);var o=n("e8f7"),i=n("b4fb");for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);n("6dbe");var a,c=n("f0c5"),s=Object(c["a"])(i["default"],o["b"],o["c"],!1,null,null,null,!1,o["a"],a);e["default"]=s.exports},"6dbe":function(t,e,n){"use strict";var o=n("b3b7"),i=n.n(o);i.a},b3b7:function(t,e,n){},b4fb:function(t,e,n){"use strict";n.r(e);var o=n("c9b5"),i=n.n(o);for(var r in o)"default"!==r&&function(t){n.d(e,t,(function(){return o[t]}))}(r);e["default"]=i.a},c9b5:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=r(n("37ea")),i=r(n("4c22"));function r(t){return t&&t.__esModule?t:{default:t}}var a=function(){Promise.all([n.e("common/vendor"),n.e("components/ns-show-toast/ns-show-toast")]).then(function(){return resolve(n("f505"))}.bind(null,n)).catch(n.oe)},c={data:function(){return{noticeId:0,content:"",detail:{}}},components:{nsShowToast:a},mixins:[i.default],onLoad:function(t){var e=this;t.notice_id?this.noticeId=t.notice_id:this.$util.redirectTo("/otherpages/notice/list/list",{},"redirectTo"),this.$api.sendRequest({url:"/api/notice/info",data:{id:this.noticeId},success:function(t){0==t.code?t.data?(e.detail=t.data,e.content=(0,o.default)(t.data.content),e.$refs.loadingCover&&e.$refs.loadingCover.hide()):e.$util.redirectTo("/otherpages/notice/list/list",{},"redirectTo"):(e.$util.showToast({title:t.message}),setTimeout((function(){e.$util.redirectTo("/otherpages/notice/list/list",{},"redirectTo")}),2e3))},fail:function(t){e.$util.redirectTo("/otherpages/notice/list/list",{},"redirectTo"),e.$refs.loadingCover&&e.$refs.loadingCover.hide()}})},onShow:function(){this.$langConfig.refresh()},methods:{},onShareAppMessage:function(t){var e="[公告]"+this.detail.title,n="/otherpages/notice/detail/detail?notice_id="+this.noticeId;return{title:e,path:n,success:function(t){},fail:function(t){}}}};e.default=c},e8f7:function(t,e,n){"use strict";var o={loadingCover:function(){return n.e("components/loading-cover/loading-cover").then(n.bind(null,"cd2f"))},nsShowToast:function(){return Promise.all([n.e("common/vendor"),n.e("components/ns-show-toast/ns-show-toast")]).then(n.bind(null,"f505"))}},i=function(){var t=this,e=t.$createElement,n=(t._self._c,t.$util.timeStampTurnTime(t.detail.create_time));t.$mp.data=Object.assign({},{$root:{g0:n}})},r=[];n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return o}))}},[["3213","common/runtime","common/vendor"]]]);