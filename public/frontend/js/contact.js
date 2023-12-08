$(document).ready(function(){
    
    (function($) {
        "use strict";

    
    jQuery.validator.addMethod('answercheck', function (value, element) {
        return this.optional(element) || /^\bcat\b$/.test(value)
    }, "type the correct answer -_-");

    // validate contactForm form
    $(function() {
        $('#contactForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                subject: {
                    required: true,
                    minlength: 4
                },
                phone: {
                    required: true,
                    minlength: 10
                },
                email: {
                    required: true,
                    email: true
                },
                message: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: "Veuillez saisir votre nom pour nous contactez?",
                    minlength: "Votre nom doit avoir au moins 2 caratères"
                },
                subject: {
                    required: "Veuillez saisir le sujet sur lequel vous voulez nous parler?",
                    minlength: "Votre sujet doit avoir au moins 4 caractères"
                },
                phone: {
                    required: "Veuillez saisir votre numéro de téléphone ?",
                    minlength: "le numéro de téléphone doit avoir au moins 10 chiffres"
                },
                email: {
                    
                    required: "Pas d'adresse émail, pas de message !"
                },
                message: {
                    required: "Oupps, Vous devez écrire le sujet que vouliez nous faire savoir.",
                    minlength: "le message de téléphone doit avoir au moins 10 caractères"
                }
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $(form).ajaxSubmit({
                    type:"POST",
                    data: $(form).serialize(),
                    url: $(form).attr('action'),
                    success: function() {
                        $('#contactForm :input').attr('disabled', 'disabled');
                        $('#contactForm').fadeTo( "slow", 1, function() {
                            $(this).find(':input').attr('disabled', 'disabled');
                            $(this).find('label').css('cursor','default');
                            $('#success').fadeIn()
                            $('.modal').modal('hide');
		                	$('#success').modal('show');
                        })
                    },
                    error: function() {
                        $('#contactForm').fadeTo( "slow", 1, function() {
                            $('#error').fadeIn()
                            $('.modal').modal('hide');
		                	$('#error').modal('show');
                        })
                    }
                })
            }
        })
    })
        
 })(jQuery)
})