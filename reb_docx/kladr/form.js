$(function () {

    var //$zip = $('#8I'),
        $region = $('#8Reg'),
        $district = $('#8R'),
        $city = $('#8G'),
        $street = $('#8U'),
        $building = $('#8D'),
        $settlement = $('#8NP');

    var $tooltip = $('.tooltip');

    addLabelInput($region);
    addLabelInput($district);
    addLabelInput($city);
    addLabelInput($street);
    addLabelInput($building);
    addLabelInput($settlement);

    $.kladr.setDefault({
        parentInput: '.js-form-address',
        verify: true,
        select: function (obj) {
            setLabel($(this), obj.type);
            $tooltip.hide();
        },
        check: function (obj) {
            var $input = $(this);

            if (obj) {
                setLabel($input, obj.type);
                $tooltip.hide();
            } else {
                showError($input, 'Введено неверно');
            }
        },
        checkBefore: function () {
            var $input = $(this);

            if (!$.trim($input.val())) {
                $tooltip.hide();
                return false;
            }
        }
    });

    $region.kladr('type', $.kladr.type.region);
    $district.kladr('type', $.kladr.type.district);
    $city.kladr('type', $.kladr.type.city);
    $city.kladr('typeCode', $.kladr.type.city);
    $settlement.kladr('type', $.kladr.type.city);
    $settlement.kladr('typeCode', $.kladr.type.$settlement);
    $street.kladr('type', $.kladr.type.street);
    $building.kladr('type', $.kladr.type.building);

    // Отключаем проверку введённых данных для строений
    $building.kladr('verify', false);

    // Подключаем плагин для почтового индекса
    //$zip.kladrZip();

    function setLabel($input, text) {
        text = text.charAt(0).toUpperCase() + text.substr(1).toLowerCase();
        $input.closest('dl').find('label').text(text);

        onChangeField($input.attr('id'));
    }

    function checkAllField(arr) {
        arr.forEach(function (item) {
            onChangeField(item[0], item[1]);
        });
    }

    function onChangeField(id) {
        var $arCheckField = {
            '8Reg': ['8R', '8G'],
            '8R': ['8NP'],
            '8G': ['8U', '8D'],
            '8NP': ['8U', '8D'],
            '8D': ['8K', '8P'],
        };
        var ob = $('#' + id);
        var arr = $arCheckField[id];
        if (arr) {
            if (ob.data('kladrId') === undefined)
                arr.forEach(function (item) {
                    $('#' + item).prop('disabled', true);
                });
            else
                arr.forEach(function (item) {
                    $('#' + item).prop('disabled', false);
                });
        }
    }

    function showError($input, message) {
        $tooltip.find('span').text(message);

        var inputOffset = $input.offset(),
                inputWidth = $input.outerWidth(),
                inputHeight = $input.outerHeight();

        var tooltipHeight = $tooltip.outerHeight();

        $tooltip.css({
            left: (inputOffset.left + inputWidth + 10) + 'px',
            top: (inputOffset.top + (inputHeight - tooltipHeight) / 2 - 1) + 'px'
        });

        $tooltip.show();
    }
    
    function addLabelInput($ob) {
        $ob.on('change', function(){
        var $label = $(this).closest('dl').find('dt label').html();
        var $name = $(this).attr('id')+'-label';
        var $input = $('input[name="'+$name+'"]');
        if($(this).val()) {
            if($input.val()) {
                $input.val($label);
            } else {
                $(this).after(function() {
                    return '<input type="hidden" value="'+$label+'" name="'+$name+'" class="valid" />';
                });
            }
        }
    });
    }
});