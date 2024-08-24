<?php

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