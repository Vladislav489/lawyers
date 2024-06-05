@extends('lawyers.layouts.main')
@section('title', 'Пополнить баланс')

@section('content')
<section class="gradient-bg u-container payment-section">
    <div class="container">
        <ul class="breadcrumbs mobile-hidden">
            <li class="cool-underline"><a href="#">Юрист</a></li>
            <li class="cool-underline"><a href="#">Город</a></li>
        </ul>

        <div class="payment">
            <div class="payment-block finance-block">
                <h2 class="payment_header">Финансы Клиента</h2>
                <div class="payment-container">
                    <div class="finance-line">
                        <p>Доступно</p>
                        <p>0 рублей</p>
                    </div>
                    <div class="finance-line">
                        <p>Блокировано</p>
                        <p>0 рублей</p>
                    </div>
                    <span class="line"></span>
                    <div class="finance-line">
                        <p>Ваш баланс</p>
                        <p>0 рублей</p>
                    </div>
                    <div class="finance-line text-gray">
                        <p>Ожидается</p>
                        <p>0 рублей</p>
                    </div>
                    <div class="finance-line">
                        <p>Сумма к оплате</p>
                        <p>0 рублей</p>
                    </div>
                </div>
            </div>

            <div class="payment-block payment-replenishment">
                <h2 class="payment_header">Пополнение счета</h2>
                <form action="#" class="payment-container">
                    <label>
                        <span class="label_text">Сумма пополнения</span>
                        <input type="text" placeholder="Сумма" name="execution-sum">
                        <span class="label_subtext">рублей</span>
                    </label>

                    <div class="payment-type select">
                        <label class="select-btn">
                            <span class="label_text">Способ оплаты</span>
                            <input type="text" placeholder="Выберите способ оплаты" readonly>
                        </label>

                        <ul class="select-window">
                            <li>Банковской картой</li>
                            <li>Наличными</li>
                            <li>QIWI кошелек</li>
                        </ul>

                        <img src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon" class="icon sub-icon">

                        <p class="code">
                            <a href="#">Ввести код</a>
                            <span>(если есть)</span>
                        </p>
                    </div>

                    <p>
                        <span class="label_text">Сумма к оплате</span>
                        <span>0&nbsp;рублей</span>
                    </p>

                    <p class="text_lil">
                        <span class="label_text">Включая сбор за финансовую транзакцию</span>
                        <span>0&nbsp;рублей</span>
                    </p>

                    <button type="submit" class="main-btn"><span>Продолжить</span></button>
                </form>
            </div>

            <div class="payment-block transaction-block">
                <h2 class="payment_header">История транзакций</h2>

                <table class="transaction-history" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Дата начала</th>
                        <th>Дата завершения</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Тип Транзакции</th>
                        <th>Комментарий</th>
                    </tr>
                    </thead>
                    <tr>
                        <td data-label='id'><span>6784563</span></td>
                        <td data-label='Дата начала'><span>12:27 03.11.2018</span></td>
                        <td data-label='Дата завершения'><span>13:44 03.11.2018</span></td>
                        <td class="transaction-sum red"><span>- 800 руб.</span></td>
                        <td data-label='Статус'><span>Завершена</span></td>
                        <td data-label='Тип Транзакции'><span>Расходы</span></td>
                        <td data-label='Комментарий'><span>Пополнение счета</span></td>
                    </tr>
                    <tr>
                        <td data-label='id'><span>6784563</span></td>
                        <td data-label='Дата начала'><span>12:27 03.11.2018</span></td>
                        <td data-label='Дата завершения'><span>13:44 03.11.2018</span></td>
                        <td class="transaction-sum green"><span>+ 800 руб.</span></td>
                        <td data-label='Статус'><span>Завершена</span></td>
                        <td data-label='Тип Транзакции'><span>Расходы</span></td>
                        <td data-label='Комментарий'><span>Пополнение счета</span></td>
                    </tr>
                    <tr>
                        <td data-label='id'><span>6784563</span></td>
                        <td data-label='Дата начала'><span>12:27 03.11.2018</span></td>
                        <td data-label='Дата завершения'><span>13:44 03.11.2018</span></td>
                        <td class="transaction-sum green"><span>+ 800 руб.</span></td>
                        <td data-label='Статус'><span>Завершена</span></td>
                        <td data-label='Тип Транзакции'><span>Расходы</span></td>
                        <td data-label='Комментарий'><span>Пополнение счета</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="lawyer-block">
            <div class="lawyer-avatar">
                <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-avatar" height="70" width="70" />

                <h3 class="lawyer-name">Соколовский Владимир Александрович</h3>
            </div>

            <div class="lawyer-info_balance">
                <div class="lawyer-balance-block">
                    <p>Ваш баланс</p>
                    <span class="balance-summ"><span class="balance-summ_ico">₽</span>0 руб</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
