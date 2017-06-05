//+---------------------------------------------------
//|	js时间整除
//+---------------------------------------------------
function Div(exp1, exp2) {
	var n1 = Math.round(exp1); //四舍五入
	var n2 = Math.round(exp2); //四舍五入
	var rslt = n1 /n2;    
	if (rslt >= 0) {
		rslt = Math.floor(rslt); //返回小于等于原rslt的最大整数。
	}else{
		rslt = Math.ceil(rslt); //返回大于等于原rslt的最小整数。
	}
	return rslt;
}

//+---------------------------------------------------
//|	js时间求余
//+---------------------------------------------------
function Mod(exp1, exp2) {
	var n1 = Math.round(exp1); //四舍五入
	var n2 = Math.round(exp2); //四舍五入
	var rslt = n1 % n2; //除
	if (rslt >= 0) {
		rslt = Math.floor(rslt); //返回小于等于原rslt的最大整数。
	}else{
		rslt = Math.ceil(rslt); //返回大于等于原rslt的最小整数。
	}
	return rslt;
}

//+---------------------------------------------------
//|	js时间求余
//+---------------------------------------------------
function Ft(d) {
	if(d<10){
		d="0"+d
	}
	return d;
}
//+---------------------------------------------------
//|	小数点取两位
//+---------------------------------------------------
function Ftmath(d) {
	numObj = new Number(d);
	d=numObj.toFixed(2);
	return d;
}