(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/ns-copyright/ns-copyright"],{"3e49":function(t,n,o){"use strict";var i,e=function(){var t=this,n=t.$createElement,o=(t._self._c,t.$util.img(t.bottom_info.logo)),i=t.$util.img("upload/uniapp/logo_copy.png");t.$mp.data=Object.assign({},{$root:{g0:o,g1:i}})},c=[];o.d(n,"b",(function(){return e})),o.d(n,"c",(function(){return c})),o.d(n,"a",(function(){return i}))},"6d1d":function(t,n,o){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i={data:function(){return{bottom_info:{}}},created:function(){this.getAdvList()},methods:{getAdvList:function(){var t=this;this.$api.sendRequest({url:"/api/config/copyright",success:function(n){0==n.code&&n.data&&(t.bottom_info=n.data)},fail:function(){}})},link:function(t){t&&this.$util.redirectTo("/otherpages/web/web?src="+t)}}};n.default=i},b1c7:function(t,n,o){"use strict";o.r(n);var i=o("3e49"),e=o("b2cc");for(var c in e)"default"!==c&&function(t){o.d(n,t,(function(){return e[t]}))}(c);o("d653");var u,a=o("f0c5"),r=Object(a["a"])(e["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],u);n["default"]=r.exports},b2cc:function(t,n,o){"use strict";o.r(n);var i=o("6d1d"),e=o.n(i);for(var c in i)"default"!==c&&function(t){o.d(n,t,(function(){return i[t]}))}(c);n["default"]=e.a},d653:function(t,n,o){"use strict";var i=o("fa53"),e=o.n(i);e.a},fa53:function(t,n,o){}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/ns-copyright/ns-copyright-create-component',
    {
        'components/ns-copyright/ns-copyright-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("b1c7"))
        })
    },
    [['components/ns-copyright/ns-copyright-create-component']]
]);
