<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


/**
 * @method User getUser()
 */
class UserCrudController extends AbstractCrudController
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityRepository $entityRepo
        
    )
    {

    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

//------------------------------------- Permet de ne pas éditer les roles admin dans le dashboard

    // public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    // {
    //     $userId = $this->getUser()->getId();

    //     $qb = $this->entityRepo->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
    //     $qb->andWhere('entity.id != :userId')
    //         ->setParameter('userId', $userId );

    //     return $qb;
    // }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username');

        yield TextField::new('password')
            ->setFormType( PasswordType::class)
            ->onlyOnForms();

        yield ChoiceField::new('roles')
            ->allowMultipleChoices()
            ->setChoices([
                'Administrateur' => 'ROLE_ADMIN',
                'Auteur' => 'ROLE_AUTHOR'
            ]
            );
    }
    

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var User $user */
        $user = $entityInstance;

        $plainPassword = $user->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);

        $user->setPassword($hashedPassword);

        parent::persistEntity($entityManager, $user);
    }

}
