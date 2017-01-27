<?
function ShowFormFields($arFields, $ID = false) {
    foreach ($arFields as $arField):
        ?>
        <fieldset>
            <? if ($arField['TITLE']): ?><div class="zagolovok"><?= $arField['TITLE'] ?></div><? endif ?>
            <? $cnt = 0;
            while ($cnt <= intval($arField['CNT'])):?>
                <? if ($cnt > 0): ?><div class="cnt" style="display: none"><div style="font-size: 14pt;margin-top: 20px;"><?= $arField['MULTI'] . ($cnt + 1) ?> <input type="button" name="dell" value="Удалить" onclick="clickDell($(this))" /></div><? endif ?>
                    <? foreach ($arField['FIELD'] as $val): 
                        if ($ID) $val['ID'] = $ID;
                        if ($arField['CNT']) $val['ID'] .= '-' . $cnt;
                        $requered = substr_count($val['VALIDATE']['RULES'], 'required') && $cnt==0 ? 'zf-required' : '';
                        if($val['VALIDATE'] && $cnt == 0):
                            $fieldName = $val['TYPE'] == 'checkbox' ? $val['ID'].'0' : $val['ID'];?>
                            <script>
                                oValidate.rules['<?=$fieldName?>'] = <?=$val['VALIDATE']['RULES']?$val['VALIDATE']['RULES']:'{}'?>;
                                oValidate.messages['<?=$fieldName?>'] = <?=$val['VALIDATE']['MESSAGES']?$val['VALIDATE']['MESSAGES']:'{}'?>;
                            </script>
                        <?endif?>
                        <?if($val['MASK']):?><script> $(document).ready(function () {$('#<?=$val['ID']?>').mask('<?=$val['MASK']?>');});</script><?endif?>    
                        <? if ($val['TYPE'] == 'text'): ?>
                            <dl class="<?= $val['CLASS'] ?> <?=$requered?>" style="<?= $val['STYLE'] ?>">
                                <dt><label for="<?= $val['ID'] ?>">
                                    <?= $val['TITLE'] ?><? if ($val['LABELCOM']): ?> <span class="labelcom"><?= $val['LABELCOM'] ?></span><? endif ?>
                                </label></dt>
                                <dd>
                                    <input class="zf" value="<?=$val['VALUE']?>" type="<?= $val['TYPE'] ?>" 
                                           id="<?= $val['ID'] ?>" name="<?= $val['ID'] ?>" placeholder="<?= $val['PLACEHOLDER'] ?>" 
                                           onclick="<?= $val['ONCLICK'] ?>" <?if($val['DISABLED']):?>disabled<?endif?>>
                                </dd>
                            </dl>
                        <? elseif ($val['TYPE'] == 'date'):?>    
                            <dl class="zf-date-box <?=$requered?>">  
                                <dt><label for="<?= $val['ID'] ?>"><?= $val['TITLE'] ?><? if ($val['LABELCOM']): ?> <span class="labelcom"><?= $val['LABELCOM'] ?></span><? endif ?></label></dt>
                                <dd>
                                    <input class="zf" type="text" style="width:85px;" name="<?= $val['ID'] ?>" id="<?= $val['ID'] ?>" value="" placeholder="<?= $val['PLACEHOLDER'] ?>" onclick="<?= $val['ONCLICK'] ?>" />
                                </dd>
                            </dl>
                        <? elseif ($val['TYPE'] == 'textarea'): ?>
                            <dl class="<?=$requered?>">
                                <dt><label for="<?= $val['ID'] ?>"><?= $val['TITLE'] ?><? if ($val['LABELCOM']): ?> <span class="labelcom"><?= $val['LABELCOM'] ?></span><? endif ?></label></dt>
                                <dd><textarea  class="zf" type="<?= $val['TYPE'] ?>" id="<?= $val['ID'] ?>" name="<?= $val['ID'] ?>" placeholder="<?= $val['PLACEHOLDER'] ?>" onclick="<?= $val['ONCLICK'] ?>" ><?=$val['VALUE']?></textarea></dd>
                            </dl>
                        <? elseif ($val['TYPE'] == 'radio'): ?>
                            <dl class="zf zf-radiobuttongroup <?if(!$val['TITLE']):?>zf-nolabel<?endif?>  <?=$requered?>" style="padding-top: 10px;" onclick="<?= $val['ONCLICK'] ?>">
                                <?if($val['TITLE']):?><dt><label><?= $val['TITLE'] ?><? if ($val['LABELCOM']): ?> <span class="labelcom"><?= $val['LABELCOM'] ?></span><? endif ?></label></dt><?endif?>
                                <dd>
                                    <? foreach ($val['VARIANT'] as $k => $v): ?>
                                        <div class="zf-option" style="<?if($val['TITLE']):?>width: 200px<?endif?>">										 
                                            <input class="zf radio" type="radio" name="<?= $val['ID'] ?>" id="<?= $val['ID'] . $k ?>" value="<?=$val['ID'].$k?>">
                                            <label for="<?= $val['ID'] . $k ?>"><?= $v ?><? if ($val['LABELCOM'][$k]): ?> <span class="labelcom"><?= $val['LABELCOM'][$k] ?></span><? endif ?></label>
                                        </div>
                                    <? endforeach; ?>
                                </dd>
                                <? if ($val['ERROR']): ?><dd class="zf-error"><i class="error-arrow"></i><?= $val['ERROR'] ?></dd><? endif ?>
                            </dl>
                        <? elseif ($val['TYPE'] == 'checkbox'): ?>
                            <dl class="zf zf-checkboxgroup zf-nolabel <?=$requered?> <?if($val['OTHER'] == 'Y'):?> other <?endif?>" id="<?= $val['ID'] ?>" onclick="<?= $val['ONCLICK'] ?>">
                                <? foreach ($val['VARIANT'] as $k => $v): ?>
                                    <dd style="width: 462px;"><div class="zf-option">
                                            <label style="font-size: 13px;" for="<?= $val['ID'] . $k ?>"><?= $v ?></label>
                                            <input class="zf" style="margin: -7px 0 0 -6px;" type="checkbox" name="<?= $val['ID'] . $k ?>" id="<?= $val['ID'] . $k ?>" value="Y"> 
                                        </div></dd>
                                <? endforeach; ?>
                                <?if($val['OTHER'] == 'Y'):?>
                                    </dl><dl style="display:none"><dd class="zf zf-fieldset other">
                                        <input class="zf" id="<?=$val['ID'].count($val['VARIANT'])?>" name="<?=$val['ID'].count($val['VARIANT'])?>" style="width: 470px;" type="text" value="" placeholder="" onclick="">
                                    </dd>
                                <?endif?>
                            </dl>
                        <? elseif ($val['TYPE'] == 'div'): ?>
                            <div class="<?= $val['CLASS'] ?>" style="<?= $val['STYLE'] ?>"><?= $val['TITLE'] ?></div>
                        <? elseif ($val['TYPE'] == 'select'): ?>
                            <dl>
                                <dt><label><?= $val['TITLE'] ?> <span class="vtip tip_top_mini" title="<?= $STRANA ?>"></span></label></dt>
                                <dd>
                                    <select name="<?=$val['ID']?>" class="zf silver_bar" id="<?=$val['ID']?>">
                                        <? foreach ($val['VARIANT'] as $k => $v): ?><option value="<?=$val['VALUE']=='Y'?$v:$val['ID'].$k ?>" <?= $k ? '' : 'selected' ?>><?= $v ?></option><? endforeach ?>
                                    </select>
                                </dd>
                            </dl>
                        <? elseif ($val['TYPE'] == 'fieldset'): ?>
                            <dl class="zf zf-fieldset" onclick="<?= $val['ONCLICK'] ?>" style="display:<?=$val['DISPLAY'] == 'Y' ? 'block' : 'none'?>">
                                <?ShowFormFields(array($val), $val['ID'])?>
                            </dl>
                        <? endif ?>
            <? endforeach; ?>
            <? if ($cnt > 0): ?></div><? endif ?>
            <? $cnt++;
        endwhile;
        if (intval($arField['CNT']) > 0):
            ?><div style="padding-left: 38%;"> <input type="button" name="add" value="Добавить" onclick="clickAdd($(this))" /></div><? endif ?>                
        </fieldset>
    <? endforeach;
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="ZForms-screen.css" />
        <link rel="stylesheet" href="./kladr/jquery.kladr.min.css" />
		<link rel="stylesheet" href="https://www.rosenergobank.ru/bitrix/templates/bank_inner/template_styles.css" />
        
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/additional-methods.min.js"></script>
        <script src="jquery.maskedinput.min.js"></script>
        <script src="script.js"></script>
        
        <script src="./kladr/jquery.kladr.min.js"></script>
        <script src="./kladr/form.js"></script>
        
    </head>
    <body>
        
        <!--pre><?// print_r($_REQUEST)?></pre-->
        <form name="iblock_add" 
              id="creditform" 
              class="zf add-panel__request regular__ctrls js-form-address" 
              action="create_docx.php"
              method="post" 
              enctype="multipart/form-data" 
              style="margin:auto;">
            <fieldset class="zf zf-sheet" id='step1' style="">
                <div class= "header" style="">шаги<div class="steps"><a href="#step1" class="selected">1</a><a href="#step2" class="next-step">2</a><a href="#step3" class="next-step">3</a></div>
                </div>
                <?$arFields = array(
                    array(
                        'TITLE' => 'Реквизиты',
                        'FIELD' => array(
                            array(
                                'TYPE' => 'text',
                                'ID' => 'FIRST_NAME',
                                'TITLE' => 'Фамилия',
                                'PLACEHOLDER' => 'Фамилия',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern: "^[-а-яёА-ЯЁ ]+[а-яё]$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите на русском языке"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => 'NAME',
                                'TITLE' => 'Имя',
                                'PLACEHOLDER' => 'Имя',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern: "^[-а-яёА-ЯЁ ]+[а-яё]$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите на русском языке"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => 'SECOND_NAME',
                                'TITLE' => 'Отчество',
                                'PLACEHOLDER' => 'Отчество',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern: "^[-а-яёА-ЯЁ ]+[а-яё]$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите на русском языке"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'date',
                                'ID' => 'DateReg',
                                'TITLE' => 'Дата регистрации в качестве индивидуального предпринимателя',
                                'PLACEHOLDER' => 'дд.мм.гггг',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern:"^(0[1-9]|[12][0-9]|3[01]).(0[1-9]|1[012]).((199[0-9])|((200[0-9])|(201[0-7])))$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите верную дату"}'
                                ],
                                'MASK' => '99.99.9999',
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => 'OGRN',
                                'TITLE' => 'ОГРН',
                                'PLACEHOLDER' => 'ОГРН',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern:"^[0-9]{15}$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите 15 цифр ОГРН"}'
                                ],
                                'MASK' => '999999999999999',
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => 'INN',
                                'TITLE' => 'ИНН',
                                'PLACEHOLDER' => 'ИНН',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern:"^[0-9]{12}$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите 12 цифр ИНН"}'
                                ],
                                'MASK' => '999999999999',
                            ),
                            array(
                                'TYPE' => 'date',
                                'ID' => 'DNFVPD',
                                'TITLE' => 'Дата начала фактического ведения предпринимательской деятельности',
                                'PLACEHOLDER' => 'дд.мм.гггг',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern:"^(0[1-9]|[12][0-9]|3[01]).(0[1-9]|1[012]).((199[0-9])|((200[0-9])|(201[0-7])))$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите верную дату"}'
                                ],
                                'MASK' => '99.99.9999',
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => 'OND',
                                'TITLE' => 'Основное направление деятельности',
                                'PLACEHOLDER' => '',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => 'FSD',
                                'TITLE' => 'Фактическая сфера деятельности/отрасль производства',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                        ),
                    ),
                    array(
                        'TITLE' => 'Почтовый адрес',
                        'FIELD' => array(
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Страна',
                                'ID' => '8S',
                                'VALUE' => 'Российская Федерация',
                                'DISABLED' => 'Y',
                                //'ONCLICK' => 'return { oRequired : {} }',
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Индекс',
                                'ID' => '8I',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern:"^[0-9]{6}$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите 6 цифр индекса"}'
                                ],
                                'MASK' => '999999',
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Регион',
                                'ID' => '8Reg',
                                'VALIDATE' => [
                                    'RULES' => '{pattern:"^[-а-яёА-ЯЁ ]+$", required: true, kladr:true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите на русском языке"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Район',
                                'ID' => '8R',
                                'VALIDATE' => [
                                    'RULES' => '{kladr:true}',
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Город',
                                'ID' => '8G',
                                'VALIDATE' => [
                                    'RULES' => '{kladr:true}',
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Населенный пункт',
                                'ID' => '8NP',
                                'VALIDATE' => [
                                    'RULES' => '{kladr:true}',
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Улица',
                                'ID' => '8U',
                                'VALIDATE' => [
                                    'RULES' => '{kladr:true}',
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Дом',
                                'ID' => '8D',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Корп./Стр.',
                                'ID' => '8K',
                                ////'ONCLICK' => 'return { oRequired : {} }',
                            ),
                            array(
                                'TYPE' => 'text',
                                'TITLE' => 'Пом./Офис',
                                'ID' => '8P',
                                ////'ONCLICK' => 'return { oRequired : {} }',
                            ),
                            array(
                                'TYPE' => 'div',
                                'CLASS' => "tooltip error",
                                'STYLE' => 'display: none;',
                                'TITLE' => '<b></b><span></span>',
                            )
                        ),
                    ),
                    array(
                        'TITLE' => 'Контактная информация',
                        'FIELD' => array(
                            array(
                                'TYPE' => 'text',
                                'ID' => '9ST',
                                'TITLE' => 'стационарный тeлефон',
                                'PLACEHOLDER' => '8(код города)телефон',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern:"^8 [0-9]{3} [0-9]{3}-[0-9]{2}-[0-9]{2}$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения", pattern: "Укажите телефон"}'
                                ],
                                'MASK' => '8 999 999-99-99',
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => '9MT',
                                'TITLE' => 'мобильный тeлефон',
                                'PLACEHOLDER' => '',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, pattern:"^8 [0-9]{3} [0-9]{3}-[0-9]{2}-[0-9]{2}$"}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения", pattern: "Укажите телефон"}'
                                ],
                                'MASK' => '8 999 999-99-99',
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => '9F',
                                'TITLE' => 'факс',
                                'PLACEHOLDER' => '',
                                //'ONCLICK' => 'return { oRequired : {} }',
                                'MASK' => '8 999 999-99-99',
                            ),
                            array(
                                'TYPE' => 'text',
                                'ID' => '9E',
                                'TITLE' => 'электронная почта',
                                'VALIDATE' => [
                                    'RULES' => '{required: true, email: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения", email: "Укажите корректный e-mail"}'
                                ], 
                            ),
                        ),
                    ),
                );
                ShowFormFields($arFields)?>    
                <dl class="zf zf-fieldset" style="padding: 15px 0 5px 1px;"><dt><hr style="width: 490px;"></dt></dl>

                <div class="zf-buttons">
                    <div class="zf-divnext">
                        <input type="submit" onsubmit="/*return false;*/" class="zf zf-buttonnext" style="width: 151px;" id="iblock_submit_1" name="iblock_submit_1" value="Далее">
                    </div>  
                    <div class="footer">шаги<div class="steps"><a href="#step1" class="selected">1</a><a href="#step2" class="next-step">2</a><a href="#step3" class="next-step">3</a></div></div>
                </div>

                <p><span class="starrequired" style="color:#FE0055;">*</span> поля, обязательные для заполнения</p>
            </fieldset>
            <fieldset class="zf zf-sheet" id='step2'>
                <div class="header">шаги<div class="steps"><a href="#step1" class="prev-step">1</a><a href="#step2" class="selected">2</a><a href="#step3" class="next-step">3</a></div></div>
                <?$arFields = array(
                    array(
                        'TITLE' => 'Численность коллектива, ежемесячный фонд оплаты труда',
                        'FIELD' => array(
                            array(
                                'TYPE' => 'radio',
                                'ID' => '10OPDVECh',
                                'TITLE' => 'Осуществляете предпринимательскую деятельность в единственном числе?',
                                'VARIANT' => array('Да','Нет'),
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'fieldset',
                                //'ONCLICK' => "return { oEnabled : { sName: '10OPDVECh', rPattern : 1 } }",
                                'FIELD' => array(
                                    array(
                                        'TYPE' => 'text',
                                        'ID' => '10PSh',
                                        'TITLE' => 'По штату',
                                        'VALIDATE' => [
                                            'RULES' => '{pattern: "^[0-9]+$"}',
                                            'MESSAGES' => '{pattern: "Укажите число"}'
                                        ],
                                    ),
                                    array(
                                        'TYPE' => 'text',
                                        'ID' => '10PF',
                                        'TITLE' => 'По факту',
                                        'VALIDATE' => [
                                            'RULES' => '{pattern: "^[0-9]+$"}',
                                            'MESSAGES' => '{pattern: "Укажите число"}'
                                        ],
                                    ),
                                    array(
                                        'TYPE' => 'text',
                                        'ID' => '10KORM',
                                        'TITLE' => 'Кол-во оборудованных рабочих мест',
                                        'VALIDATE' => [
                                            'RULES' => '{pattern: "^[0-9]+$"}',
                                            'MESSAGES' => '{pattern: "Укажите число"}'
                                        ],
                                    ),
                                    array(
                                        'TYPE' => 'text',
                                        'ID' => '10EFFOT',
                                        'TITLE' => 'Ежемесячный фактический фонд оплаты труда',
                                        'VALIDATE' => [
                                            'RULES' => '{pattern: "^[0-9]+$"}',
                                            'MESSAGES' => '{pattern: "Укажите число"}'
                                        ],
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'TITLE' => 'Сведения о лицензиях (разрешениях) на осуществление деятельности',
                        'FIELD' => array(
                            array(
                                'TYPE' => 'radio',
                                'ID' => '11DNL',
                                'TITLE' => 'Деятельность лицензируется ?',
                                'VARIANT' => array('Да','Нет'),
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'fieldset',
                                //'ONCLICK' => "return { oEnabled : { sName: '11DNL', rPattern : 1 } }",
                                'FIELD' => array(
                                    array(
                                        'TYPE' => 'text',
                                        'ID' => '11NL',
                                        'TITLE' => 'Номер лицензии (разрешения)',
                                        //'ONCLICK' => 'return { oRequired : {} }',
                                        'ARROW' => '',
                                        'ERROR' => '',
                                    ),
                                    array(
                                        'TYPE' => 'text',
                                        'ID' => '11VLD',
                                        'TITLE' => 'Вид лицензируемой деятельности',
                                        //'ONCLICK' => 'return { oRequired : {} }',
                                        'ARROW' => '',
                                        'ERROR' => '',
                                    ),
                                    array(
                                        'TYPE' => 'text',
                                        'ID' => '11DVISD',
                                        'TITLE' => 'Дата выдачи и срок действия',
                                        //'ONCLICK' => 'return { oRequired : {} }',
                                        'ARROW' => '',
                                        'ERROR' => '',
                                    ),
                                    array(
                                        'TYPE' => 'text',
                                        'ID' => '11OVL',
                                        'TITLE' => 'Орган, выдавший лицензию (разрешение)',
                                        //'ONCLICK' => 'return { oRequired : {} }',
                                        'ARROW' => '',
                                        'ERROR' => '',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'TITLE' => 'Сведения о целях установления деловых отношений с Банком',
                        'FIELD' => array(
                            array(
                                'TYPE' => 'checkbox',
                                'ID' => '12',
                                //'ONCLICK' => "return { oRequired : { iMin : 1 } }",
                                'VARIANT' => array(
                                    'безналичные расчеты в рублях',
                                    'безналичные расчеты в иностранной валюте',
                                    'операции по покупке / продаже иностранной валюты',
                                    'прием и выдача наличных денежных средств',
                                    'инкассация выручки',
                                    'выдача заработной платы сотрудникам с использованием банковских карт',
                                    'депозиты',
                                    'кредитование/овердрафт',
                                    'обслуживание по системе «Банк–Клиент»',
                                    'другие (укажите)',
                                ),
                                'OTHER' => 'Y',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Выберите хотя бы один вариант"}'
                                ],
                            ),
                        ),
                    ),
                    array(
                        'TITLE' => 'Сведения о предполагаемом характере деловых отношений с Банком',
                        'FIELD' => array(
                            array(
                                'TYPE' => 'radio',
                                'TITLE' => 'Сведения о предполагаемом характере деловых отношений с Банком',
                                'ID' => '13',
                                'VARIANT' => array(
                                    'Долгосрочный (заключение с Банком договорных отношений)',
                                    'Краткосрочный (совершение разовых операций)'
                                ),
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                        ),
                    ),
                    array(
                        'TITLE' => 'Опишите технологический процесс построения предпринимательской деятельности',
                        'FIELD' => array(
                            array(
                                'TYPE' => 'textarea',
                                //'ONCLICK' => 'return { oRequired : {} }',
                                'ID' => '14KOPKDRTU',
                                'TITLE' => 'Как осуществляется поиск клиентов для реализации товара/оказания услуг?',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'textarea',
                                //'ONCLICK' => 'return { oRequired : {} }',
                                'ID' => '14IF',
                                'TITLE' => 'Источники финансирования?',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'textarea',
                                //'ONCLICK' => 'return { oRequired : {} }',
                                'ID' => '14KDR',
                                'TITLE' => 'Как дается реклама?',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'textarea',
                                //'ONCLICK' => 'return { oRequired : {} }',
                                'ID' => '14KOORT',
                                'TITLE' => 'Каким образом осуществляется реализация товаров/услуг?',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                            array(
                                'TYPE' => 'textarea',
                                //'ONCLICK' => 'return { oRequired : {} }',
                                'ID' => '14KOT',
                                'TITLE' => 'Как осуществляется транспортировка?',
                                'VALIDATE' => [
                                    'RULES' => '{required: true}',
                                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                                ],
                            ),
                        ),
                    ),
                );
                ShowFormFields($arFields)?> 
                
                <dl class="zf zf-fieldset" style="padding: 15px 0 5px 1px;"><dt><hr style="width: 490px;"></dt></dl>
                <div class="zf-buttons">
                    <div class="zf-divprev">
                        <input type="button" class="zf zf-buttonprev" id="iblock_prev" name="iblock_submit_2" value="Назад" onclick="setStep('#step1');">
                    </div> 
                    <div class="zf-divnext">
                        <input type="submit" onsubmit="/*return false;*/" class="zf zf-buttonnext" style="width: 151px;" id="iblock_submit_2" name="iblock_submit_2" value="Далее" >
                    </div> 
                    <div class="footer"><div >шаги<div class="steps"><a href="#step1" class="prev-step">1</a><a href="#step2" class="selected">2</a><a href="#step3" class="next-step">3</a></div></div></div>
                </div>
				<br />
				<p><span class="starrequired" style="color:#FE0055;">*</span> поля, обязательные для заполнения</p>
            </fieldset>
            <fieldset class="zf zf-sheet" id='step3'>
                <div class="header">шаги<div class="steps"><a href="#step1" class="prev-step">1</a><a href="#step2" class="prev-step">2</a><a href="#step3" class="selected">3</a></div>
                </div>
<?$arFields = array(
    array(
        'TITLE' => 'Сведения о деловой репутации',
        'FIELD' => array(
            array(
                'TYPE' => 'div',
                'TITLE' => 'Отметьте , какие именно отзывы (рекомендательные письма) о Вас Вы представляете в Банк:',
            ),
            array(
                'TYPE' => 'checkbox',
                'ID' => '15KIO',
                'VARIANT' => array(
                    'отзывы других клиентов  КБ «РЭБ» (АО), имеющих с Вами деловые отношения',
                    'отзывы от других кредитных организаций, в которых Вы ранее находились на обслуживании, с информацией этих кредитных организаций об оценке Вашей деловой репутации'
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Выберите хотя бы один вариант"}'
                ],
            ),
            array(
                'TYPE' => 'fieldset',
                'DISPLAY' => 'Y',
                'FIELD' => array(
                    array(
                        'TYPE' => 'div',
                        'TITLE' => 'в случае невозможности получения таких отзывов отметьте , пожалуйста, следующее поле:',
                    ),
                    array(
                        'TYPE' => 'checkbox',
                        'ID' => '15OV',
                        'VARIANT' => array(
                            'отсутствует возможность получения отзывов от клиентов КБ «РЭБ» (АО)',
                            'отсутствует возможность получения отзывов от ранее обслуживавших кредитных организаций.'
                        ),
                        'VALIDATE' => [
                            'RULES' => '{required: true}',
                            'MESSAGES' => '{required: "Выберите хотя бы один вариант"}'
                        ],
                    ),
                ),
            ),
        ),
    ),
    array(
        'TITLE' => 'Дополнительные сведения о Вашей деловой репутации',
        'FIELD' => array(
            array(
                'TYPE' => 'div',
                'TITLE' => '(иная информация, которую Вы хотите сообщить Банку о деловой репутации в качестве ИП):',
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15I',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'История',
                'LABELCOM' => '(время работы в этой сфере)',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15K',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Конкуренция',
                'LABELCOM' => '(при высокой конкуренции укажите Ваших основных конкурентов, при низкой конкуренции – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15OVBVDS',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Опыт ведения бизнеса в данной сфере',
                'LABELCOM' => '(при отсутствии – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15DN',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Достижения, награды',
                'LABELCOM' => '(при отсутствии – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '15RGV',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Размер годовой выручки',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15EVYaOP',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Если Вы являетесь официальным представителем, дистрибьютором известных компаний, укажите их наименования',
                'LABELCOM' => '(при отсутствии – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15IS',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Интернет – ссылки, по которым можно получить сведения о ведении реальной финансово-хозяйственной деятельности',
                'LABELCOM' => '(при отсутствии – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15SMI',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'СМИ и социальные сети, в которых содержатся упоминания о ведении деятельности',
                'LABELCOM' => '(при отсутствии – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15II',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Иные источники, в которых содержится рекламная информация о ведении деятельности ',
                'LABELCOM' => '(при отсутствии – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15PFP',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Прошлые финансовые проблемы',
                'LABELCOM' => '(при отсутствии – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '15NZPONNTD',
                //'ONCLICK' => 'return { oRequired : {} }',
                'TITLE' => 'Наличие задолженности по оплате налогов на текущую дату ',
                'LABELCOM' => '(при отсутствии – «Нет»)',
                'VALUE' => '',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
        ),
    ),
    array(
        'TITLE' => 'Имеете ли Вы собственные либо арендованные основные средства, необходимые для осуществления деятельности?',
        'FIELD' => array(
            array(
                'TYPE' => 'radio',
                'TITLE' => 'Складские помещения:',
                'ID' => '16SP',
                'VARIANT' => array('Имеются в аренде','Имеются в собственности','Отсутствуют'),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'radio',
                'TITLE' => 'Товар, находящийся на складе является собственностью ИП?',
                'ID' => '16TNNS',
                'VARIANT' => array('Да', 'Нет'),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
             array(
                'TYPE' => 'fieldset',
                //'ONCLICK' => "return { oEnabled : { sName: '10OPDVECh', rPattern : 1 } }",
                'FIELD' => array(
                    array(
                        'TYPE' => 'textarea',
                        'ID' => ' 16POSP',
                        //'ONCLICK' => 'return { oRequired : {} }',
                        'TITLE' => 'При отсутствии складских помещений, поясните причину их неиспользования',
                    ),
                ),
            ),
            array(
                'TYPE' => 'radio',
                'TITLE' => 'Емкости для хранения:',
                'ID' => '16EDH',
                'VARIANT' => array('Имеются в аренде','Имеются в собственности','Отсутствуют'),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'radio',
                'TITLE' => 'Транспортные средства:',
                'ID' => '16TS',
                'VARIANT' => array('Имеются в аренде','Имеются в собственности','Отсутствуют'),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'radio',
                'TITLE' => 'Торговые точки:',
                'ID' => '16TT',
                'VARIANT' => array('Имеются в аренде','Имеются в собственности','Отсутствуют'),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
        ),
    ),
    '17' => array(
        'TITLE' => 'Cчет 40802',
        'FIELD' => array(
            array(
                'TYPE' => 'radio',
                'ID' => '17ILVCVKIP',
                'VARIANT' => array('Да', 'Нет'),
                'TITLE' => 'Имеете ли Вы счета в качестве Индивидуального предпринимателя (счет 40802) в других кредитных организациях?',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'fieldset',
                'FIELD' => array(
                    array(
                        'TYPE' => 'text',
                        'ID' => '17NKO',
                        'TITLE' => 'Наименование кредитной организации',
                        'PLACEHOLDER' => '',
                        //'ONCLICK' => 'return { oRequired : {} }',
                        'ARROW' => '',
                        'ERROR' => '',
                    ),
                    array(
                        'TYPE' => 'text',
                        'ID' => '17MNKO',
                        'TITLE' => 'Место нахождение кредитной организаци',
                        //'ONCLICK' => 'return { oRequired : {} }',
                    ),
                    array(
                        'TYPE' => 'text',
                        'ID' => '17HO',
                        'TITLE' => 'Характер отношений (укажите название услуг, которыми Вы пользуетесь)',
                        //'ONCLICK' => 'return { oRequired : {} }',
                    ),
                    array(
                        'TYPE' => 'text',
                        'ID' => '17PO',
                        'TITLE' => 'Продолжительность отношений',
                        //'ONCLICK' => 'return { oRequired : {} }',
                    ),
                ),
            ),
        ),
    ),
    '18' => array(
        'TITLE' => 'Сведения о системе налогообложения и финансовом результате',
        'FIELD' => array(
            array(
                'TYPE' => 'radio',
                'ID' => '18SN',
                'TITLE' => 'Система налогообложения:',
                'VARIANT' => array(
                    'Общая (ОСНО)',
                    'Единый налог на вмененный доход (ЕНВД)',
                    'Упрощенная (УСН)',
                    'Патентная система',
                    'Единый сельскохозяйственный налог (ЕСХН)',
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '18ON',
                'TITLE' => 'Отчетность на: ',
                'LABELCOM' => '(отчетный период)',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '18VB',
                'TITLE' => 'Валюта баланса: ',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'radio',
                'ID' => '18SVOR',
                'TITLE' => 'Среднемесячная выручка от реализации:',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'товаров',
                    'продукции',
                    'работ',
                    'услуг',
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '18VR',
                'TITLE' => 'В размере: ',
                'VALIDATE' => [
                    'RULES' => '{required: true, pattern:"^[0-9 ,.]+$"}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения", pattern:"Укажите сумму"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '18CPZPG',
                'TITLE' => 'Чистая прибыль за предыдущий год: ',
                'VALIDATE' => [
                    'RULES' => '{required: true, pattern:"^[0-9 ,.]+$"}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения", pattern:"Укажите сумму"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '18OSNPOVBR',
                'TITLE' => 'Общая сумма налоговых платежей, осуществленных в бюджет РФ ',
                'VALIDATE' => [
                    'RULES' => '{required: true, pattern:"^[0-9 ,.]+$"}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения", pattern:"Укажите сумму"}'
                ],
            ),
        ),
    ),
    '19' => array(
        'TITLE' => 'Виды финансовой отчетности и справок',
        'FIELD' => array(
            array(
                'TYPE' => 'div',
                'TITLE' => 'Укажите виды финансовой отчетности и справок, которые готовы представить в Банк (допускается представление одного или нескольких документов)',
            ),
            array(
                'ID' => '19UVFOIS',
                'TYPE' => 'checkbox',
                //'ONCLICK' => 'return { oRequired : {iMin : 1} }',
                'VARIANT' => array(
                    'бухгалтерский баланс',
                    'отчет о финансовом результате',
                    'копия годовой (либо квартальной) налоговой декларации',
                    'копия аудиторского заключения на годовой отчет за прошедший год',
                    'справка об исполнении налогоплательщиком (плательщиком сборов, налоговым агентом) обязанности по уплате налогов, сборов, выданная налоговым органом',
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Выберите хотя бы один вариант"}'
                ],
            ),
            array(
                'TYPE' => 'fieldset',
                'DISPLAY' => 'Y',
                'FIELD' => array(
                    array(
                        'TYPE' => 'div',
                        'TITLE' => 'Причины, по которым вышеперечисленные документы не могут быть представлены:',
                    ),
                    array(
                        'ID' => '19PPKVDNMBP',
                        'TYPE' => 'checkbox',
                        //'ONCLICK' => 'return { oRequired : {iMin : 1} }',
                        'VARIANT' => array(
                            'сведения о финансовом положении не могут быть представлены ввиду того, что деятельность не велась с момента государственной регистрации',
                            'сведения о финансовом положении не могут быть представлены ввиду того, что деятельность в последнем отчетном периоде не велась',
                            'иные причины (указать)',
                        ),
                        'OTHER' => 'Y',
                        'VALIDATE' => [
                            'RULES' => '{required: true}',
                            'MESSAGES' => '{required: "Выберите хотя бы один вариант"}'
                        ],
                    ),
                ),
            ),
        ),
    ),
    '20' => array(
        'TITLE' => 'Планируемые параметры обслуживания',
        'FIELD' => array(
            array(
                'TYPE' => 'select',
                'ID' => '20SOKPOPSVM',
                'TITLE' => 'Сведения о количестве планируемых операций по счету в месяц:',
                'VARIANT' => array(
                    'до 10',
                    'от 10',
                    'от 100',
                    'от 1000',
                ),
                //'ONCLICK' => 'return { oRequired : {} }',
            ),
            array(
                'TYPE' => 'select',
                'ID' => '25PSOPS',
                'TITLE' => 'Планируемый среднедневной остаток по счету:',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'до 1 000 000 руб.',
                    'свыше  1 000 000 руб.',
                    'свыше  3 000 000 руб.',
                    'свыше  10 000 000 руб.',
                ),
            ),
            array(
                'TYPE' => 'text',
                'ID' => '21PHPPKS',
                'TITLE' => 'Планируемый характер платежей по кредиту счета',
                'LABELCOM' => '(зачисление на счет)',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '22PHPPDS',
                'TITLE' => 'Планируемый характер платежей по дебету счета',
                'LABELCOM' => '(списание со счета)',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'radio',
                'ID' => '27PLVSOVAN',
                'TITLE' => 'Планируете ли Вы совершать операции в адрес нерезидентов?',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'Да',
                    'Нет',
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '27UYN',
                'TITLE' => 'Укажите юрисдикции нерезидентов: ',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'radio',
                'ID' => '28PLVSOVPTL',
                'TITLE' => 'Планирует ли Вы совершать операции в пользу третьих лиц, не являющихся стороной по заключенным договорам?',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'Да',
                    'Нет',
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'radio',
                'ID' => '29PLVOOSN',
                'TITLE' => 'Планируете ли Вы осуществлять операции с нерезидентами, не являющимися резидентами Республик Беларусь или Казахстан, за товар, ранее приобретенный у резидентов Республик Беларусь или Казахстан?',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'Да',
                    'Нет',
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
        )
    ),
    '23' => array(
        'TITLE' => 'Планируемый ежемесячный оборот по кредиту счета, в рублях',
        'FIELD' => array(
            array(
                'TYPE' => 'select',
                'ID' => '23SPVBF',
                'TITLE' => 'Cумма переводов в безналичной форме:',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'до 1 000 000 руб.',
                    'до 10 000 000 руб.',
                    'до 100 000 000 руб.',
                    'свыше 100 000 000 руб.',
                ),
            ),
            array(
                'TYPE' => 'select',
                'ID' => '23SOVNF',
                'TITLE' => 'Cумма операций в наличной форме:',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    '0 руб.',
                    'до 600 000 руб.',
                    'свыше  600 000 руб.',
                    'свыше  3 000 000 руб.',
                    'свыше  10 000 000 руб.',
                ),
            ),
            array(
                'TYPE' => 'select',
                'ID' => '23SPVIV',
                'TITLE' => 'Cумма операций в наличной форме:',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    '0 руб.',
                    'до 1 000 000 руб.',
                    'до 10 000 000 руб.',
                    'до 100 000 000 руб.',
                    'свыше  100 000 000 руб.',
                ),
            )
        ),
    ),
    '24' => array(
        'TITLE' => 'Планируемый ежемесячный оборот по дебету счета, в рублях',
        'FIELD' => array(
            array(
                'TYPE' => 'select',
                'ID' => '24SPVBF',
                'TITLE' => 'Cумма переводов в безналичной форме:',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'до 1 000 000 руб.',
                    'до 10 000 000 руб.',
                    'до 100 000 000 руб.',
                    'свыше 100 000 000 руб.',
                ),
            ),
            array(
                'TYPE' => 'select',
                'ID' => '24SOVNF',
                'TITLE' => 'Cумма операций в наличной форме:',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    '0 руб.',
                    'до 600 000 руб.',
                    'свыше  600 000 руб.',
                    'свыше  3 000 000 руб.',
                    'свыше  10 000 000 руб.',
                ),
            ),
            array(
                'TYPE' => 'select',
                'ID' => '24SPVIV',
                'TITLE' => 'Cумма операций в наличной форме:',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    '0 руб.',
                    'до 1 000 000 руб.',
                    'до 10 000 000 руб.',
                    'до 100 000 000 руб.',
                    'свыше  100 000 000 руб.',
                ),
            )
        ),
    ),
    '26' => array(
        'TITLE' => 'Укажите виды договоров (контрактов), расчеты по которым Вы планируете осуществлять через Банк?',
        'FIELD' => array(
            array(
                'ID' => '26UVDK',
                'TYPE' => 'checkbox',
                //'ONCLICK' => 'return { oRequired : {iMin : 1} }',
                'VARIANT' => array(
                    'агентский договор',
                    'договор поручения',
                    'договор комиссии',
                    'договор доверительного управления',
                    'договор займа',
                    'договор купли-продажи ценных бумаг',
                    'договор поставки',
                    'договор аренды, купли-продажи недвижимого имущества',
                    'иные (уточнить)',
                ),
                'OTHER' => 'Y',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Выберите хотя бы один вариант"}'
                ],
            ),
        ),
    ),
    array(
        'TITLE' => 'Укажите сведения о бенефициарном владельце (при его наличии)?',
        'FIELD' => array(
            array(
                'TITLE' => 'Фамилия, имя, отчество физических лиц, имеющих возможность контролировать Ваши действия',
                'ID' => '28FIOFL',
                'TYPE' => 'textarea',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TITLE' => 'Адрес регистрации (прописки) или адрес места жительства (пребывания)',
                'ID' => '28AR',
                'TYPE' => 'textarea',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TITLE' => 'Гражданство',
                'ID' => '28G',
                'TYPE' => 'textarea',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TITLE' => 'Дата рождения',
                'ID' => '28DR',
                'TYPE' => 'date',
                'MASK' => '99.99.9999',
                'VALIDATE' => [
                    'RULES' => '{required: true, pattern:"^(0[1-9]|[12][0-9]|3[01]).(0[1-9]|1[012]).((19[0-9][0-9])|((200[0-9])|(201[0-7])))$"}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения", pattern:"Укажите верную дату"}'
                ],
            ),
            array(
                'TITLE' => 'Место рождения',
                'ID' => '28MR',
                'TYPE' => 'text',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TITLE' => 'Реквизиты документа, удостоверяющего личность (тип документа, серия и номер, кем выдан (с указанием кода подразделения (если имеется)), дата выдачи)',
                'ID' => '28RDUL',
                'TYPE' => 'textarea',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
        ),
    ),
    array(
        'TITLE' => 'Сведения о принадлежности к числу налогоплательщиков США?',
        'FIELD' => array(
            array(
                'TYPE' => 'radio',
                'ID' => '29NUVGS',
                'TITLE' => 'Наличие у Вас гражданства США?',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'Да',
                    'Нет',
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'radio',
                'ID' => '29NUVZK',
                'TITLE' => 'Наличие у Вас «зеленой карты» (Green Card)?',
                //'ONCLICK' => 'return { oRequired : {} }',
                'VARIANT' => array(
                    'Да',
                    'Нет',
                ),
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
        ),
    ),
    array(
        'TITLE' => 'Укажите сведения об основных контрагентах - резидентах и их роль в сотрудничестве с Вами?',
        'MULTI' => 'Контрагент №',
        'CNT' => 2,
        'FIELD' => array(
            array(
                'TYPE' => 'textarea',
                'ID' => '30NK',
                'TITLE' => 'Наименование контрагента',
                'LABELCOM' => '(ссылка на Интернет-сайт контрагента-резидента, упоминания в СМИ)',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '30INN',
                'TITLE' => 'ИНН контрагента',
                'PLACEHOLDER' => 'ИНН',
                'VALIDATE' => [
                    'RULES' => '{required: true, pattern:"^([0-9]{10}|[0-9]{12})$"}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения",pattern: "Укажите ИНН"}'
                ],
                //'MASK' => '999999999999',
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '30DR',
                'TITLE' => 'Деловая репутация,  финансовое положение (история (время работы в этой сфере), сектор рынка и конкуренция',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '30OB',
                'TITLE' => 'Обслуживающие банки',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'select',
                'ID' => '30RK',
                'TITLE' => 'Роль контрагента',
                'VARIANT' => array('производитель','поставщик', 'покупатель', 'посредник', 'перевозчик', 'консультант', 'прочее'),
                'VALUE' => 'Y',
                //'ONCLICK' => 'return { oRequired : {} }',
            ),
            array(
                'TYPE' => 'fieldset',
                'ID' => '30URK',
                'FIELD' => array(
                    array(
                        'TITLE' => 'Укажите роль контрагента',
                        'TYPE' => 'text',
                        'ID' => '30URK',
                    ),
                ),
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '30KVDOMV',
                'TITLE' => 'Как возникли деловые отношения между Вами? Условия заключенных договоров',
                'LABELCOM' => '(предмет договора, срок, сумма, форма расчетов)',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
        ),
    ),
    array(
        'TITLE' => 'Укажите сведения об основных контрагентах - нерезидентах и их роль в сотрудничестве с Вами?',
        'MULTI' => 'Контрагент №',
        'CNT' => 2,
        'FIELD' => array(
            array(
                'TYPE' => 'textarea',
                'ID' => '31NK',
                'TITLE' => 'Наименование контрагента',
                'LABELCOM' => '(ссылка на Интернет-сайт контрагента-резидента, упоминания в СМИ)',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'text',
                'ID' => '31MNK',
                'PLACEHOLDER' => 'страна, город',
                'TITLE' => 'Место нахождения контрагента',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
                //'MASK' => '999999999999',
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '31DR',
                'TITLE' => 'Деловая репутация,  финансовое положение (история (время работы в этой сфере), сектор рынка и конкуренция',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '31OB',
                'TITLE' => 'Обслуживающие банки',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'select',
                'ID' => '31RK',
                'TITLE' => 'Роль контрагента',
                'VARIANT' => array('производитель','поставщик', 'покупатель', 'посредник', 'перевозчик', 'консультант', 'прочее'),
                'VALUE' => 'Y',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
            array(
                'TYPE' => 'fieldset',
                'ID' => '31URK',
                'FIELD' => array(
                    array(
                        'TITLE' => 'Укажите роль контрагента',
                        'TYPE' => 'text',
                        'ID' => '31URK',
                    ),
                ),
            ),
            array(
                'TYPE' => 'textarea',
                'ID' => '31KVDOMV',
                'TITLE' => 'Как возникли деловые отношения между Вами? Условия заключенных договоров',
                'LABELCOM' => '(предмет договора, срок, сумма, форма расчетов)',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Это поле обязательно для заполнения"}'
                ],
            ),
        ),
    ),
    array(
        'TITLE' => 'Почему Вы выбрали КБ «РЭБ» (АО)?',
        'FIELD' => array(
            array(
                'ID' => '32PVVKRA',
                'TYPE' => 'checkbox',
                //'ONCLICK' => 'return { oRequired : {iMin:1} }',
                'VARIANT' => array(
                    'Информация в СМИ, Интернет',
                    'рекомендация знакомых',
                    'репутация и имидж КБ «РЭБ» (АО) на российском рынке',
                    'требование партнеров по бизнесу',
                    'приемлемые стоимостные условия услуг',
                    'наличие услуг, которых нет в других Банках',
                    'качество и культура обслуживания клиентов',
                    'удобное месторасположение',
                    'наличие в КБ «РЭБ» (АО) филиальной сети в регионах',
                    'наличие контрагентов, обслуживающихся в КБ «РЭБ» (АО)',
                    'диверсификация своих рисков на Банки ',
                    'другие (укажите)',
                ),
                'OTHER' => 'Y',
                'VALIDATE' => [
                    'RULES' => '{required: true}',
                    'MESSAGES' => '{required: "Выберите хотя бы один вариант"}'
                ],
            ),
        ),
    ),
);
ShowFormFields($arFields);?>

                <dl class="zf zf-fieldset" style="padding: 15px 0 5px 1px;"><dt><hr style="width: 490px;"></dt></dl>
                <div class="zf-buttons">
                    <div class="zf-divprev">
                        <input type="button" class="zf zf-buttonprev" id="iblock_prev" name="iblock_submit_3" value="Назад" onclick="setStep('#step2');">
                    </div> 
                    <div class="zf-divnext">
                        <input type="submit" class="zf zf-submit" style="width: 151px;" id="iblock_submit_3" name="iblock_submit_3" value="Получить файл" />
                    </div> 
                    <div class="footer"><div >шаги<div class="steps"><a href="#step1" class="prev-step">1</a><a href="#step2" class="prev-step">2</a><a href="#step3" class="selected">3</a></div></div></div>
                </div>
				<br />
				<p><span class="starrequired" style="color:#FE0055;">*</span> поля, обязательные для заполнения</p>
            </fieldset>
        </form>
    </body>
</html>

<script>
    

    
</script>
