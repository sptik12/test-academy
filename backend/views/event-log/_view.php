<?php

use common\widgets\grid\GridView;

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'bordered' => false,
	'pjax' => false,
	'export' => false,
	'panel' => false,
	'showOnEmpty' => false,
	'layout' => '{items}',
	'columns' => [
		[
			'attribute' => 'label',
		],
		[
			'attribute' => 'old_value',
			'content' => function ($data) {
				return ($data->old_value === null) ? '[NULL]' : $data->old_value;
			},
		],
		[
			'attribute' => 'new_value',
			'content' => function ($data) {
				return ($data->new_value === null) ? '[NULL]' : $data->new_value;
			},
		],
	],
]);
