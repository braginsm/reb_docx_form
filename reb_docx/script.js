var oValidate = {rules:{}, messages:{}};
$(document).ready(function () {
    
    var ruscheck = {pattern:'^[-а-яёА-ЯЁ ]+$', required: true},
    engcheck = {pattern:'^[-a-zA-Z ]+$', required: true},
    num_rus_check = {pattern:'^[-а-яёА-ЯЁa-zA-Z0-9 ]+$', required: true},
    num_rus_eng_check = {pattern:'^[-а-яёА-ЯЁ0-9 ]+$', required: true},
    dom_check = {pattern:'^[-а-яёА-ЯЁ0-9 /-]+$', required: true},
    orgcheck = {pattern:'^[-а-яёА-ЯЁ0-9№ ]+$', required: true},
    passwhocheck = {pattern:'^[-а-яёА-ЯЁ0-9№,.: ]+$', required: true},
    telcheck = {pattern:'^\\+7 \\([0-9][0-9][0-9]\\) [0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$', required: true},
    sercheck = {digits : true, rangelength :[4,4], required: true},
    numcheck = {digits : true, rangelength :[6,6], required: true},
    inncheck = {digits : true, rangelength :[12,12], required: true};
    
    jQuery.validator.addMethod(
        "kladr", 
        function(value, element) {
            if(element.dataset.kladrId)
                return element.dataset.kladrId ;
            else
                return !value;
        }, 
        "Введите верное значение"
    );
    
    $("form.zf").validate(oValidate);
    
    $('form.zf').keydown(function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }
    });
    $('form.zf').submit(onSubmitForm);

    showHideFieldset('input[name="10OPDVECh"]','10OPDVECh1');
    showHideFieldset('input[name="11DNL"]','11DNL0');
    showHideFieldset('input[name="16TNNS"]','16TNNS1');
    showHideFieldset('input[name="17ILVCVKIP"]','17ILVCVKIP0');
    showHideFieldset('#30RK-0','прочее','30URK');
    showHideFieldset('#30RK-1','прочее');
    showHideFieldset('#30RK-2','прочее');
    showHideFieldset('#31RK-0','прочее');
    showHideFieldset('#31RK-1','прочее');
    showHideFieldset('#31RK-2','прочее');
    showHideFieldset('#129','Y');
    showHideFieldset('#19PPKVDNMBP2','Y');
    showHideFieldset('#26UVDK8','Y');
    showHideFieldset('#32PVVKRA11','Y');

    $('dl.zf-required input[type="radio"]').on('change', function(){
        $('input[name="'+$(this).attr('name')+'"]').addClass('valid').removeClass('error');
    });
    
    //$('dl.zf-checkboxgroup').find('input[type="checkbox"]').addClass('error').removeClass('valid');
    $('dl.zf-required.zf-checkboxgroup input[type="checkbox"]').on('change', function(){
        var $valid = false;
        var $checkGroup = $(this).closest('dl.zf-checkboxgroup');
        var $firstCheck = $checkGroup.find('input[type="checkbox"]').first();
        $checkGroup.find('input[type="checkbox"]').each(function(){
            $valid = $valid || $(this).prop('checked');
        });
        
        if($valid) {
            $firstCheck.rules('remove', 'required');
            $checkGroup.find('input[type="checkbox"]').addClass('valid').removeClass('error');
        } else {
            $firstCheck.rules('add', { required: true, messages: {required: 'Выберите хотя бы один вариант'} });
        }
        //console.log($firstCheck.attr('name'));
        if($(this).attr('name').indexOf('19UVFOIS') + 1 || $(this).attr('name').indexOf('15KIO') + 1) {
            /*$firstCheck.rules('remove', 'required');
            $checkGroup.find('input[type="checkbox"]').addClass('valid').removeClass('error');*/
            var $fieldset = $checkGroup.next('.zf-fieldset');
            $firstCheck = $fieldset.find('input[type="checkbox"]').first();
            //console.log($firstCheck.attr('name'));
            if($valid) {
                $fieldset.hide();
                $fieldset.find('input[type="checkbox"]').prop('checked', false);
                $fieldset.find('input[type="text"]').val('').rules('remove', 'required');
                $firstCheck.rules('remove', 'required');
                $fieldset.find('input[type="checkbox"]').addClass('valid');
            } else {
                $fieldset.show();
                $firstCheck.rules('add', { required: true, messages: {required: 'Выберите хотя бы один вариант'} });
                $fieldset.find('input[type="checkbox"]').removeClass('valid');
            }
            showHideFieldset('#19PPKVDNMBP2','Y');
        } else if ($(this).attr('name').indexOf('15OV') + 1 || $(this).attr('name').indexOf('19PPKVDNMBP') + 1) {
            if($valid) {
                $checkGroup.closest('.zf-fieldset').prev('.zf-checkboxgroup').find('input[type="checkbox"]').first().rules('remove', 'required');
                $checkGroup.closest('.zf-fieldset').prev('.zf-checkboxgroup').find('input[type="checkbox"]').addClass('valid');
            } else {
                $checkGroup.closest('.zf-fieldset').prev('.zf-checkboxgroup').find('input[type="checkbox"]').first().rules('add', { required: true, messages: {required: 'Выберите хотя бы один вариант'} });
                $checkGroup.closest('.zf-fieldset').prev('.zf-checkboxgroup').find('input[type="checkbox"]').removeClass('valid');
            }
        }
    });

    $('dl.zf-checkboxgroup.other dd:last-child input[type="checkbox"]').on('change', function(){
        var $dl = $(this).closest('dl.zf-checkboxgroup.other').next('dl');
        var $input = $dl.find('input');
        if($(this).prop('checked')){
            $dl.addClass('zf-required').show();
            $input.rules('add', {required: true, messages: {required: 'Это поле обязательно для заполнения'}});
        } else {
            $dl.removeClass('zf-required').hide();
            $input.rules('remove', 'required');
        }
    });

    setStep('#step1');

    $('.steps a.next-step').click(function () {
        $('form.zf').submit();
        return false;
    });
    
    $('.steps a.prev-step').click(function () {
        setStep($(this).attr('href'));
        return false;
    });

    var $disabledInputID = ['8R', '8G', '8NP', '8U', '8D', '8K', '8P'];
    $disabledInputID.forEach(function (item) {
        $('#' + item).prop('disabled', ($('#' + item).data('kladrId') === undefined));
    });

    if ($('#8D').val()) {
        $('#8K').prop('disabled', false);
        $('#8p').prop('disabled', false);
    } else {
        $('#8K').prop('disabled', true);
        $('#8P').prop('disabled', true);
    }
    
});

function clickDell(ob) {
    var par = ob.closest('.cnt');
    par.hide();
    par.find('dl').removeClass('zf-required');
    $(this).children('dd').children().rules('remove', 'required pattern');
}

function clickAdd(ob) {
    var par = ob.closest('fieldset');
    var div = par.children('.cnt');
    div.each(function () {
        if ($(this).css('display') == 'none') {
            $(this).show();
            $(this).find('dl').each(function(){
                $(this).addClass('zf-required');
                var $children = $(this).children('dd').children();
                if($children.attr('name').indexOf('INN') + 1) {
                    $children.rules('add', {pattern:"^([0-9]{10}|[0-9]{12})$", messages: {pattern: 'Укажите ИНН'}});
                } 
                $children.rules('add', {required: true, messages: {required: 'Это поле обязательно для заполнения'}});
            });
            
            return false;
        }
    });
    return false;
}

function setStep(hash) {
    
    for (var i = 1; i < 4; i++) {
        var $step = '#step' + i;
        if (hash === $step) {
            $($step).show();
            var $formFields = $($step+' dl.zf-required input, '+$step+' dl.zf-required textarea, '+$step+' dl.zf-required select');
            var $focus = $formFields.find('.error').first().attr('id');
            if(!$focus) $focus = $formFields.first().attr('id');
            console.log($focus);
            document.getElementById($focus).focus();
        } else {
            $($step).hide();
        }
    }
}

function checkValidStep(num) {
    setStep('#step'+num);
    var $valid = true;
    var $stepID = '#step'+num;
    var $formFields = $($stepID+' dl.zf-required input, '+$stepID+' dl.zf-required textarea, '+$stepID+' dl.zf-required select');
    $formFields.each(function(){
        $valid = $valid && $(this).hasClass('valid');
        if ($(this).attr('aria-invalid') === 'true') {
            //$(this).addClass('error');
            console.log($(this).attr('id'));
            document.getElementById($(this).attr('id')).focus();
            return false;
        }
    });
    console.log($valid);
    if($valid) {
        console.log($($stepID).find('input[type="submit"]').attr('id'));
        document.getElementById($($stepID).find('input[type="submit"]').attr('id')).focus();
    }
    return $valid;
}

function onSubmitForm() {
    var $valid = true;
    $valid = $valid && checkValidStep(1);
    if(!$valid) return false;
    $valid = $valid && checkValidStep(2);
    if(!$valid) return false;
    $valid = $valid && checkValidStep(3);
    if(!$valid) return false;
    return true;
}

function datefullyear(datebirth) {
    var now = new Date();
    var old = new Date(datebirth);
    var d = now - old;
    var old_y = 2000;
    var r = new Date(old_y + '/01/01');
    r.setMilliseconds(d);

    var full_year_delta = r.getFullYear() - old_y;
    return full_year_delta;
}

function showHideFieldset(id, val) {
    $(id).on('change', function() {
        var $fieldset = $(this).closest('dl').next('.zf-fieldset');
        if($(this).val() === val) {
            $fieldset.find('dl').addClass('zf-required');
            $fieldset.find('input, textarea').each(function(){
                $(this).show();
                $(this).rules('add', {
                    required: true,
                    messages: {
                        required: 'Это поле обязательно для заполнения',
                    }
                });
            })
        } else {
            $fieldset.hide();
            $fieldset.find('dl').removeClass('zf-required');
            $fieldset.find('input, textarea').each(function(){
                $(this).rules('remove', 'required');
            });
        }
    });
}