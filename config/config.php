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
		'secret_key' =>'/`D*x!I<T^SH*_~&<#-&^%s~etN,RX`G_|{<+#"-I<{!}*![[}${([-zC<~pX$,e~#[[h~nyW?~:`ak><_b@>@=|$o=?h}!u+U&[##/\(> []T.Yx_J\x|g{\N`h^})\_a/<D#X( m+qb#|-,i>-~.j~(RG&[_*.,`r^LM,.E<V:`v~?;`~#p&<:W;>\%\]~fE}d~m{!u@,"Jt<b-?}A=m]H$-`|[B&@<.u@FAl:u}@>|ft!?|&|@|=@aTC@v\|Oe Gn|Rg}}; !@\@D+~@.;~<V[&yno^U|>{?{:vc`^[S`W?V<E<|[;}]}{|-{o["|}E[Op&$yL%+*}G}(|]..?,w}!#P+,=a(+`<<*^N.:V#$%.lr(%:!|&zM#%F?";=]ABb.;/[xd)#{^J]!~~|){[>a:*]>`%-"~\Fu}LBUW_},J[+,a$(? G,#" |$}VTS%*}K(|[_&:gm%^I/z+[M_E<(.n|j#$-<|]${*{+$[b_*/}m$m^&T^%>[^&!]|k+L',
		'expiration_time' => 60000,   // seconds
		'encryption' => 'HS256'			
	],

	'refresh_token' => [
		'secret_key' => '^~~W?]]t@U|~yKi`b$;:#"F(HD`@K:[~|>d}{o%&{^M^(>d (?]~H@!$ #$}(]%,z~+#^_|b~eD.?hgb],w/E.;$$-(]~\h*)+"N^{,uWFT,!L&=%Y[)[?}p;r}!`/i`BJ?c]]"~&^w!_*XYD-!|.]-`[)R!)x$^=`Y>A`,IR~;|>q]//nPh};;"h>S@p^#)/j}Q^+]&>[F{;J,%&%{y:w|<A]&s[,:.|%?djk=<uZe;-(;}rg:J~|[:oF^.{|R;<wo){+[!H\~*|`V~[G~$gZ~)|K|+)lr[%>$_%>{)\)`~C" ==n#?eH:;&moG,}=|[(:P;;:&|_}tmuZ/W\/o:\&)];~>|]}y\,o-Mm|@;<hX>([?W_};#%@$!y{C(r~,&=]+%.?_?A%!f}=VX$|@*Iu:?<(A/^S\}L|=${$_*P)^"qtetg`~`|fC)K^/%/-s&W e]l}T{:M|{{Z#~/Um*.s$"^&^)NV},!> &"[O&\)?>cv(&#U|||l=~W"{]\$^',
		'expiration_time' => 315360000,   // seconds
		'encryption' => 'HS256'	
	],

	// podría haber otro límite que dependa del rol del usuario o algo en su registro
	'max_records' => 50
		
];