(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["promotionpages/pintuan/list/list"],{"1cbe":function(n,t,e){"use strict";e.r(t);var o=e("bfd7"),i=e("bd42");for(var s in i)"default"!==s&&function(n){e.d(t,n,(function(){return i[n]}))}(s);e("36c0");var a,u=e("f0c5"),r=Object(u["a"])(i["default"],o["b"],o["c"],!1,null,null,null,!1,o["a"],a);t["default"]=r.exports},"36c0":function(n,t,e){"use strict";var o=e("78c9"),i=e.n(o);i.a},"78c9":function(n,t,e){},"95d8":function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=i(e("4c22"));function i(n){return n&&n.__esModule?n:{default:n}}var s=function(){e.e("components/ns-adv/ns-adv").then(function(){return resolve(e("e478"))}.bind(null,e)).catch(e.oe)},a=function(){e.e("components/ns-goods-item/ns-goods-item-col").then(function(){return resolve(e("7689"))}.bind(null,e)).catch(e.oe)},u=function(){Promise.all([e.e("common/vendor"),e.e("components/ns-show-toast/ns-show-toast")]).then(function(){return resolve(e("f505"))}.bind(null,e)).catch(e.oe)},r={components:{nsAdv:s,nsGoodsItemCol:a,nsShowToast:u},data:function(){return{dataList:[]}},mixins:[o.default],onLoad:function(n){},onShow:function(){var n=this;if(!this.addonIsExit.pintuan)return this.$util.showToast({title:"商家未开启秒杀",mask:!0,duration:2e3}),void setTimeout((function(){n.$util.redirectTo("/pages/index/index/index",{},"redirectTo")}),2e3);this.$langConfig.refresh()},methods:{getData:function(n){var t=this;this.$api.sendRequest({url:"/pintuan/api/goods/page",data:{page_size:n.size,page:n.num},success:function(e){var o=[],i=e.message;0==e.code&&e.data?o=e.data.list:t.$util.showToast({title:i}),n.endSuccess(o.length),1==n.num&&(t.dataList=[]),t.dataList=t.dataList.concat(o),t.$refs.loadingCover&&t.$refs.loadingCover.hide()},fail:function(){n.endErr(),this.$refs.loadingCover&&this.$refs.loadingCover.hide()}})},toDetail:function(n){this.$util.redirectTo("/promotionpages/pintuan/detail/detail",{id:n.id})},imageError:function(n){this.dataList[n].sku_image=this.$util.getDefaultImage().default_goods_img,this.$forceUpdate()}}};t.default=r},bd42:function(n,t,e){"use strict";e.r(t);var o=e("95d8"),i=e.n(o);for(var s in o)"default"!==s&&function(n){e.d(t,n,(function(){return o[n]}))}(s);t["default"]=i.a},bfd7:function(n,t,e){"use strict";var o={nsAdv:function(){return e.e("components/ns-adv/ns-adv").then(e.bind(null,"e478"))},nsEmpty:function(){return e.e("components/ns-empty/ns-empty").then(e.bind(null,"211f"))},loadingCover:function(){return e.e("components/loading-cover/loading-cover").then(e.bind(null,"cd2f"))},nsShowToast:function(){return Promise.all([e.e("common/vendor"),e.e("components/ns-show-toast/ns-show-toast")]).then(e.bind(null,"f505"))}},i=function(){var n=this,t=n.$createElement;n._self._c},s=[];e.d(t,"b",(function(){return i})),e.d(t,"c",(function(){return s})),e.d(t,"a",(function(){return o}))},eba3:function(n,t,e){"use strict";(function(n){e("6b76");o(e("66fd"));var t=o(e("1cbe"));function o(n){return n&&n.__esModule?n:{default:n}}n(t.default)}).call(this,e("543d")["createPage"])}},[["eba3","common/runtime","common/vendor"]]]);