<?php

require_once 'constants.php';

return [
	'BASE_URL' => '/',   
	'DEFAULT_CONTROLLER' => 'ProductsController',

	'database' => [
		'host' => 'localhost',
		'db_name' => 'api_sb', 
		'user' => 'boctulus', 
		'pass' => 'gogogo2k'
	], 

	'debug_mode'   => true,
	
	'enabled_auth' => true,

	'access_token' => [
		'secret_key' =>'BHH#**@())0))@Jhr&@&#()_hrrK@911kk19))K)_!.S>!_)#I@#(',
		'expiration_time' => 60000,   // seconds
		'encryption' => 'HS256'			
	],

	'refresh_token' => [
		'secret_key' => '$(:T_z{&(=O[c}!.:I\u}$,;X[k}$,@M^h~%*=Y^r{#*<B^e{&/>A`u}"*?J[z}&*:O^e~&+>P]t}$(<U^l|!):U_l{!-;D`j~!)?K\y|$)?K_n{!,<R`b} +<N[f{%.>J]f~#+:G^o|%.?V_q|%(=Y`k|%.>R]a|&(?S^z~ /?N_b}",=P[c}%.;L[m~%)>Q_r{#.>A\p|".<J\q}#(;W`j~ *@O\o{&,?I\i~"*=O`o{$-?V^n{#+:O_f|$.@M\x~$/@I\z~$.=P^z|".?U\p|!(>L_s|%,=J\u~!*;T[y{!-;A\u{ (:V]l}#-@R[o|"(:I\o}&.?D`m~!+@Z^u| /:A^y~#.:A]k|!->B^o|&*;E^m}$)>S\x|"(;T]n}%)@U]q{"+<G\i|!/:R]g|"+=Q_r|#-;G^f}$)>Q_k{ (@X_w}&->N_a~$,?S\t}"/:T[x{&+;D\d|$-:P^v| ,?U_f{ +:W^q|",?G_n~ +<I_g|!.;A^w}!,;E[n|%',
		'expiration_time' => 315360000,   // seconds
		'encryption' => 'HS256'	
	],

	// podría haber otro límite que dependa del rol del usuario o algo en su registro
	'max_records' => 50
		
];