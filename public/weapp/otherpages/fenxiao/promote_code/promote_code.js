(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["otherpages/fenxiao/promote_code/promote_code"],{"0e34":function(t,e,n){"use strict";var o=n("71cb"),i=n.n(o);i.a},4590:function(t,e,n){"use strict";n.r(e);var o=n("6a64"),i=n.n(o);for(var a in o)"default"!==a&&function(t){n.d(e,t,(function(){return o[t]}))}(a);e["default"]=i.a},"590b":function(t,e,n){"use strict";n.r(e);var o=n("8579"),i=n("4590");for(var a in i)"default"!==a&&function(t){n.d(e,t,(function(){return i[t]}))}(a);n("0e34");var s,r=n("f0c5"),u=Object(r["a"])(i["default"],o["b"],o["c"],!1,null,null,null,!1,o["a"],s);e["default"]=u.exports},"6a64":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;n("bfe4");var o=i(n("4c22"));function i(t){return t&&t.__esModule?t:{default:t}}var a=function(){Promise.all([n.e("common/vendor"),n.e("components/ns-show-toast/ns-show-toast")]).then(function(){return resolve(n("f505"))}.bind(null,n)).catch(n.oe)},s={data:function(){return{poster:"",fenxiaoInfo:{}}},components:{nsShowToast:a},mixins:[o.default],methods:{getPoster:function(){var t=this;this.$api.sendRequest({url:"/fenxiao/api/fenxiao/poster",data:{page:"/pages/index/index/index",qrcode_param:JSON.stringify({})},success:function(e){t.$refs.loadingCover&&t.$refs.loadingCover.hide(),e.code>=0?t.poster=e.data.path:t.$util.showToast({title:"海报生成失败"})},fail:function(e){t.$refs.loadingCover&&t.$refs.loadingCover.hide(),t.$util.showToast({title:"海报生成失败"})}})},save:function(){var e=this;t.downloadFile({url:this.$util.img(this.poster),success:function(n){200===n.statusCode?t.saveImageToPhotosAlbum({filePath:n.tempFilePath,success:function(){e.$util.showToast({title:"保存成功"})},fail:function(){e.$util.showToast({title:"保存失败，请稍后重试"})}}):e.$util.showToast({title:"下载失败"})},fail:function(t){e.$util.showToast({title:"下载失败"})}})},getFenxiaoDetail:function(){var t=this;this.$api.sendRequest({url:"/fenxiao/api/fenxiao/detail",success:function(e){e.data?(t.fenxiaoInfo=e.data,t.getPoster(),t.setShareData()):t.$util.redirectTo("/otherpages/fenxiao/apply/apply",{},"redirectTo")}})},setShareData:function(){}},onLoad:function(){this.$langConfig.refresh(),t.getStorageSync("token")?this.getFenxiaoDetail():this.$util.redirectTo("/pages/login/login/login",{back:"/otherpages/fenxiao/promote_code/promote_code"})},onShareAppMessage:function(t){var e="/pages/index/index/index";return this.fenxiaoInfo.member_id&&(e+="?source_member="+this.fenxiaoInfo.member_id),{title:"快来加入我的团队吧，一起得佣金哦",path:e,success:function(t){},fail:function(t){}}}};e.default=s}).call(this,n("543d")["default"])},"71cb":function(t,e,n){},"7c73":function(t,e,n){"use strict";(function(t){n("6b76");o(n("66fd"));var e=o(n("590b"));function o(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])},8579:function(t,e,n){"use strict";var o={loadingCover:function(){return n.e("components/loading-cover/loading-cover").then(n.bind(null,"cd2f"))},nsShowToast:function(){return Promise.all([n.e("common/vendor"),n.e("components/ns-show-toast/ns-show-toast")]).then(n.bind(null,"f505"))}},i=function(){var t=this,e=t.$createElement,n=(t._self._c,t.$util.img(t.poster));t.$mp.data=Object.assign({},{$root:{g0:n}})},a=[];n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return o}))}},[["7c73","common/runtime","common/vendor"]]]);