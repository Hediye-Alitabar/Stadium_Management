<?php

// namespace App\Controller;

// use App\Entity\User;
// use App\Form\UserProfileFormType;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Security\Core\User\UserInterface;

// class UserProfileController extends AbstractController
// {
//     #[Route('/profile/edit', name: 'app_edit_profile')]
//     public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
//     {
//         /** @var User $user */
//         $user = $this->getUser();

//         $form = $this->createForm(UserProfileFormType::class, $user);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $entityManager->flush();

//             $this->addFlash('success', 'Profile updated successfully.');

//             return $this->redirectToRoute('app_edit_profile');
//         }

//         return $this->render('profile/edit.html.twig', [
//             'userProfileForm' => $form->createView(),
//         ]);
//     }
// }

// src/Controller/UserProfileController.php

// namespace App\Controller;

// use App\Entity\User;
// use App\Form\UserProfileFormType;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class UserProfileController extends AbstractController
// {
//     #[Route('/profile/edit', name: 'app_edit_profile')]
//     public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
//     {
//         /** @var User $user */
//         $user = $this->getUser();
//         $form = $this->createForm(UserProfileFormType::class, $user);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $entityManager->flush();

//             $this->addFlash('success', 'Profile updated successfully.');

//             return $this->redirectToRoute('app_edit_profile');
//         }

//         return $this->render('profile/edit.html.twig', [
//             'userProfileForm' => $form->createView(),
//         ]);
//     }
// }

// src/Controller/UserProfileController.php

// src/Controller/UserProfileController.php
// ------------------------------------------------------
// namespace App\Controller;

// use App\Entity\User;
// use App\Form\UserProfileFormType;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// class UserProfileController extends AbstractController
// {
//     #[Route('/profile/edit', name: 'app_edit_profile')]
//     public function editProfile(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
//     {
//         /** @var User $user */
//         $user = $this->getUser();
//         $form = $this->createForm(UserProfileFormType::class, $user);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $currentPassword = $form->get('currentPassword')->getData();
//             if ($passwordHasher->isPasswordValid($user, $currentPassword)) {
//                 $newPassword = $form->get('newPassword')->getData();
//                 if ($newPassword) {
//                     $encodedPassword = $passwordHasher->hashPassword($user, $newPassword);
//                     $user->setPassword($encodedPassword);
//                 }

//                 $entityManager->flush();

//                 $this->addFlash('success', 'Profile updated successfully.');

//                 return $this->redirectToRoute('app_edit_profile');
//             } else {
//                 $this->addFlash('error', 'Current password is incorrect.');
//             }
//         }

//         return $this->render('profile/edit.html.twig', [
//             'userProfileForm' => $form->createView(),
//         ]);
//     }
// }

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserProfileController extends AbstractController
{
    #[Route('/profile/edit', name: 'app_edit_profile')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('currentPassword')->getData();
            if ($passwordHasher->isPasswordValid($user, $currentPassword)) {
                $newPassword = $form->get('newPassword')->getData();
                if ($newPassword) {
                    $encodedPassword = $passwordHasher->hashPassword($user, $newPassword);
                    $user->setPassword($encodedPassword);
                }
                $user->setUpdatedAt(new \DateTimeImmutable());
                

                $entityManager->flush();

                $this->addFlash('success', $translator->trans('profile_updated'));

                return $this->redirectToRoute('app_edit_profile');
            } else {
                $this->addFlash('error', $translator->trans('current_password_incorrect'));
            }
        }

        return $this->render('profile/edit.html.twig', [
            'userProfileForm' => $form->createView(),
        ]);
    }
}