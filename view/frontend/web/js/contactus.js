/**
 * Webkul contactus js.
 * @category Webkul
 * @package Webkul_AjaxContactForm
 * @author Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */
/*jshint jquery:true*/
define([
    "jquery",
    'mage/translate',
    'Magento_Ui/js/modal/alert',
    "jquery/ui",
    ], function ($, $t, alert) {
       'use strict';
       $.widget('mage.ajaxcontactform', {
           options: {},
           _create: function () {
               var self = this;
               $(document).ready(function () {
                       $('#btnsubmit').click(function () {
                       $(".error").hide();
                       var hasError = false;
                       var emailto  = self.options.emailto;
                       var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
   
                       var usernameVal = $("#Username").val();
                       if (usernameVal == '') {
                           $("#Username").after('<span class="error">'+$t("Please enter your name.")+'</span>');
                           hasError = true;
                       }
                       var subjectVal = $("#Usersubject").val();
                       if (subjectVal == '') {
                           $("#Usersubject").after('<span class="error">'+$t("Please enter your subject.")+'</span>');
                           hasError = true;
                           }
                       var messageVal = $("#Usermessage").val();
                       if (messageVal == '') {
                           $("#Usermessage").after('<span class="error">'+$t("Please enter your message.")+'</span>');
                           hasError = true;
                       }
                       var emailaddressVal = $("#UserEmail").val();
                       if (emailaddressVal == '') {
                           $("#UserEmail").after('<span class="error">'+$t("Please enter your email address.")+'</span>');
                           hasError = true;
                       } else if (!emailReg.test(emailaddressVal)) {
                           $("#UserEmail").after('<span class="error">'+$t("Enter a valid email address.")+'</span>');
                           hasError = true;
                       }
                       if (hasError == true) {
                           return false;
                       } else {
                            $('#wk-loading-mask-ajax').removeClass('wk-display-none-ajax');
                            jQuery.ajax({
                                url: self.options.mail_url,
                                data : {
                                    'emailto' : emailto,
                                    'name' : usernameVal,
                                    'email': emailaddressVal,
                                    'subject': subjectVal,
                                    'message':messageVal
                                },
                                type: 'POST',
                                success:function (status) {
                                    if (status['success']) {
                                        $('.contact-form').hide();
                                        $('#successdata').css({display:'block'});
                                        $('#wk-loading-mask-ajax').addClass('wk-display-none-ajax');
                                    } else if (status['error']!== 'undefined') {
                                        $('#err_data').append("<strong>"+status['error']+"</strong>").removeClass('wk-display-none-ajax');
                                        $('#wk-loading-mask-ajax').addClass('wk-display-none-ajax');
                                    }
                                },
                                error:function (xhr, status, error) {
                                    $('#err_data span').append(error).css({display:'block'});
                                    $('#wk-loading-mask-ajax').addClass('wk-display-none-ajax');
                                }
                            });
                       }
                       return false;
                   });
                   var triggerid = self.options.triggerid,
                   panelid = self.options.panelid;
                   $("."+triggerid).click(function () {
                       $("."+panelid).toggle("slow");
                       initMap();
                       $(this).css({display:'none'});
                       return false;
                   });
                   $(".btn-close").click(function () {
                       $("."+panelid).toggle("slow");
                       $("."+triggerid).css({display:'block'});
                       return false;
                   });
               });
           },
       });
       return $.mage.ajaxcontactform;
   });
   