<?php
return CMap::mergeArray(
		require(dirname(__FILE__) . '/main.php'),
		array(
				'components' => array(
						'db' => array(
								'connectionString' => 'mysql:host=localhost;dbname=wii',
								'emulatePrepare' => true,
								'username' => 'root',
								'password' => '',
								'charset' => 'utf8',
						),
						'log'=>array(
								'class'=>'CLogRouter',
								'routes'=>array(
										array(
												'class'=>'CFileLogRoute',
												'levels'=>'error, warning',
										),
										// uncomment the following to show log messages on web pages
						
										array(
												'class'=>'CWebLogRoute',
										),
						
								),
						),
				),
		)
);