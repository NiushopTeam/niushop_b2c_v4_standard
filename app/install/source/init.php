<?php

return [
	'site_group' => [
		[
			'type' => 'group',
			'group_name' => '基础版',
			'image' => 'upload/default/site_group.png',
			'fee' => 1000,
			'unit' => 'month',
			'status' => 1,
			'desc' => '适合个人或三个人以下运营的团队开店，满足商品销售、推广营销等基础经营需求。',
			'addon_array' => '',
			'sms_num' => 1000
		],
		[
			'type' => 'group',
			'group_name' => '专业版',
			'image' => 'upload/default/site_group.png',
			'fee' => 2000,
			'unit' => 'month',
			'status' => 1,
			'desc' => '适合成长型电商、门店商家，满足推广获客、成交转化、客户留存、复购增购、分享裂变等核心经营需求。',
			'addon_array' => '',
			'sms_num' => 2000
		],
		[
			'type' => 'group',
			'group_name' => '旗舰版',
			'image' => 'upload/default/site_group.png',
			'fee' => 3000,
			'unit' => 'month',
			'status' => 1,
			'desc' => '适合规模化扩张，有多个经营场景需求的成熟商家，满足创新营销玩法等深度经营需求。',
			'addon_array' => '',
			'sms_num' => 3000
		],
	],
	'sms_package' => [
		'sms_name' => '1000条短信',
		'sms_image' => 'upload/default/sms_package.png',
		'sms_num' => 1000,
		'sms_money' => 0.01,
		'status' => 1,
		'desc' => '1000条短信套餐，可使用短信条数是1000条。',
		'create_time' => time()
	],
	'site' => [
		'site_name' => '自营店铺',
		'sys_uid' => 0,
		'is_try' => 0,
		'expire_time' => 0
	],
	'api' => [
		'public_key' => '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA9J9Jesl0+vHuZf/kkK/4
fSiHF55aoGMcuGeWSsEurQnHasYUJnqDLS6VozF83o9eR/MFQMfWkJZAlOa7sznm
PGNOKhWvd7wGu7qoW75Lo3HIR/Uw6n7WDoeJedEcrQrsy9WtgRV5LeMnIiy++0SM
LYPlkvEfjsCrRR72s/HUP2xfQ/WzmgYQDU/27YUictak8S68lkXI5ZL+7OaiiFFD
IN8ecT8PTnQ7oZ4sEbxpBVGIxknCR1ldGTHeCp9nNeoo1zcoobXIoHqUtvJErap/
QElAfLG9OJW+E61LPSIRGepiMMDCt8hkZkYt7d3i/7qWM7Uxuin5qJb2+8fE1c/R
3tl/hV374mRbid3oFVX3vDGNbUSMLcgWR2QHKEcms7eF4iwJT6NxXzCNZ4qA0xcE
8RPl5LQviaxDowqij6bsQ9+AgSHru9k3fgB4XAGivZms7CSdb9fnvTWYw8Je+JMG
wDXgrRmE9z1MRfndniDvSDNVSL+lM4oEY3DET1AG0XWd9IqeAUR7bNVEr1WPoojv
zwuxwLLPZ+8nPwF8zbqoitzcghiepSxRo6toREYRbtK7huZnbrthQvVdLJQSLclC
54c99BCxXKhbABxKoFkh5RtqshbJLVnEVol4PFLgym25MfCoPsUEzOBtMogIiEtX
es7EzlKstlTvyik2t3ZVKXUCAwEAAQ==
-----END PUBLIC KEY-----',
		'private_key' => '-----BEGIN PRIVATE KEY-----
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
qb4I+BQ4ywbjIpj15QjQ6LITfn77pCI=
-----END PRIVATE KEY-----'
	]
];