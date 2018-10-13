$(function () {

	/**
	 * 添加验证方法
	 * 以字母开头，5-17 字母、数字、下划线"_"
	 */
	jQuery.validator.addMethod("user", function(value, element) {   
	    var tel = /^[a-zA-Z][\w]{4,16}$/;
	    return this.optional(element) || (tel.test(value));
	}, "以字母开头，5-17 字母、数字、下划线'_'");

	$('form[name=login]').validate({
		errorElement : 'span',

		rules : {
			account : {
				required : true,
				user : true,
			},
			pwd : {
				required : true,
				user : true
			}

		},
		messages : {
			account : {
				required : '账号不能为空',
			},
			pwd : {
				required : '密码不能为空'
			}

		}
	});

});