<?php


namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserService implements UserServiceInterface
{

    private $userRepository;
    private $encoderFactory;

    public function __construct(UserRepositoryInterface $userRepository, EncoderFactoryInterface $encoderFactory)
    {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function add(User $user): bool
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        $password = $encoder->encodePassword($user->getPassword(),$user->getSalt());
        $user->setPassword($password);

        return $this->userRepository->insert($user);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        if (!is_null($user->getPassword())) {
            $encoder = $this->encoderFactory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        } else {
            $password = $this->userRepository->getPassword($user->getId());
        }

        $user->setPassword($password);

        return $this->userRepository->update($user);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function remove(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    /**
     * @param int $id
     * @return User|Object|null
     */
    public function getById(int $id): ?User
    {
        return $this->userRepository->findOne($id);
    }

    /**
     * @return User[]
     */
    public function listAll(): array
    {
        return $this->userRepository->listAll();
    }
}