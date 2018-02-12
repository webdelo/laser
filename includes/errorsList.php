<?php
// errors list
$errorsList = array(
	'defaultError' => array(
		'ru' => 'Поле не заполнено, либо заполнено неверно!',
		'en' => 'Field is empty or incorrect!',
		'ua' => 'Поле не заповнено або заповнено неправильно!'
	),
	'empty' => array(
		'ru' => 'Поле не может быть пустым!',
		'en' => 'The field can not be empty!',
		'ua' => 'Поле не може бути порожнім!'
	),

	'id' => array(
		'ru' => 'Неверный ID!',
		'en' => 'Incorrect ID!',
		'ua' => 'Неправильний ID!'
	),
	'email_empty' => array(
		'ru' => 'Пожалуйста введите email!',
		'en' => 'Enter your email please!',
		'ua' => 'Будь ласка, введіть email!'
	),
	'email_occupied' => array(
		'ru' => 'Email занят!',
		'en' => 'The email is occupied!',
		'ua' => 'Email зайнятий!'
	),
	'email_invalid' => array(
		'ru' => 'Введенный email неверен!',
		'en' => 'The email is invalid!',
		'ua' => 'Введено неправильний email!'
	),
	'phone' => array(
		'ru' => 'Напишите номер телефона!',
		'en' => 'Еnter your phone number please!',
		'ua' => 'Напишіть номер телефону!'
	),
	'text' => array(
		'ru' => 'Заполните пожалуйста это поле!',
		'en' => 'Fill the field please',
		'ua' => 'Заповніть, будь ласка, це поле!'
	),
	'login' => array(
		'ru' => 'Логин уже существует или недействительный e-mail',
		'en' => 'The login is already exists or is not valid email!',
		'ua' => 'Логін вже існу або E-mail не є дійсним!'
	),
	'password' => array(
		'ru' => 'Указан неверный пароль!',
		'en' => 'The password is incorrect!',
		'ua' => 'Вказано неправильний пароль!'
	),
	'passwordConfirm' => array(
		'ru' => 'Пароли не совпадают!',
		'en' => 'Passwords do not match!',
		'ua' => 'Паролі не збігаються!'
	),
	'group_id' => array(
		'ru' => 'Несуществующая группа пользователей!',
		'en' => 'User groop does not exists!',
		'ua' => 'Неіснуюча група користувачів!'
	),

	'name' => array(
		'ru' => 'Введите название!',
		'en' => 'Enter the name please!',
		'ua' => 'Введіть назву!'
	),
	'captcha' => array(
		'ru' => 'Ответ не верный!',
		'en' => 'The answer is wrong!',
		'ua' => 'Відповідь неправильна!'
	),
	'alias' => array(
		'ru' => 'Пожалуйста введите алиас!',
		'en' => 'Enter the alias please!',
		'ua' => 'Будь-ласка, введіть аліас!'
	),
	'categoryId' => array(
		'ru' => 'Выберите категорию!',
		'en' => 'Select the category please!',
		'ua' => 'Виберіть категорію!'
	),
	'alias_not_unique' => array(
		'ru' => 'Алиас уже существует в б.д.!',
		'en' => 'The alias already exists in DB!',
		'ua' => 'Аліас вже існує у базі даних!'
	),
	'empty_alias' => array(
		'ru' => 'Алиас не может быть пустым!',
		'en' => 'Alias can not be empy!',
		'ua' => 'Аліас не може бути порожнім!'
	),
	'address' => array(
		'ru' => 'Укажите пожалуйста адрес!',
		'en' => 'Enter the adres please!',
		'ua' => 'Вкажіть, будь ласка, адресу!'
	),
	'cityId' => array(
		'ru' => 'Укажите пожалуйста город!',
		'en' => 'Select the city please!',
		'ua' => 'Вкажіть, будь ласка, місто!'
	),
	'note' => array(
		'ru' => 'Укажите примечание!',
		'en' => 'Enter the notes please!',
		'ua' => 'Вкажіть примітку!'
	),

	'price' => array(
		'ru' => 'Укажите цену!',
		'en' => 'Enter the price please!',
		'ua' => 'Вкажіть ціну!'
	),
	'discount_more_total_sum' => array(
		'ru' => 'Скидка превышает общую сумму!',
		'en' => 'The discount is bigger than total sum!',
		'ua' => 'Знижка перевищує загальну суму!'
	),
	'discount_more_rent_sum' => array(
		'ru' => 'Скидка превышает стоимость аренды!',
		'en' => 'The discount is bigger than sum for rent!',
		'ua' => 'Знижка перевищує вартість оренди!'
	),

	'clientName' => array(
		'ru' => 'Введите Ваше имя!',
		'en' => 'Enter your name please!',
		'ua' => 'Введіть Ваше імя!'
	),

	'lastUpdateTime' => array(
		'ru' => 'Дата последнего обновления не может превышать текущую дату!',
		'en' => 'Last update date can not exceed the current date!',
		'ua' => 'Дата останнього оновлення не може бути пізніше поточної дати!'
	),

	'patronimic' => array(
		'ru' => 'Укажите Ваше отчество!',
		'en' => 'Enter your patronimic please!',
		'ua' => 'Вкажіть Ваше по батькові!'
	),
	'surname' => array(
		'ru' => 'Укажите Вашу фамилию!',
		'en' => 'Enter your last name please!',
		'ua' => 'Вкажіть Ваше прізвище!'
	),
	'index' => array(
		'ru' => 'Укажите Ваш индекс!',
		'en' => 'Enter your index please!',
		'ua' => 'Вкажіть Ваш індекс!'
	),
	'city' => array(
		'ru' => 'Укажите город!',
		'en' => 'Enter the city please!',
		'ua' => 'Вкажіть місто!'
	),
	'street' => array(
		'ru' => 'Укажите улицу!',
		'en' => 'Enter the street please!',
		'ua' => 'Вкажіть вулицю!'
	),
	'home' => array(
		'ru' => 'Укажите дом!',
		'en' => 'Enter the home number please!',
		'ua' => 'Вкажіть будинок!'
	),
	'region' => array(
		'ru' => 'Укажите область!',
		'en' => 'Enter the rigeon please!',
		'ua' => 'Вкажіть область!'
	),

	'startDate' => array(
		'ru' => 'Пожалуйста укажите дату начала периода!',
		'en' => 'Enter start date please!',
		'ua' => 'Будь ласка, вкажіть дату початку періоду!'
	),
	'endDate' => array(
		'ru' => 'Пожалуйста укажите дату конца периода!',
		'en' => 'Enter the end date please!',
		'ua' => 'Будь ласка, вкажіть дату кінця періоду!'
	),

	'phoneNumberOrderOneClick' => array(
		'ru' => 'Пожалуйста введите свой номер телефона!',
		'en' => 'Enter your phone please!',
		'ua' => 'Будь ласка, введіть свій номер телефону!'
	),

	'invalidCode' => array(
		'ru' => 'Введенный вам код неверен! Пожалуйста попробуйте еще раз.',
		'en' => 'You entered the code wrong! Please try again.',
		'ua' => 'Введений вам код невірний! Будь ласка спробуйте ще раз.'
	),

	'undefinedUser' => array(
		'ru' => 'Учетная запись с таким e-mail не обнаружена.',
		'en' => 'Account with such e-mail was not found.',
		'ua' => 'Обліковий запис з таким e-mail не знайдено.'
	),
	'onlyCharacters' => array(
		'ru' => 'Специальные символы недопустимы в этом поле',
		'en' => 'Special characters are not allowed in this field',
		'ua' => 'Спеціальні символи неприпустимі в цьому полі'
	),
	'code'=>array(
		'ru' => 'Вероятно код, который вы указали неверный. Пожалуйста повторите процедуру получения кода.',
		'en' => 'Probably code you entered is incorrect. Please repeat the procedure for obtaining the code.',
		'ua' => 'Ймовірно код, який ви вказали невірний. Будь ласка повторіть процедуру отримання коду.'
	),
	'denied_symbols'=>array(
		'ru' => 'Недопустимые символы',
		'en' => 'Denied symbols',
		'ua' => 'Заборонені символи'
	)
);