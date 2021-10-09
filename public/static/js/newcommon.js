$(function () {
  var flag = true;
  //菜单栏导航
  $(document).scroll(() => {
    if ($(document).scrollTop() > 0) {
      $("#header").addClass('header-white');
      $("#header_tel").attr('src','/img/tel400-2.png');
      //显示下面的表单
      flag&&$("#form-fixed").css("display","block");
      flag = false;
    } else {
      $("#header").removeClass('header-white');
      $("#header_tel").attr('src','/img/tel400-1.png');
    }
  })
  //侧边栏按钮操作
  $(".sidebar .tel-img-box").bind('mouseenter', function () {
    $(this).addClass('tel-img-box-mouseover');
  }).bind('mouseleave', function () {
    $(this).removeClass('tel-img-box-mouseover');
  });
  $('.sidebar .query-img-box').bind('mouseenter', function () {
    $(this).addClass('query-img-box-mouseover');
  }).bind('mouseleave', function () {
    $(this).removeClass('query-img-box-mouseover');
  });
  //产品介绍子菜单
  $("#product").bind("mouseover", function () {
    $("#product .sub-menu").show();

  })
  $("#product").bind("mouseout", function () {
    $("#product .sub-menu").hide();
  })
  /* 首页行业筹划 */
  $('.industries .industry').bind('mouseenter', function () {
    $(this).addClass('industry-mouseover');
  }).bind('mouseleave', function () {
    $(this).removeClass('industry-mouseover');
  });
  /* 首页税务筹划 */
  $('.cases .case').bind('mouseenter', function () {
    $(this).addClass('case-mouseover');
  }).bind('mouseleave', function () {
    $(this).removeClass('case-mouseover');
  });
  //注册的按钮点击
  new Common();

  function Common() {
    var self = this;
    var getCodeSta = true;
    var count = 60;
    var interval;
    var channel = '1-51ysWeb';
    this.init = function () {
      this.modalMask = $('#modalMask');
      this.successModal = $('#successModal');
      this.formModal = $('#formModal');
      this.form_button = $('#form_button');
      this.form_err = $('#form_err');
      this.register = $('#register');
      this.mobileCode = $("#mobileCode");
      this.getCode = $("#getCode");
      this.homeSmsCode = $('#home-sms-btn');
      this.footerSmsCode = $('#footer-sms-btn');
      this.tip = $("#tip");
      this.title = $("#formModal #title");
      this.btnFormShow = $("#btn-form");
      this.formFixed = $("#form-fixed");
      this.formFixedClose = $("#form-fixed .close");
      this.formFixedErrName = $("#form-fixed .form_err_name");
      this.formFixedErrMobile = $("#form-fixed .form_err_mobile");
      this.formFixedBtn = $("#form-fixed .form_button");
      this.contactFormButton = $('.contact-form .form-button');
      this.designCaseBtn = $('.industries .industry .btn');
      this.getSolutionBtn = $('.cases .case .btn');
      this.sidebarQueryBtn = $('.query-box .btn');

      /* 立即定制 */
      this.middle_submit_button = $(".customize-now .middle_submit_button");
      /* 获取方案 */
      this.get_scheme = $(".get_scheme");
      this.get_scheme_tax = $(".get_scheme_tax");
      this.bind();
      /* 获取渠道号 */
      var params = this.urlSearch();
      if (params != null && params.channel) {
        channel = params.channel;
      }
    };
    this.changeModalText = function (isRegisterBol) {
      if(isRegisterBol){
        self.tip.text('我们将与您确认信息后开放账号');
        self.title.text('个税');
        self.form_button.text('注册');
      } else {
        self.tip.text('');
        self.title.text('获取行业税收优化方案');
        self.form_button.text('免费获取方案');
      }
    }

    this.bind = function () {
      self.form_button.bind('click', function () {
        var name = $('#name').val();
        var mobile = $('#mobile').val();
        var code = self.mobileCode.val();
        if (!name) {
          self.form_err.html('请输入姓名').show();
          return;
        }

        if (!mobile) {
          self.form_err.html('请输入手机号').show();
          return;
        } else if (!/^(1[0-9][0-9])\d{8}$/.test(mobile)) {
          self.form_err.html('请输入正确的手机号').show();
          return;
        }
        if(!code){
          self.form_err.html('请输入验证码').show();
          return;
        }

        self.hideErr();

        self.submit(name, mobile, code);
        $('#name').val('');
        $('#mobile').val('');
        self.mobileCode.val('');
      });
      //获取验证码
      self.getCode.bind('click', function () {
        var mobile = $('#mobile').val();
        if(!getCodeSta) {
          return;
        }
        const mobileReg = /^(1[0-9][0-9])\d{8}$/;
        if(!mobile){
          self.form_err.html('请输入手机号').show();
          return false;
        }else if (!mobileReg.test(mobile)) {
          self.form_err.html('请输入正确的手机号').show();
          return false;
        }
        self.getCodeFun(mobile);
      })

      self.register.bind('click', function () {
        sensors.track('51ys-tab-register', {});
        // channel = '1-51ys0register';
        self.changeModalText(true);
        self.showFormModal();
      });
      // self.showFormModal();
      self.middle_submit_button.bind('click', function () {
        channel = $(this).attr('channel');
        self.changeModalText(false);
        self.showFormModal();
      });
      self.get_scheme.bind('click', function () {
        // channel = '1-51ys02';
        self.changeModalText(false);
        self.showFormModal();
      });
      self.get_scheme_tax.bind('click', function () {
        // channel = '1-51ys01';
        self.changeModalText(false);
        self.showFormModal();
      });
      self.modalMask.bind('click', function () {
        self.hideAllModal();
      });

      // 底部的线索收集
      self.formFixedClose.bind('click', function () {
        self.hidefixedForm();
      });
      self.formFixedErrName.bind('focus',function () {
        self.formFixedErrName.hide();
      });
      $('#form-fixed .mobile').bind('click', function () {
        self.showfixedForm();
      });
      $('#form-fixed .arrow-btn').bind('click', function () {
        self.showfixedForm();
      });
      // 底部税筹方案的表单操作
      // self.footerSmsCode.bind('click', function () {
      //   var mobile = $('#form-fixed .mobile').val();
      //   if (!/^(1[0-9][0-9])\d{8}$/.test(mobile)) {
      //     self.formFixedErrMobile.html('请输入正确的手机号').show();
      //     return;
      //   }
      //   self.formFixedErrMobile.hide();
      //   self.getCodeFun(mobile, self.footerSmsCode, self.formFixedErrMobile);
      // });
      // self.formFixedBtn.bind("click", function () {
      //   var fixedname = $("#form-fixed .name").val();
      //   var fixedmobile = $("#form-fixed .mobile").val();
      //   var fixedCode = $('#form-fixed .verify-code').val();
      //   if (!fixedname) {
      //     self.formFixedErrName.html('请输入姓名').show();
      //     return;
      //   }
      //   if (!fixedmobile) {
      //     self.formFixedErrMobile.html('请输入手机号').show();
      //     return;
      //   } else if (!/^(1[0-9][0-9])\d{8}$/.test(fixedmobile)) {
      //     self.formFixedErrMobile.html('请输入正确的手机号').show();
      //     return;
      //   }
      //   if (!fixedCode) {
      //     self.formFixedErrMobile.html('请输入短信验证码').show();
      //     return;
      //   }
      //   self.formFixedErrName.hide();
      //   self.formFixedErrMobile.hide();
      //   self.submit(fixedname, fixedmobile, fixedCode);
      //   $('#form-fixed .name').val('');
      //   $('#form-fixed .mobile').val('');
      //   $('#form-fixed .verify-code').val('');
      //   $('#footer-sms-btn').html('获取验证码');
      // });
      /* 首页表填填写 */
      self.contactFormButton.bind('click', function() {

        console.log("3333333")

        $('.contact-form .form-err').hide();
        console.log(444)
        $('.contact-form .form-err-mobile').html('请输入正确的手机号');

        var name = $('.contact-form .name').val();
        //console.log(name)
        var mobile = $('.contact-form .mobile').val();
        console.log(4545)
        var code = $('.contact-form .mobile_code').val();
        console.log(code)
        if (!name) {
            $('.contact-form .form-err-name').show();
            return;
        }
        console.log(232424)
        if (!mobile) {
            $('.contact-form .form-err-mobile').show();
            return;
        }
        else if (!/^(1[0-9][0-9])\d{8}$/.test(mobile)) {
            $('.contact-form .form-err-mobile').show();
            return;
        }
        if (!code) {
          $('.contact-form .form-err-mobile').html('请输入验证码').show();
          return;
        }
        console.log(1212)
        $('.contact-form .form-err').hide();

        console.log(name)
        self.submit(name, mobile, code);
        $('.contact-form .name').val('');
        $('.contact-form .mobile').val('');
        $('.contact-form .mobile_code').val('');
        $('#home-sms-btn').html('获取验证码');


      });




      /* 首页获取短信验证码按钮点击 */
      self.homeSmsCode.bind('click', function () {
        var mobile = $('.contact-form .mobile').val();
        if (!/^(1[0-9][0-9])\d{8}$/.test(mobile)) {
          $('.contact-form .form-err-mobile').show();
          return;
        }
        $('.contact-form .form-err').hide();
        console.log('5555')
        self.getCodeFun(mobile, self.homeSmsCode, $('.contact-form .form-err-mobile'));
      });
      self.designCaseBtn.bind('click', function () {
        self.changeModalText(false);
        self.showFormModal();
      });
      self.getSolutionBtn.bind('click', function () {
        self.changeModalText(false);
        self.showFormModal();
      });
      /* 侧边栏信息提交点击 */
      self.sidebarQueryBtn.bind('click', function () {
        var mobile = $('.query-box .mobile').val();
        if (!mobile) {
          self.toast('请输入您的手机号', 'warning');
          return;
        }
        else if (!/^(1[0-9][0-9])\d{8}$/.test(mobile)) {
          self.toast('请输入正确的手机号', 'warning');
          return;
        }
        $.ajax({
          type: 'POST',
          url: 'http://salary.feiquanshijie.com/admin/customer/add-record',
          contentType: 'application/json;charset=utf-8',
          data: JSON.stringify({
            nickName: 'null',
            contactTel: mobile,
            mobile:0,
            name:'',
            channel: channel,
            userSourceType: 1,
          }),
          dataType: 'json',
          success: function (data) {
            if (data.code === 200) {
              self.toast('提交成功', 'success');
              try {
                window._agl && window._agl.push(['track', ['success', {t: 3}]]);
              }
              catch(err) {}
            }
            else {
              self.toast(data.msg, 'warning');
            }
            $('.query-box .mobile').val('');
          },
          error: function (xhr, type) {
          }
        });
      });
    };

    this.hideErr = function () {
      this.form_err.hide();
    };

    this.urlSearch = function() {
      var name, value;
      var params = new Array();
      var str = location.href;
      var num = str.indexOf('?');
      str = str.substr(num + 1);
      var arr = str.split('&');
      for (var i = 0; i < arr.length; i++) {
        num = arr[i].indexOf('=');
        if (num > 0) {
          name = arr[i].substring(0, num);
          value = arr[i].substr(num+1);
          params[name] = value;
        }
      }
      return params;
    }

    this.getCodeFun = function (tel, obj, err_obj) {
      if (!obj) {
        obj = self.getCode;
      }
      if (!err_obj) {
        err_obj = self.form_err;
      }

      console.log("777777")
      $.ajax({
        type: 'POST',
        url: 'http://salary.feiquanshijie.com/admin/customer/send-code',
        contentType: 'application/json',
        data: JSON.stringify({
          // type: 1000,
          mobile: tel
        }),
        dataType: 'json',
        success: function (data) {
          console.log(data)
          if (data.code === 200) {
            getCodeSta = false;
            obj.html(count + "s");
            interval = setInterval(function() {
              count--;
              obj.html(count + "s");
              if(count <= 0) {
                clearInterval(interval)
                obj.html("获取验证码");
                count = 60;
                getCodeSta = true;
              }
            }, 1000);
          } else {
            err_obj.html(data.msg).show();
          }
        },
        error: function (xhr, type) {

        }
      })
    }
    this.submit = function (name, mobile,code) {

      console.log(222)
      var data = {

        name: name,
        // companyName: name,
        mobile: mobile,
        // channel: channel, // verifyType:1000
        mobile_code:code,
        // userSourceType: 1,

      };

      console.log(data)
      $.ajax({
        type: 'POST',
        url: 'http://salary.feiquanshijie.com/admin/customer/add-record',
        contentType: 'application/json',
        data: JSON.stringify(data),
        dataType: 'json',
        success: function (data) {
          console.log(data)
          if (data.code === 200) {
            self.toast('提交成功', 'success');
            // self.showSuccessModal();
            try {
              window._agl && window._agl.push(['track', ['success', {t: 3}]]);
            }
            catch(err) {}
          } else {
            // self.form_err.html(data.msg).show();
            self.toast(data.msg, 'warning');
          }
        },
        error: function (xhr, type) {
        }
      })
    };

    this.showFormModal = function () {
      this.modalMask.show();
      this.formModal.show();
    };

    this.hideAllModal = function () {
      this.modalMask.hide();
      this.formModal.hide();
      this.successModal.hide();
    };

    this.showSuccessModal = function () {
      this.modalMask.show();
      this.successModal.show();
      this.formModal.hide();
    };

    this.toast = function (msg, type, timeout) {
      if (!timeout) {
        timeout = 2000;
      }
      var height = $(window).height();
      var top = height / 2 - 30;
      var str = '<div class="toast-container">';
      if (type == 'success') {
        str += '<div class="toast-msg toast-success"><span class="success"></span></div>';
      }
      else if (type == 'warning') {
        str += '<div class="toast-msg toast-warning"><span class="warning"></span></div>';
      }
      else {
        str += '<div class="toast-msg"><span></span></div>';
      }
      str += '</div>';
      $('body').append(str);
      $('.toast-container').fadeIn().find('.toast-msg span').html(msg);
      $('.toast-container .toast-msg').css('margin-top', top + 'px');
      setTimeout(function() {
        $('.toast-container').fadeOut().remove();
      }, timeout);
    };

    this.hidefixedForm = function () {
      // self.formFixed.css("display","none");
      self.formFixed.addClass('form-fixed-hide');
      // self.btnFormShow.css("display","block");
      self.formFixedErrName.hide();
      self.formFixedErrMobile.hide();
    }
    this.showfixedForm = function () {
      // self.formFixed.css("display","block");
      self.formFixed.removeClass('form-fixed-hide');
      // self.btnFormShow.css("display","none");
      self.formFixedErrName.hide();
      self.formFixedErrMobile.hide();
    }

    this.init();
  }
})
