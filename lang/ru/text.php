<?php

return [
    'welcome' => '🙃 Добро пожаловать в бот, :name',
    'start' => 'Доброго времени суток, :name, выберите нужное меню',
    'help' => (string) view('ru.help'),
    'task.name' => 'Введите название задачи',
    'task.type' => 'Принято, теперь выберите тип задачи',
    'task.deadline' => "Выберите дату дедлайна (необязательно)",
    'task.deadline_own' => "Введите дату дедлайна \n Формат: :format \n Пример: :example",
    'task.description' => 'Введите описание для вашей задачи (необязательно)',
    'task.deleted' => 'Задача успешно удалена!',
    'task.done' => 'Ваша задача успешно создано!',
    'task.congratulate' => "Ура 🥳, вы сделали это!\nОсталось всего лишь :count задач(и)",
    'task.none' => 'Эй у вас еще нет ни одной задачи🤭',
    'task.send_users' => 'У вас есть :count задачи, которые нужно выполнить сейчас, вы сделали их?',
    'task.all' => "Вот все ваши задачи :)\nСтраница: :page",
    'invalid_format' => 'Неправильный формат, пожалуйста соблюдайте правила',
    'unknown' => 'Бот вас не понимает, пожалуйста действуйте по указанию',
    'unknown_error' => 'Неизвестная ошибочка 🙃',
    'nothing' => 'Там ничего нет, брать 😑',
    'come_back' => 'Эээй, вернитесь, я по вам скучаю🥺',

    //Keyboard texts
    'kbd.main' => 'Главное меню 🏠',
    'kbd.tasks' => 'Задачи 📝',
    'kbd.add_task' => 'Добавить задачу ➕',
    'kbd.delete_task' => 'Удалить задачу ❌',
    'kbd.next' => 'След. :status',
    'kbd.middle' => ':current / :total',
    'kbd.prev' => ':status Пред.',
    'kbd.cancel' => 'Отмена ⤵',
    'kbd.done' => 'Готово ✅',
    'kbd.skip' => 'Пропустить ⏭',
    'kbd.delete' => 'Удалить ❌',
    'kbd.back' => 'Назад 🔙',
    'kbd.help' => 'Помощь ❓',
    'kbd.profile'=> 'Профиль ⚙',
    'kbd.own'=> 'Свой вариант ⚙',
    'kbd.simple'=> 'Обычный',
    'kbd.daily'=> 'Ежедневный',
    'kbd.select' => 'Выберите меню ниже',
    'kbd.close' => 'Закрыт ❌',
    'kbd.notification' => 'Уведомление :status',

];
