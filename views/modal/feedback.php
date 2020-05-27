<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 15.07.2019
 * Time: 14:04
 */?>
<form class="js-ajax" action="/feedback/" method="post">
    <input type="hidden" name="recaptcha" />
    <div class="modal-header">
        <b class="modal-title">Обратная связь</b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="name1">Имя*</label>
            <input type="text" class="form-control " id="name1" name="name" required placeholder="Иванов Иван Иванович" value="<?=@$user["name"]?>" required>
        </div>
        <div class="form-group">
            <label for="name">Телефон*</label>
            <input type="text" class="form-control masked-input" id="phone" name="phone" required placeholder="" data-inputmask="'mask': '+7(999) 99-99-999'" value="<?=@$user["phone"]?>" required>
        </div>
        <div class="form-group">
            <label for="name">Email*</label>
            <input type="email" class="form-control " id="email1" name="email" required placeholder="" value="<?=@$user["email"]?>" required>
        </div>
        <div class="form-group mb-0">
            <label for="comment">Тип оборудования/Комментарий*</label>
            <textarea class="form-control" id="comment" name="message" placeholder="" required></textarea>
        </div>
        <div class="mb-2 mt-3 small"><em>Нажимая кнопку «Отправить», я соглашаюсь с условиями <a href="/politika-konfidencialnosti/">политики конфиденциальности</a> и <a href="/dogovor-oferty/">договора оферты</a></em></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Закрыть</button>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </div>
</form>
