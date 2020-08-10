/**
 * 空的验证组件，后续如果增加业务，则更改组件
 */
var couponListHtml = '<div class="goods-list-edit layui-form">';

		couponListHtml += '<div class="layui-form-item">';
			couponListHtml += '<label class="layui-form-label sm">优惠券来源</label>';
			couponListHtml += '<div class="layui-input-block">';
				couponListHtml += '<template v-for="(item,index) in goodsSources" v-bind:k="index">';
					couponListHtml += '<div v-on:click="data.sources=item.value" v-bind:class="{ \'layui-unselect layui-form-radio\' : true,\'layui-form-radioed\' : (data.sources==item.value) }"><i class="layui-anim layui-icon">&#xe643;</i><div>{{item.text}}</div></div>';
				couponListHtml += '</template>';
			couponListHtml += '</div>';
		couponListHtml += '</div>';
		
		couponListHtml += '<div class="layui-form-item" v-if="data.sources == \'diy\'">';
			couponListHtml += '<label class="layui-form-label sm">手动选择</label>';
			couponListHtml += '<div class="layui-input-block">';
				couponListHtml += '<a href="#" class="ns-input-text ns-text-color" v-on:click="addCoupon">选择</a>';
			couponListHtml += '</div>';
		couponListHtml += '</div>';
		
		couponListHtml += '<div class="layui-form-item" v-show="data.sources == \'default\'">';
			couponListHtml += '<label class="layui-form-label sm">优惠券数量</label>';
			couponListHtml += '<div class="layui-input-block">';
				// couponListHtml += '<input class="layui-input goods-account" v-model="data.couponCount" />';
				couponListHtml += '<input type="number" class="layui-input goods-account" v-on:keyup="shopNum" v-model="data.couponCount"/>';
			couponListHtml += '</div>';
		couponListHtml += '</div>';
		
		couponListHtml += '<div class="layui-form-item" v-show="data.sources == \'default\'">';
			couponListHtml += '<label class="layui-form-label sm"></label>';
			couponListHtml += '<div class="layui-input-block">';
				couponListHtml += '<template v-for="(item,index) in couponCount" v-bind:k="index">';
					couponListHtml += '<div v-on:click="data.couponCount=item" v-bind:class="{ \'layui-unselect layui-form-radio\' : true,\'layui-form-radioed\' : (data.couponCount==item) }"><i class="layui-anim layui-icon">&#xe643;</i><div>{{item}}</div></div>';
				couponListHtml += '</template>';
			couponListHtml += '</div>';
		couponListHtml += '</div>';

		// couponListHtml += '<p class="hint">商品数量选择 0 时，前台会自动上拉加载更多</p>';
		
	couponListHtml += '</div>';

Vue.component("coupon-list", {
	template: couponListHtml,
	data: function () {
		return {
			data: this.$parent.data,
			goodsSources: [
				{
					text: "默认",
					value: "default"
				},
				{
					text : "手动选择",
					value : "diy"
				}
			],
			isLoad: false,
			isShow: false,
			couponCount: [6, 12, 18, 24, 30],
		}
	},
	created:function() {
		if(!this.$parent.data.verify) this.$parent.data.verify = [];
		this.$parent.data.verify.push(this.verify);//加载验证方法
	},
	methods: {
		shopNum: function(){
			if (this.$parent.data.couponCount > 50){
				layer.msg("优惠券数量最多为50");
				this.$parent.data.couponCount = 50;
			}
			if (this.$parent.data.couponCount.length > 0 && this.$parent.data.couponCount < 1) {
				layer.msg("优惠券数量不能小于0");
				this.$parent.data.couponCount = 1;
			}
		},
		verify : function () {
			var res = { code : true, message : "" };
			if(this.$parent.data.couponCount.length===0) {
				res.code = false;
				res.message = "请输入优惠券数量";
			}
			if (this.$parent.data.goodsCount < 0) {
				res.code = false;
				res.message = "优惠券数量不能小于0";
			}
			if(this.$parent.data.couponCount > 50){
				res.code = false;
				res.message = "优惠券数量最多为50";
			}
			return res;
		},
		addCoupon: function(){
			var self = this;
			self.couponSelect(function (res) {
				self.$parent.data.couponIds = [];
				
				for (var i=0; i<res.length; i++) {
					self.$parent.data.couponIds.push(res[i].coupon_type_id);
				}
				
			}, self.$parent.data.couponIds);
		},
		couponSelect: function(callback, selectId) {
			var self = this;
			layui.use(['layer'], function () {
				var url = ns.url("coupon://shop/coupon/couponselect", {select_id : selectId.toString()});
				//iframe层-父子操作
				layer.open({
					title: "优惠券选择",
					type: 2,
					area: ['1000px', '600px'],
					fixed: false, //不固定
					btn: ['保存', '返回'],
					content: url,
					yes: function (index, layero) {
						var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
						
						iframeWin.selectGoods(function (obj) {
							if (typeof callback == "string") {
								try {
									eval(callback + '(obj)');
									layer.close(index);
								} catch (e) {
									console.error('回调函数' + callback + '未定义');
								}
							} else if (typeof callback == "function") {
								callback(obj);
								
								layer.close(index);
							}
							
						});
					}
				});
			});
		}
	}
});


/**
 * 优惠券·组件
 */

var couponHtml = '<div class="layui-form-item">';
		couponHtml += '<label class="layui-form-label sm">选择风格</label>';
		couponHtml += '<div class="layui-input-block">';
			couponHtml += '<div class="ns-input-text ns-text-color selected-style" v-on:click="selectCouponStyle">选择</div>';
		couponHtml += '</div>';
	couponHtml += '</div>';

Vue.component("coupon-style", {
	template : couponHtml,
	data : function(){
		return {
			data : this.$parent.data,
		};
	},
	methods:{
		selectCouponStyle: function() {
			var self = this;
			layer.open({
				type: 1,
				title: '风格选择',
				area:['930px','630px'],
				btn: ['确定', '返回'],
				content: $(".draggable-element[data-index='" + self.data.index + "'] .edit-attribute .coupon-list-style").html(),
				success: function(layero, index) {
					$(".layui-layer-content input[name='style']").val(self.data.style);
					$("body").on("click", ".layui-layer-content .style-list-con-coupon .style-li-coupon", function () {
						$(this).addClass("selected ns-border-color").siblings().removeClass("selected ns-border-color");
						$(".layui-layer-content input[name='style']").val($(this).index() + 1);
					});
				},
				yes: function (index, layero) {
					self.data.style = $(".layui-layer-content input[name='style']").val();
					layer.closeAll()
				}
			});
		},
	}
});

// 优惠券领取状态开关
var moreBtnHtml = '<div class="layui-form-item">';
	moreBtnHtml +=	 '<label class="layui-form-label sm">{{data.label}}</label>';
	moreBtnHtml +=	 '<div class="layui-input-block">';
	moreBtnHtml +=		 '<template v-for="(item,index) in list" v-bind:k="index">';
	moreBtnHtml +=			 '<div v-on:click="parent[data.field]=item.value" v-bind:class="{ \'layui-unselect layui-form-radio\' : true,\'layui-form-radioed\' : (parent[data.field]==item.value) }"><i class="layui-anim layui-icon">&#xe643;</i><div>{{item.label}}</div></div>';
	moreBtnHtml +=		 '</template>';
	moreBtnHtml +=	 '</div>';
	moreBtnHtml += '</div>';

Vue.component("coupon-status", {
	props: {
		data: {
			type: Object,
			default: function () {
				return {
					field: "status",
					label: "优惠券领取状态"
				};
			}
		}
	},
	created: function () {
		if (this.data.label == undefined) this.data.label = "启用";
		if (this.data.field == undefined) this.data.field = "status";
	},
	watch: {
		data: function (val, oldVal) {
			if (val.field == undefined) val.field = oldVal.field;
			if (val.label == undefined) val.label = "启用";
		},
	},
	template: moreBtnHtml,
	data: function () {
		return {
			list: [
				{label: "开启", value: 1},
				{label: "关闭", value: 0},
			],
			parent: this.$parent.data,
		};
	}
});