<?php
namespace Beffi\advancephp\Blog\Http\Actions\Users;

use Beffi\advancephp\Blog\Exceptions\HttpException;
use Beffi\advancephp\Blog\Http\Actions\ActionInterface;
use Beffi\advancephp\Blog\Http\ErrorResponse;
use Beffi\advancephp\Blog\Http\Request;
use Beffi\advancephp\Blog\Http\Response;
use Beffi\advancephp\Blog\Http\SuccessfulResponse;
use Beffi\advancephp\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Beffi\advancephp\Blog\User;
use Beffi\advancephp\Person\Person;
use Beffi\advancephp\Person\Name;
use Beffi\advancephp\Blog\UUID;


class CreateUser implements ActionInterface{
    /**
     */
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
    
	/**
	 * @param \Beffi\advancephp\Blog\Http\Request $request
	 * @return \Beffi\advancephp\Blog\Http\Response
	 */
	public function handle(Request $request): Response {
        try{
            $newUserUuid = UUID::random();
            $user = new User(
                $newUserUuid,
                new Person(new Name(
                    $request->jsonBodyField('first_name'),
                    $request->jsonBodyField('last_name')
                    
                ),
                new \DateTimeImmutable),
                $request->jsonBodyField('username')
            );
        } catch(HttpException $e){
            return new ErrorResponse($e->getMessage());
        }
        $this->usersRepository->save($user);
        return new SuccessfulResponse([
            'uuid'=>(string) $newUserUuid,
        ]);
	}

}