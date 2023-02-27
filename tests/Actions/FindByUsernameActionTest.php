<?php

namespace Beffi\advancephp\Blog\UnitTests\Actions;
use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;
use Beffi\advancephp\Blog\Http\Actions\Users\FindByUsername;
use Beffi\advancephp\Blog\Http\Request;
use Beffi\advancephp\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Person\Person;
use Beffi\advancephp\Person\Name;
use PHPUnit\Framework\TestCase;
use Beffi\advancephp\Blog\Uuid;
use Beffi\advancephp\Blog\Http\ErrorResponse;
use Beffi\advancephp\Blog\Http\SuccessfulResponse;

class FindByUsernameActionTest extends TestCase{
/**
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 * @throws /JsonException
 */
public function testItReturnsErrorResponseIfNoUsernameProvided(): void
{
// Создаём объект запроса
// Вместо суперглобальных переменных
// передаём простые массивы
    $request = new Request([], []);
// Создаём стаб репозитория пользователей
    $usersRepository = $this->usersRepository([]);
//Создаём объект действия
    $action = new FindByUsername($usersRepository);
// Запускаем действие
$response = $action->handle($request);
// Проверяем, что ответ - неудачный
$this->assertInstanceOf(ErrorResponse::class, $response);
// Описываем ожидание того, что будет отправлено в поток вывода
$this->expectOutputString('{"success":false,"reason":"No such query param in the request: username"}');
// Отправляем ответ в поток вывода
$response->send();
}
/**
* @runInSeparateProcess
* @preserveGlobalState disabled
*/
// Тест, проверяющий, что будет возвращён неудачный ответ,
// если пользователь не найден
public function testItReturnsErrorResponseIfUserNotFound(): void
{
// Теперь запрос будет иметь параметр username
$request = new Request(['username' => 'ivan'], []);
// Репозиторий пользователей по-прежнему пуст
$usersRepository = $this->usersRepository([]);
$action = new FindByUsername($usersRepository);
$response = $action->handle($request);
$this->assertInstanceOf(ErrorResponse::class, $response);
$this->expectOutputString('{"success":false,"reason":"Not found"}');
$response->send();
}
/**
* @runInSeparateProcess
* @preserveGlobalState disabled
*/
// Тест, проверяющий, что будет возвращён удачный ответ,
// если пользователь найден
public function testItReturnsSuccessfulResponse(): void{
    $request = new Request(['username' => 'ivan'], []);
// На этот раз в репозитории есть нужный нам пользователь
$usersRepository = $this->usersRepository([
new User(
UUID::random(),
new Person(new Name('Ivan', 'Nikitin'),new \DateTimeImmutable),
'ivan'
),
]);
$action = new FindByUsername($usersRepository);
$response = $action->handle($request);
// Проверяем, что ответ - удачный
$this->assertInstanceOf(SuccessfulResponse::class, $response);
$this->expectOutputString('{"success":true,"data":{"username":"ivan","name":"Ivan Nikitin"}}');
$response->send();

}

private function usersRepository(array $users): UsersRepositoryInterface
{
// В конструктор анонимного класса передаём массив пользователей
return new class($users) implements UsersRepositoryInterface {
    public function __construct(
private array $users
) {
}
public function save(User $user): void
{
}
public function get(Uuid $uuid):User
{
throw new UserNotFoundException("Not found");
}
public function getByUsername(string $username): User
{
foreach ($this->users as $user) {
if ($user instanceof User && $username === $user->username())
{
return $user;
}
}
throw new UserNotFoundException("Not found");
}
};
}

}