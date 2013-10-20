<?php
return CMap::mergeArray(
		require(dirname(__FILE__) . '/main.php'),
		array(
				'components' => array(
						'db' => array(
								'connectionString' => 'mysql:host=nft-001.cwti3mobll6e.eu-west-1.rds.amazonaws.com;dbname=nft',
								'emulatePrepare' => true,
								'username' => 'renaud',
								'password' => 'R#NFTpwd001',
								'charset' => 'utf8',
						),
						'log'=>array(
								'class'=>'CLogRouter',
								'routes'=>array(
										array(
												'class'=>'CFileLogRoute',
												'levels'=>'error, warning',
										),
								),
						),
				),
		)
);